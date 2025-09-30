@props([
    'title' => 'Set a title in the parent view',
])
<!-- Page title -->
<div class="my-6 text-gray-700 dark:text-gray-200">
    <h2 class="text-2xl font-semibold">
    {{ $title }}
    </h2>
    @isset($slot)
        <p>{{ $slot }}</p>
    @endisset
</div>
