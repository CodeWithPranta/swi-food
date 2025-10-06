<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-favicon />
    <meta property="og:image" content="">
    <meta property="og:description" content="Home made foods and groceries selling marketplace.">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <x-colors />
    <livewire:styles />
</head>

<body class="bg-gray-50">
    <nav class="fixed top-0 left-0 z-20 w-full bg-red-700 shadow-md">
        <div class="flex flex-wrap items-center justify-between max-w-screen-xl p-4 mx-auto">
            <x-logo class="w-40 md:w-48" />
            <div class="flex md:order-2">
                <livewire:cart-icon class="mr-4">
                <button data-collapse-toggle="navbar-sticky" type="button"
                    class="inline-flex items-center cursor-pointer p-1 text-sm text-gray-50 rounded-lg md:hidden focus:ring-0"
                    aria-controls="navbar-sticky" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
                <ul
                    class="flex flex-col p-4 mt-4 text-lg font-medium border border-gray-100 rounded-lg md:p-0 md:flex-row md:space-x-8 md:mt-0 md:border-0 bg-red-700">

                    @if (Route::has('login'))
                        <x-dropdown>
                            <x-slot name="dropdown_name">
                                @auth
                                    {{ auth()->user()->name }}
                                @else
                                    {{ __('Login & registration area') }}
                                @endauth
                            </x-slot>

                            @auth('web')
                                <x-dropdown-link
                                    href="{{ route('settings.profile') }}">{{ __('Profile') }}</x-dropdown-link>
                                    <!-- <form action="{{route('logout')}}" method="POST">
                                        @csrf
                                        <button type="submit" class="pl-4 flex cursor-pointer border w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mt-1.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                               <path stroke-linecap="round" stroke-linejoin="round" d="M12 9.75 14.25 12m0 0 2.25 2.25M14.25 12l2.25-2.25M14.25 12 12 14.25m-2.58 4.92-6.374-6.375a1.125 1.125 0 0 1 0-1.59L9.42 4.83c.21-.211.497-.33.795-.33H19.5a2.25 2.25 0 0 1 2.25 2.25v10.5a2.25 2.25 0 0 1-2.25 2.25h-9.284c-.298 0-.585-.119-.795-.33Z" />
                                            </svg> Logout
                                        </button>
                                    </form> -->
                                @if (auth()->user()->user_type === 2 && optional(auth()->user()->vendorApplication)->is_approved === 1)
                                    <x-dropdown-link
                                        href="{{ route('filament.vendor.pages.dashboard') }}">{{ __('Homestaurant Dashboard') }}</x-dropdown-link>
                                @elseif (auth()->user()->user_type === 2)
                                    <x-dropdown-link href="{{route('homestaurant.application')}}">{{ __('Application Form') }}</x-dropdown-link>
                                @endif
                            @else
                                <x-dropdown-link href="{{ route('login') }}">{{ __('Login') }}</x-dropdown-link>
                                <x-dropdown-link href="{{ route('register') }}">{{ __('Register') }}</x-dropdown-link>
                            @endauth
                        </x-dropdown>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>
    
     <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <livewire:scripts />
</body>

</html>
