<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Food;

class OrderController extends Controller
{
    public function orderNow(Request $request)
    {
        $formData = $request->validate([
            'food_id'    => 'required|integer|exists:foods,id',
            'quantity'   => 'required|integer|min:1',
            'preference' => 'nullable|string|max:255',
        ]);

        $userId = auth()->id();
        $food   = Food::findOrFail($formData['food_id']);

        // 🔒 Check if cart already has an item from another vendor
        $existingCartItem = Cart::where('user_id', $userId)->first();
        if ($existingCartItem && $existingCartItem->vendor_application_id !== $food->user_id) {
            return redirect()->route('cart.details')->with('error', 'You can only order from one homestaurant at a time.');
        }

        // 💰 Calculate unit price (with discount if applicable)
        $unitPrice = $food->discount == 0
            ? $food->price
            : $food->price - ($food->price * $food->discount / 100);

        // 🔍 Check if the same food already exists in the user's cart
        $cartItem = Cart::where('user_id', $userId)
            ->where('food_id', $food->id)
            ->first();

        if ($cartItem) {
            // Update existing cart item
            $cartItem->quantity += $formData['quantity'];
            $cartItem->price     = $unitPrice; // always update to latest price

            // Merge preferences
            if (!empty($formData['preference'])) {
                $cartItem->preference = trim($cartItem->preference . ', ' . $formData['preference'], ', ');
            }

            $cartItem->save();
        } else {
            // Add a new cart item
            Cart::create([
                'user_id'               => $userId,
                'vendor_application_id' => $food->user_id,
                'food_id'               => $food->id,
                'quantity'              => $formData['quantity'],
                'price'                 => $unitPrice,
                'preference'            => $formData['preference'] ?? '',
            ]);
        }

        return redirect()->route('cart.details')->with('success', 'Item added to cart successfully!');
    }
}
