<div class="relative">
    <!-- Bell button -->
    <button wire:click="toggle"
            class="p-2 rounded-md hover:bg-gray-100 focus:outline-none relative"
            aria-label="Notifications">
        <x-heroicon-o-bell class="h-5 w-5" />
        @if($notifications->count())
            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center 
                         px-1.5 py-0.5 text-xs font-bold leading-none text-white 
                         bg-red-600 rounded-full">
                {{ $notifications->count() }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    @if($open)
        <div class="absolute right-0 mt-2 w-72 bg-white shadow-lg rounded-lg border z-50">
            <div class="p-3 border-b font-semibold text-gray-700">
                Notifications
            </div>
            <ul class="max-h-60 overflow-y-auto divide-y">
                @forelse($notifications as $note)
                    <li class="px-3 py-2 hover:bg-gray-50 text-sm text-gray-700">
                        {{ $note->data['message'] }}
                    </li>
                @empty
                    <li class="px-3 py-2 text-gray-500 text-sm">No notifications</li>
                @endforelse
            </ul>
        </div>
    @endif
</div>