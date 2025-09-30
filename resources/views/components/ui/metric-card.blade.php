@props([
    'bgColor' => 'bg-orange-100',
    'textColor' => 'text-orange-500',
    'icon' => 'icons.speech',
    'text' => 'Set this in the parent',
    'value' => '123456'
])
<!-- Card with Icon -->
<div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
    <div class="p-3 mr-4 {{ $textColor }} {{ $bgColor }} rounded-full">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <x-dynamic-component :component="$icon" />
        </svg>
    </div>
    <div>
        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
            {{ $text }}
        </p>
        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
            {{ $value }}
        </p>
    </div>
</div>
