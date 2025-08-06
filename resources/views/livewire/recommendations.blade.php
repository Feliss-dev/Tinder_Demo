<div class="h-full">
    @if ($recommendations && count($recommendations) > 0)
        <ul class="space-y-2">
            @foreach ($recommendations as $recommendation)
                @php($recommendedUser = \App\Models\User::where('id', $recommendation['user_id'])->first())

                <a class="p-2.5 w-full bg-white hover:bg-gray-200 focus:bg-gray-400 flex flex-row h-full cursor-pointer" @click="openModal = true; $dispatch('recommendation-info-modal-open', { user: {{$recommendedUser->id}}, infos: @js($recommendation) })">
                    <div class="flex flex-col items-center justify-center">
                        <x-avatar class="w-10 h-10 rounded-full" :user="$recommendedUser" alt="{{$recommendedUser->name}}'s Avatar" />
                    </div>

                    <div class="ml-2 h-full flex-grow-0">
                        <p class="text-black text-lg">{{$recommendedUser->name}}</p>
                        <p class="text-black text-sm">{{__('recommendation.similarity', ['percentage' => number_format($recommendation['combined_score'] * 100, 3) ])}}</p>
{{--                        <p class="text-black text-sm">Similarity: {{ number_format($recommendation['combined_score'] * 100, 3) }}%</p>--}}
                    </div>
                </a>
            @endforeach
        </ul>
    @elseif ($error)
        <div class="flex flex-col justify-center items-center h-full">
            <p class="text-center">{{$error}}</p>
            <button wire:click="fetchRecommendations" class="mt-2 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                {{__('Retry')}}
            </button>
        </div>
    @else
        <div class="flex flex-col justify-center items-center h-full">
            <p class="text-center">{{__('recommendation.no_recommend')}}</p>
            <button wire:click="fetchRecommendations" class="mt-2 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                {{__('recommendation.request')}}
            </button>
        </div>
    @endif

    <livewire:recommendation-info-modal/>
</div>
