<div x-data="{ showChatbot: false }" class="relative">
    <button id="chatbot-toggler" @click="showChatbot = !showChatbot"
        class="fixed bottom-8 right-8 bg-pink-500 text-white rounded-full w-12 h-12 flex items-center justify-center">
        <span x-show="!showChatbot">+</span>
        <span x-show="showChatbot">×</span>
    </button>

    <div x-show="showChatbot" x-transition
        class="chatbot-popup fixed bottom-20 right-8 bg-white shadow-lg rounded-lg w-96 z-50">
        <div class="chat-header bg-pink-500 p-4 flex justify-between items-center rounded-lg">
            <span class="logo-text text-white font-semibold text-lg">Cupid AI Chatbot</span>
            <button @click="showChatbot = false" class="text-pink-500 rounded-full px-3 bg-white text-xl">×</button>
        </div>

        <div class="chat-body p-4 h-96 overflow-y-auto">
            @if (!empty($messages))
                @foreach ($messages as $message)
                    <div class="message flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
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
