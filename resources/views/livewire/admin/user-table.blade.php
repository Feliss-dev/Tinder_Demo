<div x-data="{ notificationModal: { show: false, userId: 0 } }">
    <!-- Alert Notifications -->
    <div x-data="{ show: false }" @notification-sent.window="show = true; setTimeout(() => show = false, 3000)">
        @if (session()->has('success'))
            <div x-show="show" class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50">
                Notification sent successfully!
            </div>
        @endif
    </div>

    <div x-data="{ show: false }" @user-deleted.window="show = true; setTimeout(() => show = false, 3000)">
        @if (session()->has('message'))
            <div x-show="show" class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50">
                {{ session('message') }}
            </div>
       @endif
    </div>

    <section class="flex flex-row justify-between items-center">
        <p class="font-semibold text-xl">Registered Users</p>

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
                <th class="px-6 py-3 text-center">Birth Date</th>
                <th class="px-6 py-3 text-center">Dating Goal</th>
                <th class="px-6 py-3 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach ($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-center">{{ $user->id }}</td>
                    <td class="px-6 py-4 text-center">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-center">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-center">{{ $user->birth_date }}</td>
                    <td class="px-6 py-4 text-center">
                        {{ $user->datingGoals->isNotEmpty() ? $user->datingGoals->pluck('name')->join(', ') : 'No data available' }}
                    </td>
                    <td class="px-6 py-4 flex flex-row justify-center gap-2">
                        <button class="bg-blue-500 text-white py-1 px-4 rounded focus:outline-none hover:bg-blue-600" @click="notificationModal = { show: true, userId: {{$user->id}} }">
                            Send Notification
                        </button>

                        <!-- Delete Button -->
                        <button class="bg-red-500 text-white py-1 px-4 rounded hover:bg-red-600 focus:outline-none" @click="confirm('Are you sure you want to delete this user?') ? $wire.deleteUser({{ $user->id }}) : false">
                            Delete Account
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

    <!-- Notification Modal -->
    <div x-cloak x-show="notificationModal.show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md" @click.outside="notificationModal.show = false;">
            <h2 class="text-xl font-semibold mb-4">Send Notification</h2>
            <textarea wire:model="message" class="w-full h-24 p-3 border rounded focus:ring-2 focus:ring-blue-400" placeholder="Enter your message..."></textarea>
            <div class="mt-4 flex justify-end space-x-2">
                <button class="bg-gray-500 text-white py-1 px-4 rounded hover:bg-gray-600" @click="notificationModal.show = false;">Cancel</button>
                <button class="bg-blue-500 text-white py-1 px-4 rounded hover:bg-blue-600" wire:click="sendNotification(`${notificationModal.userId}`)" @click="notificationModal.show = false">Send</button>
            </div>
        </div>
    </div>
</div>
