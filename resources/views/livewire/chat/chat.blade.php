<div class="flex h-screen overflow-hidden">

    <main class="w-full grow border flex flex-col relative">
        {{-- Header --}}
        <header class="flex items-center gap-2.5 p-2 border">

            <a class="sm:hidden" wire:navigate href="{{ route('chat.index') }}">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class=" h-6 w-6 bi bi-chevron-left text-gray-500" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0" />
                    </svg>
                </span>
            </a>
            <x-avatar src="https://picsum.photos/seed/' . rand() . '/300/300" />

            <h5 class="font-bold text-gray-500 truncate">
                {{ $receiver->name }}
            </h5>

            <div class="ml-auto flex items-center gap-2  px-2">

                {{-- Dots --}}
                <span>

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-three-dots text-gray-500 w-7 h-7" viewBox="0 0 16 16">
                        <path
                            d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3" />
                    </svg>

                </span>

                {{-- Cancel button --}}
                <a href="{{ route('dashboard') }}">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-x-octagon text-gray-500 w-7 h-7" viewBox="0 0 16 16">
                            <path
                                d="M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353zM5.1 1 1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1z" />
                            <path
                                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                        </svg>
                    </span>
                </a>
            </div>
        </header>

        {{-- Body --}}
        <section
            class="flex flex-col gap-5 overflow-auto h-full p-2.5 overflow-y-auto flex-grow overflow-x-hidden w-full my-auto">

            @for ($i = 0; $i < 12; $i++)
                @php
                    $belongsToAuth = fake()->randomElement([true, false]);
                @endphp

                <div @class([
                    'max-w-[85%] md:max-w-[78%] flex w-auto gap-2 relative mt-2',
                    'ml-auto' => $belongsToAuth,
                ])>

                    {{-- Avatar --}}
                    <div @class(['shrink-0 mt-auto', 'invisible' => $belongsToAuth])>
                        <x-avatar class="w-7 h-7" src="https://picsum.photos/seed/' . rand() . '/300/300" />

                    </div>

                    {{-- Message --}}
                    <div @class([
                        'flex flex-wrap text-[15px] border-gray-200/40 rounded-xl p-2.5 flex flex-col bg-[#f6f6f8fb]',
                        'bg-blue-500 text-white' => $belongsToAuth,
                    ])>
                        <p class="whitespace-normal text-sm md:text-base tracking-wide lg:tracking-normal">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Dignissimos nesciunt voluptatem,
                            eum
                            sunt blanditiis facere perspiciatis maiores sapiente, natus quas nulla excepturi,
                            praesentium
                            similique velit fugit ad soluta eveniet aut.</p>
                    </div>
                </div>
            @endfor

        </section>

        {{-- Footer --}}
        <footer class="sticky bottom py-2 inset-x-0 p-2">
            <form action="" x-data="{ body: @entangle('body') }" autocomplete="off">
                @csrf
                {{-- Hiddin input --}}
                <input type="hidden" autocomplete="false" style="display: none">

                <div class="grid grid-cols-12 items-center">
                    {{-- Spotify --}}
                    <span class="col-span-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-spotify w-8 h-8 text-gray-500" viewBox="0 0 16 16">
                            <path
                                d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0m3.669 11.538a.5.5 0 0 1-.686.165c-1.879-1.147-4.243-1.407-7.028-.77a.499.499 0 0 1-.222-.973c3.048-.696 5.662-.397 7.77.892a.5.5 0 0 1 .166.686m.979-2.178a.624.624 0 0 1-.858.205c-2.15-1.321-5.428-1.704-7.972-.932a.625.625 0 0 1-.362-1.194c2.905-.881 6.517-.454 8.986 1.063a.624.624 0 0 1 .206.858m.084-2.268C10.154 5.56 5.9 5.419 3.438 6.166a.748.748 0 1 1-.434-1.432c2.825-.857 7.523-.692 10.492 1.07a.747.747 0 1 1-.764 1.288" />
                        </svg>
                    </span>
                    <input x-model="body" type="text" autocomplete="off" autofocus
                        placeholder="Write your message here" maxlength="1700"
                        class="col-span-9 bg-gray-100 border-0 outline-0 focus:ring-0 hover:ring-0 rounded-lg focus:outline-none">
                    <button x-bind@disabled="!body.trim()" type="submit" class="col-span-2">Send</button>
                </div>
            </form>
        </footer>
    </main>

    {{-- Profile --}}
    <aside class="w-[48%] hidden sm:flex border">
        {{-- Profile Card --}}
        <div style="contain: content"
            class=" inset-0 overflow-y-auto overflow-hidden overscroll-contain w-full  bg-white space-y-4">

            @php
                $slides = [
                    'https://picsum.photos/seed/' . rand() . '/500/300',
                    'https://picsum.photos/seed/' . rand() . '/500/300',
                    'https://picsum.photos/seed/' . rand() . '/500/300',
                ];
                $user= App\Models\User::first();
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
                    <h3 class="font-bold">{{ Auth::user()->name }}</h3>
                    <span class="font-semibold text-gray-800">
                        {{ Auth::user()->age }}
                    </span>
                </div>

                {{-- About --}}
                <ul>
                    <li class="items-center text-gray-6000 text-lg">
                        {{ Auth::user()->birth_date }}
                    </li>
                    <li class="items-center text-gray-6000 text-lg">
                        {{ Auth::user()->gender }}
                    </li>
                    <li class="items-center text-gray-6000 text-lg">
                        {{ Auth::user()->interests }}
                    </li>
                </ul>
                <hr class="-mx-2.5">

                {{-- Bio --}}
                <p class="text-gray-600">{{ Auth::user()->bio }}</p>

                {{-- Relationships Goals --}}
                <div class="rounded-xl bg-green-200 h-24 px-4 py-2 max-w-fit flex gap-4 items-center">
                    <div class="text-3xl"></div>
                    <div class="grid w-4/5">

                        <span class="font-bold text-sm text-green-800">Looking for</span>
                        <span class="text-lg text-green-800 capitalize">
                            {{ Auth::user()->dating_goal }}👋</span>
                    </div>
                </div>
                {{-- More information --}}
                {{-- @if ($user->languages) --}}
                <section class="divide-y space-y-2">
                    <div class="space-y-3 py-2">

                        <h3 class="font-bold text-xl">Languages i know</h3>
                        <ul class="flex flex-wrap gap-3">

                            {{-- @foreach ($user->languages as $language)
                                <li
                                    class="border border-gray-500 rounded-2xl text-sm px-2.5 p-1.5 capitalize">
                                    {{ $language->name }}</li>
                            @endforeach --}}


                        </ul>
                    </div>
                    {{-- @endif --}}

                    {{-- @if ($user->basics) --}}
                    <div class="space-y-3 py-2">

                        <h3 class="font-bold text-xl">Basics</h3>
                        <ul class="flex flex-wrap gap-3">
                            {{-- @foreach ($user->basics as $basic)
                            <li class="border border-gray-500 rounded-2xl text-sm px-2.5 p-1.5">
                                {{ $basic->name }}</li>
                        @endforeach --}}

                        </ul>
                    </div>
                    {{-- @endif --}}
                </section>


            </section>


        </div>
    </aside>
</div>
