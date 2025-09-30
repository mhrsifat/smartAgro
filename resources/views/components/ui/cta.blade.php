@props([
    'url' => '#',
    'icon' => 'icons.star',
    'text' => 'Set this in the parent',
    'cta' => 'Do this now'
])
<!-- CTA -->
<a
    class="flex items-center justify-between p-4 mb-8 text-sm font-semibold text-purple-100 bg-purple-600 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple"
    href="{{ $url }}"
    >
    <div class="flex items-center">
        <svg
            class="w-5 h-5 mr-2"
            fill="currentColor"
            viewBox="0 0 20 20"
            >
            <x-dynamic-component :component="$icon" />
        </svg>
        <span>{{ $text }}</span>
    </div>
    <span>{{ $cta }} &RightArrow;</span>
</a>
