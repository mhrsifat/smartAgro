@extends('layouts.master')

@section('title', 'Researches - SmartAgro')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-green-700 mb-6">Researches 📚</h1>

    <!-- Search Input -->
    <div class="mb-6">
        <input type="text" id="searchInput" placeholder="Search researches..."
               class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
    </div>

    <!-- Research List -->
    <div id="researchList" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($researches as $research)
            <div class="research-card bg-white shadow-md rounded-lg p-5 hover:shadow-xl transition">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $research->title }}</h2>
                <p class="text-gray-600 mb-4">{{ Str::limit($research->description, 100) }}</p>
                <a href="{{ route('research.show', $research->slug) }}" class="text-green-700 hover:underline font-medium">Read More →</a>
            </div>
        @endforeach
    </div>

    <div class="mt-8" id="paginationLinks">
        {{ $researches->links() }}
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const researchList = document.getElementById('researchList');
    const paginationLinks = document.getElementById('paginationLinks');

    searchInput.addEventListener('input', function() {
        const query = this.value;

        axios.get("{{ route('research.index') }}", { params: { search: query } })
            .then(res => {
                researchList.innerHTML = '';
                if(res.data.data.length === 0){
                    researchList.innerHTML = '<p class="text-gray-500">No researches found.</p>';
                    paginationLinks.innerHTML = '';
                    return;
                }

                res.data.data.forEach(research => {
                    const card = document.createElement('div');
                    card.className = 'research-card bg-white shadow-md rounded-lg p-5 hover:shadow-xl transition';
                    card.innerHTML = `
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">${research.title}</h2>
                        <p class="text-gray-600 mb-4">${research.description.substring(0, 100)}</p>
                        <a href="/researches/${research.id}" class="text-green-700 hover:underline font-medium">Read More →</a>
                    `;
                    researchList.appendChild(card);
                });

                paginationLinks.innerHTML = ''; // optional: update links via res.data.links
            })
            .catch(err => console.error(err));
    });
</script>
@endsection