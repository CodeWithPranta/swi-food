<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class DashboardStats extends Component
{
    public $deliveredOrders = 0;
    public $pendingOrders = 0;
    public $totalPurchased = 0.0;
    public $likedFoods;

    public function mount()
    {
        $user = Auth::user();

        if ($user) {
            $this->deliveredOrders = $user->orders()->where('status', 'delivered')->count();
            $this->pendingOrders   = $user->orders()->where('status', 'pending')->count();
            $this->totalPurchased  = $user->orders()->where('status', 'delivered')->sum('total_price');
        }

        $this->likedFoods = $user->likedFoods()->latest()->get();
        //dd($this->likedFoods);
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }
}
