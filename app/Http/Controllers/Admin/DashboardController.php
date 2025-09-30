<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Donation\Models\Donation;
use Modules\Blog\Models\Blog;
use Modules\Research\Models\Research;
use Modules\SuccessStories\Models\SuccessStory;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Donations ---
        $donationsCount   = Donation::count();
        $donationsSum     = Donation::sum('amount');
        $donationsPending = Donation::where('status', 'pending')->count();
        $donationsLast30  = Donation::where('created_at', '>=', now()->subDays(30))->count();

        // --- Blogs ---
        $blogsCount = Blog::count();
        $blogsDraft = Blog::where('status', 'draft')->count();

        // --- Research ---
        $researchCount       = Research::count();
        $researchRecentCount = Research::where('created_at', '>=', now()->subDays(30))->count();

        // --- Success Stories ---
        $successCount       = SuccessStory::count();
        $successRecentCount = SuccessStory::where('created_at', '>=', now()->subDays(30))->count();
        $successDraftCount  = SuccessStory::where('status', 'draft')->count();

        // --- Recent Items ---
        $recentDonations = Donation::latest()->limit(10)->get();
        $recentBlogs     = Blog::latest()->limit(5)->get();
        $recentResearch  = Research::latest()->limit(5)->get();
        $recentSuccess   = SuccessStory::latest()->limit(5)->get();

        // --- Donation Trend (12 months) ---
        $labels = [];
        $values = [];
        for ($i = 11; $i >= 0; $i--) {
            $dt = Carbon::now()->subMonths($i);
            $labels[] = $dt->format('M Y');
            $values[] = Donation::whereYear('created_at', $dt->year)
                        ->whereMonth('created_at', $dt->month)
                        ->sum('amount');
        }
        $donationTrend = ['labels' => $labels, 'values' => $values];

        return view('admin.dashboard', compact(
            'donationsCount','donationsSum','donationsPending','donationsLast30',
            'blogsCount','blogsDraft',
            'researchCount','researchRecentCount',
            'successCount','successRecentCount','successDraftCount',
            'recentDonations','recentBlogs','recentResearch','recentSuccess',
            'donationTrend'
        ));
    }
}