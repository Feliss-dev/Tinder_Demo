<div id="tinder" class="m-auto md:pl-4 md:pr-4 w-full h-full relative overflow-hidden">
    <div class="grid grid-cols-4 h-full">
        <!-- Filtering Form -->
        <div>
            <form wire:submit.prevent="applyFilters" class="bg-white p-6 rounded-lg shadow-lg flex flex-col space-y-6 mt-3">
                <!-- Name search -->
                <div class="w-full">
                    <label for="searchTerm">Search Name</label>
                    <input type="text" id="searchTerm" wire:model="searchTerm" placeholder="Enter name..."
                           class="w-full border p-2 rounded-md">
                </div>

                <!-- Age From -->
                <div class="w-full">
                    <label for="ageFrom">Age From</label>
                    <input type="number" id="ageFrom" min="0" max="120" wire:model="ageFrom"
                           placeholder="Min Age" class="w-full border p-2 rounded-md">
                </div>

                <!-- Age To -->
                <div class="w-full">
                    <label for="ageTo">Age To</label>
                    <input type="number" id="ageTo" min="0" max="120" wire:model="ageTo"
                           placeholder="Max Age" class="w-full border p-2 rounded-md">
                </div>

                <!-- Gender Filter -->
                <div class="w-full">
                    <label for="gender">Gender</label>
                    <select id="gender" wire:model="gender" class="w-full border p-2 rounded-md">
                        <option value="">Any</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="w-full">
                    <button type="submit"
                            class="w-full bg-tinder text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-200">Filter</button>
                </div>
            </form>
        </div>

        <!-- User Swipe -->
        <div class="col-span-3">
            <!-- Main User Swiping -->
            <div class="relative h-full md:h-[600px] w-full md:w-96 m-auto flex items-center justify-center">
                @if($users->isEmpty())
                    <div class="col-span-full text-center ">
                        <p class="text-gray-500">No suitable user found.</p>
                    </div>
                @else
                    @foreach ($users as $i => $user)
                        <div @swipedright.window="console.log('right')"
                             @swipedleft.window="console.log('left')"
                             @swipedup.window="console.log('up')" wire:key="swipe-{{ $user->id }}" x-data="{
                                profile: false,
                                isSwiping: false,
                                swipingLeft: false,
                                swipingRight: false,
                                swipingUp: false,
                                swipeRight: function() {
                                    moveOutWidth = document.body.clientWidth * 1.5;
                                    $el.style.transform = 'translate(' + moveOutWidth + 'px, -100px ) rotate(-30deg)';

                                    setTimeout(() => {
                                        $el.remove();
                                    }, 300);

                                    $dispatch('swipedright', { user: '{{ $user->id }}' });
                                },
                                swipeLeft: function() {
                                    moveOutWidth = document.body.clientWidth * 1.5;
                                    $el.style.transform = 'translate(' + -moveOutWidth + 'px, -100px ) rotate(-30deg)';

                                    setTimeout(() => {
                                        $el.remove();
                                    }, 300);

                                    $dispatch('swipedleft', { user: '{{ $user->id }}' });
                                },
                                swipeUp: function() {
                                    moveOutWidth = document.body.clientWidth * 1.5;
                                    $el.style.transform = 'translate(0px, ' + -moveOutWidth + 'px) rotate(-30deg)';

                                    setTimeout(() => {

                                        $el.remove();
                                    }, 300);

                                    $dispatch('swipedup', { user: '{{ $user->id }}' });
                                }
                            }"
                             x-init="element = $el;

                            // Initialize hammer.js
                            var hammertime = new Hammer(element);

                            // Let pan support all directions
                            hammertime.get('pan').set({
                                direction: Hammer.DIRECTION_ALL,
                                touchAction: 'pan'
                            });

                            // On Pan
                            hammertime.on('pan', function(event) {
                                isSwiping = true;

                                if (event.deltaX === 0) return;
                                if (event.center.x === 0 && event.center.y === 0) return;

                                // Swiped right
                                if (event.deltaX > 20) {
                                    swipingLeft = false;
                                    swipingRight = true;
                                    swipingUp = false;
                                }
                                // Swiped left
                                else if (event.deltaX < -20) {
                                    swipingLeft = true;
                                    swipingRight = false;
                                    swipingUp = false;
                                }
                                // Super like Swipe
                                else if (event.deltaY < -50 && Math.abs(event.deltaX) < 20) {
                                    swipingLeft = false;
                                    swipingRight = false;
                                    swipingUp = true;
                                }

                                // Rotate
                                var rotate = event.deltaX / 10;

                                // Apply transformation to rotate only in X direction in somewhat Clockwise and Anti Clockwise
                                event.target.style.transform = 'translate(' + event.deltaX + 'px,' + event.deltaY + 'px) rotate(' + rotate + 'deg)';
                            });

                            hammertime.on('panend', function(event) {
                                // Reset State
                                isSwiping = false;
                                swipingLeft = false;
                                swipingRight = false;
                                swipingUp = false;

                                // Set threshold
                                var horizontalThreshold = 200;
                                var verticalThreshold = 200;

                                // Velocity threshold
                                var velocityXThreshold = 0.5;
                                var velocityYThreshold = 0.5;

                                // Determine keep
                                var keep = Math.abs(event.deltaX) < horizontalThreshold && Math.abs(event.velocityX) < velocityXThreshold &&
                                    Math.abs(event.deltaY) < verticalThreshold && Math.abs(event.velocityY) < velocityYThreshold;

                                if (keep) {
                                    // Adjust the duration and timing as needed
                                    event.target.style.transition = 'transform 0.3s ease-in-out';
                                    event.target.style.transform = '';
                                    $el.style.transform = '';

                                    // Clear the transition
                                    setTimeout(() => {
                                        event.target.style.transition = '';
                                        event.target.style.transform = '';
                                        $el.style.transform = '';
                                    }, 300); // Use the same duration
                                } else {
                                    var moveOutWidth = document.body.clientWidth;
                                    var moveOutHeight = document.body.clientHeight;

                                    if (event.deltaX > 20) {
                                        event.target.style.transform = 'translate(' + moveOutWidth + 'px, 10px)';
                                        $dispatch('swipedright', { user: '{{ $user->id }}' });
                                    } else if (event.deltaX < -20) {
                                        event.target.style.transform = 'translate(' + -moveOutWidth + 'px, 10px)';
                                        $dispatch('swipedleft', { user: '{{ $user->id }}' });
                                    } else if (event.deltaY < -50 && Math.abs(event.deltaX) < 20) {
                                        event.target.style.transform = 'translate(0px, ' + -moveOutHeight + 'px)';
                                        $dispatch('swipedup', { user: '{{ $user->id }}' });
                                    }

                                    event.target.remove();
                                    $el.remove();
                                }
                            });" :class="{ 'transform-none cursor-grab': isSwiping }"
                             class="absolute inset-0 m-auto transform ease-in-out duration-300 rounded-xl cursor-pointer z-50">

                            <!-- Swipe Card -->
                            <div x-show="!profile" x-transition.duration.150ms.origin.bottom class="relative overflow-hidden w-full h-full rounded-xl bg-cover bg-white">
                                @php
                                    $userImages = $user->images()->pluck('image_path')->toArray();
                                    $isFakeUser = $user->isFake();

                                    $slides = !empty($userImages)
                                        ? $userImages
                                        : [
                                            'https://randomuser.me/api/portraits/women/' . rand(0, 99) . '.jpg',
                                            'https://picsum.photos/seed/' . rand() . '/500/300',
                                            'https://randomuser.me/api/portraits/women/' . rand(0, 99) . '.jpg',
                                        ];
                                @endphp

                                <!-- Carousel section -->
                                <section x-data="{ activeSlide: 1, slides: @js($slides) }">
                                    <!-- Sliders -->
                                    <template x-for="(image, index) in slides" :key="index">
                                        <img :src="(image.startsWith('http') ? image : '/storage/' + image)" alt="User Image"
                                             class="absolute inset-0 pointer-events-none w-full h-full object-cover"
                                             :lazy-src="'/storage/' + image" x-show="activeSlide === index"
                                             x-transition:enter="transition-opacity ease-out duration-500"
                                             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition-opacity ease-in duration-500"
                                             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" />
                                    </template>

                                    <!-- Pagination -->
                                    <div :class="{ 'hidden': slides.length === 1 }"
                                         class="absolute top-1 inset-x-0 z-10 w-full flex items-center justify-center">
                                        <template x-for="(image, index) in slides" :key="index">
                                            <button @click="activeSlide = index"
                                                    :class="{ 'bg-white': activeSlide === index, 'bg-gray-500': activeSlide !== index }"
                                                    class="flex-1 w-4 h-2 mx-1 rounded-full overflow-hidden transition-colors duration-300"></button>
                                        </template>
                                    </div>

                                    <!-- Prev Button -->
                                    <button :class="{ 'hidden': slides.length === 1 }"
                                            @click="activeSlide = activeSlide === 0 ? slides.length - 1 : activeSlide - 1"
                                            class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                        </svg>
                                    </button>

                                    <!-- Next Button -->
                                    <button :class="{ 'hidden': slides.length === 1 }"
                                            @click="activeSlide = activeSlide === slides.length - 1 ? 0 : activeSlide + 1"
                                            class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 p-2 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                        </svg>
                                    </button>
                                </section>

                                <!-- Swiper indicators -->
                                <div class="pointer-events-none">
                                    <span x-cloak :class="!swipingRight ? 'invisible' : ''"
                                          class="border-2 rounded-md p-1 px-2 border-green-500 text-green-500 text-4xl -rotate-12 capitalize font-extrabold top-10 left-5 absolute z-5">
                                        LIKE
                                    </span>

                                    <span x-cloak :class="!swipingLeft ? 'invisible' : ''"
                                          class="border-2 rounded-md p-1 px-2 border-red-500 text-red-500 text-4xl rotate-12 capitalize font-extrabold top-10 right-5 absolute z-5">
                                        NOPE
                                    </span>

                                    <span x-cloak :class="!swipingUp ? 'invisible' : ''"
                                          class="border-2 rounded-md p-1 px-2 border-green-500 text-green-500 text-4xl -rotate-12 capitalize font-extrabold bottom-48 max-w-fit inset-x-0 mx-auto absolute z-5">
                                        SUPER LIKE
                                    </span>
                                </div>

                                <!-- Information and Actions -->
                                <section
                                    class="absolute inset-x-0 bottom-0 inset-y-1/2 py-2 bg-gradient-to-t from-black to-black/0 pointer-events-none">

                                    <div class="flex flex-col h-full gap-2.5 mt-auto p-5 text-white">
                                        <!-- Personal Details -->
                                        <div class="grid grid-cols-12 items-center">
                                            <div class="col-span-10">
                                                <h4 class="font-bold text-3xl">
                                                    {{ $user->name }} {{ $user->age }}
                                                </h4>
                                                <p class="text-lg line-clamp-3">
                                                    {{ $user->bio }}
                                                </p>
                                            </div>
                                            <!-- Open profile -->
                                            <div class="col-span-2 justify-end flex pointer-events-auto">
                                                <button @click="profile =!profile " draggable="true">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                         fill="currentColor" class="bi bi-info-circle-fill text-white w-5 h-5"
                                                         viewBox="0 0 16 16">
                                                        <path
                                                            d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Action -->
                                        <div class="grid grid-cols-5 gap-2 items-center mt-auto">

                                            <!-- Rewind -->
                                            <div>
                                                <button draggable="true"
                                                        class="rounded-full border-2 pointer-events-auto group border-yellow-600 p-3 shrink-0 max-w-fit flex items-center text-yellow-600">

                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                         stroke-width="1.5" stroke="currentColor"
                                                         class="w-9 h-9 shrink-0 m-auto group:hover-scale-10 stroke-current transition-transform stroke-2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <!-- Swipe Left -->
                                            <div>
                                                <button draggable="true" @click="swipeLeft()"
                                                        class="rounded-full border-2 pointer-events-auto group border-red-600 p-3 shrink-0 max-w-fit flex items-center text-red-600">

                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                         stroke-width="3" stroke="currentColor"
                                                         class="w-9 h-9 shrink-0 m-auto group:hover-scale-10 stroke-current transition-transform stroke-2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M6 18 18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <!-- Super Like -->
                                            <div>
                                                <button draggable="true" @click="swipeUp()"
                                                        class="rounded-full border-2 pointer-events-auto group border-blue-600 p-3 shrink-0 max-w-fit flex items-center text-blue-600 scale-95">

                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                         stroke-width="1.5" stroke="currentColor"
                                                         class="w-10 h-10 shrink-0 m-auto group:hover-scale-10 stroke-current transition-transform stroke-2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <!-- Swipe Right -->
                                            <div>
                                                <button draggable="false" @click="swipeRight()"
                                                        class="rounded-full border-2 pointer-events-auto group border-green-600 p-3 shrink-0 max-w-fit flex items-center text-green-600">

                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                         stroke-width="1.5" stroke="currentColor"
                                                         class="w-9 h-9 shrink-0 m-auto group:hover-scale-10 stroke-current transition-transform stroke-2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <!-- Thunder Bolt -->
                                            <div>
                                                <button draggable="false"
                                                        class="rounded-full border-2 pointer-events-auto group border-purple-600 p-3 shrink-0 max-w-fit flex items-center text-purple-600">

                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                         fill="currentColor"
                                                         class="w-9 h-9 shrink-0 m-auto group:hover-scale-10 stroke-current transition-transform stroke-2">
                                                        <path fill-rule="evenodd"
                                                              d="M14.615 1.595a.75.75 0 0 1 .359.852L12.982 9.75h7.268a.75.75 0 0 1 .548 1.262l-10.5 11.25a.75.75 0 0 1-1.272-.71l1.992-7.302H3.75a.75.75 0 0 1-.548-1.262l10.5-11.25a.75.75 0 0 1 .913-.143Z"
                                                              clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>

                            <!-- Profile Card -->
                            <div x-cloak x-show="profile" x-transition.duration.150ms.orgin.top draggable="true"
                                 style="contain: content"
                                 class="absolute inset-0 overflow-y-auto overflow-hidden overscroll-contain border rounded-xl bg-white space-y-4">

                                @php
                                    $userImages = $user->images()->pluck('image_path')->toArray();
                                    $isFakeUser = $user->isFake();
                                    $slides = !empty($userImages)
                                        ? $userImages
                                        : [
                                            'https://picsum.photos/seed/' . rand() . '/500/300',
                                            'https://randomuser.me/api/portraits/women/' . rand(0, 99) . '.jpg',
                                            'https://picsum.photos/seed/' . rand() . '/500/300',
                                        ];
                                @endphp

                                    <!-- Carousel -->
                                <section class="relative h-96" x-data="{ activeSlide: 1, slides: @js($slides) }">
                                    <!-- Sliders -->
                                    <template x-for="(image, index) in slides" :key="index">
                                        <img :src="(image.startsWith('http') ? image : '/storage/' + image)" alt="User Image"
                                             class="absolute inset-0 pointer-events-none w-full h-full object-cover"
                                             :lazy-src="'/storage/' + image" x-show="activeSlide === index"
                                             x-transition:enter="transition-opacity ease-out duration-500"
                                             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition-opacity ease-in duration-500"
                                             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" />
                                    </template>

                                    <!-- Pagination -->
                                    <div :class="{ 'hidden': slides.length === 1 }"
                                         class="absolute top-1 inset-x-0 z-10 w-full flex items-center justify-center">
                                        <template x-for="(image, index) in slides" :key="index">
                                            <button @click="activeSlide = index"
                                                    :class="{ 'bg-white': activeSlide === index, 'bg-gray-500': activeSlide !== index }"
                                                    class="flex-1 w-4 h-2 mx-1 rounded-full overflow-hidden"></button>
                                        </template>
                                    </div>

                                    <!-- Prev Button -->
                                    <button :class="{ 'hidden': slides.length === 1 }"
                                            @click="activeSlide = activeSlide === 0 ? slides.length - 1 : activeSlide - 1"
                                            class="absolute left-2 top-1/2 transform -translate-y-1/2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="size-9 text-white text-bold">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15.75 19.5 8.25 12l7.5-7.5" />
                                        </svg>
                                    </button>

                                    <!-- Next Button -->
                                    <button :class="{ 'hidden': slides.length === 1 }"
                                            @click="activeSlide = activeSlide === slides.length - 1 ? 0 : activeSlide + 1"
                                            class="absolute right-2 top-1/2 transform -translate-y-1/2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="size-9 text-white text-bold">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                        </svg>
                                    </button>

                                    <!-- Close Profile Button -->
                                    <button @click="profile=false"
                                            class="absolute -bottom-4 right-3 bg-tinder p-3 hover:scale-110 transition-transform rounded-full max-w-fit max-h-fit text-white ">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="3" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                                        </svg>
                                    </button>
                                </section>

                                <!-- Profile Info -->
                                <section class="grid gap-4 p-3">
                                    <div class="flex items-center text-3xl gap-3 text-wrap">
                                        <h3 class="font-bold">{{ $user->name }}</h3>
                                        <span class="font-semibold text-gray-800">
                                            {{ $user->age }}
                                        </span>
                                    </div>

                                    <!-- About -->
                                    <ul>
                                        <li class="items-center text-gray-6000 text-lg">
                                            {{ $user->birth_date }}
                                        </li>
                                        <li class="items-center text-gray-6000 text-lg">
                                            {{ $user->gender }}
                                        </li>
                                        <li class="items-center text-gray-6000 text-lg">
                                            {{ $user->interests }}
                                        </li>
                                    </ul>
                                    <hr class="-mx-2.5">

                                    <!-- Bio -->
                                    <p class="text-gray-600">{{ $user->bio }}</p>

                                    <!-- Relationships Goals -->
                                    <div class="rounded-xl bg-green-200 h-24 px-4 py-2 max-w-fit flex gap-4 items-center">
                                        <div class="text-3xl"></div>
                                        <div class="grid w-4/5">

                                            <span class="font-bold text-sm text-green-800">Looking for</span>
                                            <span class="text-lg text-green-800 capitalize">
                                                {{ $user->dating_goal }}👋</span>
                                        </div>
                                    </div>

                                    <!-- More information -->
                                    @if ($user->languages)
                                        <section class="divide-y space-y-2">
                                            <div class="space-y-3 py-2">

                                                <h3 class="font-bold text-xl">Languages i know</h3>
                                                <ul class="flex flex-wrap gap-3">

                                                    @foreach ($user->languages as $language)
                                                        <li
                                                            class="border border-gray-500 rounded-2xl text-sm px-2.5 p-1.5 capitalize">
                                                            {{ $language->name }}</li>
                                                    @endforeach


                                                </ul>
                                            </div>
                                            @endif

                                            @if ($user->basics)
                                                <div class="space-y-3 py-2">

                                                    <h3 class="font-bold text-xl">Basics</h3>
                                                    <ul class="flex flex-wrap gap-3">
                                                        @foreach ($user->basics as $basic)
                                                            <li class="border border-gray-500 rounded-2xl text-sm px-2.5 p-1.5">
                                                                {{ $basic->name }}</li>
                                                        @endforeach

                                                    </ul>
                                                </div>
                                            @endif
                                        </section>


                                </section>

                                <!-- Action -->
                                <section
                                    class="sticky bg-gradient-to-b from-white/50 to-white bottom-0 py-2 flex items-center justify-center gap-4 inset-x-0 mx-auto">

                                    <!-- Match of Info Section -->
                                    <!-- Swipe Left -->
                                    <div>
                                        <button draggable="true" @click="swipeLeft()"
                                                class="bg-white rounded-full border-2 pointer-events-auto group border-red-600 p-3 shrink-0 max-w-fit flex items-center text-red-600">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="3" stroke="currentColor"
                                                 class="w-9 h-9 shrink-0 m-auto group:hover-scale-10 stroke-current transition-transform stroke-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                            </svg>


                                        </button>
                                    </div>

                                    <!-- Super Like -->
                                    <div>
                                        <button draggable="true" @click="swipeUp()"
                                                class="bg-white  rounded-full border-2 pointer-events-auto group border-blue-600 p-3 shrink-0 max-w-fit flex items-center text-blue-600 scale-95">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor"
                                                 class="w-10 h-10 shrink-0 m-auto group:hover-scale-10 stroke-current transition-transform stroke-2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                            </svg>




                                        </button>
                                    </div>

                                    <!-- Swipe Right -->
                                    <div>
                                        <button draggable="false" @click="swipeRight()"
                                                class="bg-white rounded-full border-2 pointer-events-auto group border-green-600 p-3 shrink-0 max-w-fit flex items-center text-green-600">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor"
                                                 class="w-9 h-9 shrink-0 m-auto group:hover-scale-10 stroke-current transition-transform stroke-2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                            </svg>



                                        </button>
                                    </div>

                                </section>
                            </div>

                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Match found model -->
    <div x-data="{ modalOpen: false }" @keydown.escape.window="modalOpen = false"
         @close-match-modal.window="modalOpen=false" @match-found.window="modalOpen=true"
         class="relative z-50 w-auto h-auto">

        <template x-teleport="body">
            <div x-show="modalOpen"
                 class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen  " x-cloak>
                <div x-show="modalOpen" x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-300" x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0" @click="modalOpen=false"
                     class="absolute inset-0 w-full h-full bg-black bg-opacity-40"></div>
                <div x-show="modalOpen" x-trap.inert.noscroll="modalOpen" x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative w-full py-6 bg-white h-full sm:h-[550px] px-7 sm:max-w-lg sm:rounded-lg border-2 border-rose-500">

                    <!-- Close Button -->
                    <div class=" items-center justify-between p-2 py-3 block ">

                        <button @click="modalOpen=false"
                                class="absolute top-0 right-0 flex items-center justify-center w-8 h-8 mt-5 mr-5 text-gray-600 rounded-full hover:text-gray-800 hover:bg-gray-50">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Main -->
                    <main class="relative w-auto flex flex-col gap-y-9">
                        <div class="mx-auto flex flex-col gap-2 items-center justify-center">
                            <!-- Tinder logo -->
                            <div class="mx-auto">
                                <svg class="ml-5 icon flat-color" fill="#000000" width="50px" height="50px"
                                     viewBox="0 0 24 24" id="tinder" data-name="Flat Color"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path id="primary"
                                          d="M11.39,2.08a1,1,0,0,0-1,.14,1,1,0,0,0-.35,1c.72,3.62.41,6.08-1,7.46A7.57,7.57,0,0,1,8,7.85a1,1,0,0,0-.68-.8,1,1,0,0,0-1,.24C6.16,7.43,3,10.62,3,14c0,5,3.36,8,9,8,3.23,0,7-2.09,7-8A13.17,13.17,0,0,0,11.39,2.08Z"
                                          style="fill: rgb(237, 105, 129);"></path>
                                </svg>

                            </div>
                            <h5 class="font-bold text-3xl">
                                It's a Match
                            </h5>
                        </div>
                        <div class="flex items-center justify-center gap-4 mx-">
                            <span>
                                <img src="https://picsum.photos/seed/' . rand() . '/300/300" alt=""
                                     class="rounded-full h-32 w-32 ring ring-rose-500">
                            </span>

                            <span>
                                <img src="https://picsum.photos/seed/' . rand() . '/300/300" alt=""
                                     class="rounded-full h-32 w-32 ring ring-pink-500/40">
                            </span>
                        </div>

                        <!-- Action -->
                        <div class="mx-auto flex flex-col gap-5">
                            <button wire:click="createConversation"
                                    class="bg-tinder text-white font-bold items-center px-3 py-2 rounded-full">
                                Send a message
                            </button>

                            <button @click="modalOpen=false"
                                    class="bg-gray-500 text-white font-bold items-center px-3 py-2 rounded-full">
                                Continue Swiping
                            </button>
                        </div>
                    </main>
                </div>
            </div>
        </template>
    </div>
</div>
