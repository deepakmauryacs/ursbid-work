@extends('seller.layouts.app')
@section('title', 'Comparative Information Statement')

@section('content')
@php
    $records = $records ?? collect();
    $enquiry = $enquiry ?? null;
    $quantityValue = $enquiry->quantity ?? null;
    $unitValue = $enquiry->unit ?? null;
    $quantityLabel = trim(($quantityValue ? (string) $quantityValue : '-') . ' ' . ($unitValue ?? ''));
    $topSellers = $records->take(2);
    $startPrice = $records->map(function ($item) {
        return is_numeric($item->rate ?? null) ? (float) $item->rate : null;
    })->filter(fn ($value) => $value !== null)->min();
    $productTitle = $enquiry->product_title
        ?? $enquiry->bidding_price_product_name
        ?? $enquiry->qutation_form_product_name
        ?? '-';
    $productBrand = $enquiry->product_brand ?? $enquiry->qutation_form_product_brand ?? null;
    $specification = $productBrand ?: ($enquiry->material ?? null);
@endphp
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h4 class="text-capitalize breadcrumb-title mb-1">Forward Auction - Comparative Information Statement</h4>
                        @if(!empty($enquiry->qutation_id))
                            <small class="text-muted">Auction ID: {{ $enquiry->qutation_id }}</small>
                        @endif
                    </div>
                    <div class="breadcrumb-action d-flex flex-wrap gap-2">
                        <a href="{{ route('buyer.price-list', $dataId) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left-short me-1"></i>Back
                        </a>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.location.reload();">
                            <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                        </button>
                        <button type="button" class="btn btn-success btn-sm">
                            <i class="bi bi-hand-thumbs-up-fill me-1"></i>Like
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Details</th>
                                        <th scope="col">Information</th>
                                        @foreach($topSellers as $index => $sellerRecord)
                                            <th scope="col" class="text-center">
                                                Seller {{ $index + 1 }}<br>
                                                <small class="text-muted">{{ $sellerRecord->seller_name ?? 'N/A' }}</small>
                                            </th>
                                        @endforeach
                                        @if($topSellers->count() === 1)
                                            <th scope="col" class="text-center text-muted">Seller 2<br><small>N/A</small></th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row" class="fw-semibold">Product</th>
                                        <td>{{ $productTitle }}</td>
                                        @foreach($topSellers as $sellerRecord)
                                            <td class="text-center">{{ $sellerRecord->product_title ?? $sellerRecord->bidding_price_product_name ?? '-' }}</td>
                                        @endforeach
                                        @if($topSellers->count() === 1)
                                            <td class="text-center text-muted">-</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th scope="row" class="fw-semibold">Specs</th>
                                        <td>{{ $specification ?? 'Not specified' }}</td>
                                        @foreach($topSellers as $sellerRecord)
                                            <td class="text-center">{{ $sellerRecord->product_brand ?? $sellerRecord->material ?? 'Not specified' }}</td>
                                        @endforeach
                                        @if($topSellers->count() === 1)
                                            <td class="text-center text-muted">Not specified</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th scope="row" class="fw-semibold">Quantity / UOM</th>
                                        <td>{{ $quantityLabel }}</td>
                                        @foreach($topSellers as $sellerRecord)
                                            <td class="text-center">{{ trim(($sellerRecord->quantity ?? '-') . ' ' . ($sellerRecord->unit ?? '')) }}</td>
                                        @endforeach
                                        @if($topSellers->count() === 1)
                                            <td class="text-center text-muted">-</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th scope="row" class="fw-semibold">Start Price</th>
                                        <td>{{ $startPrice !== null ? number_format($startPrice, 2) : 'N/A' }}</td>
                                        @foreach($topSellers as $sellerRecord)
                                            <td class="text-center">{{ is_numeric($sellerRecord->rate ?? null) ? number_format((float) $sellerRecord->rate, 2) : 'N/A' }}</td>
                                        @endforeach
                                        @if($topSellers->count() === 1)
                                            <td class="text-center text-muted">N/A</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th scope="row" class="fw-semibold">Quote Value</th>
                                        <td>{{ $startPrice !== null && is_numeric($quantityValue) ? number_format((float) $quantityValue * (float) $startPrice, 2) : 'N/A' }}</td>
                                        @foreach($topSellers as $sellerRecord)
                                            <td class="text-center">
                                                @php
                                                    $rate = is_numeric($sellerRecord->rate ?? null) ? (float) $sellerRecord->rate : null;
                                                    $quantity = 0;
                                                    if (is_numeric($sellerRecord->quantity ?? null)) {
                                                        $quantity = (float) $sellerRecord->quantity;
                                                    } elseif (is_string($sellerRecord->quantity ?? null) && preg_match('/\d+(?:\.\d+)?/', $sellerRecord->quantity, $matches)) {
                                                        $quantity = (float) ($matches[0] ?? 0);
                                                    }
                                                @endphp
                                                {{ $rate !== null ? number_format($quantity * $rate, 2) : 'N/A' }}
                                            </td>
                                        @endforeach
                                        @if($topSellers->count() === 1)
                                            <td class="text-center text-muted">N/A</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th scope="row" class="fw-semibold">Platform Fee</th>
                                        <td>{{ $enquiry->platform_fee ?? 'N/A' }}</td>
                                        @foreach($topSellers as $sellerRecord)
                                            <td class="text-center">{{ is_numeric($sellerRecord->platform_fee ?? null) ? number_format((float) $sellerRecord->platform_fee, 2) : 'N/A' }}</td>
                                        @endforeach
                                        @if($topSellers->count() === 1)
                                            <td class="text-center text-muted">N/A</td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-3">
                                <div class="border rounded p-3 h-100">
                                    <h6 class="text-uppercase text-muted fs-12 mb-2">Remarks</h6>
                                    <p class="mb-0">{{ $enquiry->qutation_form_message ?? 'Nothing' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3 h-100">
                                    <h6 class="text-uppercase text-muted fs-12 mb-2">Price Basis</h6>
                                    <p class="mb-0">{{ $enquiry->qutation_form_material ?? $enquiry->material ?? 'Not specified' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3 h-100">
                                    <h6 class="text-uppercase text-muted fs-12 mb-2">Payment Terms</h6>
                                    <p class="mb-0">{{ $enquiry->payment_terms ?? 'Not mentioned' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3 h-100">
                                    <h6 class="text-uppercase text-muted fs-12 mb-2">Delivery Period (In Days)</h6>
                                    <p class="mb-0">{{ $enquiry->bid_time ?? 'Not available' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
