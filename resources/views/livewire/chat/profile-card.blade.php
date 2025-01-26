<div class="w-full h-full overflow-auto">
    <div style="contain: content" class=" inset-0 overflow-y-auto overflow-hidden overscroll-contain w-full  bg-white space-y-4">
        @php
            $slides = [
                'https://picsum.photos/seed/' . rand() . '/500/300',
                'https://picsum.photos/seed/' . rand() . '/500/300',
                'https://picsum.photos/seed/' . rand() . '/500/300',
            ];
        @endphp

        {{-- Carousel section --}}

        <section class="relative h-96" x-data="{ activeSlide: 1, slides: @js($slides) }">

            {{-- Sliders --}}
            <template x-for="(image, index) in slides" :key="index">
                <img x-show="activeSlide === index+1" :src="image" alt=""
                     class="absolute inset-0 pointer-events-none w-full h-full object-cover">
            </template>

            {{-- Pagination --}}
            <div draggable="true" :class="{ 'hidden': slides.length === 1 }"
                 class="absolute top-1 inset-x-0 z-10 w-full flex items-center justify-center">

                <template x-for="(image, index) in slides" :key="index">
                    <button @click="activeSlide = index+1"
                            :class="{ 'bg-white': activeSlide === index + 1, 'bg-gray-500': activeSlide !== index + 1 }"
                            class="flex-1 w-4 h-2 mx-1 rounded-full overflow-hidden"></button>
                </template>
            </div>

            {{-- Prev Button --}}
            <button draggable="true" :class="{ 'hidden': slides.length === 1 }"
                    @click="activeSlide = activeSlide === 1 ? slides.length : activeSlide - 1"
                    class="absolute left-2 top-1/2 my-auto">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-9 text-white text-bold">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>

            {{-- Next Button --}}
            <button draggable="true" :class="{ 'hidden': slides.length === 1 }"
                    @click="activeSlide = activeSlide === slides.length ? 1 : activeSlide + 1"
                    class="absolute right-2 top-1/2 my-auto">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-9 text-white text-bold">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </button>
        </section>

       

        {{-- Profile Info --}}
        <section class="grid gap-4 p-3">
            <div class="flex items-center text-3xl gap-3 text-wrap">
                <h3 class="font-bold">{{ $user->name }}</h3>
                <span class="font-semibold text-gray-800">
                        {{ $user->age }}
                    </span>
            </div>

            {{-- About --}}
            <ul>
                <li class="items-center text-gray-6000 text-lg">
                    {{ $user->birth_date }}
                </li>
                <li class="items-center text-gray-6000 text-lg">
                    <p class="mb-2 mr-4"><strong>Gender:</strong>
                    @if ($user->genders->isNotEmpty())
                        <div class="flex flex-wrap gap-2">
                            @foreach ($user->genders as $gender)

                                {{ $gender->name }}

                            @endforeach
                        </div>
                    @else
                        No data available
                        @endif
                        </p>
                </li>
                <li class="items-center text-gray-6000 text-lg">
                    <p class="mb-2 mr-4"><strong>Interests:</strong>
                    @if ($user->interests->isNotEmpty())
                        <div class="flex flex-wrap gap-2">
                            @foreach ($user->interests as $interest)
                                <span class="inline-block bg-green-100 text-green-700 border border-green-300 rounded-full px-3 py-1 text-sm font-semibold">
                                    {{ $interest->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        No data available
                        @endif
                        </p>
                </li>
            </ul>

            <hr class="-mx-2.5">

            {{-- Bio --}}
            <p class="text-gray-600">{{ $user->bio }}</p>

            {{-- Relationships Goals --}}
            <div
                class="rounded-lg bg-green-100 h-auto px-6 py-4 max-w-md flex gap-6 items-center shadow-lg">
                <div class="text-4xl text-green-700">
                    {{-- Thêm icon hoặc biểu tượng ở đây nếu cần --}}
                    🌟
                </div>
                <div class="grid w-full">
                    <span class="font-semibold text-sm text-green-600 uppercase">Looking for</span>
                    <p class="mb-2 mr-4"><strong>Dating Goal:</strong>

                    @if ($user->datingGoals->isNotEmpty())
                        <div class="flex flex-wrap gap-2">
                            @foreach ($user->datingGoals as $datingGoal)
                                <span class="text-xl text-green-700 font-medium capitalize">
                                    {{ $datingGoal->name }} 👋
                                </span>
                            @endforeach
                        </div>
                    @else
                        No data available
                        @endif
                        </p>
                </div>
            </div>

            {{-- More information --}}

            <section class="divide-y space-y-2">
                <div class="space-y-3 py-2">

                    <h3 class="font-bold text-xl">Languages i know</h3>
                    <ul class="flex flex-wrap gap-3">

                        @if ($user->languages->isNotEmpty())
                            <div class="flex flex-wrap gap-2">
                                @foreach ($user->languages as $language)
                                    <span class="inline-block bg-purple-100 text-purple-700 border border-purple-300 rounded-full px-3 py-1 text-sm font-semibold">
                                    {{ $language->name }}
                                </span>
                                @endforeach
                            </div>
                        @else
                            No data available
                        @endif
                    </ul>
                </div>

                <div class="space-y-3 py-2">
                    <h3 class="font-bold text-xl">Basics</h3>
                    <ul class="flex flex-wrap gap-3">
                        <p class="mb-2 mr-4"><strong>Desired Gender:</strong>
                        @if ($user->desiredGenders->isNotEmpty())
                            <div class="flex flex-wrap gap-2">
                                @foreach ($user->desiredGenders as $desiredGender)
                                    <span class="inline-block bg-pink-100 text-pink-700 border border-pink-300 rounded-full px-3 py-1 text-sm font-semibold">
                                        {{ $desiredGender->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            No data available
                            @endif
                            </p>
                    </ul>
                </div>
            </section>
            <button wire:confirm="Are you sure" wire:click="deleteMatch" class="py-6 border-y flex-col flex gap-2 text-gray-500 justify-center items-center">
                    <span class="font-bold">
                        Unmatch
                    </span>
                <span>
                        No longer interested?, remove them from your matches
                    </span>
            </button>
        </section>
    </div>
</div>
