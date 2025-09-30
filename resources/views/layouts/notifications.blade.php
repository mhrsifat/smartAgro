<div class="relative"
     x-data="notificationBell()"
     x-init="init()"
     @click.away="open = false">

    <!-- Bell button -->
    <button @click="open = !open; if(!hasLoadedOnce && open) fetchNotifications(true)"
            class="p-2 rounded-md hover:bg-gray-100 focus:outline-none relative"
            aria-label="Notifications">
        <x-heroicon-o-bell class="h-5 w-5" />
        <template x-if="unreadCount > 0">
            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center 
                         px-1.5 py-0.5 text-xs font-bold leading-none text-white 
                         bg-red-600 rounded-full animate-pulse"
                  x-text="unreadCount"></span>
        </template>
    </button>

    <!-- Dropdown -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-lg border z-50">
        
        <!-- Header with Mark All Read button -->
        <div class="p-3 border-b flex items-center justify-between">
            <div class="flex items-center">
                <span class="font-semibold text-gray-700">Notifications</span>
                <template x-if="isLoading">
                    <div class="ml-2">
                        <x-heroicon-o-arrow-path class="h-4 w-4 text-gray-500 animate-spin" />
                    </div>
                </template>
            </div>
            <template x-if="unreadCount > 0">
                <button @click="markAllAsRead()"
                        :disabled="isMarkingAllRead"
                        class="text-xs text-blue-600 hover:text-blue-800 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed px-2 py-1 rounded hover:bg-blue-50"
                        x-text="isMarkingAllRead ? 'Marking...' : 'Mark all read'">
                </button>
            </template>
        </div>

        <!-- Notifications list -->
        <ul class="max-h-60 overflow-y-auto divide-y">
            <template x-if="isLoading && !hasLoadedOnce">
                <li class="px-4 py-8 text-center">
                    <div class="text-gray-400 mb-3">
                        <x-heroicon-o-arrow-path class="h-8 w-8 mx-auto animate-spin" />
                    </div>
                    <p class="text-gray-500 text-sm">Loading notifications...</p>
                </li>
            </template>
            
            <template x-if="!isLoading && notifications.length === 0 && hasLoadedOnce">
                <li class="px-4 py-6 text-center">
                    <div class="text-gray-400 mb-2">
                        <x-heroicon-o-bell-slash class="h-8 w-8 mx-auto" />
                    </div>
                    <p class="text-gray-500 text-sm">No notifications</p>
                </li>
            </template>
            
            <template x-for="note in notifications" :key="note.id">
                <li class="group relative hover:bg-gray-50 transition-colors duration-150"
                    :class="{ 'opacity-50': note.isMarking }"
                    x-show="hasLoadedOnce || !isLoading">
                    
                    <!-- Notification content -->
                    <div class="px-4 py-3">
                        <template x-if="note.url">
                            <a :href="note.url" 
                               @click="markAsRead(note.id, $event)"
                               class="block text-sm hover:text-blue-600 transition-colors duration-150"
                               :class="note.is_unread ? 'text-gray-900' : 'text-gray-500'">
                                <!-- Content with unread indicator -->
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 mt-1" x-show="note.is_unread">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="break-words" 
                                           :class="note.is_unread ? 'font-semibold' : 'font-normal'"
                                           x-html="note.message"></p>
                                        <template x-if="note.created_at">
                                            <p class="text-xs text-gray-400 mt-1" x-text="formatTime(note.created_at)"></p>
                                        </template>
                                        <div class="flex items-center mt-1 text-xs text-blue-500">
                                            <x-heroicon-m-arrow-top-right-on-square class="h-3 w-3 mr-1" />
                                            <span>Click to view</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </template>
                        
                        <template x-if="!note.url">
                            <div class="text-sm"
                                 :class="note.is_unread ? 'text-gray-900' : 'text-gray-500'">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0 mt-1" x-show="note.is_unread">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="break-words" 
                                           :class="note.is_unread ? 'font-semibold' : 'font-normal'"
                                           x-html="note.message"></p>
                                        <template x-if="note.created_at">
                                            <p class="text-xs text-gray-400 mt-1" x-text="formatTime(note.created_at)"></p>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Mark as read button (shows on hover, only for unread notifications) -->
                        <template x-if="note.is_unread">
                            <button @click.stop="markAsRead(note.id)"
                                    :disabled="note.isMarking"
                                    class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-150 p-1 rounded-full hover:bg-gray-200 focus:outline-none disabled:opacity-50"
                                    title="Mark as read">
                                <template x-if="!note.isMarking">
                                    <x-heroicon-m-check class="h-4 w-4 text-gray-500" />
                                </template>
                                <template x-if="note.isMarking">
                                    <x-heroicon-o-arrow-path class="h-4 w-4 text-gray-500 animate-spin" />
                                </template>
                            </button>
                        </template>
                    </div>
                </li>
            </template>
        </ul>

        <!-- Footer -->
       <template x-if="hasLoadedOnce || !isLoading">
            <div class="p-3 border-t bg-gray-50 rounded-b-lg">
                <button @click="open = false; window.location.href = ''"
                        class="w-full text-center text-sm text-blue-600 hover:text-blue-800 focus:outline-none">
                    <template x-if="notifications.length > 0"> <span> </span>
                    </template>
                    <template x-if="notifications.length === 0"> <span> </span>
                    </template>
                </button>
            </div>
        </template>
        
    </div>
</div>

<script>
function notificationBell() {
    return {
        open: false,
        notifications: [],
        unreadCount: 0,
        isMarkingAllRead: false,
        isLoading: false,
        hasLoadedOnce: false,
        
        async fetchNotifications(showLoading = false) {
            if (showLoading) {
                this.isLoading = true;
            }
            
            try {
                const res = await fetch('/notifications/all', {
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (res.ok) {
                    const data = await res.json();
                    this.notifications = data.map(notification => ({
                        ...notification,
                        isMarking: false
                    }));
                    
                    // Update unread count
                    this.unreadCount = this.notifications.filter(n => n.is_unread).length;
                    this.hasLoadedOnce = true;
                } else {
                    console.error('Failed to fetch notifications', res.status);
                }
            } catch (e) {
                console.error('Error fetching notifications', e);
            } finally {
                this.isLoading = false;
            }
        },

        async markAsRead(notificationId, event = null) {
            // Find the notification to mark it as loading
            const notificationIndex = this.notifications.findIndex(n => n.id === notificationId);
            if (notificationIndex !== -1 && this.notifications[notificationIndex].is_unread) {
                this.notifications[notificationIndex].isMarking = true;
            }

            try {
                const response = await fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    // Update the notification to mark it as read
                    if (notificationIndex !== -1) {
                        this.notifications[notificationIndex].is_unread = false;
                        this.notifications[notificationIndex].read_at = new Date().toISOString();
                        this.notifications[notificationIndex].isMarking = false;
                        
                        // Update unread count
                        this.unreadCount = this.notifications.filter(n => n.is_unread).length;
                    }
                    
                    // If this was clicked from a link, allow navigation to continue
                    if (event && event.target.closest('a')) {
                        // Navigation will happen naturally from the link
                        return;
                    }
                } else {
                    console.error('Failed to mark notification as read', response.status);
                    // Reset the loading state on error
                    if (notificationIndex !== -1) {
                        this.notifications[notificationIndex].isMarking = false;
                    }
                }
            } catch (error) {
                console.error('Error marking notification as read', error);
                // Reset the loading state on error
                if (notificationIndex !== -1) {
                    this.notifications[notificationIndex].isMarking = false;
                }
            }
        },

        async markAllAsRead() {
            if (this.unreadCount === 0 || this.isMarkingAllRead) return;
            
            this.isMarkingAllRead = true;
            
            try {
                const response = await fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    // Mark all notifications as read
                    this.notifications.forEach(notification => {
                        if (notification.is_unread) {
                            notification.is_unread = false;
                            notification.read_at = new Date().toISOString();
                        }
                    });
                    
                    // Reset unread count
                    this.unreadCount = 0;
                    
                    // Show success message (optional)
                    if (window.showToast) {
                        window.showToast('All notifications marked as read', 'success');
                    }
                } else {
                    console.error('Failed to mark all notifications as read', response.status);
                    if (window.showToast) {
                        window.showToast('Failed to mark notifications as read', 'error');
                    }
                }
            } catch (error) {
                console.error('Error marking all notifications as read', error);
                if (window.showToast) {
                    window.showToast('Error marking notifications as read', 'error');
                }
            } finally {
                this.isMarkingAllRead = false;
            }
        },

        formatTime(timestamp) {
            try {
                const date = new Date(timestamp);
                const now = new Date();
                const diff = now - date;
                const minutes = Math.floor(diff / 60000);
                const hours = Math.floor(diff / 3600000);
                const days = Math.floor(diff / 86400000);

                if (minutes < 1) return 'Just now';
                if (minutes < 60) return `${minutes}m ago`;
                if (hours < 24) return `${hours}h ago`;
                if (days < 7) return `${days}d ago`;
                return date.toLocaleDateString();
            } catch (e) {
                return '';
            }
        },

        async init() {
            // Get CSRF token if needed
            try {
                await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
            } catch (e) {
                console.log('CSRF cookie not needed or failed to fetch');
            }

            // Initial fetch with loading state
            await this.fetchNotifications(true);
            
            // Set up periodic refresh (without loading state for background updates)
            setInterval(() => this.fetchNotifications(false), 30000);

            // Listen for new notifications from Echo (if available)
            if (window.Echo && window.currentUserId) {
                window.Echo.private(`App.Models.User.${window.currentUserId}`)
                    .notification((notification) => {
                        // Add new notification to the beginning of the list
                        this.notifications.unshift({
                            ...notification,
                            is_unread: true,
                            isMarking: false
                        });
                        
                        // Update unread count
                        this.unreadCount++;
                    });
            }
        }
    }
}
</script>