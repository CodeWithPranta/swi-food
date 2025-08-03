<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Food;
use Illuminate\Support\Facades\Auth;

class LikeFood extends Component
{
    public Food $food;
    public bool $isLiked = false;
    public int $likesCount = 0;

    public function mount(Food $food): void
    {
        $this->food = $food;

        $user = Auth::user();

        $this->isLiked = $user ? $food->isLikedBy($user) : false;
        $this->likesCount = $food->likedByUsers()->count();
    }

    public function toggleLike(): void
    {
        $user = Auth::user();

        if (!$user) {
            $this->dispatch('cartRejected', error: "Only logged in user can like the food."); // Optional: handle this with JS on frontend
            return;
        }

        if ($this->isLiked) {
            $this->food->likedByUsers()->detach($user->id);
            $this->isLiked = false;
            $this->likesCount--;
        } else {
            $this->food->likedByUsers()->attach($user->id);
            $this->isLiked = true;
            $this->likesCount++;
        }
    }

    public function render()
    {
        return view('livewire.like-food');
    }
}
