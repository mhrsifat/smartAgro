@props(['title', 'icon'])

<li x-data="{ open: false }" class="relative px-6 py-3">
    <!-- Dropdown button -->
    <button
        @click="open = !open"
        class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
        aria-haspopup="true"
    >
        <svg
            class="w-5 h-5"
            aria-hidden="true"
            fill="none"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            viewBox="0 0 24 24"
            stroke="currentColor"
        >
            <x-dynamic-component :component="$icon" />
        </svg>
        <span class="ml-4">{{ $title }}</span>
        <svg
            :class="{'rotate-180': open}"
            class="w-4 h-4 ml-auto transition-transform duration-200"
            aria-hidden="true"
            fill="currentColor"
            viewBox="0 0 20 20"
        >
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
    </button>

    <!-- Dropdown menu -->
    <ul
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition-all ease-in-out duration-300"
        x-transition:enter-start="opacity-0 max-h-0"
        x-transition:enter-end="opacity-100 max-h-xl"
        x-transition:leave="transition-all ease-in-out duration-300"
        x-transition:leave-start="opacity-100 max-h-xl"
        x-transition:leave-end="opacity-0 max-h-0"
        class="mt-2 ml-6 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md dark:text-gray-400"
    >
        <!-- Each child link should close menu on click -->
        {{ $slot }}
    </ul>
</li>