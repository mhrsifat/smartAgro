<x-forms.input label="Title" name="title" type="text"
    value="{{ old('title', $research->title ?? '') }}" />

<x-forms.textarea label="Description" name="description">
    {{ old('description', $research->description ?? '') }}
</x-forms.textarea>

<x-forms.input label="Slug" name="slug" type="text"
    value="{{ old('slug', $research->slug ?? '') }}" />

<x-forms.input label="Authors" name="authors" type="text"
    value="{{ old('authors', $research->authors ?? '') }}" />

<x-forms.input label="Download URL" name="download_url" type="url"
    value="{{ old('download_url', $research->download_url ?? '') }}" />

<x-forms.select label="Status" name="status"
    :options="['draft' => 'Draft', 'under_review' => 'Under Review', 'published' => 'Published']"
    :selected="old('status', $research->status ?? 'draft')" />

<x-forms.select label="Owner" name="user_id"
    :options="$users"
    :selected="old('user_id', $research->user_id ?? '')" />

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
    @if(!empty($research->paper))
        <a href="{{ asset($research->paper) }}" target="_blank" class="text-indigo-600 underline">View Uploaded Paper</a>
    @endif
</div>

<div class="mb-4 flex items-center">
    <input type="checkbox" name="is_featured" value="1"
        @checked(old('is_featured', $research->is_featured ?? false))>
    <span class="ml-2">Featured</span>
</div>
