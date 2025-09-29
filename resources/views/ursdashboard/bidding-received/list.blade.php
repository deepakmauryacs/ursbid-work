@extends('seller.layouts.app')
@section('title', 'Bidding Received')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@php
    $sellerEmail = $seller->email ?? ($seller['email'] ?? '');
    $filters = $filters ?? ($datas ?? []);
    $categories = $category_data ?? [];
    $records = $records ?? ($data ?? collect());
    $buyerOrderBaseUrl = route('buyer-dashboard');
@endphp
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Bidding Received</h4>
                </div>
            </div>
        </div>

        @include('ursdashboard.bidding-received.partials.bidding-modal', ['sellerEmail' => $sellerEmail])

        <div class="row">
            <div class="col-lg-12 mb-30">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="mb-0">Filter Bids</h5>
                        <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" data-bs-toggle="collapse"
                            data-bs-target="#buyerOrderFilters" aria-expanded="true" aria-controls="buyerOrderFilters">
                            Toggle Filters
                        </button>
                    </div>

                    <div class="collapse show" id="buyerOrderFilters">
                        <div class="card-body">
                            @include('ursdashboard.bidding-received.partials.filters', [
                                'action' => $buyerOrderBaseUrl,
                                'filters' => $filters,
                                'categories' => $categories,
                            ])
                        </div>
                    </div>
                </div>

                @include('ursdashboard.bidding-received.partials.alerts')

                <div class="card shadow-sm">
                    <div class="card-body">
                        <div id="buyerOrderTable">
                            @include('ursdashboard.bidding-received.partials.table', [
                                'records' => $records,
                                'filters' => $filters,
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('ursdashboard.bidding-received.partials.scripts')
@endsection
