<x-layouts.app title="Upgrade Account">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Switch to Homestaurant') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">
            {{ __('General user to Homestaurant owner') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @php
        $user = Auth::user();
        $vendorApplication = $user->vendorApplication ?? null;
    @endphp

    <div class="mt-8 text-center">
        @if ($user->user_type === 2)
            {{-- Homestaurant user --}}
            <div class="p-6 bg-green-50 border border-green-200 rounded-xl inline-block">
                <p class="text-green-700 font-semibold mb-3">
                    ✅ You are registered as a Homestaurant owner.
                </p>

                @if ($vendorApplication)
                    @if ($vendorApplication->is_approved)
                        <p class="text-gray-600 mt-2">
                            Account Status: <strong>Active</strong>
                        </p>
                    @else
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg mt-3">
                            <p class="text-yellow-700 font-semibold">
                                🕓 Your Homestaurant application is under review.
                            </p>
                            <p class="text-gray-600 mt-2">
                                Please wait for admin approval.
                            </p>
                        </div>
                    @endif
                @else
                    {{-- User upgraded but hasn't applied --}}
                    <div class="mt-3">
                        <a href="{{ route('homestaurant.application') }}"
                           class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-200 inline-block">
                            Apply to Become a Homestaurant Owner
                        </a>
                    </div>
                @endif
            </div>
        @else
            {{-- Normal user - show upgrade form --}}
            <form action="{{ route('account.upgrade') }}" method="POST" class="inline-block">
                @csrf
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-200">
                    Upgrade to Homestaurant
                </button>
            </form>
        @endif
    </div>

    {{-- Flash message --}}
    @if (session('status'))
        <div class="mt-6 text-center">
            <p class="text-blue-600 font-medium">{{ session('status') }}</p>
        </div>
    @endif
</x-layouts.app>
