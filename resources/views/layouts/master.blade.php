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
        console.log('Normalizing data:', data); // DEBUG
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

        // Clean message HTML for display
        const cleanMessage = message.replace(/<[^>]*>/g, '');

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

        // Event listener for the main body (if URL exists)
        if (url) {
            toast.querySelector('.flex-1').addEventListener('click', () => {
                window.location.href = url;
            });
        }
        
        // Event listener for the close button
        toast.querySelector('[data-close-toast]').addEventListener('click', (e) => {
            e.stopPropagation();
            removeToast(toast);
        });
        
        container.appendChild(toast);

        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(0)';
        });

        setTimeout(() => removeToast(toast), 8000);
    }

    function removeToast(toast) {
        if (!toast) return;
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        toast.addEventListener('transitionend', () => toast.remove(), { once: true });
    }

    function fetchFullDiagnosisHtml(diagnosisId) {
        console.log(`Fetching diagnosis HTML for ID: ${diagnosisId}`); // DEBUG
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
        console.log('Updating diagnosis UI with payload:', payload); // DEBUG
        const { status, html, diagnosis_id, id } = payload;
        const diagnosisId = diagnosis_id || id;
        const target = document.querySelector('#global-diagnosis');
        
        if (!target) {
            console.log('Target element #global-diagnosis not found'); // DEBUG
            return;
        }

        if (status === 'processing') {
            target.innerHTML = '<div class="text-blue-600">⏳ AI is analyzing your images...</div>';
        } else if (status === 'failed') {
            target.innerHTML = `<div class="text-red-600">❌ ${html || 'Analysis failed'}</div>`;
        } else if (status === 'completed') {
            if (diagnosisId) {
                console.log(`Fetching full diagnosis for completed status, ID: ${diagnosisId}`); // DEBUG
                target.innerHTML = '<div class="text-blue-600">📋 Loading detailed results...</div>';
                
                fetchFullDiagnosisHtml(diagnosisId)
                    .then(data => {
                        console.log('Full diagnosis data received:', data); // DEBUG
                        target.innerHTML = data.html || data.excerpt || '<div class="text-green-600">✅ Diagnosis completed successfully!</div>';
                    })
                    .catch(err => {
                        console.error('Failed to fetch full diagnosis:', err);
                        target.innerHTML = html || '<div class="text-yellow-600">⚠️ Diagnosis completed but failed to load details.</div>';
                    });
            } else {
                target.innerHTML = html || '<div class="text-green-600">✅ Analysis completed!</div>';
            }
        } else {
            target.innerHTML = html || '<div class="text-gray-500">⏳ Waiting for analysis results...</div>';
        }
    }

    // Channel subscription functions
    function subscribeDiagnosisChannel(userId) {
        console.log(`Subscribing to diagnosis channel for user ${userId}`); // DEBUG
        window.Echo.private(`diagnosis.${userId}`)
            .listen('DiagnosisUpdated', (e) => {
                console.log('DiagnosisUpdated event received:', e);
                const payload = normalizeIncoming(e);
                updateDiagnosisUIFromPayload(payload);
                const msg = payload.message || (payload.status === 'completed' ? 'Diagnosis complete!' : 'Diagnosis updated.');
                const url = payload.url || (payload.status === 'completed' ? `/diagnoses/${payload.diagnosis_id || payload.id}` : null);
                showToast(msg, payload.status === 'failed' ? 'error' : 'success', url);
            })
            .error(err => console.error('Diagnosis channel error:', err));
    }

    function subscribeUserNotificationChannel(userId) {
        console.log(`Subscribing to user notification channel for user ${userId}`); // DEBUG
        window.Echo.private(`App.Models.User.${userId}`)
            .notification((notification) => {
                console.log('User notification received:', notification);
                
                // FIXED: Also update diagnosis UI from user notifications
                const payload = normalizeIncoming(notification);
                
                // Check if this is a diagnosis-related notification
                if (payload.diagnosis_id && payload.status) {
                    console.log('This is a diagnosis notification, updating UI'); // DEBUG
                    updateDiagnosisUIFromPayload(payload);
                }
                
                const msg = payload.message || 'You have a new notification.';
                const type = payload.status === 'completed' ? 'success' : (payload.status === 'failed' ? 'error' : 'info');
                showToast(msg, type, payload.url);
            })
            .error(err => console.error('User notification channel error:', err));
    }

    function subscribeChatChannel() {
        console.log('Subscribing to chat channel'); // DEBUG
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
        console.log('DOM loaded, initializing Echo...'); // DEBUG
        
        if (!window.Echo) {
            console.error('Echo failed to initialize.');
            return;
        }

        console.log('Echo object available:', window.Echo); // DEBUG

        window.Echo.connector.pusher.connection.bind('connected', () => {
            console.log('✅ Successfully connected to Pusher! Subscribing to channels...');
            
            if (currentUserId) {
                subscribeDiagnosisChannel(currentUserId);
                subscribeUserNotificationChannel(currentUserId);
            } else {
                console.warn('⚠️ No current user ID found');
            }
            subscribeChatChannel();
        });

        window.Echo.connector.pusher.connection.bind('error', (err) => {
            console.error('❌ Pusher connection error:', err);
        });

        // Check current connection state
        console.log('Current Pusher connection state:', window.Echo.connector.pusher.connection.state);
    });

    // Debug helper functions
    window.debugDiagnosis = {
        testToast: function() {
            showToast('Test toast message', 'success');
        },
        
        checkEchoStatus: function() {
            console.log('Echo status:', {
                echo: !!window.Echo,
                pusherState: window.Echo?.connector?.pusher?.connection?.state,
                subscribedChannels: Object.keys(window.Echo?.connector?.channels || {}),
                currentUserId: currentUserId
            });
        },
        
        simulateDiagnosis: function(diagnosisId = 6) {
            const payload = {
                status: 'completed',
                diagnosis_id: diagnosisId,
                message: 'Test diagnosis complete!'
            };
            updateDiagnosisUIFromPayload(payload);
            showToast(payload.message, 'success');
        }
    };
</script>
<script src="{{ asset('vendor/bladewind/js/helpers.js') }}" type="text/javascript"></script>
  @livewireScripts
  @stack('scripts')
</body>
</html>