<div x-data="{ showChatbot: false }" class="relative h-0">
    <button id="chatbot-toggler" @click="showChatbot = !showChatbot"
        class="fixed bottom-8 right-8 bg-pink-500 text-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-300 ">
        <!-- Hint Text -->
        <div x-show="!showChatbot" class="absolute -top-10 bg-pink-500 text-white text-xs font-medium px-2 py-1 rounded-lg shadow-md">
            Cupid AI
        </div>

        <!-- Icon States -->
        <span x-show="!showChatbot" className="z-10">
            <img src="/chatbot.png" alt="Chatbot" className="w-5 h-5" />
        </span>
        <span x-show="showChatbot" className="z-10">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="30" height="30" viewBox="0 0 50 50">
                <path d="M 5.9199219 6 L 20.582031 27.375 L 6.2304688 44 L 9.4101562 44 L 21.986328 29.421875 L 31.986328 44 L 44 44 L 28.681641 21.669922 L 42.199219 6 L 39.029297 6 L 27.275391 19.617188 L 17.933594 6 L 5.9199219 6 z M 9.7167969 8 L 16.880859 8 L 40.203125 42 L 33.039062 42 L 9.7167969 8 z"></path>
                </svg>
        </span>
    </button>

    <div x-cloak x-show="showChatbot" x-transition
        class="chatbot-popup fixed bottom-20 right-8 bg-white shadow-lg rounded-lg w-96 z-50">
        <div class="chat-header bg-pink-500 p-4 flex justify-between items-center rounded-lg">
            <span class="logo-text text-white font-semibold text-lg">Cupid AI Chatbot</span>
            <button @click="showChatbot = false" class="text-pink-500 rounded-full px-3 bg-white text-xl">×</button>
        </div>

        <div class="chat-body p-4 h-96 overflow-y-auto">
            @if (!empty($messages))
                @foreach ($messages as $message)
                    <div class="my-2 flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div
                            class="message-text max-w-xs px-4 py-2 rounded-lg shadow-md {{ $message['role'] === 'user' ? 'bg-pink-500 text-white' : 'bg-gray-200' }}">
                            {{ $message['text'] }}
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-gray-600 text-bold text-center">Hi! I'm your Cupid AI, how can I help you today?</div>
            @endif
        </div>

        <div class="chat-footer p-4">
            <form wire:submit.prevent="sendMessage" class="chat-form flex items-center">
                <input type="text" wire:model="userMessage"
                    class="flex-grow border-none focus:outline-none focus:ring-2 rounded-lg px-4 py-2"
                    placeholder="Type a message..." required>
                <button type="submit"
                    class="bg-pink-500 ml-2 text-white rounded-full w-10 h-10 flex items-center justify-center">
                    ➤
                </button>
            </form>
        </div>
    </div>
</div>
