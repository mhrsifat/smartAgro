<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Donation\Models\Donation; // adjust namespace if different
use Modules\Blog\Models\Blog;
use Modules\Research\Models\Research;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic counts and sums
        $donationsCount = Donation::count();
        $donationsSum = Donation::sum('amount');
        $donationsPending = Donation::where('status', 'pending')->count();
        $donationsLast30 = Donation::where('created_at', '>=', now()->subDays(30))->count();

        $blogsCount = Blog::count();
        $blogsDraft = Blog::where('status', 'draft')->count();

        $researchCount = Research::count();
        $researchRecentCount = Research::where('created_at', '>=', now()->subDays(30))->count();

        // Recent items
        $recentDonations = Donation::latest()->limit(10)->get();
        $recentBlogs = Blog::latest()->limit(5)->get();
        $recentResearch = Research::latest()->limit(5)->get();

        // Build donation trend (12 months)
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
            'recentDonations','recentBlogs','recentResearch',
            'donationTrend'
        ));
    }
}