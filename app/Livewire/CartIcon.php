<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartIcon extends Component
{
    public $cartCount = 0;
    public $message = null;

    protected $listeners = ['notify' => 'updateCartCount'];

    public function mount()
    {
        $this->updateCartCount();
    }

    public function updateCartCount($wasAdded = true)
    {
        $newCount = Auth::check()
            ? Cart::where('user_id', Auth::id())->sum('quantity')
            : 0;

        $this->message = $wasAdded
            ? 'Added to cart successfully.'
            : 'At a time you will be able to order from a single homestaurant';

        $this->cartCount = $newCount;

        // Automatically hide message after 3 seconds (if using Alpine)
        $this->dispatch('cart-message-shown');
    }

    public function render()
    {
        return view('livewire.cart-icon');
    }
}
