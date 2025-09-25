<?php

namespace Modules\Donation\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Donation\Models\Donation;

class DonationController extends Controller
{
    /**
     * List all donations
     */
    public function index()
    {
        $donations = Donation::latest()->paginate(20);
        return view('donation::admin.index', compact('donations'));
    }

    /**
     * Show a donation
     */
    public function show(Donation $donation)
    {
        return view('donation::admin.show', compact('donation'));
    }

    /**
     * Update donation status
     */
    public function updateStatus(Request $request, Donation $donation)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,cancelled',
        ]);

        $donation->update(['status' => $request->status]);

        return back()->with('success', 'Donation status updated.');
    }
}