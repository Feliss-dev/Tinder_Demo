<div class="p-4 bg-white shadow rounded z-50 " id="recommendations-content" x-show="recommendationsOpen" style="max-height: 300px; overflow-y: auto;">
    <h2 class="text-xl font-semibold mb-4">Recommended Users</h2>

    <!-- Nút bấm để gửi yêu cầu -->
    <button
        wire:click="fetchRecommendations"
        class="mb-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
    >
        Get Recommendations
    </button>

    <!-- Hiển thị danh sách người dùng được đề xuất -->
    @if ($recommendations)
        @if (count($recommendations) > 0)
            <ul class="space-y-2">
                @foreach ($recommendations as $recommendation)
                    <li class="p-3 border rounded flex justify-between items-center">
                        <div>
                            <p class="font-medium">User ID: {{ $recommendation['user_id'] }}</p>
                            <p class="text-sm text-gray-600">Similarity: {{ number_format($recommendation['similarity'], 2) }}</p>
                        </div>
                        <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" wire:click="viewProfile({{ $recommendation['user_id'] }})">
                            View Profile
                        </button>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-600">No recommendations found for you at this time.</p>
        @endif
    @else
        <p class="text-red-600">Failed to load recommendations. Please try again later.</p>
    @endif
</div>
