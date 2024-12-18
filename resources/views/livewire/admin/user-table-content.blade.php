<div class="order bg-white shadow-lg rounded-lg p-2 space-y-2">
    <!-- User Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto border border-gray-200 rounded-lg">
            <thead>
            <tr class="bg-gray-100 text-left text-sm font-semibold text-gray-600">
                <th class="px-6 py-3">ID</th>
                <th class="px-6 py-3">Name</th>
                <th class="px-6 py-3">Email</th>
                <th class="px-6 py-3">Birth Date</th>
                <th class="px-6 py-3">Dating Goal</th>
                <th class="px-6 py-3 text-center">Actions</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
            @foreach($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-center">{{ $user->id }}</td>
                    <td class="px-6 py-4">{{ $user->name }}</td>
                    <td class="px-6 py-4">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-center">{{ $user->birth_date }}</td>
                    <td class="px-6 py-4 text-center">
                        {{ $user->datingGoals->isNotEmpty() ? $user->datingGoals->pluck('name')->join(', ') : 'No data available' }}
                    </td>
                    <td class="px-6 py-4 flex justify-center space-x-2">
                        <!-- Notification Button -->
                        <div x-data="{ open: false }">
                            <button class="bg-blue-500 text-white py-1 px-4 rounded focus:outline-none hover:bg-blue-600" @click="open = true">
                                Send Notification
                            </button>
                            <!-- Notification Modal -->
                            <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
                                <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
                                    <h2 class="text-xl font-semibold mb-4">Send Notification</h2>
                                    <textarea wire:model="message" class="w-full h-24 p-3 border rounded focus:ring-2 focus:ring-blue-400" placeholder="Enter your message..."></textarea>
                                    <div class="mt-4 flex justify-end space-x-2">
                                        <button class="bg-gray-500 text-white py-1 px-4 rounded hover:bg-gray-600" @click="open = false">Cancel</button>
                                        <button class="bg-blue-500 text-white py-1 px-4 rounded hover:bg-blue-600" wire:click="sendNotification({{ $user->id }})" @click="open = false">Send</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Delete Button -->
                        <button class="bg-red-500 text-white py-1 px-4 rounded hover:bg-red-600 focus:outline-none" @click="confirm('Are you sure you want to delete this user?') ? $wire.deleteUser({{ $user->id }}) : false">
                            Delete Account
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
        {{ $users->onEachSide(1)->links('vendor.pagination.tailwind') }}
    </div>
</div>
