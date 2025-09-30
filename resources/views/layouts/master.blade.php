<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'SmartAgro')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- favicon emoji -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🌾</text></svg>">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    {{-- Pusher & Echo --}}
    <script src="https://js.pusher.com/8.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>

    <!-- Bladewind UI -->
    <link href="{{ asset('vendor/bladewind/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bladewind/css/bladewind-ui.min.css') }}" rel="stylesheet" />

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
    // CSRF + axios default
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // currentUserId will be null for guests
    let currentUserId = @json(auth()->id());

    // Flag to prevent double Echo init
    window.__echoInitialized = window.__echoInitialized || false;

    // Toast helper ----------------------------------------------------------
    function showToast(message, type = 'info', url = null) {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `max-w-xs w-full bg-white shadow-lg rounded-lg pointer-events-auto flex ring-1 ring-black ring-opacity-5 transition-transform transition-opacity duration-300 ease-out`;
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';

        const color = type === 'error' ? 'text-red-600' : type === 'info' ? 'text-blue-600' : 'text-green-600';
        const cleanMessage = (message || '').replace(/<[^>]*>/g, '');

        toast.innerHTML = `
            <div class="p-4 flex items-start w-full">
                <div class="flex-shrink-0 pt-0.5">
                    <div class="${color} text-xl">🔔</div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">${cleanMessage}</p>
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

        if (url) {
            toast.querySelector('.flex-1').addEventListener('click', () => {
                window.location.href = url;
            });
        }

        toast.querySelector('[data-close-toast]').addEventListener('click', (e) => {
            e.stopPropagation();
            removeToast(toast);
        });

        container.appendChild(toast);
        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(0)';
        });

        setTimeout(() => removeToast(toast), 30000);
    }

    function removeToast(toast) {
        if (!toast) return;
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        toast.addEventListener('transitionend', () => toast.remove(), { once: true });
    }

    // ---- Echo init only for authenticated users ----
    function initEchoIfAuthenticated() {
        if (!currentUserId) return; // Guests skip Echo init for private channels
        if (window.__echoInitialized) return;

        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: '{{ config("broadcasting.connections.pusher.key") }}',
                cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
                forceTLS: location.protocol === 'https:',
                encrypted: true,
                authEndpoint: '/broadcasting/auth',
                auth: { headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } }
            });

            window.__echoInitialized = true;

            // subscribe to channels once connected
            window.Echo.connector.pusher.connection.bind('connected', () => {
                subscribeDiagnosisChannel(currentUserId);      // we still listen, but only show toasts
                subscribeUserNotificationChannel(currentUserId); // notification channel for toasts
                subscribeChatChannel(true);
            });

            window.Echo.connector.pusher.connection.bind('error', (err) => {
                console.error('Pusher connection error:', err);
            });

        } catch (err) {
            console.error('Failed to initialize Echo:', err);
        }
    }

    // Subscriptions ---------------------------------------------------------
    function subscribeDiagnosisChannel(userId) {
        if (!window.Echo || !userId) return;

        try {
            window.Echo.private(`diagnosis.${userId}`)
                .listen('.DiagnosisUpdated', (e) => {
                    // Normalize payload
                    const payload = (e?.data) ? e.data : e;
                    const message = payload.message || 'You have a diagnosis update.';
                    // Determine toast type: failed -> error, processing -> info, else success
                    const status = payload.status || '';
                    const type = status === 'failed' ? 'error' : (status === 'processing' ? 'info' : 'success');

                    // ONLY show toast. No DOM updates, no fetches.
                    showToast(message, type, payload.url || null);
                })
                .error(err => console.error('Diagnosis channel error:', err));

            console.log('Subscribed to private diagnosis.' , `diagnosis.${userId}`);
        } catch (err) {
            console.error('subscribeDiagnosisChannel err', err);
        }
    }

    function subscribeUserNotificationChannel(userId) {
        if (!window.Echo || !userId) return;
        window.Echo.private(`App.Models.User.${userId}`)
            .notification((notification) => {
                const payload = notification?.data || notification;
                const msg = payload.message || 'You have a new notification.';
                const type = payload.status === 'completed' ? 'success' : (payload.status === 'failed' ? 'error' : 'info');
                showToast(msg, type, payload.url);
            });
    }

    function subscribeChatChannel(isAuthenticated = false) {
        if (!window.Echo) return;
        if (isAuthenticated && currentUserId) {
            window.Echo.private('chat')
                .listen('MessageSent', (e) => {
                    const text = e.message || e.text || 'New message in chat';
                    showToast(text, 'info');
                });
        } else {
            window.Echo.channel('chat')
                .listen('MessageSent', (e) => {
                    const text = e.message || e.text || 'New message in chat';
                    showToast(text, 'info');
                });
        }
    }

    // Listen for login events to initialize Echo dynamically
    window.addEventListener('user:logged-in', function(e) {
        if (e?.detail?.id) {
            currentUserId = e.detail.id;
            initEchoIfAuthenticated();
        }
    });

    // DOMContentLoaded: initialize Echo only if authenticated; guests still get public chat toasts.
    document.addEventListener('DOMContentLoaded', function() {
        if (currentUserId) {
            initEchoIfAuthenticated();
        } else {
            // Guests: optional public chat subscription (keeps toasts working)
            subscribeChatChannel(false);
        }
    });
</script>


<script src="{{ asset('vendor/bladewind/js/helpers.js') }}" type="text/javascript"></script>

@livewireScripts
@stack('scripts')
</body>
</html>