<x-app-layout title="Edit Research">

    <x-headings.page-title title="Edit Research" />
    <x-headings.section-title title="Update research details" />

    @include('research::admin._form', ['research' => $research, 'users' => $users])
</x-app-layout>