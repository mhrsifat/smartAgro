@props([
    'url' => '#',
    'title' => 'Set a title in parent',
])

@php
    $active = request()->url() === url($url) || url()->current() === url($url);
@endphp

<li>
    <a
        class="block px-4 py-2 text-sm font-semibold rounded-lg transition-colors duration-150 hover:bg-gray-100 dark:hover:bg-gray-700
            {{ $active ? 'bg-gray-100 dark:bg-gray-700' : '' }}"
        href="{{ $url }}"
    >
        {{ $title }}
    </a>
</li>