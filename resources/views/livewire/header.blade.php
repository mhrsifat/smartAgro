<div class="header-root bg-white border-b sticky top-0 z-50" style="border-color: rgba(33,33,33,0.06);">
    <div class="container mx-auto px-4">
        <!-- TOP ROW -->
        <div class="flex items-center justify-between gap-4 py-3">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <a href="/" class="flex items-center gap-3">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-9 w-auto object-contain" />
                    @else
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect x="2" y="2" width="20" height="20" rx="6" fill="#2E7D32" />
                            <path d="M7 13c1.5-3 6-3 7 0" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <circle cx="9.5" cy="9.5" r="1.25" fill="white" />
                        </svg>
                    @endif

                    <span class="text-xl font-semibold text-[#212121]">{{ $siteName }}</span>
                </a>
            </div>

            <!-- Desktop actions -->
            <div class="hidden md:flex items-center gap-4">
                <!-- Search (desktop) -->
                <form action="/search" method="GET" class="relative">
                    <input type="text" name="q" placeholder="Search crops, pests, guides..."
                           class="border rounded-lg px-3 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-[#2E7D32]" />
                    <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 inline-flex items-center justify-center px-2 py-1 rounded-md bg-[#2E7D32] text-white text-sm" aria-label="Search">
                        <x-heroicon-o-magnifying-glass class="h-4 w-4" />
                        <span class="sr-only">Search</span>
                    </button>
                </form>
                
                <livewire:notification-bell />

                <!-- Language -->
                <div class="relative">
                    <button wire:click.prevent="toggleDesktopLang"
                            aria-expanded="{{ $desktopLangOpen ? 'true' : 'false' }}"
                            class="flex items-center gap-2 text-gray-700 hover:text-[#2E7D32] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2E7D32] rounded">
                        <span class="font-semibold">Language</span>
                        <x-heroicon-o-chevron-down class="h-4 w-4" />
                    </button>

                    @if($desktopLangOpen)
                        <div class="absolute right-0 mt-2 w-44 bg-white border rounded shadow-lg py-1">
                            <a href="#" class="block px-3 py-1 text-sm hover:bg-gray-50">English</a>
                            <a href="#" class="block px-3 py-1 text-sm hover:bg-gray-50">বাংলা (BN)</a>
                            <a href="#" class="block px-3 py-1 text-sm hover:bg-gray-50">العربية</a>
                        </div>
                    @endif
                </div>

                <!-- Login -->
                <a href="/login" class="inline-flex items-center gap-2 px-3 py-1 rounded border hover:bg-gray-50 text-gray-700">
                    <x-heroicon-o-user class="h-5 w-5" />
                    <span class="text-sm font-semibold">Login</span>
                </a>
            </div>

            <!-- Mobile icons -->
            <div class="flex items-center md:hidden gap-3">
                <button wire:click.prevent="toggleMobileSearch" class="p-2 rounded-md hover:bg-gray-100 focus:outline-none" aria-label="Open search">
                    <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                </button>

                <livewire:notification-bell :mobile="true" />

                <button wire:click.prevent="toggleMobileMenu" class="p-2 rounded-md hover:bg-gray-100 focus:outline-none" aria-expanded="{{ $mobileMenuOpen ? 'true' : 'false' }}" aria-controls="mobileMenu">
                    <x-heroicon-o-bars-3 class="h-6 w-6" />
                </button>
            </div>
        </div>

        <!-- SECOND ROW (desktop nav) -->
        <div class="hidden md:block border-t">
            <div class="container mx-auto px-4">
                <nav class="flex items-center gap-6 py-2">
                    @foreach($nav as $item)
                        @if(isset($item['hasChildren']) && $item['hasChildren'])
                            <div class="relative group">
                                <button class="inline-flex items-center gap-2 text-gray-700 hover:text-[#2E7D32] text-sm px-3 py-1 rounded focus:outline-none">
                                    <x-dynamic-component :component="$item['icon']" class="h-5 w-5 text-gray-500" />
                                    <span class="font-semibold">{{ $item['name'] }}</span>
                                    <x-heroicon-o-chevron-down class="h-4 w-4" />
                                </button>

                                <ul class="absolute left-0 mt-2 w-56 bg-white border rounded shadow-lg py-2 hidden group-hover:block z-10">
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
                            <a href="{{ $item['url'] }}" class="flex items-center gap-2 text-gray-700 hover:text-[#2E7D32] font-semibold">
                                <x-dynamic-component :component="$item['icon']" class="h-5 w-5 text-gray-500" />
                                <span>{{ $item['name'] }}</span>
                            </a>
                        @endif
                    @endforeach
                </nav>
            </div>
        </div>
    </div>

    <!-- MOBILE search panel -->
    @if($mobileSearchOpen)
        <div class="md:hidden px-4 py-3 bg-gray-50 border-t">
            <form action="/search" method="GET" class="flex gap-2">
                <input type="text" name="q" placeholder="Search..." class="flex-1 border rounded-lg px-3 py-2 focus:outline-none" />
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-[#2E7D32] text-white font-semibold">Search</button>
            </form>
        </div>
    @endif

    <!-- MOBILE nav -->
    <nav id="mobileMenu" class="md:hidden bg-[#F5F5F5]">
        @if($mobileMenuOpen)
            <div class="px-4 py-3">
                <ul class="flex flex-col gap-2 text-[#212121]">
                    @foreach($nav as $item)
                        @if(isset($item['hasChildren']) && $item['hasChildren'])
                            <li>
                                <button wire:click.prevent="toggleMobileService" class="w-full flex items-center gap-2 text-left px-3 py-2 rounded hover:bg-white focus:outline-none">
                                    <x-dynamic-component :component="$item['icon']" class="h-5 w-5 text-gray-700" />
                                    <span class="font-semibold">{{ $item['name'] }}</span>
                                    <span class="ml-auto">{{ $mobileServiceOpen ? '▲' : '▼' }}</span>
                                </button>

                                @if($mobileServiceOpen)
                                    <ul class="pl-6 mt-2 flex flex-col gap-1">
                                        @foreach($serviceItems as $svc)
                                            <li>
                                                <a href="{{ $svc['url'] }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-white">
                                                    <x-dynamic-component :component="$svc['icon']" class="h-5 w-5 text-gray-700" />
                                                    <span class="font-semibold">{{ $svc['name'] }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
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
                    <li>
                        <button wire:click.prevent="toggleMobileLang" class="w-full flex items-center gap-2 text-left px-3 py-2 rounded hover:bg-white focus:outline-none">
                            <x-heroicon-o-globe-alt class="h-5 w-5" />
                            <span class="font-semibold">Language</span>
                            <span class="ml-auto">{{ $mobileLangOpen ? '▲' : '▼' }}</span>
                        </button>

                        @if($mobileLangOpen)
                            <ul class="pl-6 mt-2 flex flex-col gap-1">
                                <li><a href="#" class="block px-3 py-2 rounded hover:bg-white">English</a></li>
                                <li><a href="#" class="block px-3 py-2 rounded hover:bg-white">বাংলা (Bengali)</a></li>
                                <li><a href="#" class="block px-3 py-2 rounded hover:bg-white">العربية (Arabic)</a></li>
                            </ul>
                        @endif
                    </li>

                    <!-- Login -->
                    <li>
                        <a href="/login" class="flex items-center gap-2 px-3 py-2 rounded border hover:bg-white">
                            <x-heroicon-o-user class="h-5 w-5" />
                            <span class="font-semibold">Login</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif
    </nav>
</div>

<!-- Robust Livewire-safe script: waits for livewire to be ready, queues emits, prevents duplicate handlers -->
<script>
  // global emit queue (idempotent)
  window._livewireEmitQueue = window._livewireEmitQueue || [];

  function emitWhenLivewire(eventName, ...args) {
    if (window.Livewire && typeof Livewire.emit === 'function') {
      Livewire.emit(eventName, ...args);
      return;
    }
    window._livewireEmitQueue.push({ eventName, args });
  }

  document.addEventListener('livewire:load', function () {
    // flush queue when Livewire is ready
    if (window._livewireEmitQueue && window.Livewire && typeof Livewire.emit === 'function') {
      window._livewireEmitQueue.forEach(item => {
        Livewire.emit(item.eventName, ...item.args);
      });
      window._livewireEmitQueue = [];
    }

    // add a single click-outside handler (guard to avoid duplicates on re-renders)
    if (!window._headerClickHandlerAdded) {
      window._headerClickHandlerAdded = true;

      document.addEventListener('click', function (e) {
        if (!e.target.closest('.header-root')) {
          emitWhenLivewire('closeAll');
        }
      });
    }
  });

  // Safety: if Livewire never fires 'livewire:load' (rare), also try to bind after DOMContentLoaded
  document.addEventListener('DOMContentLoaded', function () {
    // if Livewire already ready, 'livewire:load' may have fired; ensure queue handled
    if (window.Livewire && typeof Livewire.emit === 'function') {
      if (window._livewireEmitQueue && window._livewireEmitQueue.length) {
        window._livewireEmitQueue.forEach(item => Livewire.emit(item.eventName, ...item.args));
        window._livewireEmitQueue = [];
      }
      // ensure handler installed
      if (!window._headerClickHandlerAdded) {
        window._headerClickHandlerAdded = true;
        document.addEventListener('click', function (e) {
          if (!e.target.closest('.header-root')) {
            Livewire.emit && typeof Livewire.emit === 'function' && Livewire.emit('closeAll');
          }
        });
      }
    }
  });
</script>
