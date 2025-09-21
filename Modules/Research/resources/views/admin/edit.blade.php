<x-app-layout title="Edit Research">
    <x-headings.page-title title="Edit Research" />
    <x-headings.section-title title="Update research" />

    <form method="POST" action="{{ route('admin.researches.update', $research->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('research::admin._form')
        <x-forms.button type="submit">Update</x-forms.button>
    </form>
</x-app-layout>
