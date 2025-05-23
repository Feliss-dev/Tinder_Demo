<div x-cloak x-show="openModal" @click.outside="openModal = false" class="fixed top-0 left-0 w-screen h-screen bg-[#212121D0] z-[200] overflow-auto flex flex-col justify-center items-center">
    {{-- href="{{ route('users.profile', $recommendation['user_id'])  }}" --}}

    <section class="rounded-xl bg-white w-[40%] p-2">
        @if ($user != null)
            <header class="flex flex-row overflow-hidden">
                <div class="flex flex-col flex-grow-0 flex-shrink-0 basis-24">
                    <x-avatar class="w-24 h-24 rounded-full" :user="$user"/>
                </div>

                <div class="flex-grow-1 flex-shrink-1 flex flex-col ml-2 h-full flex-auto min-w-0 overflow-hidden">
                    <p class="text-lg font-semibold">{{$user->name}}</p>
                    <p class="text-sm" style="overflow-wrap: break-word;">{{$user->bio}}</p>
                </div>
            </header>

            <section class="mt-4 mx-3">
                <h6 class="font-bold">Similarity:</h6>
                <div class="flex flex-row items-center mt-2">
                    <div class="border-2 border-gray-300 w-full h-5 rounded-full">
                        <div class="bg-violet-400 rounded-full h-full" style="width: {{$infos['similarity_score'] * 100}}%"></div>
                    </div>

                    <p class="ml-2 w-14">{{number_format($infos['similarity_score'] * 100, 3)}}%</p>
                </div>

                <h6 class="font-bold mt-2">Match Probability:</h6>
                <div class="flex flex-row items-center mt-2">
                    <div class="border-2 border-gray-300 w-full h-5 rounded-full">
                        <div class="bg-pink-400 rounded-full h-full" style="width: {{$infos['match_probability'] * 100}}%"></div>
                    </div>

                    <p class="ml-2 w-14">{{number_format($infos['match_probability'] * 100, 3)}}%</p>
                </div>

                <h6 class="font-bold mt-2">Combined Score:</h6>
                <div class="flex flex-row items-center mt-2">
                    <div class="border-2 border-gray-300 w-full h-5 rounded-full">
                        <div class="bg-green-400 rounded-full h-full" style="width: {{$infos['combined_score'] * 100}}%"></div>
                    </div>

                    <p class="ml-2 w-14">{{number_format($infos['combined_score'] * 100, 3)}}%</p>
                </div>

                <table class="w-full mt-2">
                    <thead>
                        <tr>
                            <th class="w-[33.33%]">Shared interests</th>
                            <th class="w-[33.34%]">Shared languages</th>
                            <th class="w-[33.33%]">Same goal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">{{$infos['shared_interests']}}</td>
                            <td class="text-center">{{$infos['shared_languages']}}</td>
                            <td class="text-center">{{$infos['same_goal'] ? 'Yes' : 'No'}}</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <hr class="my-3"/>

            <footer class="flex flex-row justify-end">
                <a @click="openModal = false; $dispatch('recommendation-info-modal-close')" class="p-3 bg-red-500 text-white font-bold rounded-lg cursor-pointer">Cancel</a>
                <a class="ml-3 p-3 bg-violet-500 text-white font-bold rounded-lg cursor-pointer" href="{{ route('users.profile', $infos['user_id']) }}">Visit Profile</a>
                <button class="ml-3 p-3 bg-blue-500 text-white font-bold rounded-lg" @click="openModal = false; $dispatch('recommendation-info-modal-close'); $dispatch('swipedright', { user: '{{$infos['user_id']}}' }); $dispatch('recommendation-request')">Swipe Right</button>
            </footer>
        @else
            <div class="w-full flex flex-row justify-center items-center">
                <section>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" class="size-12 fill-neutral-600 motion-safe:animate-spin mx-auto">
                        <path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25" />
                        <path d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z" />
                    </svg>

                    <p class="mt-3">Loading information...</p>
                </section>
            </div>
        @endif
    </section>
</div>
