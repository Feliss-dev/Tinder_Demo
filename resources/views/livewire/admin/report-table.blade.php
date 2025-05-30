<div>
    <div class="flex flex-row justify-between items-center">
        <p class="font-semibold text-xl">Users with reported messages</p>

        <div class="flex">
            <span
                class="inline-flex items-center px-3 text-sm text-gray-900 bg-white border border-e-0 border-gray-500 rounded-s-md">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" viewBox="0 0 16 16">
                    <path
                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                </svg>
            </span>

            <input type="text" placeholder="Search user" class="rounded-none rounded-e-lg border-gray-500" wire:model.live.debounce.150ms="userSearchTerm"/>
        </div>
    </div>

    <main class="bg-white shadow-lg rounded-lg p-2 space-y-2">
        <table class="min-w-full table-auto border border-gray-200 rounded-lg">
            <thead>
                <tr class="bg-gray-100 text-left text-sm font-semibold text-gray-600">
                    <th class="px-6 py-3 text-center">ID</th>
                    <th class="px-6 py-3 text-center">Name</th>
                    <th class="px-6 py-3 text-center">Email</th>
                    <th class="px-6 py-3 text-center">Reported messages count</th>
                    <th class="px-6 py-3 text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-center">{{ $user->id }}</td>
                        <td class="px-6 py-4">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-center">{{ $user->count }}</td>
                        <td class="px-6 py-4 flex justify-center">
                            <button class="bg-blue-500 text-white py-1 px-4 rounded focus:outline-none hover:bg-blue-600" @click="$dispatch('open-report-info-modal', { 'userId': {{$user->id}} })">
                                More Information
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            {{ $users->onEachSide(1)->links('vendor.pagination.tailwind') }}
        </div>
    </main>

    <livewire:admin.user-message-report-informations/>
</div>
