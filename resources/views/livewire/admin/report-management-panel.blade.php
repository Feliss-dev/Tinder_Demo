<div>
    <h1 class="font-semibold text-3xl text-center mt-4 mb-6">Report Management Dashboard</h1>

{{--    <section class="flex flex-row gap-2 mb-0" x-data="{year: @entangle('analyzingYear')}">--}}
{{--        <div class="flex flex-col gap-2 flex-1">--}}
{{--            <section class="rounded-xl border-2 bg-white p-4 flex-grow-0 flex-shrink">--}}
{{--                <header class="font-semibold text-xl mb-3">Overall</header>--}}
{{--            </section>--}}

{{--            <section class="rounded-xl border-2 bg-white p-4 flex-grow-0 flex-shrink align-top">--}}
{{--                <header class="font-semibold text-xl mb-3">In {{$analyzingYear}}</header>--}}

{{--                <table class="w-full">--}}
{{--                    <tr>--}}
{{--                        <td class="font-semibold w-[75%]">Total:</td>--}}
{{--                    </tr>--}}
{{--                </table>--}}
{{--            </section>--}}
{{--        </div>--}}
{{--    </section>--}}

    <section class="rounded-xl border-2 bg-white p-4 mt-2">
        <livewire:admin.report-table/>
    </section>
</div>
