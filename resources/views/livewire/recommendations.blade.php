<div class="h-full" id="recommendations-content">
    @if ($recommendations && count($recommendations) > 0)
        <ul class="space-y-2">
            @foreach ($recommendations as $recommendation)
                @php($recommendedUser = \App\Models\User::where('id', $recommendation['user_id'])->first())

                <a class="p-2.5 w-full h-16 bg-white flex flex-row" href="{{ route('users.profile', $recommendation['user_id'])  }}">
                    <div class="flex flex-col items-center justify-center">
                        <x-avatar class="w-10 h-10" src="{{$recommendedUser->images}}" alt="{{$recommendedUser->name}}" />
                    </div>

                    <div class="ml-2 h-full flex-grow-0">
                        <p class="text-black text-lg">{{$recommendedUser->name}}</p>
                        <p class="text-black text-sm">Similarity: {{ number_format($recommendation['similarity'] * 100, 3) }}%</p>
                    </div>
                </a>
            @endforeach
        </ul>
    @elseif ($error)
        <div class="flex flex-col justify-center items-center h-full">
            <p class="text-center">Error: {{$error}}</p>
            <button
                wire:click="fetchRecommendations"
                class="mb-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
            >
                Retry
            </button>
        </div>
    @else
        <div class="flex flex-col justify-center items-center h-full">
            <p class="text-center">Well this is awkward... Maybe you can request some recommendation from us.</p>
            <button
                wire:click="fetchRecommendations"
                class="mb-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
            >
                Request Recommendations
            </button>
        </div>
    @endif
</div>
