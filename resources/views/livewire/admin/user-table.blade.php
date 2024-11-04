<div class="table-data">
    <!-- Alert Notification -->
    @if (session()->has('success'))
    <div x-data="{ show: false }" @notification-sent.window="show = true; setTimeout(() => show = false, 3000)">
        <div x-show="show" class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50">
            Notification sent successfully!
        </div>
    </div>
    @endif

    <!-- Alert Notification for User Deletion -->
@if (session()->has('message'))
<div x-data="{ show: false }" @user-deleted.window="show = true; setTimeout(() => show = false, 3000)">
    <div x-show="show" class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50">
        {{ session('message') }}
    </div>
</div>
@endif

    <div class="order p-6 bg-white shadow-md rounded-lg">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-700">List of users</h3>
            <div class="flex space-x-2">
                <i class='bx bx-search text-gray-500 cursor-pointer'></i>
                <i class='bx bx-filter text-gray-500 cursor-pointer'></i>
            </div>
        </div>
<div class="overflow-x-auto">
        <table class="min-w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-100 text-left text-sm uppercase text-gray-600 w-full">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Birth Date</th>
                    <th class="px-4 py-2">Dating Goal</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($users as $user)
                <tr class="border-b">
                    <td class="px-4 py-3 text-center">{{ $user->id }}</td>
                    <td class="px-4 py-3 ">{{ $user->name }}</td>
                    <td class="px-4 py-3 ">{{ $user->email }}</td>
                    <td class="px-4 py-3 text-center">{{ $user->birth_date }}</td>
                    <td class="border px-4 py-2 text-center">{{$user->datingGoals->isNotEmpty() ? $user->datingGoals->pluck('name')->join(', '): 'No data available' }}</td>
                    <td class="px-4 py-3 ">
                        {{-- Notification --}}
                        <div x-data="{ open: false }">
                            <button
                                class="bg-blue-500 mb-2 mx-2 hover:bg-blue-600 text-white py-1 px-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                                @click="open = true">
                                Send Notification
                            </button>

                            <!-- Modal để nhập thông báo -->
                            <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                                    <h2 class="text-xl font-bold mb-4">Send Notification</h2>

                                    <textarea
                                        wire:model="message"
                                        class="w-full h-20 p-2 border rounded-lg focus:ring-2 focus:ring-blue-400"
                                        placeholder="Enter your message..."></textarea>

                                    <div class="mt-4 flex justify-end">
                                        <button
                                            class="bg-gray-500 text-white py-1 px-4 rounded-lg mr-2"
                                            @click="open = false">
                                            Cancel
                                        </button>
                                        <button
                                            class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-4 rounded-lg"
                                            wire:click="sendNotification({{ $user->id }})"
                                            @click="open = false">
                                            Send
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <button class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-lg ml-2 focus:outline-none focus:ring-2 focus:ring-red-400" x-on:click="confirm('Are you sure you want to delete this user?') ? $wire.deleteUser({{ $user->id }}) : false">
                            Delete Account
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
</div>

        <!-- Pagination links -->
<!-- Pagination links -->
<div class="mt-4">
    <div class="flex justify-center">
        {{ $users->onEachSide(1)->links('vendor.pagination.tailwind') }}
    </div>
</div>

    </div>
    <div class="todo mt-10">
        <div class="head">
            <h3>Todos</h3>
            <i class='bx bx-plus' ></i>
            <i class='bx bx-filter' ></i>
        </div>
        <ul class="todo-list">
            <li class="completed">
                <p>Todo List</p>
                <i class='bx bx-dots-vertical-rounded' ></i>
            </li>
            <li class="completed">
                <p>Todo List</p>
                <i class='bx bx-dots-vertical-rounded' ></i>
            </li>
            <li class="not-completed">
                <p>Todo List</p>
                <i class='bx bx-dots-vertical-rounded' ></i>
            </li>
            <li class="completed">
                <p>Todo List</p>
                <i class='bx bx-dots-vertical-rounded' ></i>
            </li>
            <li class="not-completed">
                <p>Todo List</p>
                <i class='bx bx-dots-vertical-rounded' ></i>
            </li>
        </ul>
    </div>
</div>
