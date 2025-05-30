<div class="w-full h-full overflow-y-auto">
    <div class="p-3 overflow-y-hidden">
        <div x-show="selectedTab === 'management_users'">
            <livewire:admin.user-management-panel/>
        </div>

        <div x-show="selectedTab === 'management_conversation'">
            <livewire:admin.conversation-management-panel/>
        </div>

        <div x-show="selectedTab === 'management_swipe'">
            <livewire:admin.swipe-management-panel/>
        </div>

        <div x-show="selectedTab === 'management_reports'">
            <livewire:admin.report-management-panel/>
        </div>

        <div x-show="selectedTab === 'application_analytics'">

        </div>
    </div>
</div>
