<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Admin Panel')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts & Tailwind -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/windmill/css/tailwind.output.css') }}" />

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="{{ asset('assets/windmill/js/init-alpine.js') }}" defer></script>

    <!-- Chart.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" defer></script>
    <script src="{{ asset('assets/windmill/js/charts-lines.js') }}" defer></script>
    <script src="{{ asset('assets/windmill/js/charts-pie.js') }}" defer></script>

    @stack('head') {{-- Optional additional head scripts --}}
</head>
<body class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Mobile Sidebar Backdrop --}}
    <div x-show="isSideMenuOpen" x-transition class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"></div>

    <div class="flex flex-col flex-1 w-full">
        {{-- Header --}}
        @include('partials.header')

        {{-- Main Content --}}
        <main class="h-full overflow-y-auto">
            <div class="container px-6 mx-auto grid">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts') {{-- Optional scripts --}}
</body>
</html>