@extends('seller.layouts.app')
@section('title', 'Comparative Information Statement')

@section('content')
@php
    $records = $records ?? collect();
    $enquiry = $enquiry ?? null;
    $quantityValue = $enquiry->quantity ?? null;
    $unitValue = $enquiry->unit ?? null;
    $quantityLabel = trim(($quantityValue ? (string) $quantityValue : '-') . ' ' . ($unitValue ?? ''));
    $sellerRecords = $records->values();
    $startPrice = $sellerRecords
        ->map(function ($item) {
            return is_numeric($item->rate ?? null) ? (float) $item->rate : null;
        })
        ->filter(fn ($value) => $value !== null)
        ->min();
    $productTitle = $enquiry->product_title
        ?? $enquiry->bidding_price_product_name
        ?? $enquiry->qutation_form_product_name
        ?? '-';
    $productBrand = $enquiry->product_brand ?? $enquiry->qutation_form_product_brand ?? null;
    $specification = $productBrand ?: ($enquiry->material ?? null);
    $maxVendorColumns = max($sellerRecords->count(), 4);
    $responseDeadlineRaw = $enquiry->response_deadline ?? $enquiry->created_at ?? null;
    try {
        $responseDeadlineLabel = $responseDeadlineRaw ? \Illuminate\Support\Carbon::parse($responseDeadlineRaw)->format('d M, Y') : 'N/A';
    } catch (\Exception $exception) {
        $responseDeadlineLabel = is_string($responseDeadlineRaw) ? $responseDeadlineRaw : 'N/A';
    }
    $totalQuoteValue = static function ($record) {
        $rate = is_numeric($record->rate ?? null) ? (float) $record->rate : null;
        if ($rate === null) {
            return null;
        }

        $quantity = 0;
        if (is_numeric($record->quantity ?? null)) {
            $quantity = (float) $record->quantity;
        } elseif (is_string($record->quantity ?? null) && preg_match('/\d+(?:\.\d+)?/', $record->quantity, $matches)) {
            $quantity = (float) ($matches[0] ?? 0);
        }

        return $quantity > 0 ? $quantity * $rate : $rate;
    };
@endphp
<style>
    .cis-wrapper {
        background: #f5f8fc;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 12px 32px rgba(15, 70, 140, 0.08);
    }

    .cis-toolbar {
        border: 1px solid #dbe5f4;
        border-radius: 10px;
        background: #fff;
        padding: 12px 16px;
    }

    .cis-toolbar .title-badge {
        font-weight: 600;
        color: #0a4a8c;
        background: #e8f1ff;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 13px;
    }

    .cis-toolbar .filter-chip {
        background: linear-gradient(135deg, #ffffff, #f3f7ff);
        border: 1px solid #d8e4f8;
        border-radius: 999px;
        padding: 6px 14px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #32527a;
        cursor: pointer;
    }

    .cis-toolbar .filter-chip i {
        color: #0a4a8c;
    }

    .cis-alert {
        margin-top: 16px;
        padding: 12px 16px;
        background: linear-gradient(90deg, rgba(255,255,255,0.95), rgba(242,246,255,0.95));
        border: 1px dashed #ff9c9c;
        border-radius: 10px;
        color: #0b4685;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .cis-alert span {
        color: #ff4f4f;
        font-weight: 700;
    }

    .cis-table-wrapper {
        margin-top: 24px;
        background: #fff;
        border-radius: 12px;
        border: 1px solid #dbe5f4;
        overflow: hidden;
    }

    .cis-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13px;
    }

    .cis-table thead th {
        background: #f0f6ff;
        color: #0a4a8c;
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 700;
        text-align: center;
        border-bottom: 1px solid #d1def5;
        padding: 14px 10px;
    }

    .cis-table tbody th,
    .cis-table tbody td {
        border-bottom: 1px solid #e4ecf8;
        border-right: 1px solid #e4ecf8;
        padding: 12px 10px;
        background: #fff;
    }

    .cis-table tbody tr:nth-child(odd) th,
    .cis-table tbody tr:nth-child(odd) td {
        background: #f9fbff;
    }

    .cis-table tbody th {
        font-weight: 700;
        color: #0a4a8c;
        background: #f1f6ff;
    }

    .cis-table tbody td.text-center {
        text-align: center;
    }

    .cis-table .vendor-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }

    .cis-table .vendor-badge {
        background: #e7f2ff;
        color: #0a4a8c;
        border-radius: 6px;
        padding: 4px 8px;
        font-weight: 600;
        font-size: 12px;
    }

    .cis-table .highlight {
        background: #fffaf1 !important;
        color: #c9820f;
        font-weight: 700;
    }

    .cis-table tfoot td {
        background: #e7f2ff;
        font-weight: 700;
        color: #0a4a8c;
    }

    .cis-summary {
        margin-top: 24px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .cis-summary-card {
        background: linear-gradient(180deg, #ffffff, #f7f9ff);
        border: 1px solid #dbe5f4;
        border-radius: 12px;
        padding: 18px;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .cis-summary-card h6 {
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: .08em;
        color: #6c87b5;
        margin-bottom: 0;
    }

    .cis-summary-card p {
        margin-bottom: 0;
        font-weight: 600;
        color: #0a3d78;
        font-size: 14px;
    }

    @media (max-width: 991.98px) {
        .cis-wrapper {
            padding: 16px;
        }

        .cis-toolbar {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }

        .cis-table {
            font-size: 12px;
        }
    }
</style>
<div class="container-fluid">
    <div class="cis-wrapper">
        <div class="cis-toolbar d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="title-badge">RFQ No - {{ $enquiry->qutation_id ?? $enquiry->enquiry_id ?? 'N/A' }}</span>
                <span class="text-muted small">Branch Unit Details: {{ $enquiry->branch_name ?? 'N/A' }}</span>
                <span class="text-muted small">Last Date to Respond: {{ $responseDeadlineLabel }}</span>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="filter-chip"><i class="bi bi-funnel"></i>Sort by Price</span>
                <span class="filter-chip"><i class="bi bi-calendar3"></i>Sort by Date</span>
                <span class="filter-chip"><i class="bi bi-heart"></i>Select Favourite</span>
                <span class="filter-chip"><i class="bi bi-clock-history"></i>Select Last Vendor</span>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.location.reload();">Reset</button>
                    <a href="{{ route('buyer.price-list', $dataId) }}" class="btn btn-sm btn-primary">Back</a>
                </div>
            </div>
        </div>

        <div class="cis-alert mt-3">
            <div>Your Exclusive Automated CIS <span>(To access more offers, click the Reset button and add CIS ID here)</span></div>
            <div class="text-uppercase text-muted small">General &nbsp; <strong>•</strong> &nbsp; Fastener</div>
        </div>

        <div class="cis-table-wrapper mt-4">
            <div class="table-responsive">
                <table class="cis-table">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-start">Product</th>
                            <th rowspan="2" class="text-start">Specifications</th>
                            <th rowspan="2" class="text-start">Size</th>
                            <th rowspan="2" class="text-start">Quantity / UOM</th>
                            @for($index = 0; $index < $maxVendorColumns; $index++)
                                @php
                                    $sellerRecord = $sellerRecords->get($index);
                                @endphp
                                <th class="text-center">
                                    <div class="vendor-header">
                                        <span class="vendor-badge">Vendor {{ $index + 1 }}</span>
                                        <span class="fw-semibold">{{ optional($sellerRecord)->seller_name ?? 'N/A' }}</span>
                                        <small class="text-muted">Quotation Page</small>
                                    </div>
                                </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Product</th>
                            <td>{{ $productTitle }}</td>
                            <td>{{ $enquiry->size ?? '-' }}</td>
                            <td>{{ $quantityLabel }}</td>
                            @for($index = 0; $index < $maxVendorColumns; $index++)
                                @php
                                    $sellerRecord = $sellerRecords->get($index);
                                    $sellerProduct = optional($sellerRecord)->product_title ?? optional($sellerRecord)->bidding_price_product_name;
                                @endphp
                                <td class="text-center">{{ $sellerProduct ?? '-' }}</td>
                            @endfor
                        </tr>
                        <tr>
                            <th>Specifications</th>
                            <td>{{ $specification ?? 'Not specified' }}</td>
                            <td>{{ $enquiry->grade ?? '-' }}</td>
                            <td>{{ $enquiry->material ?? '-' }}</td>
                            @for($index = 0; $index < $maxVendorColumns; $index++)
                                @php
                                    $sellerRecord = $sellerRecords->get($index);
                                    $sellerSpec = optional($sellerRecord)->product_brand ?? optional($sellerRecord)->material;
                                @endphp
                                <td class="text-center">{{ $sellerSpec ?? 'Not specified' }}</td>
                            @endfor
                        </tr>
                        <tr>
                            <th>Counter Offer</th>
                            <td colspan="3">&nbsp;</td>
                            @for($index = 0; $index < $maxVendorColumns; $index++)
                                @php
                                    $sellerRecord = $sellerRecords->get($index);
                                    $counter = optional($sellerRecord)->counter_offer;
                                @endphp
                                <td class="text-center fw-semibold text-success">
                                    {{ $counter ? number_format((float) $counter, 2) : '-' }}
                                </td>
                            @endfor
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td class="highlight">Start Price</td>
                            <td class="highlight">{{ $startPrice !== null ? number_format($startPrice, 2) : 'N/A' }}</td>
                            <td class="highlight">{{ $quantityLabel }}</td>
                            @for($index = 0; $index < $maxVendorColumns; $index++)
                                @php
                                    $sellerRecord = $sellerRecords->get($index);
                                    $rate = optional($sellerRecord)->rate;
                                @endphp
                                <td class="text-center highlight">
                                    {{ is_numeric($rate) ? number_format((float) $rate, 2) : 'N/A' }}
                                </td>
                            @endfor
                        </tr>
                        <tr>
                            <th>Quote Value</th>
                            <td colspan="3">{{ $startPrice !== null && is_numeric($quantityValue) ? number_format((float) $quantityValue * (float) $startPrice, 2) : 'N/A' }}</td>
                            @for($index = 0; $index < $maxVendorColumns; $index++)
                                @php
                                    $sellerRecord = $sellerRecords->get($index);
                                    $totalValue = $sellerRecord ? $totalQuoteValue($sellerRecord) : null;
                                @endphp
                                <td class="text-center">
                                    {{ $totalValue !== null ? number_format($totalValue, 2) : 'N/A' }}
                                </td>
                            @endfor
                        </tr>
                        <tr>
                            <th>Platform Fee</th>
                            <td colspan="3">{{ $enquiry->platform_fee ?? 'N/A' }}</td>
                            @for($index = 0; $index < $maxVendorColumns; $index++)
                                @php
                                    $sellerRecord = $sellerRecords->get($index);
                                    $platformFee = optional($sellerRecord)->platform_fee;
                                @endphp
                                <td class="text-center">{{ is_numeric($platformFee) ? number_format((float) $platformFee, 2) : 'N/A' }}</td>
                            @endfor
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="cis-summary">
            <div class="cis-summary-card">
                <h6>Remarks</h6>
                <p>{{ $enquiry->qutation_form_message ?? 'Nothing' }}</p>
            </div>
            <div class="cis-summary-card">
                <h6>Price Basis</h6>
                <p>{{ $enquiry->qutation_form_material ?? $enquiry->material ?? 'Not specified' }}</p>
            </div>
            <div class="cis-summary-card">
                <h6>Payment Terms</h6>
                <p>{{ $enquiry->payment_terms ?? 'No payment terms' }}</p>
            </div>
            <div class="cis-summary-card">
                <h6>Delivery Period (In Days)</h6>
                <p>{{ $enquiry->bid_time ?? 'Not available' }}</p>
            </div>
        </div>

        @if(($invitedSellers ?? collect())->isNotEmpty())
            <div class="cis-table-wrapper mt-4">
                <div class="table-responsive">
                    <table class="cis-table">
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
                                    <td>{{ $invitedSeller->name ?? 'N/A' }}</td>
                                    <td>{{ $invitedSeller->email ?? 'N/A' }}</td>
                                    <td>{{ $invitedSeller->phone ?? 'N/A' }}</td>
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
