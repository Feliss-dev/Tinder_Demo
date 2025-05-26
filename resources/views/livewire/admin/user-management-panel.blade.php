@php
    use App\Models\User;
    use Carbon\Carbon;
@endphp

<div>
    <h1 class="font-semibold text-3xl text-center mt-4 mb-6">User Management Dashboard</h1>

    <section class="flex flex-row gap-2 mb-0" x-data="{year: @entangle('analyzingYear')}">
        <div class="flex flex-col gap-2 flex-1">
            <section class="rounded-xl border-2 bg-white p-4 flex-grow-0 flex-shrink">
                <header class="font-semibold text-xl mb-3">Overall</header>

                <table class="w-full">
                    <tr>
                        <td class="font-semibold w-[75%]">Total (all time):</td>
                        <td>{{User::count()}}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold w-[75%]">Total (today):</td>
                        <td>{{User::whereDate('created_at', Carbon::today())->count()}}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold w-[75%]">Total (this month):</td>
                        <td>{{User::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count()}}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold w-[75%]">Total (this year):</td>
                        <td>{{User::whereYear('created_at', Carbon::now()->year)->count()}}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold w-[75%]">Online (today):</td>
                        <td>{{User::whereDate('last_seen_at', Carbon::today())->count()}}</td>
                    </tr>
                </table>
            </section>

            <section class="rounded-xl border-2 bg-white p-4 flex-grow-0 flex-shrink align-top">
                <header class="font-semibold text-xl mb-3">In {{$analyzingYear}}</header>

                <table class="w-full">
                    <tr>
                        <td class="font-semibold w-[75%]">Total:</td>
                        <td>{{$analyzingYearStats['sum']}}</td>
                    </tr>
                    <tr>
                        <td class="font-semibold w-[75%]">Average:</td>
                        <td>{{number_format($analyzingYearStats['avg'], 2, '.', '')}}</td>
                    </tr>
                </table>
            </section>
        </div>

        <section class="rounded-xl border-2 bg-white p-2 flex-grow-[2] flex-shrink basis-[0%]">
            <x-chartjs-component :chart="$this->chart"/>

            <footer class="flex justify-center gap-2 items-center">
                <button type="button" @click="year -= 5; $wire.getChartData(year)"
                        class="border-2 border-gray-300 p-2 rounded-lg
                           bg-white hover:bg-gray-200 active:bg-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="black" viewBox="0 0 16 16">
                        <path
                            d="M8.404 7.304a.802.802 0 0 0 0 1.392l6.363 3.692c.52.302 1.233-.043 1.233-.696V4.308c0-.653-.713-.998-1.233-.696z"/>
                        <path
                            d="M.404 7.304a.802.802 0 0 0 0 1.392l6.363 3.692c.52.302 1.233-.043 1.233-.696V4.308c0-.653-.713-.998-1.233-.696z"/>
                    </svg>
                </button>

                <button type="button" @click="year--; $wire.getChartData(year)"
                        class="border-2 border-gray-300 p-2 rounded-lg
                           bg-white hover:bg-gray-200 active:bg-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="black" viewBox="0 0 16 16">
                        <path
                            d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                    </svg>
                </button>

                <p class="text-xl text-center mx-3" x-text="year"></p>

                <button type="button" @click="year++; $wire.getChartData(year)"
                        class="border-2 border-gray-300 p-2 rounded-lg
                           bg-white hover:bg-gray-200 active:bg-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="black" viewBox="0 0 16 16">
                        <path
                            d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                    </svg>
                </button>

                <button type="button" @click="year += 5; $wire.getChartData(year)"
                        class="border-2 border-gray-300 p-2 rounded-lg
                           bg-white hover:bg-gray-200 active:bg-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="black" viewBox="0 0 16 16">
                        <path
                            d="M7.596 7.304a.802.802 0 0 1 0 1.392l-6.363 3.692C.713 12.69 0 12.345 0 11.692V4.308c0-.653.713-.998 1.233-.696z"/>
                        <path
                            d="M15.596 7.304a.802.802 0 0 1 0 1.392l-6.363 3.692C8.713 12.69 8 12.345 8 11.692V4.308c0-.653.713-.998 1.233-.696z"/>
                    </svg>
                </button>
            </footer>
        </section>
    </section>

    <section class="rounded-xl border-2 bg-white p-4 mt-2">
        <livewire:admin.user-table/>
    </section>
</div>
