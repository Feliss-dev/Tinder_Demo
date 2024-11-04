@php
    use App\Models\User;
@endphp

<div class="table-data">
    <div class="order bg-white shadow-md rounded-lg p-2">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-left text-sm uppercase text-gray-600 w-full">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Partner 1</th>
                        <th class="px-4 py-2">Partner 2</th>
                        <th class="px-4 py-2">Swipe Date</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr class="border-b">
                        <td class="px-4 py-3 w-0 whitespace-nowrap">TODO</td>
                        <td class="px-4 py-3">TODO</td>
                        <td class="px-4 py-3">TODO</td>
                        <td class="px-4 py-3">TODO</td>
                        <td class="px-4 py-3">TODO</td>
                    </tr>
{{--                    @foreach($matches as $match)--}}
{{--                        <tr class="border-b">--}}
{{--                            <td class="px-4 py-3 text-center w-0 whitespace-nowrap">{{ $match->id }}</td>--}}
{{--                            <td class="px-4 py-3 text-center">{{ User::where('id', $match->swipe_id_1)->count() }}</td>--}}
{{--                            <td class="px-4 py-3 text-center">{{ User::where('id', $match->swipe_id_1)->first()->name }}</td>--}}
{{--                            <td class="px-4 py-3 text-center">{{ User::where('id', $match->swipe_id_2)->first()->name }}</td>--}}

{{--                            <td class="px-4 py-3 w-0 whitespace-nowrap">--}}
{{--                                <button class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-lg ml-2 focus:outline-none focus:ring-2 focus:ring-red-400">--}}
{{--                                    Delete Account--}}
{{--                                </button>--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
                </tbody>
            </table>
        </div>

        <!-- Pagination links -->
        <div class="mt-4">
            <div class="flex justify-center">
                {{ $matches->onEachSide(1)->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
</div>
