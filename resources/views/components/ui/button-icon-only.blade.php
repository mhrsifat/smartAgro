 @props([
    'url' => '#',
    'bgColor' => 'bg-purple-600',
    'bgHover' => 'bg-purple-700',
    'textColor' => 'text-white',
    'size' => 'regular',
    'disabled' => false,
    'icon' => 'icons.heart',
    'label' => 'label',
    'shape' => 'round',
    'shapes' => [
      'round' => 'rounded-full',
      'square' => '',
      'rounded-square' => 'rounded-lg',
    ],
    'sizes' => [
        'larger' => [
            'styling' => 'px-10 py-4',
        ],
        'large' => [
            'styling' => 'px-5 py-3',
        ],
        'regular' => [
            'styling' => 'px-2 py-2 text-sm',
        ],
        'small' => [
            'styling' => 'px-3 py-1 text-sm',
        ],
    ]
])
<button class="flex items-center justify-between {{ $sizes[$size]['styling'] }} font-medium leading-5 {{ $textColor }} transition-colors duration-150 {{ $bgColor }} border border-transparent {{ $shapes[$shape] }} active:{{ $bgColor }} hover:{{ $bgColor }} focus:outline-none focus:shadow-outline-purple" aria-label="{{ $label }}">
  <svg
    class="w-5 h-5"
    aria-hidden="true"
    fill="currentColor"
    viewBox="0 0 20 20"
    >
     <x-dynamic-component :component="$icon" />
  </svg>
</button>
