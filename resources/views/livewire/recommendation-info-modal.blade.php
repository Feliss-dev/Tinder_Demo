<div x-cloak x-show="$wire.show" class="fixed top-0 left-0 w-screen h-screen bg-[#212121D0] z-[200] overflow-auto flex flex-col justify-center items-center">
    <section x-on:click.outside="$wire.show = false;" class="rounded-xl bg-white w-[40%] p-4">
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
                <h6 class="font-bold">{{__('recommendation.modal.similarity')}}:</h6>
                <div class="flex flex-row items-center mt-2">
                    <div class="border-2 border-gray-300 w-full h-5 rounded-full">
                        <div class="bg-violet-400 rounded-full h-full" style="width: {{$infos['similarity_score'] * 100}}%"></div>
                    </div>

                    <p class="ml-2 w-14">{{number_format($infos['similarity_score'] * 100, 3)}}%</p>
                </div>

                <h6 class="font-bold mt-2">{{__('recommendation.modal.match_probability')}}:</h6>
                <div class="flex flex-row items-center mt-2">
                    <div class="border-2 border-gray-300 w-full h-5 rounded-full">
                        <div class="bg-pink-400 rounded-full h-full" style="width: {{$infos['match_probability'] * 100}}%"></div>
                    </div>

                    <p class="ml-2 w-14">{{number_format($infos['match_probability'] * 100, 3)}}%</p>
                </div>

                <h6 class="font-bold mt-2">{{__('recommendation.modal.combined_score')}}:</h6>
                <div class="flex flex-row items-center mt-2">
                    <div class="border-2 border-gray-300 w-full h-5 rounded-full">
                        <div class="bg-green-400 rounded-full h-full" style="width: {{$infos['combined_score'] * 100}}%"></div>
                    </div>

                    <p class="ml-2 w-14">{{number_format($infos['combined_score'] * 100, 3)}}%</p>
                </div>

                <table class="w-full mt-2">
                    <thead>
                        <tr>
                            <th class="w-[33.33%]">{{__('recommendation.modal.shared_interests')}}</th>
                            <th class="w-[33.34%]">{{__('recommendation.modal.shared_languages')}}</th>
                            <th class="w-[33.33%]">{{__('recommendation.modal.same_goal')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">{{$infos['shared_interests']}}</td>
                            <td class="text-center">{{$infos['shared_languages']}}</td>
                            <td class="text-center">{{$infos['same_goal'] ? __('Yes') : __('No')}}</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <hr class="my-3"/>

            <footer class="flex flex-row justify-evenly">
                <button class="ml-3 p-3 bg-red-500 text-white font-bold rounded-lg flex flex-row items-center" @click="openModal = false; $dispatch('recommendation-info-modal-close'); $dispatch('swipedleft', { user: '{{$infos['user_id']}}' }); $dispatch('recommendation-request')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" class="mr-2" fill="white" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>

                    {{__('recommendation.modal.swipe_left')}}
                </button>

                <a class="ml-3 p-3 bg-violet-500 text-white font-bold rounded-lg cursor-pointer" href="{{ route('users.profile', $infos['user_id']) }}">{{__('recommendation.modal.visit_profile')}}</a>

                <button class="ml-3 p-3 bg-blue-500 text-white font-bold rounded-lg flex flex-row items-center" @click="openModal = false; $dispatch('recommendation-info-modal-close'); $dispatch('swipedright', { user: '{{$infos['user_id']}}' }); $dispatch('recommendation-request')">
                    {{__('recommendation.modal.swipe_right')}}

                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" class="ml-2" fill="white" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                    </svg>
                </button>
            </footer>
        @endif
    </section>
</div>
