<div x-cloak x-data="{ open: false, showSuccessMessage: false }" x-show="open" @report-resolved.window="showSuccessMessage = true; setTimeout(() => showSuccessMessage = false, 3000);">
    @if (session('success'))
        <div x-show="showSuccessMessage" class="fixed top-5 right-5 bg-green-500 text-white p-4 shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    <div x-show="open" @open-report-info-modal.window="open = true;">
        <div class="fixed inset-0 flex items-center justify-center z-40 bg-black bg-opacity-65">
            <section class="bg-white rounded-xl fixed inset-8 flex flex-col" @click.outside="open = false;">
                <header class="flex flex-row justify-between border-b-2 border-b-gray-300 p-3 flex-initial">
                    <h1 class="text-black font-bold text-xl">User Message Reports</h1>

                    <button @click="open = false; $wire.close()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="gray" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                    </button>
                </header>

                @if ($user != null)
                    <main class="grid grid-cols-3 flex-auto overflow-y-hidden" x-data="{ inspectingReportId: @entangle('inspectingReportId') }">
                        <section class="pt-2 px-3 border-r-2 border-r-gray-300">
                            <header class="text-center font-bold text-xl">User Informations</header>

                            <table class="w-full mt-2">
                                <tr>
                                    <td class="font-semibold w-full">ID</td>
                                    <td>{{$user->id}}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold">Name</td>
                                    <td>{{$user->name}}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold">Email</td>
                                    <td>{{$user->email}}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold">Birth Date</td>
                                    <td>{{$user->birth_date}}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold">Join Date</td>
                                    <td>{{\Carbon\Carbon::parse($user->created_at)->format('Y-m-d H:i:s')}}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold">Last seen</td>
                                    <td>{{$user->last_seen_at == null ? "Never" : \Carbon\Carbon::parse($user->last_seen_at)->format('Y-m-d H:i:s')}}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold">Unresolved Reports Count</td>
                                    <td>{{$reportIds->count()}}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold">Warning Count</td>
                                    <td>{{$user->warn_count}}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold">Is Banned</td>
                                    <td>{{$user->is_banned ? 'True' : 'False'}}</td>
                                </tr>
                                <tr>
                                    <td class="font-semibold">Is Soft Deleted</td>
                                    <td>{{$user->trashed() ? 'True' : 'False'}}</td>
                                </tr>

                                @if ($user->trashed())
                                    <tr>
                                        <td class="font-semibold">Soft Deleted Time</td>
                                        <td>{{\Carbon\Carbon::parse($user->deleted_at)->format('Y-m-d H:i:s')}}</td>
                                    </tr>
                                @endif
                            </table>
                        </section>

                        <section class="pt-2 border-r-2 border-r-gray-300 flex flex-col overflow-y-hidden">
                            <header class="text-center font-bold text-xl flex-initial">Reported Messages</header>

                            <section class="mt-2 flex-auto overflow-x-hidden overflow-y-scroll webkit-small-scrollbar">
                                @foreach ($reportIds as $reportId)
                                    <button class="w-full p-3 {{$inspectingReportId == $reportId ? 'bg-gray-200' : 'bg-white'}} flex flex-row hover:bg-gray-200 active:bg-gray-300" @click="isLoading = true;" wire:click="loadReport({{$reportId}})">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-3" fill="black" viewBox="0 0 16 16">
                                            <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z"/>
                                            <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                                        </svg>

                                        Report ID {{$reportId}}
                                    </button>
                                @endforeach
                            </section>
                        </section>

                        <section class="pt-2 flex flex-col overflow-y-hidden">
                            <header class="text-center font-bold text-xl flex-initial">Report Informations</header>

                            <main x-cloak x-show="inspectingReportId <= 0" class="mt-2 flex-auto overflow-y-hidden flex flex-col justify-center items-center">
                                Please select a report to inspect.
                            </main>

                            <main x-cloak x-show="inspectingReportId > 0" class="flex-auto overflow-y-hidden p-2">
                                @if ($inspectingReport != null)
                                    <p class="font-semibold">Message Content</p>

                                    <livewire:chat.sender-message-bubble :message="$inspectingReport->message"/>

                                    <p class="font-semibold mt-2">Report Informations</p>

                                    <p class="font-semibold mt-3">Date: <span class="font-normal ml-3">{{\Carbon\Carbon::parse($inspectingReport->created_at)->format('Y-m-d H:i:s')}}</span></p>

                                    <p class="font-semibold mt-3">Reasons</p>

                                    <ul class="list-inside list-disc">
                                        @foreach ($inspectingReport->reasons as $reason)
                                            <li>{{$reason->desc}}</li>
                                        @endforeach
                                    </ul>

                                    @if (!empty($inspectingReport->extra))
                                        <p class="font-semibold mt-3">Extra</p>

                                        <textarea name="Extra" class="w-full h-20 resize-none mt-2" readonly>{{$inspectingReport->extra}}</textarea>
                                    @endif

                                    <p class="font-semibold mt-2">Actions</p>

                                    <div class="grid grid-cols-2 grid-rows-2 gap-2 mt-2">
                                        <button class="text-black bg-yellow-300 hover:bg-yellow-400 active:bg-yellow-500 h-12 rounded-xl" wire:click="warnUser">Warn</button>
                                        <button class="text-white bg-red-500 hover:bg-red-600 active:bg-red-700 h-12 rounded-xl" wire:click="banUser">Ban</button>
                                        <button class="col-span-2 text-white bg-green-400 hover:bg-green-500 active:bg-green-600 h-12 rounded-xl" wire:click="ignoreReport">Ignore</button>
                                    </div>
                                @else
                                    <div class="w-full h-full flex-auto flex flex-col justify-center items-center">
                                        <svg aria-hidden="true" class="w-10 h-10 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                        </svg>

                                        <p>Loading...</p>
                                    </div>
                                @endif
                            </main>
                        </section>
                    </main>
                @else
                    <main class="w-full h-full flex-auto flex flex-col justify-center items-center">
                        <svg aria-hidden="true" class="w-10 h-10 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                        </svg>

                        <p>Loading...</p>
                    </main>
                @endif
            </section>
        </div>
    </div>
</div>
