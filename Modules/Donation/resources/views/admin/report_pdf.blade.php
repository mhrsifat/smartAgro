@extends('layouts.pdf')

@section('content')
    {{-- You may pass $title_en and $title_bn from controller. Title variables keep code free of Bangla text. --}}
    <div class="report-header">
        <h1>{{ $title_en ?? 'Donations Report' }}</h1>
        @if(!empty($title_bn))
            <div class="bangla muted">{{ $title_bn }}</div>
        @endif

        <div class="muted" style="margin-top:6px;">
            {{ \Carbon\Carbon::parse($dateFrom)->format('d M, Y') }}
            &nbsp; — &nbsp;
            {{ \Carbon\Carbon::parse($dateTo)->format('d M, Y') }}
            &nbsp; · &nbsp;
            Period: {{ ucfirst($period) }}
        </div>
    </div>

    {{-- Summary --}}
    <table class="summary">
        <thead>
            <tr>
                <th>Total Donations</th>
                <th>Total Amount</th>
                <th>Completed</th>
                <th>Completed Amount</th>
                <th>Pending</th>
                <th>Failed</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">{{ $totalDonations }}</td>
                <td class="text-right">{{ number_format($totalAmount, 2) }} BDT</td>
                <td class="text-center">{{ $completedDonations }}</td>
                <td class="text-right">{{ number_format($completedAmount ?? 0, 2) }} BDT</td>
                <td class="text-center">{{ $pendingDonations }}</td>
                <td class="text-center">{{ $failedDonations }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Status Breakdown --}}
    <h3>By Status</h3>
    <table class="report-table">
        <thead>
            <tr>
                <th>Status</th>
                <th class="text-center">Count</th>
                <th class="text-right">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @if($statusBreakdown->isEmpty())
                <tr><td colspan="3" class="text-center muted">No data</td></tr>
            @else
                @foreach($statusBreakdown as $row)
                    <tr>
                        <td>{{ ucfirst($row->status) }}</td>
                        <td class="text-center">{{ $row->count }}</td>
                        <td class="text-right">{{ number_format($row->total, 2) }} BDT</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    {{-- Payment Gateway Breakdown --}}
    <h3>By Payment Gateway</h3>
    <table class="report-table">
        <thead>
            <tr>
                <th>Gateway</th>
                <th class="text-center">Count</th>
                <th class="text-right">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @if($paymentGatewayBreakdown->isEmpty())
                <tr><td colspan="3" class="text-center muted">No data</td></tr>
            @else
                @foreach($paymentGatewayBreakdown as $row)
                    <tr>
                        <td>{{ $row->payment_gateway ?? '—' }}</td>
                        <td class="text-center">{{ $row->count }}</td>
                        <td class="text-right">{{ number_format($row->total, 2) }} BDT</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    {{-- Top Donors --}}
    <h3>Top Donors</h3>
    <table class="report-table" style="font-size: 10pt;">
        <thead>
            <tr>
                <th>Donor Name</th>
                <th>Email</th>
                <th class="text-center">Donations</th>
                <th class="text-right">Total Donated</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topDonors as $donor)
                <tr>
                    {{-- donor_name may contain Bangla; class "bangla" will use bangla font registered in mPDF --}}
                    <td class="bangla">{{ $donor->donor_name }}</td>
                    <td>{{ $donor->donor_email }}</td>
                    <td class="text-center">{{ $donor->donation_count }}</td>
                    <td class="text-right">{{ number_format($donor->total_donated, 2) }} BDT</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center muted">No donors found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Optional: small footer --}}
    <div style="margin-top:10px; font-size:9pt; color:#666;">
        Generated on {{ now()->format('d M, Y H:i') }}
    </div>
@endsection