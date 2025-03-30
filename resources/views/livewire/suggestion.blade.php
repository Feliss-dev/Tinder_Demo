<div class="h-full">
    @if ($suggestions && count($suggestions) > 0)
        <ul class="space-y-2">
            @foreach ($suggestions as $suggestion)
                @php($suggestedUser = \App\Models\User::where('id', $suggestion['user_id'])->first())

                <a class="p-2.5 w-full h-full bg-white hover:bg-gray-200 focus:bg-gray-400 flex flex-row" href="{{ route('users.profile', $suggestion['user_id'])  }}">
                    <div class="flex flex-col items-center justify-center">
                        <x-avatar class="w-10 h-10" src="{{$suggestedUser->images}}" alt="{{$suggestedUser->name}}" />
                    </div>

                    <div class="ml-2 h-full">
                        <p class="text-black text-lg">{{$suggestedUser->name}}</p>
                        <p class="text-sm text-gray-600">Shared Interests: {{ $suggestion['shared_interests'] }}</p>
                        <p class="text-sm text-gray-600">Shared Languages: {{ $suggestion['shared_languages'] }}</p>
                        <p class="text-sm text-gray-600">Same Goal: {{ $suggestion['same_goal'] }}</p>
                        <p class="text-sm text-gray-600 font-semibold">Matching: {{ number_format($suggestion['score'] * 100, 2) }}%</p>
                    </div>
                </a>
            @endforeach
        </ul>
    @elseif ($error)
        <div class="flex flex-col justify-center items-center h-full">
            <p class="text-center">Error: {{$error}}</p>
            <button wire:click="fetchSuggestions" class="mb-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                Retry
            </button>
        </div>
    @else
        <div class="flex flex-col justify-center items-center h-full">
            <p class="text-center">Well this is awkward... Maybe you can request some suggestions from us.</p>
            <button wire:click="fetchSuggestions" class="mb-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                Request Suggestions
            </button>
        </div>
    @endif
</div>
