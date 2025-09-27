<x-app-layout title="Admin Dashboard">
    <x-headings.page-title title="Admin Dashboard" />
    <x-headings.section-title title="Overview: Donations · Blogs · Research" />

    @php
        // simple CSS variables for SmartAgro palette
    @endphp

    <style>
        :root{
            --sa-primary: #2E7D32; /* green */
            --sa-secondary: #1976D2; /* blue */
            --sa-accent: #FBC02D; /* yellow */
            --sa-neutral: #8D6E63;
            --sa-bg: #F5F5F5;
            --sa-text: #212121;
        }
    </style>

    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
        <!-- Donations -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
            <div class="p-3 mr-4 rounded-full" style="background:rgba(46,125,50,0.08); color:var(--sa-primary)">
                <!-- inline svg -->
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L4 7v7c0 5 4 9 8 9s8-4 8-9V7l-8-5z"/></svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Donations (total)</p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    {{ number_format($donationsCount ?? 0) }}
                </p>
                <p class="text-xs text-gray-500">Total amount: <strong>${{ number_format($donationsSum ?? 0, 2) }}</strong></p>
            </div>
        </div>

        <!-- Pending / Recent -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
            <div class="p-3 mr-4 rounded-full" style="background:rgba(247, 191, 36, 0.08); color:var(--sa-accent)">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2v10l6 4"/></svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Pending Donations</p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $donationsPending ?? 0 }}</p>
                <p class="text-xs text-gray-500">Last 30 days: {{ $donationsLast30 ?? 0 }}</p>
            </div>
        </div>

        <!-- Blogs -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
            <div class="p-3 mr-4 rounded-full" style="background:rgba(25,118,210,0.08); color:var(--sa-secondary)">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6h16v12H4z"/></svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Blog posts</p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $blogsCount ?? 0 }}</p>
                <p class="text-xs text-gray-500">Drafts: {{ $blogsDraft ?? 0 }}</p>
            </div>
        </div>

        <!-- Research -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
            <div class="p-3 mr-4 rounded-full" style="background:rgba(141,110,99,0.08); color:var(--sa-neutral)">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a7 7 0 017 7v3"/></svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Research items</p>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $researchCount ?? 0 }}</p>
                <p class="text-xs text-gray-500">Recent: {{ $researchRecentCount ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid gap-6 mb-8 md:grid-cols-2">
        <div class="p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
            <h3 class="mb-2 text-sm font-semibold text-gray-600">Donations trend (last 12 months)</h3>
            <canvas id="donationTrendChart" height="150"></canvas>
        </div>

        <div class="p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
            <h3 class="mb-2 text-sm font-semibold text-gray-600">Content breakdown</h3>
            <canvas id="contentPieChart" height="150"></canvas>
        </div>
    </div>

    <!-- Latest tables -->
    <div class="grid gap-6 mb-8 md:grid-cols-3">
        <!-- Recent Donations -->
        <div class="col-span-2 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
            <h3 class="mb-4 text-sm font-semibold text-gray-600">Recent donations</h3>
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap text-sm">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                            <th class="px-4 py-2">Donor</th>
                            <th class="px-4 py-2">Amount</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentDonations as $d)
                            <tr class="text-gray-700">
                                <td class="px-4 py-2">{{ $d->name ?? $d->donor_name ?? '—' }}</td>
                                <td class="px-4 py-2">${{ number_format($d->amount,2) }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $d->status === 'approved' ? 'bg-green-100 text-green-700' : ($d->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ ucfirst($d->status ?? 'unknown') }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">{{ $d->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr><td class="p-4 text-sm text-gray-500" colspan="4">No donations yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Latest blog & research summary -->
        <div class="p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
            <h3 class="mb-4 text-sm font-semibold text-gray-600">Latest blog posts</h3>
            <ul class="space-y-3">
                @forelse($recentBlogs as $b)
                    <li class="text-sm">
                        <a href="{{ route('blog.show', $b->id ?? $b->slug) }}" class="font-medium text-gray-700">{{ Str::limit($b->title, 60) }}</a>
                        <div class="text-xs text-gray-500">{{ $b->created_at->format('Y-m-d') }}</div>
                    </li>
                @empty
                    <li class="text-sm text-gray-500">No articles found.</li>
                @endforelse
            </ul>

            <hr class="my-4" />

            <h3 class="mb-4 text-sm font-semibold text-gray-600">Recent research</h3>
            <ul class="space-y-3 text-sm">
                @forelse($recentResearch as $r)
                    <li>
                        <a href="{{ route('research.show', $r->id ?? $r->slug) }}" class="font-medium text-gray-700">{{ Str::limit($r->title, 60) }}</a>
                        <div class="text-xs text-gray-500">{{ $r->created_at->format('Y-m-d') }}</div>
                    </li>
                @empty
                    <li class="text-sm text-gray-500">No research items.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Donations trend
        const donationLabels = @json($donationTrend['labels'] ?? []);
        const donationValues = @json($donationTrend['values'] ?? []);

        new Chart(document.getElementById('donationTrendChart'), {
            type: 'line',
            data: {
                labels: donationLabels,
                datasets: [{
                    label: 'Donation amount',
                    data: donationValues,
                    fill: true,
                    borderColor: getComputedStyle(document.documentElement).getPropertyValue('--sa-primary') || '#2E7D32',
                    backgroundColor: 'rgba(46,125,50,0.08)',
                    tension: 0.3
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        // Content pie
        const contentLabels = ['Blogs', 'Research', 'Other'];
        const contentData = [
            {{ $blogsCount ?? 0 }}, {{ $researchCount ?? 0 }}, {{ max(0, ($donationsCount ?? 0) - 0) }}
        ];

        new Chart(document.getElementById('contentPieChart'), {
            type: 'pie',
            data: {
                labels: contentLabels,
                datasets: [{
                    data: contentData,
                    backgroundColor: [
                        getComputedStyle(document.documentElement).getPropertyValue('--sa-secondary') || '#1976D2',
                        getComputedStyle(document.documentElement).getPropertyValue('--sa-neutral') || '#8D6E63',
                        getComputedStyle(document.documentElement).getPropertyValue('--sa-accent') || '#FBC02D'
                    ]
                }]
            },
            options: { maintainAspectRatio: false }
        });
    </script>
</x-app-layout>