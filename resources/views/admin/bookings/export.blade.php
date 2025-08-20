<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking #{{ $booking->reference }} - Export</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; background: #fff; color: #222; }
        .header { background: #d71418; color: #fff; padding: 24px 0; text-align: center; border-radius: 12px 12px 0 0; }
        .header h1 { margin: 0; font-size: 2rem; letter-spacing: 2px; }
        .section { margin: 32px 0; }
        .section-title { font-size: 1.2rem; font-weight: bold; color: #d71418; margin-bottom: 12px; border-bottom: 1px solid #eee; padding-bottom: 4px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .info-table th, .info-table td { text-align: left; padding: 8px 12px; border-bottom: 1px solid #f3f3f3; }
        .info-table th { background: #f8fafc; color: #d71418; font-weight: 600; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 8px; font-size: 0.9em; font-weight: 600; }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-paid { background: #d1ecf1; color: #0c5460; }
        .badge-cancelled { background: #f8d7da; color: #721c24; }
        .badge-checked-in { background: #d4edda; color: #155724; }
        .badge-checked-out { background: #e2e3e5; color: #383d41; }
        .footer { text-align: center; color: #aaa; font-size: 0.9em; margin-top: 40px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Booking Details</h1>
        <div style="margin-top:8px;font-size:1.1em;">Reference: <b>{{ $booking->reference }}</b></div>
    </div>

    <div class="section">
        <div class="section-title">Stay Information</div>
        <table class="info-table">
            <tr><th>Check-in Date</th><td>{{ $booking->from_date ? $booking->from_date->format('M d, Y') : 'N/A' }}</td></tr>
            <tr><th>Check-out Date</th><td>{{ $booking->to_date ? $booking->to_date->format('M d, Y') : 'N/A' }}</td></tr>
            <tr><th>Duration</th><td>{{ $booking->duration }} nights</td></tr>
            <tr><th>Guests</th><td>{{ $booking->pacs }} person(s)</td></tr>
            <tr><th>Status</th><td>
                @php
                    $statusColors = [
                        'pending' => 'badge badge-pending',
                        'paid' => 'badge badge-paid',
                        'checked_in' => 'badge badge-checked-in',
                        'checked_out' => 'badge badge-checked-out',
                        'cancelled' => 'badge badge-cancelled'
                    ];
                @endphp
                <span class="{{ $statusColors[$booking->status] }}">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
            </td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Accommodation & Room</div>
        <table class="info-table">
            <tr><th>Accommodation</th><td>{{ $booking->accommodation->name ?? 'N/A' }}</td></tr>
            <tr><th>Room</th><td>{{ $booking->accommodationRoom->name ?? 'N/A' }}</td></tr>
            <tr><th>Category</th><td>{{ $booking->accommodation->category ?? 'N/A' }}</td></tr>
            <tr><th>Room Category</th><td>{{ $booking->accommodationRoom->category ?? 'N/A' }}</td></tr>
            <tr><th>Room Price</th><td>{{ number_format($booking->accommodationRoom->price ?? 0) }} {{ $booking->accommodationRoom->currency ?? 'N/A' }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Customer Information</div>
        <table class="info-table">
            <tr><th>Name</th><td>{{ $booking->customer->name ?? 'N/A' }}</td></tr>
            <tr><th>Email</th><td>{{ $booking->customer->email ?? 'N/A' }}</td></tr>
            <tr><th>Phone</th><td>{{ $booking->customer->phone ?? 'N/A' }}</td></tr>
            <tr><th>Country</th><td>{{ $booking->customer->country->name ?? 'N/A' }}</td></tr>
            <tr><th>Province</th><td>{{ $booking->customer->province->name ?? 'N/A' }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Payment Information</div>
        <table class="info-table">
            <tr><th>Price per Night</th><td>{{ number_format($booking->price) }} {{ $booking->currency }}</td></tr>
            <tr><th>Total Amount</th><td>{{ number_format($booking->amount) }} {{ $booking->currency }}</td></tr>
            <tr><th>Amount Paid</th><td>{{ number_format($booking->amount_paid) }} {{ $booking->currency }}</td></tr>
            <tr><th>Payment Status</th><td><span class="badge {{ $booking->is_paid ? 'badge-paid' : 'badge-pending' }}">{{ $booking->is_paid ? 'Paid' : 'Unpaid' }}</span></td></tr>
        </table>
    </div>

    <div class="footer">
        Exported on {{ now()->format('M d, Y H:i') }}<br>
        &copy; {{ date('Y') }} Tempo Africa
    </div>
</body>
</html> 