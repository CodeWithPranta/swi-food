<x-landing-layout>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-lg my-10 py-10">
        <h2 class="text-2xl font-bold mb-4">Contact Us</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('contact.store') }}">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                       class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-indigo-300">
                @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-indigo-300">
                @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Subject -->
            <div class="mb-4">
                <label for="subject" class="block font-medium text-gray-700">Subject</label>
                <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                       class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-indigo-300">
                @error('subject') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Message -->
            <div class="mb-4">
                <label for="message" class="block font-medium text-gray-700">Message</label>
                <textarea id="message" name="message" rows="5"
                          class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-indigo-300">{{ old('message') }}</textarea>
                @error('message') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Submit -->
            <x-main-btn type="submit">Send message</x-main-btn>
        </form>
    </div>
    <x-footer-section />
</x-landing-layout>
