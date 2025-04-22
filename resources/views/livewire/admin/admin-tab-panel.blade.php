@php
    use App\Models\SwipeMatch;
@endphp

<div class="w-full h-full overflow-y-auto">
    <div class="p-3 overflow-y-hidden">
        <div x-show="selectedTab === 'management_users'">
            <livewire:admin.user-management-panel/>
        </div>

        <div x-show="selectedTab === 'management_interaction'">
            <livewire:admin.interaction-management-panel/>
{{--            <button wire:click="downloadConversationsPDF" class="float-end bg-blue-500 p-3 rounded-full text-white">--}}
{{--                <i class='bx bxs-cloud-download'></i>--}}
{{--                <span class="text">Download PDF</span>--}}
{{--            </button>--}}

{{--            <h1 class="font-bold text-3xl">Conversation Management</h1>--}}
{{--            <p class="text-gray-800 mt-2 mb-5">Manage conversation between user and user.</p>--}}

{{--            <livewire:admin.conversation-table/>--}}
        </div>

{{--        <div x-show="selectedTab === 'management_matches'">--}}
{{--            <button wire:click="downloadMatchesPDF" class=" float-end bg-blue-500 p-3 rounded-full text-white">--}}
{{--                <i class='bx bxs-cloud-download'></i>--}}
{{--                <span class="text">Download PDF</span>--}}
{{--            </button>--}}

{{--            <h1 class="font-bold text-3xl">Matching Management</h1>--}}
{{--            <p class="text-gray-800 mt-2 mb-5">Manage matching information.</p>--}}

{{--            <livewire:admin.match-table />--}}
{{--        </div>--}}

        <div x-show="selectedTab === 'management_reports'">
            <h1 class="font-bold text-3xl">Report Management</h1>
            <p class="text-gray-800 mt-2 mb-5">Manage report information.</p>
        </div>

        <div x-show="selectedTab === 'application_analytics'">

        </div>
    </div>
</div>
