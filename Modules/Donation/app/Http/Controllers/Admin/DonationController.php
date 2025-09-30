<?php

namespace Modules\Donation\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Donation\Models\Donation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class DonationController extends Controller
{
    /**
     * List all donations with search and filters
     */
    public function index(Request $request)
    {
        $query = Donation::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('donor_name', 'like', "%{$search}%")
                    ->orWhere('donor_email', 'like', "%{$search}%")
                    ->orWhere('donor_phone', 'like', "%{$search}%")
                    ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Payment gateway filter (fixed column name)
        if ($request->filled('payment_gateway')) {
            $query->where('payment_gateway', $request->payment_gateway);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Amount range filter
        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        $donations = $query->latest()->paginate(20)->withQueryString();

        // Get filter options for the view
        $statuses = ['pending', 'completed', 'failed', 'cancelled'];
        $paymentGateways = Donation::distinct()->pluck('payment_gateway')->filter();

        return view('donation::admin.index', compact('donations', 'statuses', 'paymentGateways'));
    }

    /**
     * Show the form for creating a new donation (manual entry)
     */
    public function create()
    {
        return view('donation::admin.create');
    }

    /**
     * Store a manually created donation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'required|email|max:255',
            'donor_phone' => 'nullable|string|max:20',
            'amount' => 'required|numeric|min:1',
            'currency' => 'nullable|string|max:10',
            'payment_gateway' => 'required|string|in:manual,sslcommerz,bkash,nagad,cash,bank_transfer,cheque,other',
            'status' => 'required|in:pending,completed,failed,cancelled',
            'message' => 'nullable|string|max:1000',
            'transaction_id' => 'nullable|string|max:100|unique:donations,transaction_id',
            'donated_at' => 'nullable|date',
        ]);

        // Set default values
        $validated['currency'] = $validated['currency'] ?? 'BDT';
        $validated['donated_at'] = $validated['donated_at'] ?? now();

        // Generate transaction ID if not provided
        if (empty($validated['transaction_id'])) {
            $validated['transaction_id'] = 'MAN-' . strtoupper(uniqid());
        }

        $donation = Donation::create($validated);

        return redirect()->route('admin.donations.show', $donation)
            ->with('success', 'Donation created successfully.');
    }

    /**
     * Show a specific donation
     */
    public function show(Donation $donation)
    {
        return view('donation::admin.show', compact('donation'));
    }

    /**
     * Update donation status with logging
     */
    public function updateStatus(Request $request, Donation $donation)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,cancelled',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $donation->status;

        $updateData = ['status' => $request->status];

        // Only add admin_notes if the column exists
        if ($request->filled('admin_notes') && \Schema::hasColumn('donations', 'admin_notes')) {
            $updateData['admin_notes'] = $request->admin_notes;
        }

        // Only add timestamp fields if they exist
        if (\Schema::hasColumn('donations', 'status_updated_at')) {
            $updateData['status_updated_at'] = now();
        }

        if (\Schema::hasColumn('donations', 'status_updated_by')) {
            $updateData['status_updated_by'] = auth()->id();
        }

        $donation->update($updateData);

        // Log the status change (only if activity log package is installed)
        if (class_exists('\Spatie\Activitylog\ActivitylogServiceProvider')) {
            activity()
                ->performedOn($donation)
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => $request->status,
                    'admin_notes' => $request->admin_notes,
                ])
                ->log('Donation status updated');
        }

        return back()->with('success', "Donation status updated from {$oldStatus} to {$request->status}.");
    }

    /**
     * Generate donations report
     */
    public function report(Request $request)
    {
        $request->validate([
            'period' => 'nullable|in:today,week,month,year,custom',
            'date_from' => 'nullable|date|required_if:period,custom',
            'date_to' => 'nullable|date|required_if:period,custom|after_or_equal:date_from',
        ]);

        $period = $request->get('period', 'month');

        // Set date range based on period
        switch ($period) {
            case 'today':
                $dateFrom = Carbon::today();
                $dateTo = Carbon::today()->endOfDay();
                break;
            case 'week':
                $dateFrom = Carbon::now()->startOfWeek();
                $dateTo = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $dateFrom = Carbon::now()->startOfMonth();
                $dateTo = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $dateFrom = Carbon::now()->startOfYear();
                $dateTo = Carbon::now()->endOfYear();
                break;
            case 'custom':
                $dateFrom = Carbon::parse($request->date_from);
                $dateTo = Carbon::parse($request->date_to)->endOfDay();
                break;
            default:
                $dateFrom = Carbon::now()->startOfMonth();
                $dateTo = Carbon::now()->endOfMonth();
        }

        // Get donations within date range
        $baseQuery = Donation::whereBetween('created_at', [$dateFrom, $dateTo]);

        // Summary statistics
        $totalDonations = $baseQuery->count();
        $totalAmount = $baseQuery->sum('amount');
        $completedDonations = $baseQuery->where('status', 'completed')->count();
        $completedAmount = $baseQuery->where('status', 'completed')->sum('amount');
        $pendingDonations = $baseQuery->where('status', 'pending')->count();
        $failedDonations = $baseQuery->where('status', 'failed')->count();

        // Group by status
        $statusBreakdown = Donation::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('status', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
            ->groupBy('status')
            ->get();

        // Group by payment gateway (fixed column name)
        $paymentGatewayBreakdown = Donation::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('payment_gateway', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
            ->groupBy('payment_gateway')
            ->get();

        // Daily breakdown for charts
        $dailyBreakdown = Donation::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count'),
                DB::raw('sum(amount) as total')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Top donors (handle null donor_name and donor_email)
        $topDonors = Donation::whereBetween('created_at', [$dateFrom, $dateTo])
            ->whereNotNull('donor_name')
            ->whereNotNull('donor_email')
            ->select('donor_name', 'donor_email', DB::raw('sum(amount) as total_donated'), DB::raw('count(*) as donation_count'))
            ->groupBy('donor_name', 'donor_email')
            ->orderBy('total_donated', 'desc')
            ->limit(10)
            ->get();

        $data = compact(
            'period',
            'dateFrom',
            'dateTo',
            'totalDonations',
            'totalAmount',
            'completedDonations',
            'completedAmount',
            'pendingDonations',
            'failedDonations',
            'statusBreakdown',
            'paymentGatewayBreakdown',
            'dailyBreakdown',
            'topDonors'
        );

        return view('donation::admin.report', $data);
    }

    /**
     * Export donations to CSV
     */
    public function export(Request $request)
    {
        $query = Donation::query();

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_gateway')) {
            $query->where('payment_gateway', $request->payment_gateway);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $donations = $query->latest()->get();

        $filename = 'donations_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($donations) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'ID',
                'Donor Name',
                'Email',
                'Phone',
                'Amount',
                'Currency',
                'Payment Gateway',
                'Status',
                'Transaction ID',
                'Message',
                'Created At'
            ]);

            // CSV data
            foreach ($donations as $donation) {
                fputcsv($file, [
                    $donation->id,
                    $donation->donor_name,
                    $donation->donor_email,
                    $donation->donor_phone,
                    $donation->amount,
                    $donation->currency,
                    $donation->payment_gateway,
                    $donation->status,
                    $donation->transaction_id,
                    $donation->message,
                    $donation->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk update donation statuses
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'donation_ids' => 'required|array',
            'donation_ids.*' => 'exists:donations,id',
            'bulk_action' => 'required|in:mark_completed,mark_pending,mark_failed,mark_cancelled',
        ]);

        $status = match ($request->bulk_action) {
            'mark_completed' => 'completed',
            'mark_pending' => 'pending',
            'mark_failed' => 'failed',
            'mark_cancelled' => 'cancelled',
        };

        $updateData = ['status' => $status];

        // Only add timestamp fields if they exist
        if (\Schema::hasColumn('donations', 'status_updated_at')) {
            $updateData['status_updated_at'] = now();
        }

        if (\Schema::hasColumn('donations', 'status_updated_by')) {
            $updateData['status_updated_by'] = auth()->id();
        }

        $updatedCount = Donation::whereIn('id', $request->donation_ids)
            ->update($updateData);

        return back()->with('success', "{$updatedCount} donations updated to {$status} status.");
    }
    public function downloadReportPdf(Request $request)
    {
        $request->validate([
            'period' => 'nullable|in:today,week,month,year,custom',
            'date_from' => 'nullable|date|required_if:period,custom',
            'date_to' => 'nullable|date|required_if:period,custom|after_or_equal:date_from',
        ]);

        // --- Determine date range ---
        $period = $request->get('period', 'month');
        $dateRanges = match ($period) {
            'today' => [Carbon::today(), Carbon::today()->endOfDay()],
            'week'  => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'month' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
            'year'  => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
            'custom' => [Carbon::parse($request->date_from), Carbon::parse($request->date_to)->endOfDay()],
            default => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
        };
        [$dateFrom, $dateTo] = $dateRanges;

        // --- Fetch donation data ---
        $baseQuery = \Modules\Donation\Models\Donation::whereBetween('created_at', [$dateFrom, $dateTo]);

        $totalDonations = $baseQuery->count();
        $totalAmount = $baseQuery->sum('amount');
        $completedDonations = (clone $baseQuery)->where('status', 'completed')->count();
        $completedAmount = (clone $baseQuery)->where('status', 'completed')->sum('amount');
        $pendingDonations = (clone $baseQuery)->where('status', 'pending')->count();
        $failedDonations = (clone $baseQuery)->where('status', 'failed')->count();

        $statusBreakdown = \Modules\Donation\Models\Donation::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('status', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
            ->groupBy('status')->get();

        $paymentGatewayBreakdown = \Modules\Donation\Models\Donation::whereBetween('created_at', [$dateFrom, $dateTo])
            ->select('payment_gateway', DB::raw('count(*) as count'), DB::raw('sum(amount) as total'))
            ->groupBy('payment_gateway')->get();

        $topDonors = \Modules\Donation\Models\Donation::whereBetween('created_at', [$dateFrom, $dateTo])
            ->whereNotNull('donor_name')
            ->whereNotNull('donor_email')
            ->select('donor_name', 'donor_email', DB::raw('sum(amount) as total_donated'), DB::raw('count(*) as donation_count'))
            ->groupBy('donor_name', 'donor_email')
            ->orderBy('total_donated', 'desc')
            ->limit(10)
            ->get();

        $data = compact(
            'period',
            'dateFrom',
            'dateTo',
            'totalDonations',
            'totalAmount',
            'completedDonations',
            'completedAmount',
            'pendingDonations',
            'failedDonations',
            'statusBreakdown',
            'paymentGatewayBreakdown',
            'topDonors'
        );

        // --- Load fonts from config ---
        $fonts = config('mpdf_fonts');
        $fontData = [];
        foreach ($fonts as $key => $paths) {
            $fontData[$key] = [
                'R' => basename($paths['R']),
                'B' => basename($paths['B']),
            ];
        }

        // --- Setup mPDF ---
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dankmono',
            'fontDir' => [storage_path('fonts')],
            'fontdata' => $fontData,
        ]);

        // --- Render view as HTML ---
        $html = view('donation::admin.report_pdf', $data)->render();

        // --- Write PDF ---
        $mpdf->WriteHTML($html);

        // --- Output PDF download ---
        return response($mpdf->Output('donations-report-' . now()->format('Y-m-d-H-i-s') . '.pdf', 'I'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="donations-report-' . now()->format('Y-m-d-H-i-s') . '.pdf"');
    }
}
