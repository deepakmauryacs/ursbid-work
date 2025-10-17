@extends('seller.layouts.app')
@section('title', 'Comparative Information Statement')

@section('content')
@php
    $records = ($records ?? collect())->values();
    $enquiry = $enquiry ?? null;
    $sellerRecords = $records;
    $maxVendorColumns = max($sellerRecords->count(), 4);

    $productTitle = $enquiry->product_title
        ?? $enquiry->bidding_price_product_name
        ?? $enquiry->qutation_form_product_name
        ?? '-';

    $productBrand = $enquiry->product_brand
        ?? $enquiry->category_name
        ?? $enquiry->sub_name
        ?? $enquiry->material
        ?? null;

    $quantityValue = $enquiry->quantity ?? null;
    $unitValue = $enquiry->unit ?? null;

    $startPrice = $sellerRecords
        ->map(function ($item) {
            return is_numeric($item->rate ?? null) ? (float) $item->rate : null;
        })
        ->filter(fn ($value) => $value !== null)
        ->min();

    $extractQuantity = static function ($value) {
        if (is_numeric($value)) {
            return (float) $value;
        }

        if (is_string($value) && preg_match('/\d+(?:\.\d+)?/', $value, $matches)) {
            return isset($matches[0]) ? (float) $matches[0] : null;
        }

        return null;
    };

    $buyerQuantityNumber = $extractQuantity($quantityValue);
    $buyerQuoteValue = null;

    if ($startPrice !== null) {
        $buyerQuoteValue = $buyerQuantityNumber !== null
            ? $buyerQuantityNumber * $startPrice
            : $startPrice;
    }

    $valueOrNA = static function ($value, string $default = 'N/A') {
        if (is_string($value)) {
            $value = trim($value);
        }

        return ($value === null || $value === '') ? $default : $value;
    };

    $buyerName = $enquiry->name
        ?? $enquiry->buyer_name
        ?? $enquiry->qutation_form_name
        ?? 'N/A';

    $quotationDateRaw = $enquiry->date_time
        ?? $enquiry->created_at
        ?? $enquiry->updated_at
        ?? null;

    try {
        $quotationDateLabel = $quotationDateRaw
            ? \Illuminate\Support\Carbon::parse($quotationDateRaw)->format('d/m/Y')
            : 'N/A';
    } catch (\Exception $exception) {
        $quotationDateLabel = is_string($quotationDateRaw) ? $quotationDateRaw : 'N/A';
    }

    $buyerLocationParts = collect([
        $enquiry->city ?? null,
        $enquiry->state ?? null,
    ])->filter()->implode(', ');

    $lowestVendorRate = $sellerRecords
        ->map(function ($record) {
            return is_numeric($record->rate ?? null) ? (float) $record->rate : null;
        })
        ->filter()
        ->min();

    $totalQuoteValue = static function ($record) use ($extractQuantity) {
        if (!$record) {
            return null;
        }

        $rate = is_numeric($record->rate ?? null) ? (float) $record->rate : null;
        if ($rate === null) {
            return null;
        }

        $quantity = $extractQuantity($record->quantity ?? null);

        return $quantity !== null ? $quantity * $rate : $rate;
    };

    $resolveSellerValue = static function (?object $record, string $key) use ($totalQuoteValue, $extractQuantity) {
        if (!$record) {
            return null;
        }

        switch ($key) {
            case 'product':
                return $record->product_title
                    ?? $record->bidding_price_product_name
                    ?? null;
            case 'category':
                return $record->category_name
                    ?? $record->sub_name
                    ?? $record->product_brand
                    ?? $record->material
                    ?? null;
            case 'quantity':
                return $record->quantity ?? null;
            case 'unit':
                return $record->unit ?? null;
            case 'rate':
                return is_numeric($record->rate ?? null)
                    ? number_format((float) $record->rate, 2)
                    : null;
            case 'quote_value':
                $value = $totalQuoteValue($record);

                return $value !== null ? number_format($value, 2) : null;
            case 'quotation_type':
                if (!empty($record->material)) {
                    return 'With Material';
                }

                return 'Not Specified';
            case 'delivery_period':
                return $record->bid_time ?? null;
            case 'platform_fee':
                return is_numeric($record->platform_fee ?? null)
                    ? number_format((float) $record->platform_fee, 2)
                    : null;
            default:
                return null;
        }
    };

    $comparisonRows = [
        [
            'label' => 'Product',
            'key' => 'product',
            'buyerValue' => $productTitle,
        ],
        [
            'label' => 'Category',
            'key' => 'category',
            'buyerValue' => $productBrand ?? 'Not specified',
        ],
        [
            'label' => 'Quantity',
            'key' => 'quantity',
            'buyerValue' => $quantityValue ?? 'Not specified',
        ],
        [
            'label' => 'Unit',
            'key' => 'unit',
            'buyerValue' => $unitValue ?? 'Not specified',
        ],
        [
            'label' => 'Rate (₹)',
            'key' => 'rate',
            'buyerValue' => $startPrice !== null ? number_format($startPrice, 2) : 'N/A',
        ],
        [
            'label' => 'Quote Value (₹)',
            'key' => 'quote_value',
            'buyerValue' => $buyerQuoteValue !== null ? number_format($buyerQuoteValue, 2) : 'N/A',
        ],
        [
            'label' => 'Quotation Type',
            'key' => 'quotation_type',
            'buyerValue' => !empty($enquiry->material) ? 'With Material' : 'Not Specified',
        ],
        [
            'label' => 'Delivery Period (In Days)',
            'key' => 'delivery_period',
            'buyerValue' => $enquiry->bid_time ?? 'Not specified',
        ],
        [
            'label' => 'Platform Fee (₹)',
            'key' => 'platform_fee',
            'buyerValue' => is_numeric($enquiry->platform_fee ?? null)
                ? number_format((float) $enquiry->platform_fee, 2)
                : 'N/A',
        ],
    ];
@endphp

<style>
    .cis-wrapper {
        background: linear-gradient(180deg, #f6f9ff 0%, #eef3fb 100%);
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 18px 40px rgba(15, 70, 140, 0.08);
    }

    .cis-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
    }

    .cis-meta-card {
        background: #ffffff;
        border: 1px solid #d5e2fb;
        border-radius: 16px;
        padding: 18px 20px;
        box-shadow: 0 10px 24px rgba(15, 70, 140, 0.08);
    }

    .cis-meta-card span {
        display: block;
        font-size: 12px;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #6f83aa;
        margin-bottom: 6px;
    }

    .cis-meta-card strong {
        display: block;
        font-size: 18px;
        font-weight: 700;
        color: #0f3c7c;
        word-break: break-word;
    }

    .cis-table-card {
        margin-top: 28px;
        background: #ffffff;
        border: 1px solid #d5e2fb;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 16px 38px rgba(15, 70, 140, 0.1);
    }

    .cis-table-headline {
        padding: 20px 24px;
        background: linear-gradient(135deg, #e9f1ff 0%, #ffffff 100%);
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .cis-table-headline h5 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #103b77;
    }

    .cis-table-headline p {
        margin: 0;
        color: #4e6c9a;
        font-size: 13px;
    }

    .cis-table-scroll {
        overflow-x: auto;
    }

    .cis-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        min-width: 880px;
        font-size: 13px;
        --cis-label-width: 220px;
        --cis-value-width: 260px;
    }

    .cis-table th,
    .cis-table td {
        border: 1px solid #dbe4f5;
        padding: 14px 16px;
        background: #ffffff;
        min-width: 150px;
    }

    .cis-table thead th {
        background: #e4edff;
        color: #0f3c7c;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .06em;
        text-align: center;
    }

    .cis-table thead th.cis-col-label,
    .cis-table thead th.cis-col-value {
        text-transform: none;
        font-size: 13px;
        letter-spacing: normal;
        text-align: left;
    }

    .cis-table tbody th {
        background: #f3f7ff;
        color: #0f3c7c;
        font-weight: 600;
        text-align: left;
    }

    .cis-table tbody tr:nth-child(even) td {
        background: #f9fbff;
    }

    .cis-table td {
        color: #21497f;
        font-weight: 500;
        text-align: center;
    }

    .cis-table td.text-start {
        text-align: left;
    }

    .cis-table .sticky-col {
        position: sticky;
        z-index: 5;
        box-shadow: 12px 0 16px rgba(15, 70, 140, 0.06);
    }

    .cis-table .sticky-col-label {
        left: 0;
        min-width: var(--cis-label-width);
        z-index: 6;
    }

    .cis-table .sticky-col-value {
        left: calc(var(--cis-label-width));
        min-width: var(--cis-value-width);
        z-index: 5;
        box-shadow: 6px 0 12px rgba(15, 70, 140, 0.05);
    }

    .cis-buyer-header {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .cis-buyer-header .cis-headline {
        font-weight: 700;
        color: #103b77;
        font-size: 16px;
    }

    .cis-buyer-header .cis-subtext {
        font-size: 12px;
        color: #5c77a4;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .cis-vendor-header {
        display: flex;
        flex-direction: column;
        gap: 4px;
        align-items: center;
    }

    .cis-vendor-badge {
        background: #dceaff;
        color: #0f3c7c;
        font-weight: 600;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    .cis-vendor-name {
        font-size: 14px;
        font-weight: 700;
        color: #0e386e;
        text-transform: capitalize;
    }

    .cis-vendor-contact {
        font-size: 12px;
        color: #5c77a4;
    }

    .cis-table td.is-best {
        background: #fff6e6 !important;
        color: #c47b07;
        font-weight: 700;
        box-shadow: inset 0 0 0 1px rgba(196, 123, 7, 0.25);
    }

    .cis-summary-grid {
        margin-top: 28px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
    }

    .cis-summary-card {
        background: #ffffff;
        border: 1px solid #d5e2fb;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 12px 26px rgba(15, 70, 140, 0.08);
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-height: 140px;
    }

    .cis-summary-card h6 {
        margin: 0;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #6b82ab;
    }

    .cis-summary-card p {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: #102f63;
        line-height: 1.5;
    }

    .cis-invited-card {
        margin-top: 32px;
        background: #ffffff;
        border: 1px solid #d5e2fb;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 12px 30px rgba(15, 70, 140, 0.08);
    }

    .cis-invited-card h5 {
        font-size: 16px;
        font-weight: 700;
        color: #103b77;
        margin-bottom: 16px;
    }

    .cis-invited-card table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13px;
    }

    .cis-invited-card thead th {
        background: #e4edff;
        color: #0f3c7c;
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid #dbe4f5;
        padding: 12px 14px;
    }

    .cis-invited-card tbody td {
        border: 1px solid #e1e9f8;
        padding: 12px 14px;
        color: #21497f;
        font-weight: 500;
        background: #ffffff;
    }

    .cis-invited-card tbody tr:nth-child(even) td {
        background: #f8fbff;
    }

    @media (max-width: 991.98px) {
        .cis-wrapper {
            padding: 20px;
        }

        .cis-table {
            --cis-label-width: 180px;
            --cis-value-width: 220px;
        }

        .cis-table-headline h5 {
            font-size: 16px;
        }
    }

    @media (max-width: 575.98px) {
        .cis-meta-card {
            padding: 16px;
        }

        .cis-meta-card strong {
            font-size: 16px;
        }

        .cis-summary-card {
            padding: 16px;
            min-height: auto;
        }
    }
</style>

<div class="container-fluid">
    <div class="cis-wrapper">
        <div class="cis-meta-grid">
            <div class="cis-meta-card">
                <span>Quotation ID</span>
                <strong>{{ $valueOrNA($enquiry->qutation_id ?? $enquiry->enquiry_id ?? null) }}</strong>
            </div>
            <div class="cis-meta-card">
                <span>Buyer / Client</span>
                <strong>{{ $valueOrNA($buyerName) }}</strong>
            </div>
            <div class="cis-meta-card">
                <span>Date</span>
                <strong>{{ $quotationDateLabel }}</strong>
            </div>
            @if($buyerLocationParts)
                <div class="cis-meta-card">
                    <span>Location</span>
                    <strong>{{ $buyerLocationParts }}</strong>
                </div>
            @endif
        </div>

        <div class="cis-table-card">
            <div class="cis-table-headline">
                <h5>Your Exclusive Automated CIS</h5>
                <p>Compare vendor quotations and identify the most competitive offer at a glance.</p>
            </div>
            <div class="cis-table-scroll">
                <table class="cis-table">
                    <thead>
                        <tr>
                            <th scope="col" class="cis-col-label sticky-col sticky-col-label">Attributes</th>
                            <th scope="col" class="cis-col-value sticky-col sticky-col-value">
                                <div class="cis-buyer-header">
                                    <span class="cis-headline">Buyer Details</span>
                                    <span class="cis-subtext">Reference Specification</span>
                                </div>
                            </th>
                            @for ($index = 0; $index < $maxVendorColumns; $index++)
                                @php
                                    $sellerRecord = $sellerRecords->get($index);
                                    $sellerName = optional($sellerRecord)->seller_name ?? 'Vendor ' . ($index + 1);
                                    $sellerContact = optional($sellerRecord)->seller_phone
                                        ?? optional($sellerRecord)->seller_email
                                        ?? null;
                                @endphp
                                <th scope="col">
                                    <div class="cis-vendor-header">
                                        <span class="cis-vendor-badge">Vendor {{ $index + 1 }}</span>
                                        <span class="cis-vendor-name">{{ $valueOrNA($sellerName) }}</span>
                                        <span class="cis-vendor-contact">{{ $valueOrNA($sellerContact) }}</span>
                                    </div>
                                </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comparisonRows as $row)
                            <tr>
                                <th scope="row" class="sticky-col sticky-col-label">{{ $row['label'] }}</th>
                                <td class="text-start sticky-col sticky-col-value">{{ $valueOrNA($row['buyerValue']) }}</td>
                                @for ($index = 0; $index < $maxVendorColumns; $index++)
                                    @php
                                        $sellerRecord = $sellerRecords->get($index);
                                        $sellerValue = $resolveSellerValue($sellerRecord ?? null, $row['key']);
                                        $cellClasses = '';

                                        if ($row['key'] === 'rate' && $sellerRecord && is_numeric($sellerRecord->rate ?? null)) {
                                            $isBestRate = $lowestVendorRate !== null
                                                && abs((float) $sellerRecord->rate - $lowestVendorRate) < 0.0001;

                                            if ($isBestRate) {
                                                $cellClasses = 'is-best';
                                            }
                                        }
                                    @endphp
                                    <td class="{{ trim($cellClasses) }}">{{ $valueOrNA($sellerValue) }}</td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="cis-summary-grid">
            <div class="cis-summary-card">
                <h6>Remarks</h6>
                <p>{{ $valueOrNA($enquiry->qutation_form_message ?? null, 'Nothing specified') }}</p>
            </div>
            <div class="cis-summary-card">
                <h6>Price Basis</h6>
                <p>{{ $valueOrNA($enquiry->material ?? null, 'Not specified') }}</p>
            </div>
            <div class="cis-summary-card">
                <h6>Payment Terms</h6>
                <p>{{ $valueOrNA($enquiry->payment_terms ?? null, 'No payment terms available') }}</p>
            </div>
            <div class="cis-summary-card">
                <h6>Delivery Period (Days)</h6>
                <p>{{ $valueOrNA($enquiry->bid_time ?? null, 'Not available') }}</p>
            </div>
        </div>

        @if(($invitedSellers ?? collect())->isNotEmpty())
            <div class="cis-invited-card">
                <h5>Invited Vendors</h5>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th class="text-start">#</th>
                                <th class="text-start">Seller Name</th>
                                <th class="text-start">Email</th>
                                <th class="text-start">Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invitedSellers as $index => $invitedSeller)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $valueOrNA($invitedSeller->name ?? null) }}</td>
                                    <td>{{ $valueOrNA($invitedSeller->email ?? null) }}</td>
                                    <td>{{ $valueOrNA($invitedSeller->phone ?? null) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
