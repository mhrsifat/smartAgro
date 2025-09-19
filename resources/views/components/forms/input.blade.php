@props([
'label' => 'F Name',
'name' => 'first_name',
'placeholder' => 'Al Elliott',
])
<label class="block text-sm">
	<span class="text-gray-700 dark:text-gray-400">{{ $label }}</span>
	<input
		name="{{ $name }}"
		class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-900 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
		placeholder="{{ $placeholder }}"
	/>
</label>
