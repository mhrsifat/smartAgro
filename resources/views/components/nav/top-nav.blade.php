@props([
    'search' => "hide",   //pass something like search="show" when calling component to show this
    'notifications'  => "hide",
    'profile',
])
<!-- Top Nav -->
<header class="z-10 py-4 bg-white shadow-md dark:bg-gray-800">
    <div class="container flex items-center justify-between h-full px-6 mx-auto text-purple-600 dark:text-purple-300">
        <x-nav.mobile-hamburger />
        @if($search != "hide" )
            <x-nav.search-input />
        @else
            <div class="flex justify-center flex-1 lg:mr-32"></div>
        @endif

        <ul class="flex items-center flex-shrink-0 space-x-6">
            @if($notifications != "hide" )
                <x-nav.notifications-menu />
            @endif
            @empty($profile)
                <x-nav.profile-menu />
            @endempty
        </ul>
    </div>
</header>
