<x-layouts.app title="Order Success">
    @if(session('order-success'))
        <div class="min-h-[80vh] flex items-center justify-center px-4 bg-white dark:bg-gray-900 transition-colors duration-300">
            <div class="text-center max-w-xl space-y-6">
                {{-- ✅ Animated SVG --}}
                <div class="mx-auto w-24 h-24">
                    <svg class="mx-auto w-full h-full text-green-600 dark:text-green-400" viewBox="0 0 52 52">
                        <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" stroke="currentColor" stroke-width="2"/>
                        <path class="checkmark__check" fill="none" stroke="currentColor" stroke-width="3" d="M14 27l7 7 16-16"/>
                    </svg>
                    <style>
                        .checkmark__circle {
                            stroke-dasharray: 166;
                            stroke-dashoffset: 166;
                            animation: stroke 0.6s ease-in-out forwards;
                        }

                        .checkmark__check {
                            stroke-dasharray: 48;
                            stroke-dashoffset: 48;
                            animation: stroke 0.4s 0.6s ease-in-out forwards;
                        }

                        @keyframes stroke {
                            to {
                                stroke-dashoffset: 0;
                            }
                        }
                    </style>
                </div>

                {{-- ✅ Message Section --}}
                <h2 class="text-2xl font-bold text-green-700 dark:text-green-400">
                    {{ __('Order Placed Successfully!') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-300">
                    {{ __('Thank you for your order. Your delicious food is being prepared and will be delivered soon!') }}
                </p>
            </div>
        </div>
    @else
        <div class="min-h-[80vh] flex items-center justify-center px-4 bg-white dark:bg-gray-900 transition-colors duration-300">
            <div class="text-center max-w-xl space-y-6">
                <h2 class="text-2xl font-bold text-red-700 dark:text-red-400">
                    {{ __('No Order Placed Recently') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-300">
                    {{ __('It seems like you have not placed any order in recent time. Please check orders menu from dashboard!') }}
                </p>
            </div>
        </div>
    @endif
</x-layouts.app>
