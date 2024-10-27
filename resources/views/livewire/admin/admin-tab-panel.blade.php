<div class="w-full h-full overflow-y-scroll">
    <div class="p-3 overflow-y-hidden">
        <div x-show="selectedTab === 'management_users'">
            <h1 class="font-bold text-3xl">User Management</h1>
            <p class="text-gray-800 mt-2">Manage user registering information.</p>

            <div class="mt-6 flex flex-row justify-between items-center">
                <h1 class="font-medium text-xl">All users <span class="ml-2 text-gray-600 text-2xl">{{ \App\Models\User::count() }}</span> </h1>

                <div class="flex">
                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-white border border-e-0 border-gray-500 rounded-s-md">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                        </svg>
                    </span>

                    <input type="text" placeholder="Search by name" class="rounded-none rounded-e-lg border-gray-500" />
                </div>
            </div>

            <table class="mt-2 w-full">
                <thead class="border border-slate-500">
                    <tr>
                        <th class="text-center w-[3rem] border border-slate-500 p-2">ID</th>
                        <th class="text-left border border-slate-500 p-2">Username</th>
                        <th class="text-left w-auto border border-slate-500 p-2">Role</th>
                        <th class="text-left w-auto border border-slate-500 p-2">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @for($i = 0; $i < 13; $i++)
                        <tr>
                            <td class="p-2 text-center border border-slate-500">{{ $i }}</td>
                            <td class="p-2 border border-slate-500">A</td>
                            <td class="p-2 border border-slate-500">B</td>
                            <td class="p-2 border border-slate-500">C</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <div x-show="selectedTab === 'management_conversations'">
            <h1 class="font-bold text-3xl">Conversation Management</h1>
            <p class="text-gray-800 mt-2">Manage conversation between user and user.</p>
        </div>

        <div x-show="selectedTab === 'management_matches'">
            <h1 class="font-bold text-3xl">Matching Management</h1>
            <p class="text-gray-800 mt-2">Manage matching information.</p>
        </div>

        <div x-show="selectedTab === 'management_reports'">
            <h1 class="font-bold text-3xl">Report Management</h1>
            <p class="text-gray-800 mt-2">Manage report information.</p>
        </div>

        <div x-show="selectedTab === 'application_analytics'">
            Application -> Analytics
        </div>
    </div>
</div>
