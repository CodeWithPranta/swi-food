<div>
    <a href="" class="focus:ring-0 flex">
    @if ($cartCount > 0)
        <sup class="px-2 pt-0.5 md:pt-0 text-sm md:text-lg bg-red-500 text-white font-semibold rounded-full">{{ $cartCount }}</sup>
    @endif
    <svg fill="none" class="w-5 h-5 md:w-6 md:h-6 cursor-pointer text-white mt-1.5 mr-2 md:mt-0" stroke="currentColor" stroke-width="1.5"
        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z">
        </path>
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
