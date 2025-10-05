<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\VendorApplication;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;

class Chat extends Component
{
    public $homerestaurants;
    public $selectedHomestaurant;
    public $newMessage;
    public $messages;

    public function mount()
    {
        $user = auth()->user();

        if ($user->user_type == 2) {
            // Case: vendor + customer

            // 1️⃣ Get all customers who ordered from this vendor
            $customerIds = Order::where('vendor_application_id', $user->vendorApplication->id ?? 0)
                ->pluck('user_id')
                ->unique();

            $customers = \App\Models\User::whereIn('id', $customerIds)
                ->with(['sentMessages' => function ($q) use ($user) {
                    $q->where('receiver_id', $user->id)->latest();
                }, 'receivedMessages' => function ($q) use ($user) {
                    $q->where('sender_id', $user->id)->latest();
                }])
                ->get();

            // 2️⃣ Get all vendors this user has ordered from
            $vendorIds = Order::where('user_id', $user->id)
                ->pluck('vendor_application_id')
                ->unique();

            $vendors = \App\Models\VendorApplication::with(['user', 'user.sentMessages' => function ($q) use ($user) {
                    $q->where('receiver_id', $user->id)->latest();
                }, 'user.receivedMessages' => function ($q) use ($user) {
                    $q->where('sender_id', $user->id)->latest();
                }])
                ->whereIn('id', $vendorIds)
                ->get()
                ->pluck('user'); // extract actual vendor users

            // 3️⃣ Merge customers + vendors
            $this->homerestaurants = $customers->merge($vendors)
                ->unique('id') // avoid duplicates
                ->sortByDesc(function ($contact) use ($user) {
                    $lastSent = $contact->sentMessages->first()?->created_at;
                    $lastReceived = $contact->receivedMessages->first()?->created_at;
                    return max([$lastSent, $lastReceived]);
                })
                ->values();

            $this->selectedHomestaurant = $this->homerestaurants->first();

        } else {
            // Case: regular customer
            $vendorIds = Order::where('user_id', $user->id)
                ->pluck('vendor_application_id')
                ->unique();

            $this->homerestaurants = \App\Models\VendorApplication::with(['user', 'user.sentMessages' => function ($q) use ($user) {
                $q->where('receiver_id', $user->id)->latest();
            }, 'user.receivedMessages' => function ($q) use ($user) {
                $q->where('sender_id', $user->id)->latest();
            }])
                ->whereIn('id', $vendorIds)
                ->get()
                ->sortByDesc(function ($vendor) use ($user) {
                    $lastSent = $vendor->user->sentMessages->first()?->created_at;
                    $lastReceived = $vendor->user->receivedMessages->first()?->created_at;
                    return max([$lastSent, $lastReceived]);
                })
                ->pluck('user') // again return vendor users
                ->values();

            $this->selectedHomestaurant = $this->homerestaurants->first();
        }

        $this->loadMessages();
    }



    public function selectHomestaurant($id)
    {
        $user = auth()->user();

        if ($user->user_type == 2) {
            $this->selectedHomestaurant = \App\Models\User::find($id);
        } else {
            $this->selectedHomestaurant = \App\Models\VendorApplication::with('user')->find($id);
        }
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $user = auth()->user();

        if ($this->selectedHomestaurant) {
            $receiverId = $user->user_type == 2
                ? $this->selectedHomestaurant->id              // vendor: selectedHomestaurant is User
                : $this->selectedHomestaurant->user->id;       // user: selectedHomestaurant is VendorApplication

            $this->messages = ChatMessage::where(function($q) use ($receiverId, $user) {
                    $q->where('sender_id', $user->id)
                    ->where('receiver_id', $receiverId);
                })
                ->orWhere(function($q) use ($receiverId, $user) {
                    $q->where('sender_id', $receiverId)
                    ->where('receiver_id', $user->id);
                })
                ->orderBy('created_at', 'asc')
                ->get()
                ->toArray();
        } else {
            $this->messages = [];
        }
    }

    public function submit()
    {
        $this->validate([
            'newMessage' => 'required|string|max:1000',
        ]);

        $user = auth()->user();

         // ✅ Define receiver ID first
        $receiverId = $user->user_type == 2
            ? $this->selectedHomestaurant['id']          // vendor sending to user
            : $this->selectedHomestaurant['user']['id']; // user sending to vendor

        if ($user->user_type == 2 && $this->selectedHomestaurant) {
            // Vendor sending message to user
            $message = \App\Models\ChatMessage::create([
                'sender_id' => $user->id,
                'receiver_id' => $this->selectedHomestaurant->id,
                'message' => $this->newMessage,
            ]);

            $this->messages[] = $message->toArray();

        } elseif ($user->user_type != 2 && $this->selectedHomestaurant) {
            // User sending message to vendor
            $message = \App\Models\ChatMessage::create([
                'sender_id' => $user->id,
                'receiver_id' => $this->selectedHomestaurant->user->id,
                'message' => $this->newMessage,
            ]);

            $this->messages[] = $message->toArray();
        }

        // Reorder homestaurants so last conversed user comes first
        $this->homerestaurants = $this->homerestaurants->sortByDesc(function($homestaurant) use ($receiverId, $user) {
            $id = $user->user_type == 2 
                ? $homestaurant['id'] 
                : $homestaurant['user']['id'];

            return $id === $receiverId ? now()->timestamp : 0; // ✅ Carbon -> timestamp
        })->values();


        $this->newMessage = '';

        broadcast(new \App\Events\MessageSent($message));
    }

    public function getListeners()
    {
        $user = auth()->user();
        return [
            "echo-private:chat.{$user->id},MessageSent" => 'echoMessageReceived'
        ];
    }

    public function echoMessageReceived($data)
    {
        // Ensure the message is for the currently selected homestaurant
        $user = auth()->user();
        $selectedId = $user->user_type == 2
            ? $this->selectedHomestaurant['id']          // vendor: selectedHomestaurant is User
            : $this->selectedHomestaurant['user']['id']; // user: selectedHomestaurant is VendorApplication

        if ($data['sender_id'] == $selectedId) {
            $this->messages[] = $data;

            // Reorder homestaurants so last conversed user comes first
            $this->homerestaurants = $this->homerestaurants->sortByDesc(function($homestaurant) use ($data, $user) {
                $id = $user->user_type == 2 
                    ? $homestaurant['id'] 
                    : $homestaurant['user']['id'];

                return $id === $data['sender_id'] ? now()->timestamp : 0; // ✅ Carbon -> timestamp
            })->values();
        }
    }


    public function render()
    {
        return view('livewire.chat');
    }
}
