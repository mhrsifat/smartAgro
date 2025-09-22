@extends('layouts.master')

@section('title', 'Blogs - SmartAgro')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-green-700 mb-6 flex items-center gap-2">
        <x-heroicon-o-newspaper class="w-8 h-8 text-green-600" /> Blogs 📝
    </h1>

    <!-- Search Input -->
    <div class="mb-6">
        <x-bladewind::input 
            name="search" 
            id="searchInput" 
            placeholder="Search blogs..." 
            prefix="search" />
    </div>

    <!-- Blog List -->
    <div id="blogList" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($blogs as $blog)
            <x-bladewind::card class="hover:shadow-xl transition">
                @if($blog->image)
                    <img src="{{ asset('storage/' . $blog->image) }}" 
                         alt="{{ $blog->title }}" 
                         class="w-full h-40 object-cover rounded mb-3">
                @endif
                <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $blog->title }}</h2>
                <p class="text-gray-600 mb-4">{{ Str::limit($blog->excerpt, 120) }}</p>
                <a href="{{ route('blog.show', $blog->slug) }}" 
                   class="text-green-700 hover:underline font-medium flex items-center gap-1">
                   Read More <x-heroicon-o-arrow-right class="w-4 h-4"/>
                </a>
            </x-bladewind::card>
        @endforeach
    </div>

    <div class="mt-8" id="paginationLinks">
        {{ $blogs->links() }}
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const blogList = document.getElementById('blogList');
    const paginationLinks = document.getElementById('paginationLinks');

    searchInput.addEventListener('input', function() {
        const query = this.value;

        axios.get("{{ route('blog.index') }}", { params: { search: query } })
            .then(res => {
                blogList.innerHTML = '';
                if(res.data.data.length === 0){
                    blogList.innerHTML = '<p class="text-gray-500">No blogs found.</p>';
                    paginationLinks.innerHTML = '';
                    return;
                }

                res.data.data.forEach(blog => {
                    const card = document.createElement('div');
                    card.className = 'bg-white shadow-md rounded-lg p-5 hover:shadow-xl transition';
                    card.innerHTML = `
                        ${blog.image ? `<img src="/storage/${blog.image}" class="w-full h-40 object-cover rounded mb-3" />` : ''}
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">${blog.title}</h2>
                        <p class="text-gray-600 mb-4">${blog.excerpt.substring(0, 120)}</p>
                        <a href="/blogs/${blog.slug}" class="text-green-700 hover:underline font-medium flex items-center gap-1">
                            Read More →
                        </a>
                    `;
                    blogList.appendChild(card);
                });

                paginationLinks.innerHTML = '';
            })
            .catch(err => console.error(err));
    });
</script>
@endsection