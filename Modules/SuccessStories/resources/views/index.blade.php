@extends('layouts.master')

@section('title', 'Success Stories - SmartAgro')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-green-700 mb-6">Success Stories ⭐</h1>

    <!-- Search Input -->
    <div class="mb-6">
        <input type="text" id="searchInput" placeholder="Search success stories..."
               class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
    </div>

    <!-- Success Stories List -->
    <div id="storyList" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($successstories as $story)
            <div class="story-card bg-white shadow-md rounded-lg p-5 hover:shadow-xl transition">
                @if($story->image)
                    @if (Str::startsWith($story->image, 'http'))
                        <img src="{{ $story->image }}" alt="{{ $story->title }}" 
                         class="w-full h-48 object-cover rounded mb-4">
                    @else
                        <img src="{{ asset('storage/' . $story->image) }}" alt="{{ $story->title }}" 
                         class="w-full h-48 object-cover rounded mb-4">
                    @endif
                @endif

                <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $story->title }}</h2>
                <p class="text-gray-600 mb-4">{{ Str::limit($story->summary, 100) }}</p>
                <a href="{{ route('success-stories.show', $story->slug) }}" 
   class="text-green-700 hover:underline font-medium">Read More →</a>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8" id="paginationLinks">
        {{ $successstories->links() }}
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const storyList = document.getElementById('storyList');
    const paginationLinks = document.getElementById('paginationLinks');

    searchInput.addEventListener('input', function() {
        const query = this.value;

        axios.get("{{ route('success-stories.index') }}", { params: { search: query } })
            .then(res => {
                storyList.innerHTML = '';
                if(res.data.data.length === 0){
                    storyList.innerHTML = '<p class="text-gray-500">No success stories found.</p>';
                    paginationLinks.innerHTML = '';
                    return;
                }

                res.data.data.forEach(story => {
                    const card = document.createElement('div');
                    card.className = 'story-card bg-white shadow-md rounded-lg p-5 hover:shadow-xl transition';
                    card.innerHTML = `
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">${story.title}</h2>
                        <p class="text-gray-600 mb-4">${story.summary ? story.summary.substring(0, 100) : ''}</p>
                        <a href="/success-stories/${story.slug}" class="text-green-700 hover:underline font-medium">Read More →</a>
                    `;
                    storyList.appendChild(card);
                });

                paginationLinks.innerHTML = ''; 
            })
            .catch(err => console.error(err));
    });
</script>
@endsection