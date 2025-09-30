@props([
    'editUrl' => '/',
    'deleteUrl' => '/',
])
<div class="flex items-center space-x-4 text-sm">
    <button
    class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
    aria-label="Edit"
    >
        <svg
            class="w-5 h-5"
            aria-hidden="true"
            fill="currentColor"
            viewBox="0 0 20 20"
            >
            <x-icons.edit />
        </svg>
    </button>
    <button
    class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
    aria-label="Delete"
    >
        <svg
            class="w-5 h-5"
            aria-hidden="true"
            fill="currentColor"
            viewBox="0 0 20 20"
            >
            <x-icons.delete />
        </svg>
    </button>
</div>
