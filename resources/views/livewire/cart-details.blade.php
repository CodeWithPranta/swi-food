<div>
    <!-- ✅ Flash Messages -->
    @if(session('success'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 10000)" 
            class="mb-4 p-4 rounded-lg bg-green-100 text-green-800 border border-green-300 shadow-md transition duration-500 ease-in-out"
        >
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 10000)" 
            class="mb-4 p-4 rounded-lg bg-red-100 text-red-800 border border-red-300 shadow-md transition duration-500 ease-in-out"
        >
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Cart Items -->
     <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Your Cart') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage your cart') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @if(count($carts) > 0)
        <table class="table-auto w-full border mb-6 border-gray-300 dark:border-gray-700">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-800 text-left">
                    <th class="p-2 text-gray-800 dark:text-gray-200">Food</th>
                    <th class="p-2 text-gray-800 dark:text-gray-200">Price</th>
                    <th class="p-2 text-gray-800 dark:text-gray-200">Quantity</th>
                    <th class="p-2 text-gray-800 dark:text-gray-200">Subtotal</th>
                    <th class="p-2 text-gray-800 dark:text-gray-200">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($carts as $cart)
                    <tr class="border-b border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="p-2 text-gray-700 dark:text-gray-200">{{ $cart['food']['name'] }}</td>
                        <td class="p-2 text-gray-700 dark:text-gray-200">{{ number_format($cart['price'], 2) }} CHF</td>
                        <td class="p-2">
                            <input type="number" min="1"
                                   wire:change="updateQuantity({{ $cart['id'] }}, $event.target.value)"
                                   value="{{ $cart['quantity'] }}"
                                   class="w-16 border p-1 rounded bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200">
                        </td>
                        <td class="p-2 text-gray-700 dark:text-gray-200">{{ number_format($cart['price'] * $cart['quantity'], 2) }} CHF</td>
                        <td class="p-2">
                            <button wire:click="removeItem({{ $cart['id'] }})"
                                class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 p-2 rounded-lg shadow-md transition flex items-center justify-center"
                                title="Remove item">
                                <svg class="w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 15v3c0 .5523.44772 1 1 1h16c.5523 0 1-.4477 1-1v-3M3 15V6c0-.55228.44772-1 1-1h16c.5523 0 1 .44772 1 1v9M3 15h18M8 15v4m4-4v4m4-4v4m-5.5061-7.4939L12 10m0 0 1.5061-1.50614M12 10l1.5061 1.5061M12 10l-1.5061-1.50614"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="mb-4 gap-4 text-gray-800 dark:text-gray-200">
            <p>Subtotal: <b id="subtotal">{{ number_format($subtotal, 2) }} CHF</b></p>
            <p>Delivery: <b id="deliveryCharge">{{ number_format($delivery_charge, 2) }} CHF</b></p>
            <p>Total: <b id="totalPrice">{{ number_format($total, 2) }} CHF</b></p>
        </div>

        <!-- ✅ Order Form -->
        <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100">Order Information</h2>

        <form action="{{ route('orders.place') }}" method="POST" class="space-y-4" id="orderForm">
        @csrf

        <!-- Contact Name -->
        <div>
            <label class="text-gray-700 dark:text-gray-300">Contact name</label>
            <input type="text" name="contact_name"
                class="border p-2 rounded w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200"
                required>
        </div>

        <!-- Contact Phone -->
        <div>
            <label class="text-gray-700 dark:text-gray-300">Phone</label>
            <input type="text" name="contact_phone"
                class="border p-2 rounded w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200"
                required>
        </div>

        <!-- Expected Receive Time -->
        <div>
            <label class="text-gray-700 dark:text-gray-300">Expected receive time</label>
            <input type="datetime-local" name="expected_receive_time"
                class="border p-2 rounded w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200"
                required>
        </div>

        <!-- Special Instructions -->
        <div>
            <label class="text-gray-700 dark:text-gray-300">Special instructions</label>
            <textarea name="special_instructions"
                    class="border p-2 rounded w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200"></textarea>
        </div>

        <!-- Delivery Option -->
        <div>
            <label class="text-gray-700 dark:text-gray-300">Delivery option</label>
            <select name="delivery_option" id="deliveryOption"
                    class="border p-2 rounded w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">
                <option value="delivery">Delivery</option>
                <option value="pickup">Pickup</option>
            </select>
        </div>

        <!-- Delivery Area -->
        <div id="deliveryAreaWrap">
            <label class="text-gray-700 dark:text-gray-300">Delivery area</label>
            <select name="delivery_area" id="deliveryArea"
                    class="border p-2 rounded w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">
                <option value="">Select area</option>
                @foreach($delivery_areas as $area)
                    <option value="{{ $area->area }}" data-charge="{{ $area->charge }}">
                        {{ $area->area }} ({{ number_format($area->charge, 2) }} CHF)
                    </option>
                @endforeach
            </select>
        </div>
         
        <!-- Hidden Inputs -->
        <input type="hidden" id="deliveryChargeInput" name="delivery_charge" value="{{ $delivery_charge }}">
        <input type="hidden" id="totalPriceInput" name="total_price" value="{{ $total }}">

        <!-- Delivery Address -->
        <div id="deliveryAddressWrap">
            <label class="text-gray-700 dark:text-gray-300">Delivery address</label>
            <input type="text" name="delivery_address"
                class="border p-2 rounded w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">
        </div>

        <!-- Payment Method -->
        <div>
            <label class="text-gray-700 dark:text-gray-300">Payment method</label>
            <select name="payment_method"
                    class="border p-2 rounded w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">
                <option value="cash">Cash on delivery</option>
                <option value="recommended">Homestaurant recommended</option>
            </select>
        </div>

        <x-main-btn type="submit">Place order</x-main-btn>
    </form>


    @else
        <p class="text-gray-700 dark:text-gray-300">Your cart is empty.</p>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const deliveryOption = document.getElementById("deliveryOption");
            const deliveryArea = document.getElementById("deliveryArea");
            const deliveryAreaWrap = document.getElementById("deliveryAreaWrap");
            const deliveryAddressWrap = document.getElementById("deliveryAddressWrap");

            const deliveryCharge = document.getElementById("deliveryCharge");
            const totalPrice = document.getElementById("totalPrice");

            const deliveryChargeInput = document.getElementById("deliveryChargeInput");
            const totalPriceInput = document.getElementById("totalPriceInput");

            const subtotal = parseFloat({{ $subtotal }});

            function toggleDeliveryFields() {
                if (deliveryOption.value === "pickup") {
                    deliveryAreaWrap.style.display = "none";
                        deliveryAddressWrap.style.display = "none";

                        deliveryCharge.textContent = "0.00 CHF";
                        totalPrice.textContent = subtotal.toFixed(2) + " CHF";

                        deliveryChargeInput.value = 0;
                        totalPriceInput.value = subtotal.toFixed(2);
                    } else {
                        deliveryAreaWrap.style.display = "block";
                        deliveryAddressWrap.style.display = "block";
                    }
                }

                function updateTotals() {
                    let charge = 0;

                    if (deliveryArea && deliveryArea.value) {
                        const selected = deliveryArea.options[deliveryArea.selectedIndex];
                        charge = parseFloat(selected.getAttribute("data-charge")) || 0;
                    }

                    const total = subtotal + charge;

                    deliveryCharge.textContent = charge.toFixed(2) + " CHF";
                    totalPrice.textContent = total.toFixed(2) + " CHF";

                    deliveryChargeInput.value = charge.toFixed(2);
                    totalPriceInput.value = total.toFixed(2);
                }

                deliveryOption.addEventListener("change", () => {
                    toggleDeliveryFields();
                    updateTotals();
                });

                if (deliveryArea) {
                    deliveryArea.addEventListener("change", updateTotals);
                }

                // init on page load
                toggleDeliveryFields();
                updateTotals();
            });
    </script>


</div>
