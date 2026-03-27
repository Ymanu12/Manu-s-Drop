<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.5;
            margin: 32px;
        }

        .header {
            margin-bottom: 24px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 8px;
        }

        .muted {
            color: #6b7280;
        }

        .info-table,
        .items-table,
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .info-table td {
            padding: 6px 0;
            vertical-align: top;
        }

        .items-table th,
        .items-table td,
        .totals-table th,
        .totals-table td {
            border: 1px solid #d1d5db;
            padding: 10px 12px;
        }

        .items-table th,
        .totals-table th {
            background: #f3f4f6;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Order Confirmation</p>
        <p class="muted">Thank you. Your order has been received.</p>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Order Number</strong></td>
            <td>{{ $order->id ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Date</strong></td>
            <td>{{ $order->created_at ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Payment Method</strong></td>
            <td>{{ $order->transaction->mode ?? 'Not yet processed' }}</td>
        </tr>
        <tr>
            <td><strong>Total</strong></td>
            <td>${{ number_format((float) ($order->total ?? 0), 2) }}</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Product</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                    <td class="text-right">{{ $item->quantity ?? 0 }}</td>
                    <td class="text-right">${{ number_format((float) (($item->price ?? 0) * ($item->quantity ?? 0)), 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No items found in this order.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <th>Subtotal</th>
            <td class="text-right">${{ number_format((float) ($order->subtotal ?? 0), 2) }}</td>
        </tr>
        <tr>
            <th>Discount</th>
            <td class="text-right">${{ number_format((float) ($order->discount ?? 0), 2) }}</td>
        </tr>
        <tr>
            <th>VAT</th>
            <td class="text-right">${{ number_format((float) ($order->tax ?? 0), 2) }}</td>
        </tr>
        <tr>
            <th>Total</th>
            <td class="text-right">${{ number_format((float) ($order->total ?? 0), 2) }}</td>
        </tr>
    </table>
</body>
</html>
