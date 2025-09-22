@php
  $siteName     = config('sitedata.name');
  $logoUrl      = config('sitedata.logoUrl');
  $nav          = config('sitedata.nav');
  $serviceItems = config('sitedata.serviceItems');
  $languages    = config('sitedata.languages');
@endphp

<div class="header-root bg-white border-b sticky top-0 z-50"
     style="border-color: rgba(33,33,33,0.06);"
     x-data="{ mobileMenu: false, searchOpen: false }">

  <div class="container mx-auto px-4">
    <!-- TOP ROW -->
    <div class="flex items-center justify-between gap-4 py-3">
      <!-- Logo -->
      <div class="flex items-center gap-3">
        <a href="/" class="flex items-center gap-3">
          @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-9 w-auto object-contain" />
          @else
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none">
              <rect x="2" y="2" width="20" height="20" rx="6" fill="#2E7D32" />
              <path d="M7 13c1.5-3 6-3 7 0" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
              <circle cx="9.5" cy="9.5" r="1.25" fill="white" />
            </svg>
          @endif
          <span class="text-xl font-semibold text-[#212121]">{{ $siteName }}</span>
        </a>
      </div>

      <!-- Desktop actions -->
      <div class="hidden md:flex items-center gap-4">
        <!-- Search -->
        <form action="/search" method="GET" class="relative">
          <input type="text" name="q" placeholder="Search crops, pests, guides..."
            class="border rounded-lg px-3 py-2 w-64 focus:ring-2 focus:ring-[#2E7D32]" />
          <button type="submit"
            class="absolute right-1 top-1/2 -translate-y-1/2 bg-[#2E7D32] text-white px-2 py-1 rounded-md text-sm"
            aria-label="Search">
            <x-heroicon-o-magnifying-glass class="h-4 w-4" />
          </button>
        </form>

        <!-- Notification -->
        <div>
          @include('layouts.notifications')
        </div>

        <!-- Language -->
        <div class="relative" x-data="{ open: false }">
          <button @click="open = !open" class="flex items-center gap-2 font-semibold">
            Language <x-heroicon-o-chevron-down class="h-4 w-4" />
          </button>
          <div x-show="open" @click.away="open = false"
               x-transition
               class="absolute right-0 mt-2 w-44 bg-white border rounded shadow-lg py-1">
            @foreach($languages as $lang)
              <a href="?lang={{ $lang['code'] }}" class="block px-3 py-1 text-sm hover:bg-gray-50">
                {{ $lang['name'] }}
              </a>
            @endforeach
          </div>
        </div>

        <!-- Login -->
        @auth
  <div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="flex items-center gap-2">
      @if(Auth::user()->profile_photo_path)
        <img src="{{ Auth::user()->profile_photo_path }}"
             alt="{{ Auth::user()->name }}"
             class="h-8 w-8 rounded-full object-cover" />
      @else
        <x-heroicon-o-user-circle class="h-8 w-8 text-gray-600" />
      @endif
      <x-heroicon-o-chevron-down class="h-4 w-4" />
    </button>
    <div x-show="open" @click.away="open = false"
         x-transition
         class="absolute right-0 mt-2 w-44 bg-white border rounded shadow-lg py-1">
      <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-sm hover:bg-gray-50">
        Profile
      </a>
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50">
          Logout
        </button>
      </form>
    </div>
  </div>
@else
  <a href="/login" class="px-3 py-1 border rounded text-sm font-semibold">Login</a>
@endauth
      </div>

      <!-- Mobile icons -->
      <div class="flex items-center md:hidden gap-3">
        <!-- Search toggle: open top bar, close mobile menu -->
        <button @click="searchOpen = true; mobileMenu = false" class="p-2 rounded-md hover:bg-gray-100" aria-label="Open search">
          <x-heroicon-o-magnifying-glass class="h-5 w-5" />
        </button>

        @include('layouts.notifications')

        <!-- Hamburger: open mobile menu, close search -->
        <button @click="mobileMenu = true; searchOpen = false" class="p-2 rounded-md hover:bg-gray-100" aria-label="Open menu">
          <x-heroicon-o-bars-3 class="h-6 w-6" />
        </button>
      </div>
    </div>

    <!-- Desktop nav -->
    <div class="hidden md:block border-t">
      <nav class="flex items-center gap-6 py-2">
        @foreach($nav as $item)
          @if($item['hasChildren'] ?? false)
            <div class="relative" x-data="{ open: false }">
              <button @click="open = !open" class="inline-flex items-center gap-2 px-3 py-1">
                <x-dynamic-component :component="$item['icon']" class="h-5 w-5" />
                <span class="font-semibold">{{ $item['name'] }}</span>
                <x-heroicon-o-chevron-down class="h-4 w-4" />
              </button>
              <ul x-show="open" @click.away="open = false" x-transition
                  class="absolute left-0 mt-2 w-56 bg-white border rounded shadow-lg py-2">
                @foreach($serviceItems as $svc)
                  <li>
                    <a href="{{ $svc['url'] }}" class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-gray-50">
                      <x-dynamic-component :component="$svc['icon']" class="h-5 w-5 text-gray-500" />
                      <span class="font-semibold">{{ $svc['name'] }}</span>
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          @else
            <a href="{{ $item['url'] }}" class="flex items-center gap-2">
              <x-dynamic-component :component="$item['icon']" class="h-5 w-5" />
              <span class="font-semibold">{{ $item['name'] }}</span>
            </a>
          @endif
        @endforeach
      </nav>
    </div>
  </div>

  <!-- MOBILE MENU (slide-in) -->
  <div x-show="mobileMenu" x-transition
       class="fixed inset-0 z-50 md:hidden" @click.self="mobileMenu = false">
    <!-- backdrop -->
    <div class="absolute inset-0 bg-black/40"></div>

    <!-- panel -->
    <nav class="absolute left-0 top-0 bottom-0 w-72 bg-[#F5F5F5] p-4 overflow-y-auto transform -translate-x-0 transition-transform duration-200">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
          @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-8 w-auto object-contain" />
          @else
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
              <rect x="2" y="2" width="20" height="20" rx="6" fill="#2E7D32" />
            </svg>
          @endif
          <span class="font-semibold text-sm">{{ $siteName }}</span>
        </div>
        <button @click="mobileMenu = false" class="p-2 rounded hover:bg-gray-100">
          <x-heroicon-o-x-mark class="h-6 w-6" />
        </button>
      </div>

      <ul class="flex flex-col gap-2 text-[#212121]">
        @foreach($nav as $item)
          @if($item['hasChildren'] ?? false)
            <li x-data="{ open: false }">
              <button @click="open = !open" class="w-full flex items-center gap-2 px-3 py-2 rounded hover:bg-white">
                <x-dynamic-component :component="$item['icon']" class="h-5 w-5 text-gray-700" />
                <span class="font-semibold">{{ $item['name'] }}</span>
                <span class="ml-auto">▼</span>
              </button>

              <ul x-show="open" x-transition class="pl-6 mt-2 flex flex-col gap-1 overflow-hidden">
                @foreach($serviceItems as $svc)
                  <li>
                    <a href="{{ $svc['url'] }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-white">
                      <x-dynamic-component :component="$svc['icon']" class="h-5 w-5 text-gray-700" />
                      <span class="font-semibold">{{ $svc['name'] }}</span>
                    </a>
                  </li>
                @endforeach
              </ul>
            </li>
          @else
            <li>
              <a href="{{ $item['url'] }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-white">
                <x-dynamic-component :component="$item['icon']" class="h-5 w-5 text-gray-700" />
                <span class="font-semibold">{{ $item['name'] }}</span>
              </a>
            </li>
          @endif
        @endforeach

        <hr class="my-3" />

        <!-- Mobile language -->
        <li x-data="{ open: false }">
          <button @click="open = !open" class="w-full flex items-center gap-2 px-3 py-2 rounded hover:bg-white">
            <x-heroicon-o-globe-alt class="h-5 w-5 text-gray-700" />
            <span class="font-semibold">Language</span>
            <span class="ml-auto">▼</span>
          </button>
          <ul x-show="open" x-transition class="pl-6 mt-2 flex flex-col gap-1 overflow-hidden">
            @foreach($languages as $lang)
              <li>
                <a href="?lang={{ $lang['code'] }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-white">
                  <span>{{ $lang['name'] }}</span>
                </a>
              </li>
            @endforeach
          </ul>
        </li>
@auth
  <li class="flex items-center gap-2 px-3 py-2">
    @if(Auth::user()->profile_photo_path)
      <img src="{{ Auth::user()->profile_photo_path }}"
           alt="{{ Auth::user()->name }}"
           class="h-8 w-8 rounded-full object-cover" />
    @else
      <x-heroicon-o-user-circle class="h-8 w-8 text-gray-600" />
    @endif
    <span class="font-semibold">{{ Auth::user()->name }}</span>
  </li>

  <li>
    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-white">
      <x-heroicon-o-user class="h-5 w-5" />
      <span>Profile</span>
    </a>
  </li>

  <li>
    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" class="flex items-center gap-2 px-3 py-2 w-full text-left rounded hover:bg-white">
        <x-heroicon-o-arrow-right-on-rectangle class="h-5 w-5" />
        <span>Logout</span>
      </button>
    </form>
  </li>
@else
  <li>
    <a href="/login" class="flex items-center gap-2 px-3 py-2 rounded border hover:bg-white">
      <x-heroicon-o-user class="h-5 w-5" />
      <span class="font-semibold">Login</span>
    </a>
  </li>
@endauth
        
      </ul>
    </nav>
  </div>

  <!-- MOBILE SEARCH (top bar, not full page) -->
  <div x-show="searchOpen" x-transition class="fixed top-0 left-0 right-0 z-50 md:hidden">
    <div class="bg-white border-b px-4 py-3 flex items-center gap-2">
      <form action="/search" class="flex-1" method="GET">
        <input x-ref="mobileSearchInput" type="text" name="q" placeholder="Search crops, pests, guides..."
               class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#2E7D32]" />
      </form>
      <button @click="searchOpen = false" class="p-2 rounded hover:bg-gray-100">
        <x-heroicon-o-x-mark class="h-6 w-6" />
      </button>
    </div>

    <!-- ensure body content pushed down so nav isn't covered (small spacer) -->
    <div class="h-3"></div>

    <script>
      // focus input when search opens (works with Alpine)
      document.addEventListener('alpine:init', () => {
        Alpine.effect(() => {
          try {
            const root = document.querySelector('[x-data]');
            if (!root) return;
            const searchOpen = root.__x.$data.searchOpen;
            if (searchOpen) {
              // slight delay so DOM is painted
              setTimeout(() => {
                const input = document.querySelector('[x-ref="mobileSearchInput"]');
                input?.focus();
              }, 60);
            }
          } catch(e) {}
        });
      });
    </script>
  </div>
</div>