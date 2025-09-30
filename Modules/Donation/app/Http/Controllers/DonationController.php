<?php

namespace Modules\Donation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Modules\Donation\Models\Donation;
use Modules\Donation\Library\SslCommerz\SslCommerzNotification;

class DonationController extends Controller
{
    /**
     * Show donation form
     */
     
    public function index()
    {
        return view('donation::index' );
    }
    
    public function create()
    {
        $user = Auth::user();
        return view('donation::form', compact('user'));
    }

    /**
     * Store donation and handle payment
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'donor_name'   => 'nullable|string|max:255',
            'donor_email'  => 'nullable|email|max:255',
            'donor_phone'  => 'nullable|string|max:20',
            'amount'       => 'required|numeric|min:10',
            'currency'     => 'nullable|string|max:10',
            'message'      => 'nullable|string',
            'anonymous'    => 'nullable|boolean',
            'payment_gateway' => 'required|in:sslcommerz,bkash,nagad',
            'product_category' => 'nullable|string|max:50',
        ]);

        if (Auth::check() && empty($data['anonymous'])) {
            $data['user_id']     = Auth::id();
            $data['donor_name']  = $data['donor_name'] ?? Auth::user()->name;
            $data['donor_email'] = $data['donor_email'] ?? Auth::user()->email;
        }

        $donation = Donation::create([
            'user_id'         => $data['user_id'] ?? null,
            'donor_name'      => $data['donor_name'] ?? null,
            'donor_email'     => $data['donor_email'] ?? null,
            'donor_phone'     => $data['donor_phone'] ?? null,
            'amount'          => $data['amount'],
            'currency'        => $data['currency'] ?? 'BDT',
            'message'         => $data['message'] ?? null,
            'anonymous'       => $data['anonymous'] ?? false,
            'status'          => 'pending',
            'payment_gateway' => $data['payment_gateway'],
            'product_category'=> $data['product_category'] ?? 'donation',
        ]);

        switch ($donation->payment_gateway) {
            case 'bkash':
                return $this->payWithBkash($donation);

            case 'nagad':
                return $this->payWithNagad($donation);

            case 'sslcommerz':
                return $this->payWithSslcommerz($donation);

            default:
                return redirect()->route('donation.thankyou')
                                 ->with('success', 'Donation saved.');
        }
    }

    /**
     * bKash Payment (simplified for sandbox/demo)
     */
    protected function payWithBkash(Donation $donation)
    {
        $config = config('donation.payment.bkash');

        // 1. Get access token
        $tokenResponse = Http::withBasicAuth($config['app_key'], $config['app_secret'])
            ->post("{$config['base_url']}/token/grant", [
                'username' => $config['username'],
                'password' => $config['password'],
            ]);

        $token = $tokenResponse->json('id_token');

        // 2. Create payment (simulate for now)
        $donation->update([
            'status' => 'completed',
            'transaction_id' => 'BKASH-' . uniqid(),
        ]);

        return redirect()->route('donation.thankyou')
            ->with('success', 'Donation completed via bKash.');
    }

    /**
     * Nagad Payment (sandbox simulation)
     */
    protected function payWithNagad(Donation $donation)
    {
        $donation->update([
            'status' => 'completed',
            'transaction_id' => 'NAGAD-' . uniqid(),
        ]);

        return redirect()->route('donation.thankyou')
            ->with('success', 'Donation completed via Nagad.');
    }

    /**
     * SSLCommerz Payment
     */
    protected function payWithSslcommerz(Donation $donation)
    {
        $post_data = [
    'total_amount'     => $donation->amount,
    'currency'         => $donation->currency,
    'tran_id'          => $donation->id,

    // Redirect URLs
    'success_url'      => route('donation.ssl.success'),
    'fail_url'         => route('donation.ssl.fail'),
    'cancel_url'       => route('donation.ssl.cancel'),

    // Customer Info
    'cus_name'         => $donation->donor_name ?? 'Guest',
    'cus_email'        => $donation->donor_email ?? 'guest@example.com',
    'cus_add1'         => 'Dhaka',
    'cus_city'         => 'Dhaka',
    'cus_country'      => 'Bangladesh',
    'cus_phone'        => $donation->donor_phone ?? '01700000000',

    // Product Info
    'product_name'     => 'Donation',
    'product_category' => $donation->product_category ?? 'donation',
    'product_profile'  => 'non-physical-goods',

    // Shipping Info (mandatory)
    'shipping_method'  => 'NO',
    'num_of_item'      => 1,
];


        $sslc = new SslCommerzNotification();
        return $sslc->makePayment($post_data, 'hosted', 'json');
    }

    /**
     * SSLCommerz Callbacks
     */
    public function sslSuccess(Request $request)
    {
        $donation = Donation::find($request->input('tran_id'));

        if ($donation) {
            $donation->update([
                'status' => 'completed',
                'transaction_id' => $request->input('tran_id'),
            ]);
        }

        return redirect()->route('donation.thankyou')->with('success', 'Donation successful.');
    }

    public function sslFail(Request $request)
    {
        $donation = Donation::find($request->input('tran_id'));
        if ($donation) {
            $donation->update(['status' => 'failed']);
        }
        return redirect()->route('donation.create')->with('error', 'Payment failed.');
    }

    public function sslCancel(Request $request)
    {
        $donation = Donation::find($request->input('tran_id'));
        if ($donation) {
            $donation->update(['status' => 'cancelled']);
        }
        return redirect()->route('donation.create')->with('error', 'Payment cancelled.');
    }
    
    
public function report()
{
    $totalDonations = \Modules\Donation\Models\Donation::sum('amount');
    $completedCount = \Modules\Donation\Models\Donation::where('status', 'completed')->count();
    $pendingCount   = \Modules\Donation\Models\Donation::where('status', 'pending')->count();

    return view('donation::admin.report', compact('totalDonations', 'completedCount', 'pendingCount'));
}

    /**
     * Thank you page
     */
    public function thankyou()
    {
        return view('donation::thankyou');
    }
}

