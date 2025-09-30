<x-app-layout title="Admin Dashboard">
  
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">Dashboard</h2>

    <!-- Stats Cards -->
    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">

        <!-- Donations -->
        <x-dashboard.stat-card 
            title="Donations" 
            :count="$donationsCount" 
            :extra="'$'.number_format($donationsSum, 2)" 
            icon="donation" 
            color="green" />

        <!-- Blogs -->
        <x-dashboard.stat-card 
            title="Blogs" 
            :count="$blogsCount" 
            :extra="'Drafts: '.$blogsDraft" 
            icon="blog" 
            color="blue" />

        <!-- Research -->
        <x-dashboard.stat-card 
            title="Research" 
            :count="$researchCount" 
            :extra="'Recent: '.$researchRecentCount" 
            icon="research" 
            color="brown" />

        <!-- Success Stories -->
        <x-dashboard.stat-card 
            title="Success Stories" 
            :count="$successCount" 
            :extra="'Recent: '.$successRecentCount.' · Drafts: '.$successDraftCount" 
            icon="success" 
            color="yellow" />
    </div>

    <!-- Charts -->
    <div class="grid gap-6 mb-8 md:grid-cols-2">
        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs h-72">
            <h4 class="mb-4 font-semibold text-gray-800">Donations Trend</h4>
            <canvas id="donationChart" class="w-full h-full"></canvas>
        </div>
        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs h-72">
            <h4 class="mb-4 font-semibold text-gray-800">Content Distribution</h4>
            <canvas id="contentPie" class="w-full h-full"></canvas>
        </div>
    </div>

    <!-- Recent Items -->
    <div class="grid gap-6 mb-8 lg:grid-cols-2">
        <div class="p-4 bg-white rounded-lg shadow-xs">
            <h4 class="mb-4 font-semibold text-gray-800">Recent Donations</h4>
            <ul class="space-y-2">
                @forelse($recentDonations as $d)
                    <li class="text-sm">
                        {{ $d->donor_name ?? 'Anonymous' }} – 
                        <span class="font-medium">${{ number_format($d->amount, 2) }}</span>
                        <span class="text-xs text-gray-500">({{ $d->created_at->format('Y-m-d') }})</span>
                    </li>
                @empty
                    <li class="text-sm text-gray-500">No donations yet.</li>
                @endforelse
            </ul>
        </div>
        <div class="p-4 bg-white rounded-lg shadow-xs">
            <h4 class="mb-4 font-semibold text-gray-800">Latest Success Stories</h4>
            <ul class="space-y-2">
                @forelse($recentSuccess as $s)
                    <li class="text-sm">
                        <a href="{{ route('admin.successstories.index', $s->id) }}" class="font-medium text-blue-600">
                            {{ Str::limit($s->title, 50) }}
                        </a>
                        <span class="text-xs text-gray-500">{{ $s->created_at->format('Y-m-d') }}</span>
                    </li>
                @empty
                    <li class="text-sm text-gray-500">No success stories yet.</li>
                @endforelse
            </ul>
        </div>
        <div class="grid gap-6 mb-8 lg:grid-cols-2">
    <!-- Latest Blogs -->
    <div class="p-4 bg-white rounded-lg shadow-xs">
        <h4 class="mb-4 font-semibold text-gray-800">Latest Blogs</h4>
        <ul class="space-y-2">
            @forelse($recentBlogs as $b)
                <li class="text-sm">
                    <a href="{{ route('admin.blogs.edit', $b->id) }}" class="font-medium text-blue-600">
                        {{ Str::limit($b->title, 50) }}
                    </a>
                    <span class="text-xs text-gray-500">{{ $b->created_at->format('Y-m-d') }}</span>
                </li>
            @empty
                <li class="text-sm text-gray-500">No blogs yet.</li>
            @endforelse
        </ul>
    </div>

    <!-- Latest Research -->
    <div class="p-4 bg-white rounded-lg shadow-xs">
        <h4 class="mb-4 font-semibold text-gray-800">Latest Research</h4>
        <ul class="space-y-2">
            @forelse($recentResearch as $r)
                <li class="text-sm">
                    <a href="{{ route('admin.researches.edit', $r->slug) }}" class="font-medium text-blue-600">
                        {{ Str::limit($r->title, 50) }}
                    </a>
                    <span class="text-xs text-gray-500">{{ $r->created_at->format('Y-m-d') }}</span>
                </li>
            @empty
                <li class="text-sm text-gray-500">No research yet.</li>
            @endforelse
        </ul>
    </div>
</div>
    </div>
</div>
</x-app-layout>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const donationCtx = document.getElementById('donationChart').getContext('2d');
new Chart(donationCtx, {
    type: 'line',
    data: {
        labels: @json($donationTrend['labels']),
        datasets: [{
            label: 'Donation Amount',
            data: @json($donationTrend['values']),
            borderColor: '#2E7D32',
            backgroundColor: 'rgba(46,125,50,0.1)',
            fill: true,
            tension: 0.3
        }]
    },
    options: { 
        responsive: true, 
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    }
});

// ✅ Content Pie (with fixed height)
const contentCtx = document.getElementById('contentPie').getContext('2d');
new Chart(contentCtx, {
    type: 'pie',
    data: {
        labels: ['Blogs','Research','Success Stories','Donations'],
        datasets: [{
            data: [
                {{ $blogsCount ?? 0 }},
                {{ $researchCount ?? 0 }},
                {{ $successCount ?? 0 }},
                {{ $donationsCount ?? 0 }}
            ],
            backgroundColor: ['#1976D2','#8D6E63','#FBC02D','#2E7D32'],
        }]
    },
    options: { 
        responsive: true, 
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>