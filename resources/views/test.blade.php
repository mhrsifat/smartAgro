<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SmartAgro</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

<header class="bg-white border-b border-gray-200">
  <div class="container mx-auto flex justify-between items-center px-4 py-3">
    <!-- Logo -->
    <a href="/" class="text-xl font-bold text-gray-900">SmartAgro</a>

    <!-- Desktop Actions -->
    <div class="hidden md:flex items-center gap-4">
      <form action="/search" method="GET" class="relative">
        <input type="text" name="q" placeholder="Search..." class="px-3 py-1 border rounded focus:outline-none" />
        <button type="submit" class="absolute right-0 top-0 mt-1 mr-1 px-2 bg-green-700 text-white rounded">Go</button>
      </form>
      <button type="button" class="text-gray-500" aria-label="Notifications">🔔</button>

      <!-- Language Selector (Desktop) -->
      <div class="relative">
        <button id="lang-btn-desktop" type="button" class="flex items-center gap-1" aria-expanded="false" aria-controls="lang-menu-desktop">
          EN
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <ul id="lang-menu-desktop" class="absolute right-0 mt-2 w-24 bg-white border rounded shadow-lg hidden">
          <li><a href="#" class="block px-3 py-1 hover:bg-gray-100">EN</a></li>
          <li><a href="#" class="block px-3 py-1 hover:bg-gray-100">BN</a></li>
        </ul>
      </div>

      <a href="/login" class="text-gray-700 hover:underline">Login</a>
    </div>

    <!-- Mobile Hamburger -->
    <button id="mobile-menu-btn" type="button" class="md:hidden text-gray-500 focus:outline-none"
            aria-label="Open main menu" aria-expanded="false" aria-controls="mobile-menu">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
  </div>

  <!-- Mobile Menu -->
  <nav id="mobile-menu" class="md:hidden hidden bg-gray-50 border-t border-gray-200 px-4 py-3" aria-label="Main navigation">
    <form action="/search" method="GET" class="flex gap-2 mb-3">
      <input type="text" name="q" placeholder="Search..." class="flex-1 border rounded px-2 py-1" />
      <button type="submit" class="bg-green-700 text-white px-3 rounded">Go</button>
    </form>

    <ul class="flex flex-col gap-2 text-gray-800">
      <li><a href="/">Home</a></li>
      <li><a href="/research">Research</a></li>
      <li>
        <button id="mobile-service-btn" type="button"
                class="w-full text-left flex items-center justify-between"
                aria-expanded="false" aria-controls="mobile-service-menu">
          Service
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform rotate-90"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <ul id="mobile-service-menu" class="pl-4 mt-1 flex flex-col gap-1 hidden">
          <li><a href="/crop-planner">Crop Planner</a></li>
          <li><a href="/pesticide">Pesticide</a></li>
          <li><a href="/fertilizer">Fertilizer</a></li>
          <li><a href="/disease">Disease</a></li>
        </ul>
      </li>
      <li><a href="/impact">Impact</a></li>
      <li><a href="/where-we-work">Where We Work</a></li>
      <li><a href="/our-team">Our Team</a></li>
      <li><a href="/career">Career</a></li>

      <!-- Mobile User Actions -->
      <li class="flex flex-col gap-2 mt-2 border-t pt-2">
        <a href="/login" class="text-gray-700 hover:underline">Login</a>
        <button type="button" class="text-gray-500 text-left" aria-label="Notifications">Notifications</button>
        <div class="relative">
          <button id="lang-btn-mobile" type="button" class="flex items-center gap-1 w-full text-left"
                  aria-expanded="false" aria-controls="lang-menu-mobile">
            EN
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <ul id="lang-menu-mobile" class="absolute left-0 mt-2 w-24 bg-white border rounded shadow-lg hidden">
            <li><a href="#" class="block px-3 py-1 hover:bg-gray-100">EN</a></li>
            <li><a href="#" class="block px-3 py-1 hover:bg-gray-100">BN</a></li>
          </ul>
        </div>
      </li>
    </ul>
  </nav>

  <!-- Desktop Category Menu -->
  <nav class="hidden md:block bg-gray-100" aria-label="Main navigation">
    <div class="container mx-auto px-4">
      <ul class="flex items-center gap-4 py-2 text-gray-800">
        <li><a href="/" class="hover:text-green-700">Home</a></li>
        <li><a href="/research" class="hover:text-green-700">Research</a></li>
        <li class="relative group">
          <button type="button" class="hover:text-green-700 inline-flex items-center">
            Service
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <ul class="absolute hidden group-hover:block bg-white border mt-1 shadow">
            <li><a href="/crop-planner" class="block px-4 py-2 hover:bg-gray-100">Crop Planner</a></li>
            <li><a href="/pesticide" class="block px-4 py-2 hover:bg-gray-100">Pesticide</a></li>
            <li><a href="/fertilizer" class="block px-4 py-2 hover:bg-gray-100">Fertilizer</a></li>
            <li><a href="/disease" class="block px-4 py-2 hover:bg-gray-100">Disease</a></li>
          </ul>
        </li>
        <li><a href="/impact" class="hover:text-green-700">Impact</a></li>
        <li><a href="/where-we-work" class="hover:text-green-700">Where We Work</a></li>
        <li><a href="/our-team" class="hover:text-green-700">Our Team</a></li>
        <li><a href="/career" class="hover:text-green-700">Career</a></li>
      </ul>
    </div>
  </nav>
</header>

<script>
  // Toggle Mobile Menu
  const mobileMenuBtn = document.getElementById('mobile-menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  mobileMenuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
    const expanded = mobileMenuBtn.getAttribute('aria-expanded') === 'true';
    mobileMenuBtn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
  });

  // Toggle Mobile Service Submenu
  const mobileServiceBtn = document.getElementById('mobile-service-btn');
  const mobileServiceMenu = document.getElementById('mobile-service-menu');
  mobileServiceBtn.addEventListener('click', () => {
    mobileServiceMenu.classList.toggle('hidden');
    const expanded = mobileServiceBtn.getAttribute('aria-expanded') === 'true';
    mobileServiceBtn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
  });

  // Toggle Desktop Language Dropdown
  const langBtnDesktop = document.getElementById('lang-btn-desktop');
  const langMenuDesktop = document.getElementById('lang-menu-desktop');
  langBtnDesktop.addEventListener('click', (e) => {
    e.stopPropagation();
    langMenuDesktop.classList.toggle('hidden');
    const expanded = langBtnDesktop.getAttribute('aria-expanded') === 'true';
    langBtnDesktop.setAttribute('aria-expanded', expanded ? 'false' : 'true');
  });

  // Toggle Mobile Language Dropdown
  const langBtnMobile = document.getElementById('lang-btn-mobile');
  const langMenuMobile = document.getElementById('lang-menu-mobile');
  langBtnMobile.addEventListener('click', (e) => {
    e.stopPropagation();
    langMenuMobile.classList.toggle('hidden');
    const expanded = langBtnMobile.getAttribute('aria-expanded') === 'true';
    langBtnMobile.setAttribute('aria-expanded', expanded ? 'false' : 'true');
  });

  // Close menus if clicked outside
  document.addEventListener('click', (e) => {
    if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
      mobileMenu.classList.add('hidden');
      mobileMenuBtn.setAttribute('aria-expanded', 'false');
    }
    if (!mobileServiceMenu.contains(e.target) && !mobileServiceBtn.contains(e.target)) {
      mobileServiceMenu.classList.add('hidden');
      mobileServiceBtn.setAttribute('aria-expanded', 'false');
    }
    if (!langMenuDesktop.contains(e.target) && !langBtnDesktop.contains(e.target)) {
      langMenuDesktop.classList.add('hidden');
      langBtnDesktop.setAttribute('aria-expanded', 'false');
    }
    if (!langMenuMobile.contains(e.target) && !langBtnMobile.contains(e.target)) {
      langMenuMobile.classList.add('hidden');
      langBtnMobile.setAttribute('aria-expanded', 'false');
    }
  });
</script>

</body>
</html>