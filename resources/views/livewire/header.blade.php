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
        <!-- Search -->
        <form action="/search" method="GET" class="relative">
          <input type="text" name="q" placeholder="Search crops, pests, guides..."
            class="border rounded-lg px-3 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-[#2E7D32]" />
          <button type="submit"
            class="absolute right-1 top-1/2 -translate-y-1/2 inline-flex items-center justify-center px-2 py-1 rounded-md bg-[#2E7D32] text-white text-sm"
            aria-label="Search">
            <x-heroicon-o-magnifying-glass class="h-4 w-4" />
            <span class="sr-only">Search</span>
          </button>
        </form>

        <!-- Notification -->
        <div class="relative" data-notification>
          <livewire:notification-bell />
        </div>

        <!-- Language -->
        <div class="relative">
          <button data-dropdown aria-expanded="false"
            class="flex items-center gap-2 text-gray-700 hover:text-[#2E7D32] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2E7D32] rounded">
            <span class="font-semibold">Language</span>
            <x-heroicon-o-chevron-down class="h-4 w-4" />
          </button>
          <div data-menu class="absolute right-0 mt-2 w-44 bg-white border rounded shadow-lg py-1">
            <a href="#" class="block px-3 py-1 text-sm hover:bg-gray-50">English</a>
            <a href="#" class="block px-3 py-1 text-sm hover:bg-gray-50">বাংলা (BN)</a>
            <a href="#" class="block px-3 py-1 text-sm hover:bg-gray-50">العربية</a>
          </div>
        </div>

        <!-- Login -->
        <a href="/login"
          class="inline-flex items-center gap-2 px-3 py-1 rounded border hover:bg-gray-50 text-gray-700">
          <x-heroicon-o-user class="h-5 w-5" />
          <span class="text-sm font-semibold">Login</span>
        </a>
      </div>

      <!-- Mobile icons -->
      <div class="flex items-center md:hidden gap-3">
        <button class="p-2 rounded-md hover:bg-gray-100 focus:outline-none" aria-label="Open search">
          <x-heroicon-o-magnifying-glass class="h-5 w-5" />
        </button>

        <div data-notification>
          <livewire:notification-bell :mobile="true" />
        </div>

        <button class="p-2 rounded-md hover:bg-gray-100 focus:outline-none" aria-expanded="false" aria-controls="mobileMenu">
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
              <div class="relative">
                <button data-dropdown aria-expanded="false"
                  class="inline-flex items-center gap-2 text-gray-700 hover:text-[#2E7D32] text-sm px-3 py-1 rounded focus:outline-none">
                  <x-dynamic-component :component="$item['icon']" class="h-5 w-5 text-gray-500" />
                  <span class="font-semibold">{{ $item['name'] }}</span>
                  <x-heroicon-o-chevron-down class="h-4 w-4" />
                </button>
                <ul data-menu class="absolute left-0 mt-2 w-56 bg-white border rounded shadow-lg py-2">
                  @foreach($serviceItems as $svc)
                    <li>
                      <a href="{{ $svc['url'] }}"
                        class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-gray-50">
                        <x-dynamic-component :component="$svc['icon']" class="h-5 w-5 text-gray-500" />
                        <span class="font-semibold">{{ $svc['name'] }}</span>
                      </a>
                    </li>
                  @endforeach
                </ul>
              </div>
            @else
              <a href="{{ $item['url'] }}"
                class="flex items-center gap-2 text-gray-700 hover:text-[#2E7D32] font-semibold">
                <x-dynamic-component :component="$item['icon']" class="h-5 w-5 text-gray-500" />
                <span>{{ $item['name'] }}</span>
              </a>
            @endif
          @endforeach
        </nav>
      </div>
    </div>
  </div>

  <!-- MOBILE nav -->
  <nav id="mobileMenu" class="md:hidden bg-[#F5F5F5]">
    <div class="px-4 py-3">
      <ul class="flex flex-col gap-2 text-[#212121]">
        @foreach($nav as $item)
          @if(isset($item['hasChildren']) && $item['hasChildren'])
            <li>
              <button data-collapsible aria-expanded="false"
                class="w-full flex items-center gap-2 text-left px-3 py-2 rounded hover:bg-white focus:outline-none">
                <x-dynamic-component :component="$item['icon']" class="h-5 w-5 text-gray-700" />
                <span class="font-semibold">{{ $item['name'] }}</span>
                <span class="ml-auto">▼</span>
              </button>
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
          <button data-collapsible aria-expanded="false"
            class="w-full flex items-center gap-2 text-left px-3 py-2 rounded hover:bg-white focus:outline-none">
            <x-heroicon-o-globe-alt class="h-5 w-5" />
            <span class="font-semibold">Language</span>
            <span class="ml-auto">▼</span>
          </button>
          <ul class="pl-6 mt-2 flex flex-col gap-1">
            <li><a href="#" class="block px-3 py-2 rounded hover:bg-white">English</a></li>
            <li><a href="#" class="block px-3 py-2 rounded hover:bg-white">বাংলা (Bengali)</a></li>
            <li><a href="#" class="block px-3 py-2 rounded hover:bg-white">العربية (Arabic)</a></li>
          </ul>
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
  </nav>
</div>


<script>
document.addEventListener("DOMContentLoaded", () => {
  // --- Utility ---
  function closeAll() {
    document.querySelectorAll("[data-dropdown][aria-expanded='true']").forEach(btn => {
      toggleDropdown(btn, false);
    });
    if (mobileToggle?.getAttribute("aria-expanded") === "true") {
      toggleMobile(false);
    }
  }

  function toggleDropdown(btn, forceOpen) {
    const expanded = forceOpen ?? btn.getAttribute("aria-expanded") !== "true";
    const menu = btn.nextElementSibling;

    btn.setAttribute("aria-expanded", expanded);
    if (expanded) {
      menu.classList.remove("opacity-0", "scale-95", "pointer-events-none");
      menu.classList.add("opacity-100", "scale-100");
    } else {
      menu.classList.add("opacity-0", "scale-95", "pointer-events-none");
      menu.classList.remove("opacity-100", "scale-100");
    }
  }

  function toggleMobile(forceOpen) {
    const expanded = forceOpen ?? mobileToggle.getAttribute("aria-expanded") !== "true";
    mobileToggle.setAttribute("aria-expanded", expanded);
    if (expanded) {
      mobileMenu.classList.remove("-translate-x-full");
      mobileMenu.classList.add("translate-x-0");
    } else {
      mobileMenu.classList.add("-translate-x-full");
      mobileMenu.classList.remove("translate-x-0");
    }
  }

  // --- Desktop dropdowns (services, lang, notification) ---
  document.querySelectorAll("[data-dropdown]").forEach(btn => {
    const menu = btn.nextElementSibling;
    menu.classList.add(
      "absolute", "transition-all", "duration-200", "ease-out",
      "opacity-0", "scale-95", "pointer-events-none"
    );
    btn.setAttribute("aria-expanded", "false");

    btn.addEventListener("click", e => {
      e.preventDefault();
      const isOpen = btn.getAttribute("aria-expanded") === "true";
      closeAll();
      toggleDropdown(btn, !isOpen);
    });

    btn.addEventListener("keydown", e => {
      if (e.key === "Escape") {
        toggleDropdown(btn, false);
        btn.focus();
      }
    });
  });

  // --- Click outside to close ---
  document.addEventListener("click", e => {
    if (!e.target.closest("[data-dropdown]") && !e.target.closest("[data-menu]") && !e.target.closest("[data-notification]")) {
      closeAll();
    }
  });

  // --- Mobile menu ---
  const mobileMenu = document.getElementById("mobileMenu");
  const mobileToggle = document.querySelector('[aria-controls="mobileMenu"]');

  if (mobileMenu && mobileToggle) {
    mobileMenu.classList.add(
      "fixed", "top-0", "left-0", "h-full", "w-64",
      "transform", "-translate-x-full",
      "transition-transform", "duration-300", "ease-in-out",
      "z-40", "shadow-lg", "bg-white"
    );

    mobileToggle.addEventListener("click", () => toggleMobile());

    document.addEventListener("keydown", e => {
      if (e.key === "Escape" && mobileToggle.getAttribute("aria-expanded") === "true") {
        toggleMobile(false);
      }
    });
  }

  // --- Mobile collapsibles ---
  document.querySelectorAll("[data-collapsible]").forEach(btn => {
    const panel = btn.nextElementSibling;
    panel.style.maxHeight = "0px";
    panel.classList.add("overflow-hidden", "transition-all", "duration-300", "ease-out");

    btn.addEventListener("click", () => {
      const isOpen = btn.getAttribute("aria-expanded") === "true";
      btn.setAttribute("aria-expanded", String(!isOpen));
      if (isOpen) {
        panel.style.maxHeight = "0px";
      } else {
        panel.style.maxHeight = panel.scrollHeight + "px";
      }
    });
  });
});
</script>