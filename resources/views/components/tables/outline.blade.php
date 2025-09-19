@props([
    'pagination'
])
<div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
    <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
            {{ $slot }}
        </table>
    </div>
    @isset($pagination)
        <x-tables.pagination />
    @endisset
</div>
