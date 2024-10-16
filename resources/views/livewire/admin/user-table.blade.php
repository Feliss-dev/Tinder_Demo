<div class="table-data">
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
                        <button class="bg-blue-500 mb-2 mx-2 hover:bg-blue-600 text-white py-1 px-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" wire:click="sendNotification({{ $user->id }})">
                            Send Notification
                        </button>
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
