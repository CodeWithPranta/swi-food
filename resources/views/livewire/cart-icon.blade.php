<div>
    <a href="{{ route('cart.details') }}" class="focus:ring-0 flex">
    @if ($cartCount > 0)
        <sup class="px-2 pt-0.5 md:pt-0 text-sm md:text-lg bg-red-500 text-white font-semibold rounded-full">{{ $cartCount }}</sup>
    @endif
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-5 h-5 md:w-6 md:h-6 cursor-pointer text-white mt-1.5 mr-2 md:mt-0"
                            stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
    </svg>
</a>
    <div 
        x-data="{ showSuccess: false, showError: false, success: '', error: '' }"
        x-init="
            window.addEventListener('notify', e => {
                showSuccess = true;
                success = e.detail.success;
                setTimeout(() => showSuccess = false, 3000);
            });

            window.addEventListener('cartRejected', e => {
                showError = true;
                error = e.detail.error;
                setTimeout(() => showError = false, 3000);
            });
        "
        x-cloak
    >
        <!-- Success Message -->
        <div
            x-show="showSuccess"
            class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded shadow-lg"
        >
            <span x-text="success"></span>
        </div>

        <!-- Error Message -->
        <div
            x-show="showError"
            class="fixed top-4 right-4 bg-red-500 text-white p-4 rounded shadow-lg"
        >
            <span x-text="error"></span>
        </div>
</div>

</div>
