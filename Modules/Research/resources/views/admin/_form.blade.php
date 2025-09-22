
@if ($errors->any())
    <div class="p-4 mb-4 text-red-700 bg-red-100 rounded">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<x-forms.input label="Title" name="title" type="text"
    value="{{ old('title', $research->title ?? '') }}" />

<x-forms.textarea label="Description" name="description">
    {{ old('description', $research->description ?? '') }}
</x-forms.textarea>

<x-forms.input label="Slug" name="slug" type="text"
    value="{{ old('slug', $research->slug ?? '') }}" />

<x-forms.input label="Authors" name="authors" type="text"
    value="{{ old('authors', $research->authors ?? '') }}" />

<x-forms.select label="Status" name="status"
    :options="['draft' => 'Draft', 'under_review' => 'Under Review', 'published' => 'Published']"
    :selected="old('status', $research->status ?? 'draft')" />

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
    <input type="file" name="image" class="block w-full text-sm text-gray-700">
    @if(!empty($research->image))
        <img src="{{ asset($research->image) }}" alt="Image" class="mt-2 h-20">
    @endif
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Research Paper</label>
    <input type="file" name="paper" class="block w-full text-sm text-gray-700">
    @if(!empty($research->research_file))
        <a href="{{ asset($research->research_file) }}" target="_blank" class="text-indigo-600 underline">View Uploaded Paper</a>
    @endif
</div>

<div class="mb-4 flex items-center">
    <input type="checkbox" name="is_featured" value="1"
        @checked(old('is_featured', $research->is_featured ?? false))>
    <span class="ml-2">Featured</span>
</div>
