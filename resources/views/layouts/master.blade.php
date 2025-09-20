<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'SmartAgro')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script src="https://js.pusher.com/8.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>

    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-800">

   @include('layouts.header')

  <main class="container mx-auto px-6 py-8">
      @yield('content')
  </main>

   <livewire:footer />

  <div id="toast-container" class="fixed bottom-5 right-5 space-y-3 z-50"></div>

 <script>
    /**
     * Laravel Echo Configuration
     */
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ config("broadcasting.connections.pusher.key") }}',
        cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
        forceTLS: location.protocol === 'https:',
        encrypted: true,
        auth: {
            headers: {
                Accept: 'application/json',
            },
        },
        authorizer: (channel, options) => {
            return {
                authorize: (socketId, callback) => {
                    axios.post('/broadcasting/auth', {
                        socket_id: socketId,
                        channel_name: channel.name
                    })
                    .then(response => {
                        callback(false, response.data);
                    })
                    .catch(error => {
                        callback(true, error);
                    });
                }
            };
        },
    });

    /**
     * Toast & Notification Logic
     */
    const currentUserId = @json(auth()->id());

    function normalizeIncoming(data) {
        if (!data) return {};
        if (data.status || data.html || data.diagnosis_id || data.message) return data;
        if (data.data && (data.data.status || data.data.html || data.data.diagnosis_id || data.data.message)) return data.data;
        if (data.notification && data.notification.data) return data.notification.data;
        if (data.notification && (data.notification.status || data.notification.message)) return data.notification;
        return {};
    }

    function showToast(message, type = 'info', url = null) {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `max-w-xs w-full bg-white shadow-lg rounded-lg pointer-events-auto flex ring-1 ring-black ring-opacity-5 transition-transform transition-opacity duration-300 ease-out`;
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';

        const color = type === 'error' ? 'text-red-600' : type === 'info' ? 'text-blue-600' : 'text-green-600';

        // CHANGE: Added a close button and improved toast structure
        toast.innerHTML = `
            <div class="p-4 flex items-start w-full">
                <div class="flex-shrink-0 pt-0.5">
                    <div class="${color} text-xl">🔔</div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">${message}</p>
                    ${url ? '<p class="mt-1 text-sm text-gray-500 underline cursor-pointer">View Details</p>' : ''}
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none" data-close-toast>
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        `;

        // Event listener for the main body (if URL exists)
        if (url) {
            toast.querySelector('.flex-1').addEventListener('click', () => {
                window.location.href = url;
            });
        }
        
        // Event listener for the close button
        toast.querySelector('[data-close-toast]').addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent the main click event from firing
            removeToast(toast);
        });
        
        container.appendChild(toast);

        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(0)';
        });

        // CHANGE: Reduced auto-remove time to 8 seconds
        setTimeout(() => removeToast(toast), 50 * 1000);
    }

    function removeToast(toast) {
        if (!toast) return;
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        toast.addEventListener('transitionend', () => toast.remove(), { once: true });
    }

    function fetchFullDiagnosisHtml(diagnosisId) {
        if (!diagnosisId) return Promise.reject('No diagnosis ID provided');
        return fetch(`/diagnoses/${diagnosisId}`, {
            credentials: 'include',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
        }).then(r => {
            if (!r.ok) throw new Error('Failed to fetch report: ' + r.status);
            return r.json();
        });
    }

    function updateDiagnosisUIFromPayload(payload) {
        const { status, html, diagnosis_id, id } = payload;
        const diagnosisId = diagnosis_id || id;
        const target = document.querySelector('#global-diagnosis');
        if (!target) return;

        if (status === 'processing') {
            target.innerHTML = '⏳ Processing...';
        } else if (status === 'failed') {
            target.innerHTML = `❌ ${html || 'Failed'}`;
        } else if (status === 'completed') {
            if (diagnosisId) {
                fetchFullDiagnosisHtml(diagnosisId)
                    .then(data => {
                        target.innerHTML = data.html || data.excerpt || 'Diagnosis ready';
                    })
                    .catch(err => {
                        console.error(err);
                        target.textContent = html || 'Diagnosis ready (failed to load full report)';
                    });
            } else {
                target.innerHTML = html || 'Diagnosis ready';
            }
        } else {
            target.innerHTML = html || 'Waiting for result...';
        }
    }

    // Channel subscription functions remain the same
    function subscribeDiagnosisChannel(userId) {
        window.Echo.private(`diagnosis.${userId}`)
            .listen('DiagnosisUpdated', (e) => {
                console.log('DiagnosisUpdated event:', e);
                const payload = normalizeIncoming(e);
                updateDiagnosisUIFromPayload(payload);
                const msg = payload.message || (payload.status === 'completed' ? 'Diagnosis complete!' : 'Diagnosis updated.');
                const url = payload.url || (payload.status === 'completed' ? `/diagnoses/${payload.diagnosis_id || payload.id}` : null);
                showToast(msg, payload.status === 'failed' ? 'error' : 'success', url);
            })
            .error(err => console.error('Diagnosis channel error:', err));
    }

    function subscribeUserNotificationChannel(userId) {
        window.Echo.private(`App.Models.User.${userId}`)
            .notification((notification) => {
                console.log('User notification received:', notification);
                const payload = normalizeIncoming(notification);
                const msg = payload.message || 'You have a new notification.';
                showToast(msg, 'info', payload.url);
            })
            .error(err => console.error('User notification channel error:', err));
    }

    function subscribeChatChannel() {
        window.Echo.private('chat')
            .listen('MessageSent', (e) => {
                console.log('MessageSent event received:', e);
                const text = e.message || e.text || 'New message in chat';
                showToast(text, 'info');
            })
            .error(err => console.error('Chat channel error:', err));
    }

    // --- Initialization ---
    document.addEventListener('DOMContentLoaded', function() {
        if (!window.Echo) {
            console.error('Echo failed to initialize.');
            return;
        }

        // CHANGE: Removed setTimeout and used the 'connected' event for robust subscription
        window.Echo.connector.pusher.connection.bind('connected', () => {
            console.log('Successfully connected to Pusher! Subscribing to channels...');
            
            if (currentUserId) {
                subscribeDiagnosisChannel(currentUserId);
                subscribeUserNotificationChannel(currentUserId);
            }
            subscribeChatChannel();
        });

        window.Echo.connector.pusher.connection.bind('error', (err) => {
            console.error('Pusher connection error:', err);
        });
    });
</script>


  @livewireScripts
  @stack('scripts')
</body>
</html>
