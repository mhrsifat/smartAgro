@props([
    'url' => '#',
    'text' => 'set this in parent',
    'bgColor' => 'bg-purple-600',
    'bgHover' => 'bg-purple-700',
    'textColor' => 'text-white',
    'size' => 'regular',
    'disabled' => false,
    'fullWidth',
    'sizes' => [
        'larger' => [
            'styling' => 'px-10 py-4',
        ],
        'large' => [
            'styling' => 'px-5 py-3 font-medium',
        ],
        'regular' => [
            'styling' => 'px-4 py-2 text-sm',
        ],
        'small' => [
            'styling' => 'px-3 py-1 text-sm rounded-md',
        ],
    ]
])
<button class="rounded-lg {{ $sizes[$size]['styling'] }} font-medium leading-5 {{ $textColor }} transition-colors duration-150 {{ $bgColor }} border border-transparent
    {{ $disabled ? 'opacity-50 cursor-not-allowed' : 'active:bg-purple-600 hover:' . $textColor }} focus:outline-none focus:shadow-outline-purple {{ $fullWidth ?? 'w-full' }}">
{{ $text }}
</button>
