<x-layouts.app title="My Orders">
    <div class="max-w-5xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">My Orders</h1>

        <div class="grid md:grid-cols-2 gap-6">
            @foreach($orders as $order)
                <a href="#"
                   class="block p-6 bg-white dark:bg-gray-800 shadow rounded-2xl border border-gray-200 dark:border-gray-700 hover:shadow-lg transition duration-300 hover:scale-[1.02]">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="text-lg font-semibold text-red-600 dark:text-red-500 underline">
                            Order #{{ $order->id }}
                        </h2>

                        {{-- 🔥 Status badge --}}
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-700 border border-yellow-300',
                                'accepted' => 'bg-blue-100 text-blue-700 border border-blue-300',
                                'ready' => 'bg-purple-100 text-purple-700 border border-purple-300',
                                'delivered' => 'bg-green-100 text-green-700 border border-green-300',
                                'canceled' => 'bg-red-100 text-red-700 border border-red-300',
                            ];
                        @endphp
                        <span class="px-3 py-1 text-sm font-medium rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700 border border-gray-300' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <p class="text-gray-700 dark:text-gray-300 mb-1">
                        <strong>Homestaurant:</strong> {{ $order->vendor->user->name }}
                    </p>
                    <p class="text-gray-700 dark:text-gray-300 mb-1">
                        <strong>Total:</strong> {{ number_format($order->total_price, 2) }} CHF
                    </p>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        Expected on {{ $order->expected_receive_time->format('M d, Y H:i') }}
                    </p>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</x-layouts.app>
