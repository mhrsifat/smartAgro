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

  <!-- Echo + Toast Script -->
  <script>
    // Make sure axios will send cookies (important if front-end and backend are different ports)
    if (window.axios) {
      axios.defaults.withCredentials = true;
    }

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;

    if (!csrfToken) {
      console.warn('CSRF token not found in meta tag. Broadcasting auth will fail without it.');
    }

    // Optional: enable Pusher console logs for debugging (turn off in production)
    if (window.Pusher) {
      Pusher.logToConsole = true;
    }

    // Initialize Echo (CDN build)
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "{{ config('broadcasting.connections.pusher.key') }}",
    cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
    forceTLS: location.protocol === 'https:',
    authEndpoint: "{{ url('/broadcasting/auth') }}",
    auth: {
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    }
});

    // Helpful debug: log subscription/auth failures
    try {
      // If the connector exists, bind to low-level pusher events
      if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
        const pusherConn = window.Echo.connector.pusher.connection;

        pusherConn.bind('error', function(err) {
          console.error('Pusher connection error:', err);
        });

        // subscription_error fires on auth failure for private channels
        pusherConn.bind('subscription_error', function(status) {
          console.error('Subscription/auth error status:', status);
        });
      }
    } catch (e) {
      console.warn('Echo debug binding failed', e);
    }

    // Listen to channel
    window.Echo.private('chat')
        .listen('MessageSent', (e) => {
            showToast(e.message, 'info');
        });

    // Toast function
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        
        toast.className =
            "max-w-xs w-full bg-white shadow-lg rounded-lg ring-1 ring-black ring-opacity-5 p-4 flex items-center space-x-3 animate-slide-in";

        let color = type === 'error' ? 'text-red-600' : (type === 'info' ? 'text-blue-600' : 'text-green-600');

        toast.innerHTML = `
            <div class="${color} text-lg">🔔</div>
            <div class="flex-1 text-sm font-medium text-gray-900">${message}</div>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('animate-slide-out');
            setTimeout(() => toast.remove(), 500);
        }, 5000);
    }
  </script>

  <!-- Animations -->
  <style>
    @keyframes slide-in {
        from { transform: translateX(120%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slide-out {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(120%); opacity: 0; }
    }
    .animate-slide-in { animation: slide-in 0.5s ease-out; }
    .animate-slide-out { animation: slide-out 0.5s ease-in forwards; }
  </style>

  @livewireScripts
  @stack('scripts')
</body>
</html>
