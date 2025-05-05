@php
    use App\Models\Swipe;
    use Carbon\Carbon;
@endphp

<div>
    <p class="font-semibold text-3xl text-center mt-4 mb-6">Swipe Management Dashboard</p>

    <section class="flex flex-row gap-2 mb-0" x-data="{year: @entangle('analyzingYear')}">
        <div class="flex flex-col gap-2 flex-1">
            <section class="rounded-xl border-2 bg-white p-4 flex-grow-0 flex-shrink">
                <header class="font-semibold text-xl mb-3">Overall</header>

                <table class="w-full">
                    <tr>
                        @php
                            $left = Swipe::where('type', 'left')->count();
                            $up = Swipe::where('type', 'up')->count();
                            $right = Swipe::where('type', 'right')->count();
                            $all = $left + $up + $right;
                        @endphp
                        <td class="font-semibold w-[70%]">Totals (all time):</td>
                        <td>
                            <span class="text-[red]">{{$left}}</span>/<span class="text-[#009DFF]">{{$up}}</span>/<span class="text-[#00B800]">{{$right}}</span>/<span class="text-black">{{$all}}</span>
                        </td>
                    </tr>
                    <tr>
                        @php
                            $left = Swipe::where('type', 'left')->whereDate('created_at', Carbon::today())->count();
                            $up = Swipe::where('type', 'up')->whereDate('created_at', Carbon::today())->count();
                            $right = Swipe::where('type', 'right')->whereDate('created_at', Carbon::today())->count();
                            $all = $left + $up + $right;
                        @endphp

                        <td class="font-semibold w-[70%]">Totals (today):</td>
                        <td>
                            <span class="text-[red]">{{$left}}</span>/<span class="text-[#009DFF]">{{$up}}</span>/<span class="text-[#00B800]">{{$right}}</span>/<span class="text-black">{{$all}}</span>
                        </td>
                    </tr>
                    <tr>
                        @php
                            $left = Swipe::where('type', 'left')->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();
                            $up = Swipe::where('type', 'up')->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();
                            $right = Swipe::where('type', 'right')->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();
                            $all = $left + $up + $right;
                        @endphp

                        <td class="font-semibold w-[70%]">Totals (this month):</td>
                        <td>
                            <span class="text-[red]">{{$left}}</span>/<span class="text-[#009DFF]">{{$up}}</span>/<span class="text-[#00B800]">{{$right}}</span>/<span class="text-black">{{$all}}</span>
                        </td>
                    </tr>
                    <tr>
                        @php
                            $left = Swipe::where('type', 'left')->whereYear('created_at', Carbon::now()->year)->count();
                            $up = Swipe::where('type', 'up')->whereYear('created_at', Carbon::now()->year)->count();
                            $right = Swipe::where('type', 'right')->whereYear('created_at', Carbon::now()->year)->count();
                            $all = $left + $up + $right;
                        @endphp

                        <td class="font-semibold w-[70%]">Totals (this year):</td>
                        <td>
                            <span class="text-[red]">{{$left}}</span>/<span class="text-[#009DFF]">{{$up}}</span>/<span class="text-[#00B800]">{{$right}}</span>/<span class="text-black">{{$all}}</span>
                        </td>
                    </tr>
                </table>
            </section>

            <section class="rounded-xl border-2 bg-white p-4 flex-grow-0 flex-shrink align-top">
                <header class="font-semibold text-xl mb-3">In {{$analyzingYear}}</header>

                <table class="w-full">
                    <tr>
                        @php
                            $left = $analyzingYearStats['sum']['left'];
                            $up = $analyzingYearStats['sum']['up'];
                            $right = $analyzingYearStats['sum']['right'];
                            $all = $left + $up + $right;
                        @endphp

                        <td class="font-semibold w-[70%]">Totals:</td>
                        <td>
                            <span class="text-[red]">{{$left}}</span>/<span class="text-[#009DFF]">{{$up}}</span>/<span class="text-[#00B800]">{{$right}}</span>/<span class="text-black">{{$all}}</span>
                        </td>
                    </tr>

                    <tr>
                        @php
                            $left = number_format($analyzingYearStats['avg']['left'], 2, '.', '');
                            $up = number_format($analyzingYearStats['avg']['up'], 2, '.', '');
                            $right = number_format($analyzingYearStats['avg']['right'], 2, '.', '');
                            $all = number_format($analyzingYearStats['avg']['left'] + $analyzingYearStats['avg']['up'] + $analyzingYearStats['avg']['right'], 2, '.', '');
                        @endphp

                        <td class="font-semibold w-[70%]">Averages:</td>
                        <td>
                            <span class="text-[red]">{{$left}}</span>/<span class="text-[#009DFF]">{{$up}}</span>/<span class="text-[#00B800]">{{$right}}</span>/<span class="text-black">{{$all}}</span>
                        </td>
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
        <livewire:admin.match-table/>
    </section>
</div>
