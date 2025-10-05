<div x-data="{ open: false }" class="relative w-full h-[85vh] flex flex-col md:flex-row rounded-xl shadow bg-white dark:bg-gray-900 overflow-hidden">

    <!-- 🔴 Left Sidebar (Users List) -->
    <div 
        class="absolute md:static inset-0 md:inset-auto w-3/4 md:w-1/4 h-full bg-gray-50 dark:bg-gray-800 transform md:transform-none transition-transform duration-300 z-20"
        :class="open ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
    >
        <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
            <div class="font-bold text-gray-700 dark:text-gray-200">
                Chats
            </div>
            <!-- Close button (mobile only) -->
            <button class="md:hidden text-gray-500 dark:text-gray-300" @click="open=false">✖</button>
        </div>

        <!-- 🔽 Scrollable user list -->
        <div class="overflow-y-auto h-[calc(100%-3rem)] divide-y dark:divide-gray-700">
            @if (auth()->user()->user_type != "2")
                @foreach($homerestaurants as $vendor)
                    <div wire:click="selectHomestaurant({{$vendor->id}})" class="p-3 cursor-pointer hover:bg-red-100 dark:hover:bg-red-900/40 transition
                        {{ $selectedHomestaurant && $selectedHomestaurant->id === $vendor->id ? 'bg-gray-200 dark:bg-gray-900/50' : '' }}">
                        <div class="text-gray-800 dark:text-gray-100 font-medium">{{ $vendor->kitchen_name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $vendor->user->email }}</div>
                    </div>
                @endforeach
            @else 
                @foreach($homerestaurants as $vendor)
                    <div wire:click="selectHomestaurant({{$vendor->id}})" class="p-3 cursor-pointer hover:bg-red-100 dark:hover:bg-red-900/40 transition
                        {{ $selectedHomestaurant && $selectedHomestaurant->id === $vendor->id ? 'bg-gray-200 dark:bg-gray-900/50' : '' }}">
                        <div class="text-gray-800 dark:text-gray-100 font-medium">{{ $vendor->name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $vendor->email }}</div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- 🔴 Right Chat Section -->
    <div class="flex flex-col w-full md:w-3/4 h-full">

        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
            @if (auth()->user()->user_type != "2")
            <div>
                <div class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $selectedHomestaurant->kitchen_name }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $selectedHomestaurant->user->email }}</div>
            </div>
            @else
            <div>
                <div class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $selectedHomestaurant->name }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $selectedHomestaurant->email }}</div>
            </div>
            @endif
            <!-- 3 Dot Menu Button (mobile only) -->
            <button class="md:hidden p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700"
                    @click="open = true">
                <!-- Heroicons Ellipsis Vertical -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zm0 6a.75.75 0 110-1.5.75.75 0 010 1.5zm0 6a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                </svg>
            </button>
        </div>

        <div id="chat-box" class="flex-1 p-4 overflow-y-auto space-y-3 bg-gray-50 dark:bg-gray-900">
            @foreach($messages as $message)
                @php
                    // Ensure created_at exists
                    $createdAt = $message['created_at'] ?? ($message->created_at ?? now());
                @endphp
                <div class="flex {{ $message['sender_id'] === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="message-container relative max-w-xs">
                        <!-- Small timestamp container (empty initially) -->
                        <div class="timestamp text-xs text-gray-400 dark:text-gray-300 mb-1" style="display:none;"></div>
                        
                        <!-- Actual message -->
                        <div 
                            class="message px-4 py-2 rounded-2xl shadow text-white cursor-pointer
                                {{ $message['sender_id'] === auth()->id() ? 'bg-red-500 rounded-br-none' : 'bg-gray-500 rounded-bl-none' }}"
                            data-timestamp="{{ $createdAt }}"
                        >
                            {{ $message['message'] }}
                        </div>
                    </div>
                </div>
            @endforeach

        </div>



        <!-- Input -->
        <div class="p-3 border-t dark:border-gray-700 flex items-center gap-2 bg-white dark:bg-gray-800">
            <form wire:submit="submit" class="flex w-full gap-2">
                <input type="text" placeholder="Type your message..."
                   wire:model.live="newMessage"
                   class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 dark:bg-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500">
                <button type="submit" class="px-5 cursor-pointer py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 dark:hover:bg-red-500 transition focus:outline-none">
                    Send
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('click', function(e) {
    // Only handle clicks on messages
    if (e.target.classList.contains('message')) {
        const message = e.target;
        const timestampDiv = message.parentElement.querySelector('.timestamp');
        const timestamp = message.getAttribute('data-timestamp');
        if (!timestamp) return;

        const date = new Date(timestamp);
        const options = { year: 'numeric', month: 'short', day: '2-digit', hour: '2-digit', minute: '2-digit', hour12: true };
        const formatted = date.toLocaleString('en-US', options);

        timestampDiv.innerText = `Sent at: ${formatted}`;
        timestampDiv.style.display = 'block'; // show timestamp
    }
});
</script>





