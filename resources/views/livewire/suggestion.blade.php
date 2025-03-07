<div class="p-4 bg-white shadow rounded z-50 " id="suggestions-content" x-show="suggestionsOpen" style="max-height: 300px; overflow-y: auto;">
    <h2 class="text-xl font-semibold mb-4">User Suggestions</h2>

    <!-- Nút bấm để gửi yêu cầu -->
    <button
        wire:click="fetchSuggestions"
        class="mb-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
    >
        Refresh Suggestions
    </button>

    <!-- Hiển thị danh sách người dùng được đề xuất -->
    @if ($suggestions)
        @if (count($suggestions) > 0)
            <ul class="space-y-2">
                @foreach ($suggestions as $suggestion)
                    @if (is_array($suggestion))
                        <li class="p-3 border rounded flex justify-between items-center">
                            <div>
                                <p class="font-medium">User ID: {{ $suggestion['user_id'] }}</p>
                                <p class="text-sm text-gray-600">Shared Interests: {{ $suggestion['shared_interests'] }}</p>
                                <p class="text-sm text-gray-600">Shared Languages: {{ $suggestion['shared_languages'] }}</p>
                                <p class="text-sm text-gray-600">Same Goal: {{ $suggestion['same_goal'] }}</p>
                                <p class="text-sm text-gray-600 font-semibold">Match Score: {{ number_format($suggestion['score'], 2) }}</p>
                            </div>
                            <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                                View Profile
                            </button>
                        </li>
                    @endif
                @endforeach

            </ul>
        @else
            <p class="text-gray-600">No suggestions found at this time.</p>
        @endif
    @else
        @if ($error)
            <p class="text-red-600">{{ $error }}</p>
        @else
            <p class="text-gray-600">Loading suggestions...</p>
        @endif
    @endif
</div>
