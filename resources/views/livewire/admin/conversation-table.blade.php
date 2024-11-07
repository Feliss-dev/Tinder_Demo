@php
    use App\Models\User;
    use App\Models\Conversation;
@endphp

<div>
    <div class="flex flex-row justify-between items-center">
        <h1 class="font-medium text-xl">
            All conversations

            <span class="ml-2 text-gray-600 text-2xl">{{ Conversation::count() }}</span>
        </h1>

        <div class="flex">
            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-white border border-e-0 border-gray-500 rounded-s-md">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                </svg>
            </span>

            <input type="text" placeholder="Search user" class="rounded-none rounded-e-lg border-gray-500"/>
        </div>
    </div>

    <div class="table-data">
        <div class="order bg-white shadow-md rounded-lg p-2">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border-collapse" @conversation_table_initialize="$refresh">
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
</div>
