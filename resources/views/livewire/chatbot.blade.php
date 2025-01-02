<div x-data="{ showChatbot: false }" class="relative">
    <button id="chatbot-toggler" @click="showChatbot = !showChatbot"
        class="fixed bottom-8 right-8 bg-pink-500 text-white rounded-full w-12 h-12 flex items-center justify-center">
        <span x-show="!showChatbot">+</span>
        <span x-show="showChatbot">×</span>
    </button>

    <div x-show="showChatbot" x-transition
        class="chatbot-popup fixed bottom-20 right-8 bg-white shadow-lg rounded-lg w-96 z-50">
        <div class="chat-header bg-pink-500 p-4 flex justify-between items-center">
            <span class="logo-text text-white font-semibold text-lg">Chatbot</span>
            <button @click="showChatbot = false" class="text-white text-xl">×</button>
        </div>

        <div class="chat-body p-4 h-96 overflow-y-auto">
            @if (!empty($messages))
                @foreach ($messages as $message)
                    <div class="message {{ $message['role'] === 'user' ? 'user-message' : 'bot-message' }}">
                        <div
                            class="message-text {{ $message['role'] === 'user' ? 'bg-pink-500 text-white' : 'bg-gray-200' }}">
                            {{ $message['text'] }}
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-gray-500 text-center">No messages yet.</div>
            @endif
        </div>

        <div class="chat-footer p-4">
            <input type="text" wire:model="userMessage" class="w-full p-2 border rounded"
                placeholder="Type a message...">
            <button wire:click="sendMessage" class="bg-pink-500 text-white p-2 rounded mt-2">Send</button>
        </div>
    </div>
</div>
