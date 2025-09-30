<div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
    <!-- Icon -->
    <div class="p-3 mr-4 rounded-full"
         style="background-color: rgba(var(--tw-color-{{ $color }}, 59,130,246),0.1); color: var(--tw-color-{{ $color }}, #3B82F6)">
        @if ($icon === 'donation')
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5..."/>
            </svg>
        @elseif ($icon === 'blog')
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M4 4h16v2H4zm0 6h16v2H4zm0 6h10v2H4z"/>
            </svg>
        @elseif ($icon === 'research')
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 2v2H7v2h2v2h2V6h2V4h-2V2zM5 20h14v2H5z"/>
            </svg>
        @elseif ($icon === 'success')
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l2.09 6.26H20l-5.45 3.96L16.18 18 12 14.77..."/>
            </svg>
        @else
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
            </svg>
        @endif
    </div>

    <!-- Text -->
    <div>
        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">{{ $title }}</p>
        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $count }}</p>
        @if ($extra)
            <p class="text-xs text-gray-500">{{ $extra }}</p>
        @endif
    </div>
</div>