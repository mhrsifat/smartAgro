@props([
    'label' => null,
    'name',
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
        </label>
    @endif

    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-textarea w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']) }}
    >{{ $slot }}</textarea>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div> 