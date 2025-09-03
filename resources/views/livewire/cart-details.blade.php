<div>
    <!-- Cart Items -->
    <h2 class="text-xl font-bold mb-4">Your Cart</h2>

    @if(count($carts) > 0)
        <table class="table-auto w-full border mb-6">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="p-2">Food</th>
                    <th class="p-2">Price</th>
                    <th class="p-2">Quantity</th>
                    <th class="p-2">Subtotal</th>
                    <th class="p-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($carts as $cart)
                    <tr class="border-b">
                        <td class="p-2">{{ $cart['food']['name'] }}</td>
                        <td class="p-2">৳{{ number_format($cart['price'], 2) }}</td>
                        <td class="p-2">
                            <input type="number" min="1" wire:change="updateQuantity({{ $cart['id'] }}, $event.target.value)"
                                value="{{ $cart['quantity'] }}"
                                class="w-16 border p-1 rounded">
                        </td>
                        <td class="p-2">৳{{ number_format($cart['price'] * $cart['quantity'], 2) }}</td>
                        <td class="p-2">
                            <button wire:click="removeItem({{ $cart['id'] }})"
                                    class="bg-red-500 text-white px-3 py-1 rounded">Remove</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="mb-4">
            <p>Subtotal: <b>৳{{ number_format($subtotal, 2) }}</b></p>
            <p>Delivery: <b>৳{{ number_format($delivery_charge, 2) }}</b></p>
            <p>Total: <b>৳{{ number_format($total, 2) }}</b></p>
        </div>

        <!-- Order Form -->
        <h2 class="text-xl font-bold mb-2">Order Information</h2>

        <form wire:submit.prevent="placeOrder" class="space-y-4">
            <div>
                <label>Contact Name</label>
                <input type="text" wire:model="contact_name" class="border p-2 rounded w-full">
                @error('contact_name') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label>Phone</label>
                <input type="text" wire:model="contact_phone" class="border p-2 rounded w-full">
                @error('contact_phone') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label>Delivery Area</label>
                <input type="text" wire:model="delivery_area" class="border p-2 rounded w-full">
            </div>

            <div>
                <label>Delivery Address</label>
                <input type="text" wire:model="delivery_address" class="border p-2 rounded w-full">
            </div>

            <div>
                <label>Payment Method</label>
                <select wire:model="payment_method" class="border p-2 rounded w-full">
                    <option value="cash">Cash on Delivery</option>
                    <option value="card">Card</option>
                </select>
            </div>

            <div>
                <label>Special Instructions</label>
                <textarea wire:model="special_instructions" class="border p-2 rounded w-full"></textarea>
            </div>

            <div>
                <label>Delivery Option</label>
                <select wire:model="delivery_option" class="border p-2 rounded w-full" wire:change="calculateTotals">
                    <option value="1">Delivery</option>
                    <option value="0">Pickup</option>
                </select>
            </div>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
                Place Order
            </button>
        </form>
    @else
        <p>Your cart is empty.</p>
    @endif
</div>
