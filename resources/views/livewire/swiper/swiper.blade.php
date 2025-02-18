<div id="tinder" class="m-auto md:p-8 w-full h-full relative flex overflow-hidden">
    <!-- Filtering Panel -->
    <div class="mt-4 ">
        <form wire:submit.prevent="applyFilters"
              class="bg-white p-8 rounded-3xl shadow-xl space-y-8 w-full max-w-4xl  border-pink-500 h-auto">

            <h2 class="text-xl font-bold text-gray-800 text-center mb-6">Find Your Match</h2>



            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cột 1 -->
                <div class="space-y-6">
                    <div class="relative w-full">
                        <label for="searchTerm" class="block text-sm font-semibold text-gray-600">Name</label>
                        <input type="text" id="searchTerm" wire:model="searchTerm" placeholder="Enter name..."
                            class="w-full mt-1 p-3 rounded-lg border focus:ring-2 focus:ring-pink-400 focus:outline-none">
                    </div>

                    <div class="w-full">
                        <label class="block font-semibold text-gray-600 text-sm mb-2">Age</label>

                        <label for="ageFrom" class="block font-semibold text-gray-600 text-xs">From</label>
                        <input type="number" id="ageFrom" min="0" max="120" wire:model="ageFrom"
                            class="w-full mt-1 p-3 rounded-lg border focus:ring-2 focus:ring-pink-400 focus:outline-none">

                        <label for="ageTo" class="block font-semibold text-gray-600 text-xs">To</label>
                        <input type="number" id="ageTo" min="0" max="120" wire:model="ageTo"
                               class="w-full mt-1 p-3 rounded-lg border focus:ring-2 focus:ring-pink-400 focus:outline-none">
                    </div>

                    <div class="w-full">

                    </div>

                    <div class="w-full">
                        <label for="gender" class="block text-sm font-semibold text-gray-600">Gender</label>
                        <select id="gender" wire:model="gender"
                            class="w-full mt-1 p-3 rounded-lg border focus:ring-2 focus:ring-pink-400 focus:outline-none">
                            <option value="">Any</option>
                            @foreach ($genders as $gender)
                                <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Cột 2 -->
                <div class="space-y-6 mt-20">
                    <!-- Interests Filter in Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full p-3 bg-gray-100 border rounded-lg focus:ring-2 focus:ring-pink-400">
                            Select Interests
                        </button>

                        <div x-show="open" @click.outside="open = false" class="absolute z-10 mt-1 bg-white border rounded-lg shadow-xl w-auto">
                            <div class="p-3 grid grid-cols-[auto_auto] gap-2">
                                @foreach ($interests as $interest)
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" value="{{ $interest->id }}" wire:model="selectedInterests"
                                            class="text-pink-500 focus:ring-pink-400 rounded">
                                        <span>{{ $interest->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Languages Filter in Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full p-3 bg-gray-100 border rounded-lg focus:ring-2 focus:ring-pink-400">
                            Select Languages
                        </button>
                        <div x-show="open" @click.outside="open = false" class="absolute z-10 mt-2 bg-white border rounded-lg shadow-xl w-auto">
                            <div class="p-3 grid grid-cols-[auto_auto] gap-2">
                                @foreach ($languages as $language)
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" value="{{ $language->id }}" wire:model="selectedLanguages"
                                            class="text-pink-500 focus:ring-pink-400 rounded">
                                        <span>{{ $language->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Dating Goals Filter in Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" type="button"
                            class="w-full p-3 bg-gray-100 border rounded-lg focus:ring-2 focus:ring-pink-400">
                            Select Dating Goals
                        </button>
                        <div x-show="open" @click.outside="open = false" class="absolute z-10 mt-2 bg-white border rounded-lg shadow-xl w-auto">
                            <div class="p-3 grid grid-cols-[auto_auto] gap-2">
                                @foreach ($datingGoals as $goal)
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" value="{{ $goal->id }}" wire:model="selectedDatingGoals"
                                            class="text-pink-500 focus:ring-pink-400 rounded">
                                        <span>{{ $goal->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-center mt-8">
                <button type="submit"
                    class="w-full md:w-1/2 py-3 bg-pink-500 text-white font-semibold rounded-lg hover:bg-pink-600 transition-all duration-200">
                    <span class="flex items-center justify-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                        Apply Filters
                    </span>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Swiper -->
    <div class="relative h-full w-full md:w-96 m-auto flex items-center justify-center">
        @if ($users->isEmpty())
            <div class="col-span-full text-center ">
                <p class="text-gray-500">Found no suitable match.</p>
            </div>
        @else
            @foreach ($users as $user)
                <div wire:key="swipe-{{ $user->id }}"
                     x-data="{
                        profile: false,
                        profiles: false,
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
                    x-init="
                    element = $el;

                    // Initialize hammer.js
                    var hammer = new Hammer(element);

                    // Let pan support all directions
                    hammer.get('pan').set({
                        direction: Hammer.DIRECTION_ALL,
                        touchAction: 'pan'
                    });

                    // On Pan
                    hammer.on('pan', function(event) {
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

                    hammer.on('panend', function(event) {
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
                    <div x-show="!profile" x-transition.duration.150ms.origin.bottom
                        class="relative overflow-hidden w-full h-full rounded-xl bg-cover bg-white">

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
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 19.5L8.25 12l7.5-7.5" />
                                </svg>
                            </button>

                            <!-- Next Button -->
                            <button :class="{ 'hidden': slides.length === 1 }"
                                @click="activeSlide = activeSlide === slides.length - 1 ? 0 : activeSlide + 1"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>

                        </section>

                        <!-- Swiper indicators -->
                        <div class="pointer-events-none">
                            <span x-cloak :class="{ 'invisible': !swipingRight }"
                                class="border-2 rounded-md p-1 px-2 border-green-500 text-green-500 text-4xl -rotate-12 capitalize font-extrabold top-10 left-5 absolute z-5">
                                LIKE
                            </span>

                            <span x-cloak :class="{ 'invisible': !swipingLeft }"
                                class="border-2 rounded-md p-1 px-2 border-red-500 text-red-500 text-4xl rotate-12 capitalize font-extrabold top-10 right-5 absolute z-5">
                                NOPE
                            </span>

                            <span x-cloak :class="{ 'invisible': !swipingUp }"
                                class="border-2 rounded-md p-1 px-2 border-green-500 text-green-500 text-4xl -rotate-12 capitalize font-extrabold bottom-48 max-w-fit inset-x-0 mx-auto absolute z-30">
                                SUPER LIKE
                            </span>
                        </div>

                        <!-- Information and Actions -->
                        <section class="absolute inset-x-0 bottom-0 inset-y-1/2 py-2 bg-gradient-to-t from-black to-black/0 pointer-events-none">
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
                                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2" />
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

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
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

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
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

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
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

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
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
                        <!-- Carousel section -->
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
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m8.25 4.5 7.5 7.5-7.5 7.5" />
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
                                                    <span
                                                        class="inline-block bg-green-100 text-green-700 border border-green-300 rounded-full px-3 py-1 text-sm font-semibold">
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

                            <!-- Bio -->
                            <p class="text-gray-600">{{ $user->bio }}</p>

                            <!-- Relationships Goals -->
                            <div
                                class="rounded-lg bg-green-100 h-auto px-6 py-4 max-w-md flex gap-6 items-center shadow-lg">
                                <div class="text-4xl text-green-700">
                                    <!-- Add icon or symbol if needed -->
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

                            <!-- More information -->
                            <section class="divide-y space-y-2">
                                <div class="space-y-3 py-2">
                                    <h3 class="font-bold text-xl">Languages i know</h3>
                                    <ul class="flex flex-wrap gap-3">
                                        @if ($user->languages->isNotEmpty())
                                            <div class="flex flex-wrap gap-2">
                                                @foreach ($user->languages as $language)
                                                    <span
                                                        class="inline-block bg-purple-100 text-purple-700 border border-purple-300 rounded-full px-3 py-1 text-sm font-semibold">
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
                                        <p class="mb-2 mr-4">
                                            <strong>Desired Gender:</strong>
                                            @if ($user->desiredGenders->isNotEmpty())
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach ($user->desiredGenders as $desiredGender)
                                                        <span
                                                            class="inline-block bg-pink-100 text-pink-700 border border-pink-300 rounded-full px-3 py-1 text-sm font-semibold">
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
                        </section>

                        <!-- Actions -->
                        <section
                            class="sticky bg-gradient-to-b from-white/50 to-white bottom-0 py-2 flex items-center justify-center gap-4 inset-x-0 mx-auto">

                            <!-- Match of Info Section -->
                            <!-- Swipe Left -->
                            <div>
                                <button draggable="true" @click="swipeLeft()"
                                    class="bg-white rounded-full border-2 pointer-events-auto group border-red-600 p-3 shrink-0 max-w-fit flex items-center text-red-600">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-9 h-9 shrink-0 m-auto group:hover-scale-10 stroke-current transition-transform stroke-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 18 18 6M6 6l12 12" />
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

    <!-- Match popup modal -->
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
                                <svg class="ml-5" fill="#000000" width="50px" height="50px"
                                    viewBox="0 0 24 24" id="tinder" data-name="Flat Color"
                                    xmlns="http://www.w3.org/2000/svg" class="icon flat-color">
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
                            <!-- Show the activating avatar of the user -->
                            <span>
                                @if ($currentUser && $currentUser->activeAvatar)
                                    <img src="{{ asset('storage/' . $currentUser->activeAvatar->path) }}"
                                        alt="Current User Avatar" class="rounded-full h-32 w-32 ring ring-rose-500">
                                @else
                                    <img src="https://randomuser.me/api/portraits/women/{{ rand(0, 99) }}.jpg"
                                        alt="Random User" class="rounded-full h-32 w-32 ring ring-rose-500">
                                @endif
                            </span>

                            <!-- Show the activating avatar of the matched user -->
                            <span>
                                @if ($matchedUser && $matchedUser->activeAvatar)
                                    <img src="{{ asset('storage/' . $matchedUser->activeAvatar->path) }}"
                                        alt="Matched User Avatar"
                                        class="rounded-full h-32 w-32 ring ring-pink-500/40">
                                @else
                                    <img src="https://randomuser.me/api/portraits/women/{{ rand(0, 99) }}.jpg"
                                        alt="Random User" class="rounded-full h-32 w-32 ring ring-pink-500/40">
                                @endif
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
