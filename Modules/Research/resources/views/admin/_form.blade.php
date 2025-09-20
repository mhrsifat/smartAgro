@php $isEdit = isset($research); @endphp

<form action="{{ $isEdit ? route('admin.researches.update', $research->id) : route('admin.researches.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <x-forms.input label="Title" name="title" :value="old('title', $research->title ?? '')" />
    <x-forms.input label="Slug" name="slug" :value="old('slug', $research->slug ?? '')" />
    <x-forms.textarea label="Description" name="description">{{ old('description', $research->description ?? '') }}</x-forms.textarea>
    
    <div>
        <label class="block text-sm font-medium mb-1">Image</label>
        <input type="file" name="image" class="w-full border rounded p-2" />
        @if($isEdit && $research->image)
            <img src="{{ asset($research->image) }}" class="w-32 h-20 mt-2 object-cover rounded" />
        @endif
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Authors</label>
        <div class="flex gap-2 mb-2">
            <input id="author_input" class="flex-1 border rounded p-2" placeholder="Add author" />
            <button type="button" id="add_author_btn" class="px-3 py-2 border rounded">Add</button>
        </div>
        <div id="authors_list" class="flex flex-wrap gap-2">
            @php
                $oldAuthors = old('authors', isset($research) ? (is_string($research->authors) ? json_decode($research->authors, true) : $research->authors) : []);
            @endphp
            @foreach((array)$oldAuthors as $a)
                <span class="px-2 py-1 border rounded flex items-center gap-2">
                    <span class="author-name">{{ $a }}</span>
                    <button type="button" class="remove-author text-red-500">&times;</button>
                    <input type="hidden" name="authors[]" value="{{ $a }}">
                </span>
            @endforeach
        </div>
    </div>

    <x-forms.select label="Status" name="status" :options="['draft'=>'Draft','under_review'=>'Under Review','published'=>'Published']" :selected="old('status', $research->status ?? 'draft')" />
    
    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $research->is_featured ?? false) ? 'checked' : '' }} />
        <span class="text-sm">Featured</span>
    </div>

    <x-forms.input label="Download URL" name="download_url" :value="old('download_url', $research->download_url ?? '')" />
    
    <x-forms.select label="Owner" name="user_id" :options="$users" :selected="old('user_id', $research->user_id ?? '')" />

    <div class="flex gap-2">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">{{ $isEdit ? 'Update' : 'Create' }}</button>
        <a href="{{ route('admin.researches.index') }}" class="px-4 py-2 border rounded">Cancel</a>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const addBtn = document.getElementById('add_author_btn');
    const input = document.getElementById('author_input');
    const list = document.getElementById('authors_list');

    addBtn.addEventListener('click', () => {
        const val = input.value.trim();
        if (!val) return;
        const span = document.createElement('span');
        span.className = 'px-2 py-1 border rounded flex items-center gap-2';
        span.innerHTML = `<span class="author-name">${val}</span><button type="button" class="remove-author text-red-500">&times;</button><input type="hidden" name="authors[]" value="${val}">`;
        list.appendChild(span);
        input.value = '';
    });

    list.addEventListener('click', e => {
        if (e.target.classList.contains('remove-author')) e.target.closest('span').remove();
    });
});
</script>
@endpush