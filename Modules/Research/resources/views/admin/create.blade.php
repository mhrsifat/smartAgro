<x-app-layout title="Create Research">
    <x-headings.page-title title="Create Research" />
    <x-headings.section-title title="Add a new research" />

    <form method="POST" action="{{ route('admin.researches.store') }}" enctype="multipart/form-data">
        @csrf
        @include('research::admin._form')
        <x-forms.button type="submit">Save</x-forms.button>
    </form>
</x-app-layout>
