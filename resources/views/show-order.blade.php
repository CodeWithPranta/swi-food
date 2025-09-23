<x-layouts.app title="Order #{{ $order->id }}">
    <div class="max-w-5xl mx-auto p-6 space-y-8">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">
            Order Details — #{{ $order->id }}
        </h1>

        {{-- 🔵 Order Progress --}}
        @php
            $steps = ['pending', 'accepted', 'ready', 'delivered'];
            $currentIndex = array_search($order->status, $steps);
        @endphp
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Order Progress</h3>
            <div class="relative">
                <div class="absolute top-5 left-0 w-full h-1 bg-gray-200 dark:bg-gray-700 rounded"></div>
                <div class="absolute top-5 left-0 h-1 bg-gradient-to-r from-yellow-400 via-blue-400 to-green-500 rounded transition-all duration-500"
                    style="width: {{ ($currentIndex !== false ? ($currentIndex / (count($steps) - 1)) * 100 : 0) }}%">
                </div>
                <div class="flex justify-between relative z-10">
                    @foreach($steps as $index => $step)
                        <div class="flex flex-col items-center w-1/4">
                            <div class="w-10 h-10 flex items-center justify-center rounded-full border-2
                                @if($index < $currentIndex) bg-green-500 border-green-500 text-white
                                @elseif($index == $currentIndex) bg-white dark:bg-gray-800 border-4 border-blue-500 text-blue-600 font-bold
                                @else bg-white dark:bg-gray-800 border-gray-300 text-gray-400
                                @endif
                            ">
                                {{ $index + 1 }}
                            </div>
                            <span class="mt-2 text-sm 
                                @if($index <= $currentIndex) text-blue-600 dark:text-blue-400 font-semibold 
                                @else text-gray-400 
                                @endif
                            ">
                                {{ ucfirst($step) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- 🧑 Customer + Restaurant Info --}}
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Your Info</h3>
                <p><strong>Name:</strong> {{ $order->contact_name }}</p>
                <p><strong>Phone:</strong> {{ $order->contact_phone }}</p>
                <p><strong>Delivery Option:</strong> {{ ucfirst($order->delivery_option) }}</p>
                @if ($order->delivery_option === 'delivery')
                    <p><strong>Delivery Area:</strong> {{$order->delivery_area}}</p>
                    <p><strong>Address:</strong> {{ $order->delivery_address }}</p>
                @endif
                <p><strong>Expected:</strong> {{ \Carbon\Carbon::parse($order->expected_receive_time)->format('M d, Y H:i') }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Homestaurant Info</h3>
                <p><strong>Name:</strong> {{ $homestaurant->kitchen_name ?? $order->vendor->user->name }}</p>
                <p><strong>Chef:</strong> {{ $homestaurant->chef_name ?? 'N/A' }}</p>
                <p><strong>Chef Profession:</strong> {{ $homestaurant->profession->name ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ $homestaurant->phone_number ?? 'N/A' }}</p>
                <p><strong>Location:</strong> {{ $homestaurant->location ?? 'N/A' }}</p>
                <div class="flex items-center space-x-4 mt-4">
                @foreach ($homestaurant->links as $link)
                <a href="{{$link['link']}}" target="_blank">{!! $link['svg'] !!}</a>
                @endforeach
                </div>
            </div>
        </div>

        {{-- 🛒 Order Items --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Order Items</h3>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="p-3 text-left">Item</th>
                            <th class="p-3 text-right">Qty</th>
                            <th class="p-3 text-right">Price</th>
                            <th class="p-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderItems as $item)
                            <tr class="border-b dark:border-gray-700">
                                <td class="p-3">{{ $item->food->name }}</td>
                                <td class="p-3 text-right">{{ $item->quantity }}</td>
                                <td class="p-3 text-right">{{ number_format($item->price, 2) }} CHF</td>
                                <td class="p-3 text-right">{{ number_format($item->price * $item->quantity, 2) }} CHF</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 💳 Payment + Totals --}}
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Payment Method</h3>
                <p>{{ ucfirst($paymentMethod->bank_name ?? 'Not specified') }}</p>
                <div>
                    {!! $paymentMethod->details !!}
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Summary</h3>
                <p><strong>Subtotal:</strong> {{ number_format(($order->total_price - $order->delivery_charge), 2) }} CHF</p>
                @if($order->delivery_option === 'delivery')
                    <p><strong>Delivery Charge:</strong> {{ number_format($order->delivery_charge, 2) }} CHF</p>
                @endif
                <p class="text-xl font-bold mt-2">
                    <strong>Total:</strong> {{ number_format($order->total_price, 2) }} CHF
                </p>
            </div>
        </div>
    </div>
</x-layouts.app>
