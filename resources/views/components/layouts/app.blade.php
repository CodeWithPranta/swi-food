<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
        <div class="mt-15"></div>
        <p class="fixed bottom-0 left-0 right-0 text-sm text-gray-700 dark:text-gray-300 text-center bg-gray-100 dark:bg-gray-800 py-2">&copy;{{date('Y')}} by {{config('app.name', 'Laravel')}}</p>
    </flux:main>
</x-layouts.app.sidebar>
