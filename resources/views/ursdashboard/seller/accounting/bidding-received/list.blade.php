@extends('seller.layouts.app')
@section('title', 'Bidding Received')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@php
    $sellerEmail = $seller->email ?? ($seller['email'] ?? '');
    $filters = $filters ?? [];
    $categories = $category_data ?? collect();
    $records = $records ?? collect();
    $listingUrl = route('seller.accounting.biddrecive');
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

        <div class="row">
            <div class="col-lg-12 mb-30">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="mb-0">Filter Bids</h5>
                        <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" data-bs-toggle="collapse"
                            data-bs-target="#sellerBiddingFilters" aria-expanded="true" aria-controls="sellerBiddingFilters">
                            Toggle Filters
                        </button>
                    </div>

                    <div class="collapse show" id="sellerBiddingFilters">
                        <div class="card-body">
                            <form id="sellerBiddingFiltersForm" class="row g-3 align-items-end" method="get" action="{{ $listingUrl }}">
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Keyword</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input type="text" name="keyword" class="form-control" placeholder="Search keyword"
                                            value="{{ $filters['keyword'] ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Category</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-tags"></i></span>
                                        <select name="category" class="form-select">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $cat)
                                                @php
                                                    $categoryId = is_object($cat) ? ($cat->id ?? null) : ($cat['id'] ?? null);
                                                    $categoryLabel = is_object($cat)
                                                        ? ($cat->name ?? $cat->title ?? '')
                                                        : ($cat['name'] ?? $cat['title'] ?? '');
                                                @endphp
                                                <option value="{{ $categoryId }}" {{ ($filters['category'] ?? '') == $categoryId ? 'selected' : '' }}>
                                                    {{ $categoryLabel }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Date</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                                        <input type="date" name="date" class="form-control"
                                            value="{{ $filters['date'] ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">City</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                        <input type="text" name="city" class="form-control" placeholder="City"
                                            value="{{ $filters['city'] ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Quantity</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                                        <input type="text" name="quantity" class="form-control" placeholder="Quantity"
                                            value="{{ $filters['quantity'] ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Product Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-bag"></i></span>
                                        <input type="text" name="product_name" class="form-control" placeholder="Product Name"
                                            value="{{ $filters['product_name'] ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Quotation ID</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-file-earmark-text"></i></span>
                                        <input type="text" name="qutation_id" class="form-control" placeholder="Quotation ID"
                                            value="{{ $filters['qutation_id'] ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Records Per Page</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-list-ol"></i></span>
                                        @php $perPage = (int) ($filters['r_page'] ?? 25); @endphp
                                        <select name="r_page" id="sellerBiddingRecords" class="form-select">
                                            <option value="25" {{ $perPage === 25 ? 'selected' : '' }}>25</option>
                                            <option value="50" {{ $perPage === 50 ? 'selected' : '' }}>50</option>
                                            <option value="100" {{ $perPage === 100 ? 'selected' : '' }}>100</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label d-none d-lg-block">&nbsp;</label>
                                    <div class="d-flex flex-column flex-sm-row flex-lg-column flex-xl-row gap-2">
                                        <button type="submit" class="btn btn-primary w-100 flex-fill">
                                            <i class="bi bi-funnel-fill me-2"></i>Apply
                                        </button>
                                        <button type="button" id="resetSellerBiddingFilters" class="btn btn-outline-secondary w-100 flex-fill">
                                            <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ Session::get('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ Session::get('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-body">
                        <div id="sellerBiddingTable">
                            @include('ursdashboard.seller.accounting.bidding-received.partials.table', [
                                'records' => $records,
                                'filters' => $filters,
                                'sellerEmail' => $sellerEmail,
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script type="text/javascript">
    (function ($) {
        const $form = $('#sellerBiddingFiltersForm');
        const $recordsPerPage = $('#sellerBiddingRecords');
        const $resetButton = $('#resetSellerBiddingFilters');

        $recordsPerPage.on('change', function () {
            $form.trigger('submit');
        });

        $resetButton.on('click', function () {
            $form.find('input[type="text"], input[type="date"]').val('');
            $form.find('select').prop('selectedIndex', 0);
            $form.trigger('submit');
        });
    })(jQuery);
</script>
@endpush
@endsection
