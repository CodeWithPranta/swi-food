<footer class="fixed bottom-0 left-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600">
    <span class="text-sm text-gray-700 sm:text-center dark:text-gray-200">&copy;{{ date('Y')  }}
        <a href="{{config('app.url', 'https://homestaurants.com')}}" class="hover:underline">{{config('app.name', 'Laravel')}}™</a>. All Rights Reserved.
    </span>
</footer>