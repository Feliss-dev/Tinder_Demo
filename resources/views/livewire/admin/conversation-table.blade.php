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
                        <th class="px-4 py-2">Sender</th>
                        <th class="px-4 py-2">Receiver</th>
                        <th class="px-4 py-2">Since</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($conversations as $conversation)
                        <tr class="border-b">
                            <td class="px-4 py-3 text-center w-0 whitespace-nowrap">{{ $conversation->id }}</td>
                            <td class="px-4 py-3 text-left">{{ User::where('id', $conversation->sender_id)->first()->name }}</td>
                            <td class="px-4 py-3 text-left">{{ User::where('id', $conversation->receiver_id)->first()->name }}</td>
                            <td class="px-4 py-3 text-left w-0 whitespace-nowrap">{{ $conversation->created_at }}</td>
                            <td class="px-4 py-3 w-0 whitespace-nowrap">
                                <button class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-lg ml-2 focus:outline-none focus:ring-2 focus:ring-red-400">
                                    Delete Conversation
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination links -->
        <div class="mt-4">
            <div class="flex justify-center">
                {{ $conversations->onEachSide(1)->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
</div>
