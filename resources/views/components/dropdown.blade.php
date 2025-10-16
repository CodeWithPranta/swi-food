<li>
    <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar"
        class="flex items-center justify-between w-full py-2 pl-3 pr-4 text-gray-900 rounded hover:text-gray-950 md:hover:bg-transparent md:border-0 md:hover:text-gray-900 cursor-pointer md:p-0 md:w-auto">{{ $dropdown_name }}
        <svg class="w-5 h-5 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd"></path>
        </svg></button>
    <!-- Dropdown menu -->
    <div id="dropdownNavbar"
        class="w-3/4 sm:w-3/5 md:w-3/6 lg:w-2/7 z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow">
        <ul class="py-2 text-gray-900 hover:text-gray-950" aria-labelledby="dropdownLargeButton">
            {{ $slot }}
        </ul>
    </div>
</li>
