<div class="relative"
     x-data="notificationBell()"
     x-init="init()"
     @click.away="open = false">

    <!-- Bell button -->
    <button @click="open = !open"
            class="p-2 rounded-md hover:bg-gray-100 focus:outline-none relative"
            aria-label="Notifications">
        <x-heroicon-o-bell class="h-5 w-5" />
        <template x-if="notifications.length">
            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center 
                         px-1.5 py-0.5 text-xs font-bold leading-none text-white 
                         bg-red-600 rounded-full"
                  x-text="notifications.length"></span>
        </template>
    </button>

    <!-- Dropdown -->
    <div x-show="open" x-transition
         class="absolute right-0 mt-2 w-72 bg-white shadow-lg rounded-lg border z-50">
        <div class="p-3 border-b font-semibold text-gray-700">
            Notifications
        </div>
        <ul class="max-h-60 overflow-y-auto divide-y">
            <template x-if="notifications.length === 0">
                <li class="px-3 py-2 text-gray-500 text-sm">No notifications</li>
            </template>
            <template x-for="note in notifications" :key="note.id">
                <li class="px-3 py-2 hover:bg-gray-50 text-sm text-gray-700">
                    <template x-if="note.url">
                        <a :href="note.url" class="block hover:text-blue-600">
                            <span x-text="note.message"></span>
                            <span class="text-xs text-blue-500 ml-2">→ View</span>
                        </a>
                    </template>
                    <template x-if="!note.url">
                        <span x-text="note.message"></span>
                    </template>
                </li>
            </template>
        </ul>
    </div>
</div>

<script>
function notificationBell() {
    return {
        open: false,
        notifications: [],
        async fetchNotifications() {
            try {
                const res = await fetch('/notifications/unread', {
                    credentials: 'include'
                });
                if (res.ok) {
                    this.notifications = await res.json();
                } else {
                    console.error('Failed to fetch notifications', res.status);
                }
            } catch (e) {
                console.error('Error fetching notifications', e);
            }
        },
        async init() {
            await fetch('/sanctum/csrf-cookie', { credentials: 'include' });

            this.fetchNotifications();
            setInterval(() => this.fetchNotifications(), 10000);
        }
    }
}
</script>