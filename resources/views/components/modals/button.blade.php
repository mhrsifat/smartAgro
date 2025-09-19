@props([
    'text' => 'set this in parent',
    'bgColor' => 'bg-purple-600',
    'bgHover' => 'bg-purple-700',
    'textColor' => 'text-white'
])
<div class="pb-6">
    <button
        @click="openModal"
        class="px-4 py-2 text-sm font-medium leading-5 {{ $textColor }} transition-colors duration-150 {{ $bgColor }} border border-transparent rounded-lg active:bg-purple-600 hover:{{ $bgHover }} focus:outline-none focus:shadow-outline-purple"
    >
        {{ $text }}
    </button>
</div>
