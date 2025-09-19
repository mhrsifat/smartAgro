<ul class="mt-6">
    <x-sidebar.link 
        url="{{ url('/') }}" 
        title="Visit Website" 
        icon="icons.visit-website"
    />

    <x-sidebar.link 
        url="{{ route('admin.dashboard') }}" 
        title="Dashboard" 
        icon="icons.dashboard"
    />

    <x-sidebar.link 
        url="{{ route('admin.forms') }}" 
        title="Forms" 
        icon="icons.clipboard"
    />

    <x-sidebar.link 
        url="{{ route('admin.cards') }}" 
        title="Cards" 
        icon="icons.files" 
    />

    <x-sidebar.link 
        url="{{ route('admin.charts') }}" 
        title="Charts" 
        icon="icons.chart" 
    />

    <x-sidebar.link 
        url="{{ route('admin.buttons') }}" 
        title="Buttons" 
        icon="icons.pointer" 
    />

    <x-sidebar.link 
        url="{{ route('admin.modals') }}" 
        title="Modals" 
        icon="icons.squares" 
    />

    <x-sidebar.link 
        url="{{ route('admin.tables') }}" 
        title="Tables" 
        icon="icons.lines" 
    />
</ul>

<x-sidebar.dropdown title="Dropdown" icon="icons.pointer">
    <x-sidebar.dropdown-link 
        title="Create account" 
        url="{{ route('admin.create.account') }}" 
        />
    <x-sidebar.dropdown-link 
        title="Forgot password" 
        url="{{ route('admin.forgot.password') }}" 
        />
    <x-sidebar.dropdown-link 
        title="404" 
        url="{{ route('admin.error.404') }}" 
    />
    <x-sidebar.dropdown-link 
        title="Login" 
        url="{{ route('admin.login') }}" 
    />
    <x-sidebar.dropdown-link 
        title="Blank Page" 
        url="{{ route('admin.blank') }}" 
    />
</x-sidebar.dropdown>
<x-sidebar.button />