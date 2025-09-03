<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class AddToCart extends Component
{
    public $food;
    public $quantity = 1;
    public $preference = '';

    public function addToCart()
    {
        // Ensure the user is authenticated
        $user = Auth::user();

        if (!$user) {
            session(['url.intended' => url()->previous()]);
            return redirect()->route('login');
        }

        // Check if cart already has item from another vendor
        $existingCartItem = Cart::where('user_id', $user->id)->first();

        if ($existingCartItem && $existingCartItem->vendor_application_id !== $this->food->user_id) {
            // session()->flash('error', 'You can only order from one homestaurant at a time.');
            $this->dispatch('cartRejected', error: 'You can only order from one homestaurant at a time.');
            return;
        }

        // Update if same food exists
        $cartItem = Cart::where('user_id', $user->id)
            ->where('food_id', $this->food->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $this->quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $user->id,
                'vendor_application_id' => $this->food->user_id,
                'food_id' => $this->food->id,
                'quantity' => $this->quantity,
                'price' => $this->food->discount == 0 ? $this->food->price : $this->food->price - ($this->food->price * $this->food->discount / 100 ),
                'preference' => $this->preference,
            ]);
        }

        $successMessage = 'Item added to plate successfully.';
        // session()->flash('success', 'Item added to cart.');
        $this->dispatch('notify', success: $successMessage); // Optional: to update icon

    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
