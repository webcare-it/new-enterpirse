<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ translate('Invoice') }} #{{ $order->code }}</title>


    <style>
        @font-face {
            font-family: "NotoBangla";
            src: url("{{ public_path('fonts/NotoSansBengali-Regular.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        * {
            font-family: "NotoBangla", DejaVu Sans, sans-serif;
        }

        @page {
            margin: 18px;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 13px;
            margin: 0;
            color: #1f2937;
            background: #ffffff;
        }
        .logo_img {
            max-height: 100px;
        }

        .invoice-wrapper {
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 22px;
        }

        .header {
            background: #0b3f2e;
            color: #ffffff;
            border-radius: 16px;
            padding: 24px;
        }

        .header-table {
            width: 100%;
        }

        .company-name {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .company-info {
            font-size: 12px;
            color: #ffffff;
            line-height: 1.6;
        }

        .invoice-title {
            text-align: right;
            font-size: 34px;
            font-weight: 900;
            letter-spacing: 2px;
        }

        .invoice-code {
            display: inline-block;
            margin-top: 8px;
            background: #ffffff;
            color: #0b3f2e;
            padding: 7px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 0;
        }

        .section {
            margin-top: 18px;
        }

        .info-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 12px;
            margin-left: -12px;
            margin-right: -12px;
        }

        .info-card {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 14px;
            vertical-align: top;
        }

        .card-title {
            font-size: 13px;
            font-weight: 800;
            color: #0b3f2e;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .line {
            margin-bottom: 7px;
        }

        .label {
            font-weight: 700;
            color: #374151;
        }

        .muted {
            color: #6b7280;
            font-size: 12px;
        }

        .badge {
            display: inline-block;
            padding: 5px 11px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 800;
            background: #dcfce7;
            color: #166534;
        }

        .badge-orange {
            background: #ffedd5;
            color: #c2410c;
        }

        .items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
            overflow: hidden;
        }

        .items th {
            background: #111827;
            color: #ffffff;
            padding: 11px 9px;
            font-size: 12px;
            border: 1px solid #111827;
        }

        .items td {
            padding: 11px 9px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .items tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .product-name {
            font-weight: 800;
            color: #111827;
        }

        .small {
            font-size: 11px;
            color: #6b7280;
            margin-top: 3px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-table {
            width: 100%;
            margin-top: 20px;
        }

        .address-box {
            width: 48%;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 15px;
            vertical-align: top;
            line-height: 1.7;
        }

        .totals-box {
            width: 48%;
            vertical-align: top;
        }

        .totals {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
        }

        .totals td {
            padding: 9px 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .totals tr:last-child td {
            border-bottom: none;
        }

        .grand-total td {
            background: #0b3f2e;
            color: #ffffff;
            font-size: 18px;
            font-weight: 900;
            padding: 13px 12px;
        }

        .notes {
            margin-top: 16px;
            padding: 14px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 14px;
            line-height: 1.6;
        }

        .signature-table {
            width: 100%;
            margin-top: 46px;
        }

        .signature-line {
            border-top: 1px solid #9ca3af;
            width: 180px;
            text-align: center;
            padding-top: 8px;
            font-size: 12px;
            color: #4b5563;
        }

        .footer {
            margin-top: 28px;
            background: #f8fafc;
            border-radius: 14px;
            padding: 14px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border: 1px solid #e5e7eb;
        }
    </style>
</head>

<body>
    <div class="invoice-wrapper">

        <div class="header">
            <table class="header-table">
                <tr>
                    <td width="60%">
                        {{-- <img class="mw-100" src="{{ uploaded_asset(get_setting('header_logo')) }}" class="brand-icon"
                        alt="{{ get_setting('site_name') }}"> --}}
                        <div class="company-name">{{ get_setting('website_name', 'ShopHub') }}</div>
                        <div class="company-info">
                            {{ translate('Phone') }}: {{ get_setting('contact_phone', '') }}<br>
                            {{ translate('Email') }}: {{ get_setting('contact_email', '') }}<br>
                            {{ translate('Address') }}: {{ get_setting('contact_address', '') }}
                        </div>
                    </td>
                    <td width="40%" class="invoice-title">
                        {{ translate('INVOICE') }}<br>
                        <span class="invoice-code">#{{ $order->code }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <table class="info-grid">
                <tr>
                    <td class="info-card" width="50%">
                        <div class="card-title">{{ translate('Order Information') }}</div>

                        <div class="line">
                            <span class="label">{{ translate('Order Code') }}:</span>
                            {{ $order->code }}
                        </div>

                        <div class="line">
                            <span class="label">{{ translate('Order Date') }}:</span>
                            {{ $order->created_at->format('d M Y, h:i A') }}
                        </div>

                        <div class="line">
                            <span class="label">{{ translate('Delivery Status') }}:</span>
                            <span class="badge">{{ ucfirst($order->delivery_status) }}</span>
                        </div>

                        <div class="line">
                            <span class="label">{{ translate('Payment Status') }}:</span>
                            <span class="badge badge-orange">{{ ucfirst($order->payment_status) }}</span>
                        </div>
                    </td>

                    <td class="info-card" width="50%">
                        <div class="card-title">{{ translate('Customer Information') }}</div>

                        <div class="line">
                            <span class="label">{{ translate('Customer') }}:</span>
                            {{ $order->name ?? 'Guest' }}
                        </div>

                        <div class="line">
                            <span class="label">{{ translate('Phone') }}:</span>
                            {{ $order->phone_number ?? 'N/A' }}
                        </div>
                        <div class="line">
                            <span class="label">{{ translate('Email') }}:</span>
                            {{ $order->email_address ?? 'N/A' }}
                        </div>

                        <div class="line">
                            <span class="label">{{ translate('Payment Method') }}:</span>
                            {{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th width="5%" class="text-center">#</th>
                    <th width="40%">{{ translate('Product') }}</th>
                    <th width="15%" class="text-right">{{ translate('Price') }}</th>
                    <th width="10%" class="text-center">{{ translate('Qty') }}</th>
                    <th width="15%" class="text-right">{{ translate('Tax') }}</th>
                    <th width="15%" class="text-right">{{ translate('Total') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($order->orderDetails as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>

                        <td>
                            <div class="product-name">
                                {{ $detail->product->name ?? 'Product Not Found' }}
                            </div>
                            @php
                                $variation = json_decode($detail->variation, true);
                            @endphp

                            @if ($variation && collect($variation)->filter(fn($value) => $value !== null && $value !== '')->isNotEmpty())
                                <div class="small">
                                    {{ translate('Variation') }}:

                                    @foreach ($variation as $key => $value)
                                        @if ($value !== null && $value !== '')
                                            {{ $value }}
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            <div class="small">
                                {{ translate('SKU') }}: {{ $detail->sku }}
                            </div>
                        </td>

                        <td class="text-right">Taka{{ number_format($detail->price, 2) }}</td>
                        <td class="text-center">{{ $detail->quantity }}</td>
                        <td class="text-right">Taka{{ number_format($detail->tax, 2) }}</td>
                        <td class="text-right">
                            Taka{{ number_format($detail->price * $detail->quantity, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="summary-table">
            <tr>
                <td class="address-box">
                    <div class="card-title">{{ translate('Shipping Address') }}</div>

                    @if ($order->shipping_address)
                        {{ $order->shipping_address }}
                    @else
                        <span class="muted">{{ translate('No shipping address found') }}</span>
                    @endif
                </td>

                <td width="4%"></td>

                <td class="totals-box">
                    <table class="totals">
                        <tr>
                            <td>{{ translate('Subtotal') }}</td>
                            <td class="text-right">Taka{{ number_format($subtotal ?? 0, 2) }}</td>
                        </tr>

                        @if (($totalTax ?? 0) > 0)
                            <tr>
                                <td>{{ translate('Tax') }}</td>
                                <td class="text-right">Taka{{ number_format($totalTax, 2) }}</td>
                            </tr>
                        @endif

                        @if (($totalShipping ?? 0) > 0)
                            <tr>
                                <td>{{ translate('Shipping Cost') }}</td>
                                <td class="text-right">Taka{{ number_format($totalShipping, 2) }}</td>
                            </tr>
                        @endif

                        @if ($order->coupon_discount > 0)
                            <tr>
                                <td>{{ translate('Coupon Discount') }}</td>
                                <td class="text-right">- Taka{{ number_format($order->coupon_discount, 2) }}</td>
                            </tr>
                        @endif

                        @if ($order->discount > 0)
                            <tr>
                                <td>{{ translate('Additional Discount') }}</td>
                                <td class="text-right">- Taka{{ number_format($order->discount, 2) }}</td>
                            </tr>
                        @endif

                        <tr class="grand-total">
                            <td>{{ translate('Grand Total') }}</td>
                            <td class="text-right"> Taka
                                {{ number_format($order->grand_total, 2) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        @if ($order->notes)
            <div class="notes">
                <strong>{{ translate('Order Notes') }}</strong><br>
                {{ $order->notes }}
            </div>
        @endif

        <table class="signature-table">
            <tr>
                <td width="50%">
                    <div class="signature-line">{{ translate('Customer Signature') }}</div>
                </td>

                <td width="50%" align="right">
                    <div class="signature-line">{{ translate('Authorized Signature') }}</div>
                </td>
            </tr>
        </table>

        <div class="footer">
            <strong>{{ translate('Thank you for your business!') }}</strong><br>
            {{ get_setting('site_name', 'ShopHub') }}
        </div>

    </div>
</body>

</html>
