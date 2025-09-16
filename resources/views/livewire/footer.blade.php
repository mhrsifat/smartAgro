   <!-- Footer -->
    <footer class="mt-12" aria-label="SmartAgro Footer" style="background-color: #F5F5F5; color: #212121;">
  <div class="max-w-7xl mx-auto px-6 py-8">
    <!-- Top row: logo (left) + social icons (right) -->
    <div class="flex flex-col md:flex-row items-center md:justify-between gap-6">
      <!-- Logo -->
      <div class="flex items-center gap-3">
        <!-- Simple SVG logo placeholder -->
        <a href="{{ url('/') }}" class="flex items-center gap-3" aria-label="SmartAgro home">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <rect width="24" height="24" rx="6" fill="#2E7D32"/>
            <path d="M6 14c1.5-3 4-5 7-5" stroke="#F5F5F5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="17" cy="8" r="1.6" fill="#FBC02D"/>
          </svg>
          <span class="font-semibold text-lg" style="color: #212121;">SmartAgro</span>
        </a>
      </div>

      <!-- Social icons -->
      <div class="flex items-center gap-3">
        <a href="#" class="p-2 rounded-md hover:bg-gray-100" aria-label="Twitter">
          <!-- Twitter -->
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M20 7.5c-.6.3-1.3.5-2 .6.7-.4 1.2-1.1 1.4-1.9-.7.4-1.5.8-2.3 1- .7-.8-1.8-1.3-2.9-1.3-2.2 0-3.9 1.8-3.9 3.9 0 .3 0 .6.1.8-3.2-.2-6.1-1.7-8-4.1-.3.6-.4 1.3-.4 2 0 1.4.7 2.6 1.8 3.3-.6 0-1.2-.2-1.7-.5v.1c0 1.9 1.4 3.5 3.2 3.9-.3.1-.6.1-.9.1-.2 0-.4 0-.6-.1.4 1.3 1.6 2.3 3 2.3-1.1.9-2.5 1.4-4 1.4-.3 0-.7 0-1-.1 1.5 1 3.2 1.5 5 1.5 6 0 9.4-5 9.4-9.3v-.4c.7-.5 1.3-1.1 1.7-1.9-.6.3-1.2.5-1.8.6z" fill="#1976D2"/>
          </svg>
        </a>

        <a href="#" class="p-2 rounded-md hover:bg-gray-100" aria-label="Facebook">
          <!-- Facebook -->
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M22 12.1C22 6.6 17.5 2 12 2S2 6.6 2 12.1c0 4.9 3.6 9 8.3 9.9v-7H8.1v-2.9h2.2V9.4c0-2.2 1.3-3.4 3.3-3.4.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2v1.5h2.3l-.4 2.9h-1.9v7C18.4 21.1 22 17 22 12.1z" fill="#2E7D32"/>
          </svg>
        </a>

        <a href="#" class="p-2 rounded-md hover:bg-gray-100" aria-label="LinkedIn">
          <!-- LinkedIn -->
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4.98 3.5C4.98 4.6 4.14 5.5 3 5.5S1 4.6 1 3.5 1.84 1.5 3 1.5s1.98.9 1.98 2zM.5 8.5h4.9V23H.5V8.5zM8.5 8.5h4.7v2h.1c.7-1.3 2.4-2.6 4.9-2.6 5.2 0 6.1 3.4 6.1 7.8V23h-4.9v-7.8c0-1.9 0-4.4-2.7-4.4-2.7 0-3.1 2.1-3.1 4.3V23H8.5V8.5z" fill="#1976D2"/>
          </svg>
        </a>
      </div>
    </div>

    <!-- Divider -->
    <div class="my-8 border-t" style="border-color: rgba(33,33,33,0.08)"></div>

    <!-- Bottom row: three columns -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Column 1: Category list -->
      <div>
        <h3 class="text-sm font-semibold mb-3" style="color: #2E7D32;">Categories</h3>
        <ul class="space-y-2 text-sm">
          <li><a href="#" class="hover:underline">Crop Advisory</a></li>
          <li><a href="#" class="hover:underline">Disease Diagnosis</a></li>
          <li><a href="#" class="hover:underline">Soil Recommendations</a></li>
          <li><a href="#" class="hover:underline">Market Prices</a></li>
        </ul>
      </div>

      <!-- Column 2: Questions / Advice + Contact button -->
      <div>
        <h3 class="text-sm font-semibold mb-3" style="color: #2E7D32;">Have any questions or advice for us?</h3>
        <p class="text-sm mb-4">We’re happy to help — reach out and we’ll get back to you quickly.</p>
        <a href="{{ route('contact') }}" class="inline-block px-4 py-2 rounded-md font-medium shadow-sm"
           style="background-color: #1976D2; color: #FFFFFF;">
          Contact Us
        </a>
      </div>

      <!-- Column 3: Blank placeholder -->
      <div>
        <!-- Intentionally left blank for future content -->
      </div>
    </div>

    <!-- Small print -->
    <div class="mt-8 text-xs text-gray-600">
      <p>&copy; {{ date('Y') }} SmartAgro. All rights reserved.</p>
    </div>
  </div>
</footer>