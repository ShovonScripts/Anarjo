<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->invoice_no }}</title>
    <style>
        @page {
            margin: 25mm 20mm 25mm 20mm;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'solaimanlipi', sans-serif;
            color: #1a1a1a;
            font-size: 13px;
            line-height: 1.6;
        }
        .invoice {
            max-width: 750px;
            margin: 0 auto;
            padding: 30px;
        }

        /* Header */
        .header {
            width: 100%;
            border-bottom: 3px solid #1a1a1a;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: top;
            padding: 0;
        }
        .header-left h1 {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }
        .header-left p {
            color: #666;
            font-size: 11px;
            margin-top: 4px;
        }
        .header-right {
            text-align: right;
        }
        .header-right .label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #999;
        }
        .header-right .value {
            font-size: 13px;
            font-weight: 600;
        }
        .header-right .spacer {
            height: 8px;
        }

        /* Status badges */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 2px;
        }
        .badge-pending    { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
        .badge-completed  { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .badge-failed     { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .badge-cancelled  { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }

        /* Info grid */
        .info-grid { width: 100%; margin-bottom: 25px; }
        .info-grid-table { width: 100%; border-collapse: collapse; }
        .info-grid-table td {
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }
        .info-grid-table td:last-child {
            padding-right: 0;
            padding-left: 20px;
        }
        .info-box h3 {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #999;
            margin-bottom: 8px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .info-box p { font-size: 12px; margin-bottom: 3px; }
        .info-box .name { font-weight: 600; font-size: 13px; }

        /* Items table */
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        table.items thead th {
            background: #1a1a1a;
            color: #fff;
            padding: 10px 12px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: left;
        }
        table.items thead th:last-child { text-align: right; }
        table.items tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
            font-size: 12px;
        }
        table.items tbody td:last-child { text-align: right; font-weight: 600; }
        table.items tbody tr:nth-child(even) td { background: #fafafa; }

        /* Totals */
        .totals-section { width: 100%; margin-bottom: 30px; }
        .totals-table {
            width: 280px;
            border-collapse: collapse;
            margin-left: auto;
        }
        .totals-table td { padding: 6px 0; font-size: 12px; }
        .totals-table td:first-child { text-align: left; }
        .totals-table td:last-child  { text-align: right; }
        .totals-table .border td { border-top: 1px solid #ddd; padding-top: 8px; }
        .totals-table .total td {
            border-top: 2px solid #1a1a1a;
            padding-top: 10px;
            font-size: 16px;
            font-weight: 800;
        }

        /* Payment / notes */
        .meta-section { margin-bottom: 25px; }
        .meta-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #999;
            margin-bottom: 5px;
        }
        .meta-value { font-size: 12px; font-weight: 600; }

        /* Footer */
        .footer { border-top: 1px solid #eee; padding-top: 15px; text-align: center; }
        .footer p { font-size: 11px; color: #999; }
        .footer .brand { font-weight: 700; color: #1a1a1a; }

        .avoid-break { page-break-inside: avoid; }
    </style>
</head>
<body>
    <div class="invoice">
        {{-- Header --}}
        <div class="header avoid-break">
            <table class="header-table">
                <tr>
                    <td class="header-left">
                        <h1>ANARJO</h1>
                        <p>{{ setting('site_tagline', 'Publication House') }}</p>
                        <p>{{ setting('site_address', 'Bangladesh') }}</p>
                    </td>
                    <td class="header-right">
                        <p class="label">Invoice No</p>
                        <p class="value">{{ $order->invoice_no }}</p>
                        <div class="spacer"></div>
                        <p class="label">Date</p>
                        <p class="value">{{ $order->created_at->format('d M, Y') }}</p>
                        <div class="spacer"></div>
                        <p class="label">Payment</p>
                        <p>
                            @if($order->payment_status === 'completed')
                                <span class="badge badge-completed">Paid</span>
                            @elseif($order->payment_status === 'failed')
                                <span class="badge badge-failed">Failed</span>
                            @elseif($order->payment_status === 'cancelled')
                                <span class="badge badge-cancelled">Cancelled</span>
                            @else
                                <span class="badge badge-pending">Pending</span>
                            @endif
                        </p>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Billing & Shipping --}}
        <div class="info-grid avoid-break">
            <table class="info-grid-table">
                <tr>
                    <td>
                        <div class="info-box">
                            <h3>Bill To</h3>
                            @if($order->user)
                                <p class="name">{{ $order->user->name }}</p>
                                <p>{{ $order->user->email }}</p>
                            @elseif($order->guest_email)
                                <p class="name">Guest</p>
                                <p>{{ $order->guest_email }}</p>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="info-box">
                            <h3>Ship To</h3>
                            @if($order->address)
                                <p class="name">{{ $order->address->name }}</p>
                                <p>{{ $order->address->phone }}</p>
                                <p>{{ $order->address->address }}</p>
                                <p>{{ $order->address->area }}, {{ $order->address->district }}</p>
                                @if($order->address->postal_code)
                                    <p>Postal Code: {{ $order->address->postal_code }}</p>
                                @endif
                            @else
                                <p>No shipping address</p>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Items Table --}}
        <table class="items avoid-break">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ $item->book?->title ?? 'Product #' . $item->book_id }}
                            @if($item->item_type === 'stationery')
                                <small>(Stationery)</small>
                            @endif
                        </td>
                        <td>{{ $item->qty }}</td>
                        <td>&#2547;{{ number_format($item->price, 2) }}</td>
                        <td>&#2547;{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="totals-section avoid-break">
            <table class="totals-table">
                <tr>
                    <td>Subtotal</td>
                    <td>&#2547;{{ number_format($order->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>Shipping</td>
                    <td>&#2547;{{ number_format($order->shipping, 2) }}</td>
                </tr>
                @if($order->discount > 0)
                    <tr>
                        <td>Discount</td>
                        <td>-&#2547;{{ number_format($order->discount, 2) }}</td>
                    </tr>
                @endif
                <tr class="total">
                    <td>Total</td>
                    <td>&#2547;{{ number_format($order->total, 2) }}</td>
                </tr>
            </table>
        </div>

        {{-- Payment Info --}}
        @if($order->payment_method)
            <div class="meta-section">
                <p class="meta-label">Payment Method</p>
                <p class="meta-value">{{ strtoupper($order->payment_method) }}</p>
            </div>
        @endif

        @if($order->notes)
            <div class="meta-section">
                <p class="meta-label">Notes</p>
                <p style="font-size: 12px;">{{ $order->notes }}</p>
            </div>
        @endif

        {{-- Footer --}}
        <div class="footer">
            <p>Thank you for your order!</p>
            <p><span class="brand">ANARJO</span> &mdash; {{ setting('site_address', 'Bangladesh') }}</p>
        </div>
    </div>
</body>
</html>
