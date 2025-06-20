<x-landing-layout>
    <div class="min-h-[80vh] flex items-center justify-center px-4">
        <div class="text-center max-w-xl space-y-6">
            {{-- ✅ Animated SVG --}}
            <div class="mx-auto w-24 h-24">
                <svg class="mx-auto w-full h-full text-green-600" viewBox="0 0 52 52">
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
            @if(session('status') === 'submitted')
                <h2 class="text-2xl font-bold text-green-700">
                    {{ __('Thanks for opening a homestaurant request!') }}
                </h2>
                <p class="text-gray-600">
                    {{ __('Admin will review your application soon. We’ll notify you once it is approved.') }}
                </p>
            @elseif(auth()->check() && auth()->user()->vendorApplication?->is_approved)
                <h2 class="text-2xl font-bold text-green-700">
                    {{ __('Congratulations! Your homestaurant is approved 🎉') }}
                </h2>
                <p class="text-gray-600">
                    {{ __('We’ve reviewed and approved your application. You can now start selling your homemade food.') }}
                </p>
            @else
                <h2 class="text-xl font-semibold text-zinc-800">
                    {{ __('You already applied to open a homestaurant.') }}
                </h2>
                <p class="text-gray-600">
                    {{ __('We’ll let you know once your application is reviewed.') }}
                </p>
            @endif
        </div>
    </div>
    
    <x-footer-section />
</x-landing-layout>
