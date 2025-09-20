<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Food;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\DeliveryCharge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function placeOrder(Request $request)
    {
        $request->validate([
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'delivery_option' => 'required|in:delivery,pickup',
            'delivery_area' => 'nullable|integer',
            'delivery_address' => 'nullable|string|max:255',
            'payment_method' => 'required|string',
            'special_instructions' => 'nullable|string',
            'expected_receive_time' => 'nullable|date',
        ]);

        $carts = Cart::where('user_id', Auth::id())->with('food')->get();

        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        DB::transaction(function () use ($carts, $request) {
            $vendorId = \App\Models\VendorApplication::where('user_id', $carts[0]->food->user_id)->value('id');

            //dd($vendorId);

            $order = Order::create([
                'user_id' => Auth::id(),
                'vendor_application_id' => $vendorId,
                'special_instructions' => $request->special_instructions,
                'expected_receive_time' => $request->expected_receive_time ?? now()->addHour(),
                'delivery_option' => $request->delivery_option,
                'contact_name' => $request->contact_name,
                'contact_phone' => $request->contact_phone,
                'delivery_area' => $request->delivery_area,
                'delivery_address' => $request->delivery_address,
                'payment_method' => $request->payment_method,
                'delivery_charge' => $request->delivery_charge ?? 0,
                'total_price' => $request->total ?? $carts->sum(fn ($cart) => $cart->price * $cart->quantity),
            ]);

            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'food_id' => $cart->food_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->price,
                ]);
            }

            Cart::where('user_id', Auth::id())->delete();
        });

        return redirect()->route('orders.success')->with('order-success', 'Order placed successfully!');
    }

    public function orderSuccess()
    {
        return view('homestaurant.order-success');
    }

}
