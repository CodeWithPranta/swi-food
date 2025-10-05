<x-landing-layout>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow rounded-lg py-10 my-10">
        <h2 class="text-xl font-semibold mb-4">Submit a Report</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('reports.store') }}">
            @csrf

            <!-- Order Number -->
            <div class="mb-4">
                <label for="order_no" class="block font-medium text-gray-700">Order number</label>
                <input type="text" id="order_no" name="order_no" value="{{ old('order_no') }}"
                       class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-indigo-300">
                @error('order_no') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}"
                       class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-indigo-300">
                @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" rows="5"
                          class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-indigo-300">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Submit -->
            <x-main-btn type="submit">Submit report</x-main-btn>
        </form>
    </div>
    <x-footer-section />
</x-landing-layout>