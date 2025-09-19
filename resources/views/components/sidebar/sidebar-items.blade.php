<ul class="mt-6">
  <x-sidebar.link
    url="{{ url('/') }}"
    title="Visit Website"
    icon="icons.visit-website"
    />  <x-sidebar.link
    url="{{ route('admin.dashboard') }}"
    title="Dashboard"
    icon="icons.dashboard"
    />

  <x-sidebar.dropdown title="Research" icon="icons.research">
    <x-sidebar.dropdown-link
      title="Create new Research"
      url="{{ route('admin.create.account') }}"
      />
    <x-sidebar.dropdown-link
      title="View Research"
      url="{{ route('admin.forgot.password') }}"
      />
    <x-sidebar.dropdown-link
      title="View Publish Request"
      url="{{ route('admin.error.404') }}"
      />
  </x-sidebar.dropdown>

  <x-sidebar.dropdown title="Blog" icon="icons.blog">
    <x-sidebar.dropdown-link
      title="Create new Blog"
      url="{{ route('admin.blogs.create') }}"
      />
    <x-sidebar.dropdown-link
      title="View Blog"
      url="{{ route('admin.blogs.index') }}"
      />
  </x-sidebar.dropdown>

  <x-sidebar.dropdown title="Success Stories" icon="icons.success">
    <x-sidebar.dropdown-link
      title="Create new Storie"
      url="{{ route('admin.create.account') }}"
      />
    <x-sidebar.dropdown-link
      title="View Storie"
      url="{{ route('admin.forgot.password') }}"
      />
  </x-sidebar.dropdown>

  <x-sidebar.dropdown title="Transaction" icon="icons.transaction">
    <x-sidebar.dropdown-link
      title="Create new Transaction"
      url="{{ route('admin.create.account') }}"
      />
    <x-sidebar.dropdown-link
      title="Transaction History"
      url="{{ route('admin.forgot.password') }}"
      />

    <x-sidebar.dropdown-link
      title="Track Transaction"
      url="{{ route('admin.forgot.password') }}"
      />

  </x-sidebar.dropdown>

  <x-sidebar.dropdown title="Donation" icon="icons.donation">
    <x-sidebar.dropdown-link
      title="Create new Donation"
      url="{{ route('admin.create.account') }}"
      />
    <x-sidebar.dropdown-link
      title="View Donation"
      url="{{ route('admin.forgot.password') }}"
      />
  </x-sidebar.dropdown>

  <x-sidebar.link
    url="{{ route('admin.forms') }}"
    title="Messages"
    icon="icons.messages"
    />

  <x-sidebar.link
    url="{{ route('admin.forms') }}"
    title="Corn Job"
    icon="icons.corn"
    />

  <x-sidebar.link
    url="{{ route('admin.forms') }}"
    title="Settings"
    icon="icons.settings"
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