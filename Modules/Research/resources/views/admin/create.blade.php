<x-app-layout title="Create Research">

    <x-headings.page-title title="Create Research" />
    <x-headings.section-title title="Add a new research" />

    @include('research::admin._form', ['users' => $users])
</x-app-layout>