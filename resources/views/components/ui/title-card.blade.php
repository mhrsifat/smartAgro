@props([
	'title' => 'Set title in parent',
	'bgColor' => 'bg-white',
	'textColor' => 'text-gray-600',
])
<div class="min-w-0 p-4 {{ $bgColor }} {{ $textColor }} rounded-lg shadow-xs dark:bg-gray-800">
	<h4 class="mb-4 font-semibold {{ $textColor }} dark:text-gray-300">
		{{ $title }}
	</h4>
	<p class="{{ $textColor }} dark:text-gray-400">
		{{ $slot }}
	</p>
</div>
