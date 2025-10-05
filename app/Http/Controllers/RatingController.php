<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
   
    // Submit or update the rating
    public function submit(Request $request, $orderId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $order = Order::findOrFail($orderId);
        //dd($order);

        Rating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'vendor_application_id' => $order->vendor_application_id,
            ],
            [
                'rating' => $request->rating,
                'review' => $request->review,
            ]
        );

        return redirect()->back()->with('success', 'Rating submitted successfully!');
    }
}
