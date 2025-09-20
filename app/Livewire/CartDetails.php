<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DeliveryCharge;
use App\Models\PaymentMethod;

class CartDetails extends Component
{
    public $expected_receive_time;
    public $delivery_areas = [];
    public $delivery_area = null;
    public $payment_methods;
    public $selected_payment_method;
    public $carts = [];
    public $contact_name, $contact_phone, $delivery_address;
    public $special_instructions, $payment_method = 'cash';
    public $delivery_option = 'delivery'; // default value: 'delivery' or 'pickup'
    public $subtotal = 0, $delivery_charge = 0, $total = 0;

    protected $rules = [
        'contact_name' => 'required|string|max:255',
        'contact_phone' => 'required|string|max:15',
        'delivery_area' => 'nullable|string|max:255',
        'delivery_address' => 'nullable|string|max:255',
        'payment_method' => 'required',
        'expected_receive_time' => 'nullable|date_format:Y-m-d\TH:i',
    ];

    public function mount()
    {
        $this->loadCart();
        $this->delivery_areas = DeliveryCharge::all();
        $this->payment_methods = PaymentMethod::all();
    }

    public function loadCart()
    {
        $this->carts = Cart::with('food')
            ->where('user_id', Auth::id())
            ->get()
            ->toArray();

        $this->calculateTotals();
    }

    public function updateQuantity($cartId, $quantity)
    {
        if ($quantity < 1) return;

        $cart = Cart::find($cartId);
        if ($cart && $cart->user_id == Auth::id()) {
            $cart->quantity = $quantity;
            $cart->save();
        }

        $this->loadCart();
    }

    public function removeItem($cartId)
    {
        Cart::where('id', $cartId)->where('user_id', Auth::id())->delete();
        $this->loadCart();
    }

    public function updatedDeliveryOption()
    {
        if ($delivery_option === 'delivery') {
            if ($this->delivery_area) {
                $area = DeliveryCharge::find($this->delivery_area);
                $this->delivery_charge = $area ? $area->charge : 0;
            } else {
                $this->delivery_charge = 0;
            }
        } else {
            $this->delivery_area = null;
            $this->delivery_charge = 0;
        }

        $this->calculateTotals();
    }
   

    public function calculateTotals()
    {
        $this->subtotal = collect($this->carts)->sum(fn($item) => $item['price'] * $item['quantity']);
        $this->delivery_charge = ($this->delivery_option === 'delivery' && $this->delivery_area) 
            ? (DeliveryCharge::find($this->delivery_area)->charge ?? 0) 
            : 0;
        $this->total = $this->subtotal + $this->delivery_charge;
    }

    public function placeOrder()
    {
        $this->validate();

        if (count($this->carts) == 0) {
            session()->flash('error', 'Your cart is empty!');
            return;
        }

        DB::transaction(function () {
            $vendorId = $this->carts[0]['vendor_application_id'];

            $order = Order::create([
                'user_id' => Auth::id(),
                'vendor_application_id' => $vendorId,
                'special_instructions' => $this->special_instructions,
                'expected_receive_time' => $this->expected_receive_time ?? now()->addHour(),
                'delivery_option' => $this->delivery_option,
                'contact_name' => $this->contact_name,
                'contact_phone' => $this->contact_phone,
                'delivery_area' => $this->delivery_area,
                'delivery_address' => $this->delivery_address,
                'payment_method' => $this->payment_method,
                'delivery_charge' => $this->delivery_charge,
                'total_price' => $this->total,
            ]);

            foreach ($this->carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'food_id' => $cart['food_id'],
                    'quantity' => $cart['quantity'],
                    'price' => $cart['price'],
                ]);
            }

            Cart::where('user_id', Auth::id())->delete();
        });

        session()->flash('success', 'Order placed successfully!');
        return redirect()->route('orders.success');
    }
}
