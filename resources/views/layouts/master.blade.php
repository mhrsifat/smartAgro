<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'SmartAgro')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Axios (ensures cookies/credentials can be sent) -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Pusher + Laravel Echo -->
    <script src="https://js.pusher.com/8.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>

    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-800">
  
   <livewire:header />

  <main class="container mx-auto px-6 py-8">
      @yield('content')
  </main>
  
  <livewire:footer />

  <!-- Toast container -->
  <div id="toast-container" class="fixed bottom-5 right-5 space-y-3 z-50"></div>

 <script>
/**
 * Laravel Echo Configuration - ADD THIS FIRST
 */
// Configure axios defaults
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Initialize Laravel Echo
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
 * Full Echo + clickable toast integration for diagnosis notifications
 */

// current authenticated user id (null if guest)
const currentUserId = @json(auth()->id());

// normalize payloads from different shapes
function normalizeIncoming(data) {
    if (!data) return {};
    if (data.status || data.html || data.diagnosis_id || data.message) return data;
    if (data.data && (data.data.status || data.data.html || data.data.diagnosis_id || data.data.message)) return data.data;
    if (data.notification && data.notification.data) return data.notification.data;
    if (data.notification && (data.notification.status || data.notification.message)) return data.notification;
    return {};
}

// Smooth & lightweight toast
function showToast(message, type = 'info', url = null) {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `max-w-xs w-full bg-white shadow-md rounded-lg p-4 flex items-center justify-between space-x-3 transition-transform transition-opacity duration-300 ease-out cursor-pointer`;
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(100%)';

    const color = type === 'error' ? 'text-red-600' : type === 'info' ? 'text-blue-600' : 'text-green-600';

    toast.innerHTML = `
        <div class="${color} text-lg mr-3">🔔</div>
        <div class="flex-1 text-sm font-medium text-gray-900">${message}</div>
        ${url ? '<div class="text-xs text-gray-500 ml-3 underline">Open</div>' : ''}
    `;

    toast.addEventListener('click', () => {
        if (url) window.location.href = url;
        else removeToast(toast);
    });

    container.appendChild(toast);

    // Trigger slide-in
    requestAnimationFrame(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateX(0)';
    });

    // Auto remove
    setTimeout(() => removeToast(toast), 6000);
}

// Remove toast with smooth slide-out
function removeToast(toast) {
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(100%)';
    toast.addEventListener('transitionend', () => toast.remove(), { once: true });
}

// fetch full diagnosis HTML from server
function fetchFullDiagnosisHtml(diagnosisId) {
    if (!diagnosisId) return Promise.reject('no id');
    return fetch(`/diagnoses/${diagnosisId}`, {
        credentials: 'include',
        headers: { 'Accept': 'application/json' }
    }).then(r => {
        if (!r.ok) throw new Error('Failed to fetch report: ' + r.status);
        return r.json();
    });
}

// update #global-diagnosis
function updateDiagnosisUIFromPayload(payload) {
    const status = payload.status || '';
    const html = payload.html || '';
    const diagnosisId = payload.diagnosis_id || payload.id || payload.diagnosisId || null;

    const target = document.querySelector('#global-diagnosis');
    if (!target) return;

    if (status === 'processing') {
        target.textContent = '⏳ Processing...';
    } else if (status === 'failed') {
        target.textContent = '❌ ' + (html || 'Failed');
    } else if (status === 'completed') {
        if (diagnosisId) {
            fetchFullDiagnosisHtml(diagnosisId)
                .then(data => {
                    target.innerHTML = data.html || data.excerpt || 'Diagnosis ready';
                    const pb = document.querySelector('#progress-bar');
                    const pt = document.querySelector('#progress-text');
                    if (pb) pb.style.width = '100%';
                    if (pt) pt.textContent = 'All done';
                })
                .catch(err => {
                    console.error(err);
                    target.textContent = html || 'Diagnosis ready (failed to load full report)';
                });
        } else if (html) {
            target.innerHTML = html;
            const pb = document.querySelector('#progress-bar');
            const pt = document.querySelector('#progress-text');
            if (pb) pb.style.width = '100%';
            if (pt) pt.textContent = 'All done';
        } else {
            target.textContent = 'Diagnosis ready';
        }
    } else {
        target.textContent = html || 'Waiting for result...';
    }
}

// subscribe to diagnosis.{userId} channel
function subscribeDiagnosisChannel(userId) {
    if (!userId || !window.Echo) {
        console.warn('Cannot subscribe to diagnosis channel: missing userId or Echo');
        return;
    }
    try {
        window.Echo.private(`diagnosis.${userId}`)
            .listen('DiagnosisUpdated', (e) => {
                console.log('DiagnosisUpdated event received:', e);
                const payload = normalizeIncoming(e);
                updateDiagnosisUIFromPayload(payload);

                const diagId = payload.diagnosis_id || payload.id || null;
                const url = diagId ? `/diagnosis/${diagId}` : (payload.url || null);
                const msg = payload.message || (payload.status === 'completed' ? 'Diagnosis ready' : (payload.status || 'Update'));
                showToast(msg, payload.status === 'failed' ? 'error' : 'success', url);
            })
            .error(err => console.error('Diagnosis channel error', err));
    } catch (err) {
        console.error('Could not subscribe to diagnosis channel', err);
    }
}

// subscribe to App.Models.User.{id} notifications
function subscribeUserNotificationChannel(userId) {
    if (!userId || !window.Echo) {
        console.warn('Cannot subscribe to user notification channel: missing userId or Echo');
        return;
    }
    try {
        window.Echo.private(`App.Models.User.${userId}`)
            .notification((notification) => {
                console.log('User notification received:', notification);
                const payload = normalizeIncoming(notification);
                updateDiagnosisUIFromPayload(payload);
                const diagId = payload.diagnosis_id || payload.id || null;
                const url = payload.url || (diagId ? `/diagnosis/${diagId}` : null);
                const msg = payload.message || payload.status || 'Notification';
                showToast(msg, 'info', url);
            })
            .error(err => console.error('User notification channel error', err));
    } catch (err) {
        console.error('Could not subscribe to user notification channel', err);
    }
}

// subscribe to chat channel
function subscribeChatChannel() {
    if (!window.Echo) {
        console.warn('Cannot subscribe to chat channel: Echo not initialized');
        return;
    }
    try {
        window.Echo.private('chat')
            .listen('MessageSent', (e) => {
                console.log('MessageSent event received:', e);
                const text = e.message || e.text || JSON.stringify(e);
                showToast(text, 'info');
            })
            .error(err => console.error('Chat channel error', err));
    } catch (err) {
        console.error('Could not subscribe to chat channel', err);
    }
}

// Wait for Echo to be ready before subscribing
document.addEventListener('DOMContentLoaded', function() {
    // Small delay to ensure Echo is fully initialized
    setTimeout(() => {
        if (window.Echo && typeof window.Echo.private === 'function') {
            console.log('Echo initialized successfully');
            
            if (currentUserId) {
                subscribeDiagnosisChannel(currentUserId);
                subscribeUserNotificationChannel(currentUserId);
            }
            subscribeChatChannel();
        } else {
            console.error('Echo failed to initialize properly');
            console.log('Echo object:', window.Echo);
        }
    }, 100);
});
</script>

  @livewireScripts
  @stack('scripts')
</body>
</html>