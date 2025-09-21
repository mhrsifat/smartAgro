@props([
    'type' => 'submit',
])

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring focus:ring-indigo-200 focus:ring-opacity-50'
    ]) }}
>
    {{ $slot }}
</button>
