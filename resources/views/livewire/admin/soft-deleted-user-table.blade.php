<div>
    <div x-data="{ show: false }" @user-restored.window="show = true; setTimeout(() => show = false, 3000)">
        @if (session()->has('success'))
            <div x-show="show" class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <section class="flex flex-row justify-between items-center">
        <p class="font-semibold text-xl">Soft Deleted Users</p>

        <div class="flex">
            <span
                class="inline-flex items-center px-3 text-sm text-gray-900 bg-white border border-e-0 border-gray-500 rounded-s-md">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" viewBox="0 0 16 16">
                    <path
                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                </svg>
            </span>

            <input type="text" placeholder="Search user" class="rounded-none rounded-e-lg border-gray-500" wire:model.live.debounce.150ms="searchTerm"/>
        </div>
    </section>

    <table class="w-full mt-2 table-auto border border-gray-200 rounded-lg">
        <thead>
            <tr class="bg-gray-100 text-left text-sm font-semibold text-gray-600">
                <th class="px-6 py-3 text-center">ID</th>
                <th class="px-6 py-3 text-center">Name</th>
                <th class="px-6 py-3 text-center">Email</th>
                <th class="px-6 py-3 text-center">Deleted At</th>
                <th class="px-6 py-3 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach ($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-center">{{ $user->id }}</td>
                    <td class="px-6 py-4 text-center">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-center">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-center">{{ \Carbon\Carbon::parse($user->deleted_at)->format('Y-m-d H:i:s') }}</td>

                    <td class="px-6 py-4 flex flex-row justify-center">
                        <button class="bg-green-500 text-white py-1 px-4 rounded hover:bg-green-600 focus:outline-none" wire:click="restoreUser({{ $user->id }})">
                            Restore Account
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
