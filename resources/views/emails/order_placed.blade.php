@php
    $currency = get_setting('currency_symbol', '৳');
    $isAdmin = ($array['recipient'] ?? 'customer') == 'admin';
    $subtotal = $order->details->sum(function ($d) { return $d->price * $d->quantity; });
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $order->code }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f5f7;font-family:Arial,Helvetica,sans-serif;color:#333;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f5f7;padding:24px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:6px;overflow:hidden;">
                <tr>
                    <td style="background:#3b82f6;color:#ffffff;padding:20px 24px;">
                        <h2 style="margin:0;font-size:20px;">{{ env('APP_NAME') }}</h2>
                    </td>
                </tr>
                <tr>
                    <td style="padding:24px;">
                        @if ($isAdmin)
                            <p style="margin:0 0 12px;">{{ translate('A new order has been placed on your store.') }}</p>
                        @else
                            <p style="margin:0 0 12px;">{{ translate('Hi') }} {{ $order->name }},</p>
                            <p style="margin:0 0 12px;">{{ translate('Thank you for your order. We have received it and will process it shortly.') }}</p>
                        @endif

                        <table width="100%" cellpadding="4" cellspacing="0" style="font-size:14px;margin-bottom:16px;">
                            <tr><td><strong>{{ translate('Order Code') }}:</strong></td><td>{{ $order->code }}</td></tr>
                            <tr><td><strong>{{ translate('Date') }}:</strong></td><td>{{ date('d M Y, h:i A', strtotime($order->date)) }}</td></tr>
                            <tr><td><strong>{{ translate('Payment Method') }}:</strong></td><td>{{ strtoupper($order->payment_type) }}</td></tr>
                            <tr><td><strong>{{ translate('Name') }}:</strong></td><td>{{ $order->name }}</td></tr>
                            <tr><td><strong>{{ translate('Phone') }}:</strong></td><td>{{ $order->phone_number }}</td></tr>
                            <tr><td><strong>{{ translate('Shipping Address') }}:</strong></td><td>{{ $order->shipping_address }}</td></tr>
                        </table>

                        <table width="100%" cellpadding="8" cellspacing="0" style="font-size:14px;border-collapse:collapse;">
                            <thead>
                                <tr style="background:#f3f4f6;text-align:left;">
                                    <th>{{ translate('Product') }}</th>
                                    <th style="text-align:center;">{{ translate('Qty') }}</th>
                                    <th style="text-align:right;">{{ translate('Price') }}</th>
                                    <th style="text-align:right;">{{ translate('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->details as $detail)
                                    <tr style="border-bottom:1px solid #eee;">
                                        <td>
                                            {{ optional($detail->product)->name ?? translate('Product') }}
                                            @if ($detail->variation)
                                                <br><small style="color:#6b7280;">{{ $detail->variation }}</small>
                                            @endif
                                        </td>
                                        <td style="text-align:center;">{{ $detail->quantity }}</td>
                                        <td style="text-align:right;">{{ $currency }}{{ number_format($detail->price, 2) }}</td>
                                        <td style="text-align:right;">{{ $currency }}{{ number_format($detail->price * $detail->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <table width="100%" cellpadding="4" cellspacing="0" style="font-size:14px;margin-top:12px;">
                            <tr><td style="text-align:right;">{{ translate('Subtotal') }}:</td><td style="text-align:right;width:120px;">{{ $currency }}{{ number_format($subtotal, 2) }}</td></tr>
                            <tr><td style="text-align:right;">{{ translate('Shipping') }}:</td><td style="text-align:right;">{{ $currency }}{{ number_format($order->shipping_cost, 2) }}</td></tr>
                            @if ($order->discount > 0)
                                <tr><td style="text-align:right;">{{ translate('Discount') }}:</td><td style="text-align:right;">-{{ $currency }}{{ number_format($order->discount, 2) }}</td></tr>
                            @endif
                            @if ($order->coupon_discount > 0)
                                <tr><td style="text-align:right;">{{ translate('Coupon Discount') }}:</td><td style="text-align:right;">-{{ $currency }}{{ number_format($order->coupon_discount, 2) }}</td></tr>
                            @endif
                            <tr><td style="text-align:right;"><strong>{{ translate('Grand Total') }}:</strong></td><td style="text-align:right;"><strong>{{ $currency }}{{ number_format($order->grand_total, 2) }}</strong></td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="background:#f9fafb;color:#6b7280;font-size:12px;padding:16px 24px;text-align:center;">
                        &copy; {{ date('Y') }} {{ env('APP_NAME') }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
