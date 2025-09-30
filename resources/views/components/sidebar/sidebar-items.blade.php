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

  <x-sidebar.dropdown title="Research" icon="icons.research">
    <x-sidebar.dropdown-link
      title="Create new Research"
      url="{{ route('admin.researches.create') }}"
      />
    <x-sidebar.dropdown-link
      title="View Research"
      url="{{ route('admin.researches.index') }}"
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
      url="{{ route('admin.successstories.create') }}"
      />
    <x-sidebar.dropdown-link
      title="View Storie"
      url="{{ route('admin.successstories.index') }}"
      />
  </x-sidebar.dropdown>

  <x-sidebar.dropdown title="Donation" icon="icons.donation">
    <x-sidebar.dropdown-link
      title="Create new Donation"
      url="{{ route('admin.donations.create') }}"
      />
    <x-sidebar.dropdown-link
      title="View Donation"
      url="{{ route('admin.donations.index') }}"
      />
      <x-sidebar.dropdown-link
      title="Donation Report"
      url="{{ route('admin.donations.report') }}"
      />
  </x-sidebar.dropdown>

  <x-sidebar.link
    url="{{ route('admin.contacts.index') }}"
    title="Messages"
    icon="icons.messages"
    />

  <x-sidebar.link
    url="{{ route('admin.settings') }}"
    title="Settings"
    icon="icons.settings"
    />