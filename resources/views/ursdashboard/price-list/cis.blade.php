@extends('seller.layouts.app')
@section('title', 'Comparative Information Statement')

@section('content')
@php
    $records = ($records ?? collect())->values();
    $enquiry = $enquiry ?? null;
    $sellerRecords = $records
        ->sortBy(fn($record) => is_numeric(optional($record)->rate) ? (float) $record->rate : INF)
        ->values();
    $maxVendorColumns = max($sellerRecords->count(), 2);

    $productTitle = $enquiry->product_title
        ?? $sellerRecords->pluck('product_name')->filter(fn($name) => filled($name))->first()
        ?? '-';
    $productBrand = $enquiry->product_brand ?? null;
    $quantityValue = $enquiry->quantity ?? null;
    $unitValue = $enquiry->unit ?? null;
    $startPrice = $sellerRecords->pluck('rate')->filter(fn($r)=>is_numeric($r))->min();

    $buyerName = $enquiry->name ?? $enquiry->buyer_name ?? 'N/A';
    $quotationDateRaw = $enquiry->date_time ?? $enquiry->created_at ?? null;
    try {
        $quotationDateLabel = $quotationDateRaw
            ? \Illuminate\Support\Carbon::parse($quotationDateRaw)->format('d/m/Y')
            : 'N/A';
    } catch (\Exception $e) {
        $quotationDateLabel = is_string($quotationDateRaw) ? $quotationDateRaw : 'N/A';
    }

    $valueOrNA = fn($v, $d='-') => (is_null($v) || trim((string)$v)=='') ? $d : $v;
@endphp

<style>
    .vendor-nav-btn {
        background: #fff;
        border: 1px solid #ccc;
        padding: 5px 10px;
        margin: 0 3px;
        transition: 0.2s;
    }
    .vendor-nav-btn:hover { background: #f0f0f0; }
    .forward-action-cis-table th,
    .forward-action-cis-table td {
        vertical-align: middle;
        font-size: 14px;
        text-align: center;
    }
    .vendor-subtitle {
        margin-top: 4px;
        font-size: 12px;
        color: #555;
    }
    .border-dark th, .border-dark td {
        border-color: #000 !important;
    }
    .custom-ul {
     list-style-type: none ;
    }
    .custom-ul {
        padding-left: 0rem;
    }
</style>

<div class="container-fluid">
    <section class="card rounded">
        <div class="card-header bg-white border-0">
            <div class="row gy-3 justify-content-between align-items-center py-3 px-0 px-md-3 mb-30">
                <div class="col-12 col-lg-auto flex-grow-1 order-2 order-lg-1">
                    <h1 class="text-primary-blue font-size-18 fw-bold mb-0">
                        Comparative Information Statement
                    </h1>
                </div>

                <!-- Export -->
                <div class="col-12 col-lg-auto order-1 order-lg-2">
                    <div class="header-actions d-flex align-items-center justify-content-end">
                        <a href="{{ url('buyer/buyer/forward-auction/export-cis/' . ($enquiry->qutation_id ?? '')) }}"
                           class="ra-btn ra-btn-success px-2 font-size-11 d-inline-flex align-items-center text-nowrap">
                            <span class="bi bi-download font-size-12 me-1"></span> Export
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <!-- Auction Info -->
            <div class="forward-auction-cis cis-info pb-2 px-0 px-md-3 d-sm-flex">
                <div class="d-flex cis-info-left align-items-center">
                    <ul class="mb-0 d-flex flex-wrap align-items-center custom-ul" style="gap: 12px;">
                        <li><strong>Quotation ID: </strong>{{ $valueOrNA($enquiry->qutation_id ?? $enquiry->enquiry_id ?? null) }}</li>
                        <li><strong>Date: </strong>{{ $quotationDateLabel }}</li>
                        <li><strong>Buyer / Client: </strong>{{ $valueOrNA($buyerName) }}</li>
                        <li>
                            <a href="javascript:void(0);" class="ra-btn btn-outline-primary ra-btn-outline-primary d-inline-flex text-uppercase text-nowrap font-size-11" onclick="location.reload();">
                                <span class="bi bi-arrow-clockwise font-size-11 me-1"></span> Refresh
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main CIS details -->
            <div class="cis-details py-3 px-0 px-md-3">
                <div class="row g-0 gy-4">
                    <!-- Vendor Scroll -->
                    <div class="vendor-scroll ms-2 d-flex align-items-center" style="justify-content: flex-end;">
                        <button type="button" id="vendorsLeft" class="vendor-nav-btn" title="Scroll left">
                            <i class="bi bi-arrow-left"></i>
                        </button>
                        <button type="button" id="vendorsRight" class="vendor-nav-btn" title="Scroll right">
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>

                    <!-- Product Table -->
                    <div class="col-12 col-lg-7 px-1">
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table id="productTable" class="table table-bordered border-dark forward-action-cis-table" style="white-space:nowrap;">
                                <thead>
                                    <tr class="h-140">
                                        <th style="height:140px;">Product</th>
                                        <th style="height:140px;">Specs</th>
                                        <th style="height:140px;">Quantity/UOM</th>
                                        <th style="height:140px;">Start Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $valueOrNA($productTitle) }}</td>
                                        <td>{{ $valueOrNA($productBrand) }}</td>
                                        <td>{{ $valueOrNA($quantityValue) }} {{ $valueOrNA($unitValue) }}</td>
                                        <td>{{ $startPrice ? number_format($startPrice, 2) : '-' }}</td>
                                    </tr>
                                    <tr><td colspan="4" class="text-end">Total</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Vendor Table -->
                    <div class="col-12 col-lg-5 px-1">
                        <div id="vendorTableWrapper" class="table-responsive" style="overflow-x:auto;">
                            <table id="vendorTable" class="table table-bordered border-dark" style="white-space:nowrap;">
                                <thead>
                                    <tr class="h-140">
                                        @for ($i = 0; $i < $maxVendorColumns; $i++)
                                            @php
                                                $seller = $sellerRecords->get($i);
                                                $sName = optional($seller)->seller_name ?? 'Vendor '.($i+1);
                                                $sContact = optional($seller)->seller_phone ?? '-';
                                            @endphp
                                            <th style="min-width:220px; text-align:center; padding:0;">
                                                <div style="height:140px;display:flex;flex-direction:column;justify-content:center;align-items:center;padding:0 .5rem;">
                                                    <span style="font-weight:600;">{{ $sName }}</span>
                                                    <small style="font-weight:500;">(M: {{ $sContact }})</small>
                                                    <div class="vendor-subtitle">Rate Per Unit (<small>₹</small>)</div>
                                                </div>
                                            </th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @for ($i = 0; $i < $maxVendorColumns; $i++)
                                            <td class="text-center">
                                                {{ is_numeric(optional($sellerRecords->get($i))->rate) ? number_format(optional($sellerRecords->get($i))->rate, 2) : '-' }}
                                            </td>
                                        @endfor
                                    </tr>
                                    <tr>
                                        @for ($i = 0; $i < $maxVendorColumns; $i++)
                                            <td class="text-center">₹0.00</td>
                                        @endfor
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Remarks -->
                <div class="row g-2 mt-2">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered border-dark mb-0">
                                <tbody>
                                    <tr><th>Remarks (Message)</th><td>{{ $valueOrNA($enquiry->qutation_form_message ?? null) }}</td></tr>
                                    <tr><th>Material</th><td>{{ $valueOrNA($enquiry->material ?? null) }}</td></tr>
                                    <tr><th>Delivery Period (In Days)</th><td>{{ $valueOrNA($enquiry->bid_time ?? null) }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    const leftBtn = document.getElementById('vendorsLeft');
    const rightBtn = document.getElementById('vendorsRight');
    const wrapper = document.getElementById('vendorTableWrapper');

    leftBtn?.addEventListener('click', () => wrapper.scrollBy({ left: -200, behavior: 'smooth' }));
    rightBtn?.addEventListener('click', () => wrapper.scrollBy({ left: 200, behavior: 'smooth' }));
</script>
@endsection
 