@extends('seller.layouts.app')
@section('title', 'Bidding Received')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@php
    $sellerEmail = $seller->email ?? ($seller['email'] ?? '');
    $filters = $filters ?? ($datas ?? []);
    $categories = $category_data ?? [];
    $records = $records ?? ($data ?? collect());
    $buyerOrderBaseUrl = route('buyer.bidding-received.list');
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
                            @php
                                $filters = $filters ?? [];
                                $categories = $categories ?? [];
                            @endphp
                            <form id="buyerOrderFiltersForm" class="row g-3 align-items-end" method="get" action="{{ $buyerOrderBaseUrl }}">

                                <!-- Category -->
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Category</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-tags"></i></span>
                                        <select name="category" id="buyerOrderCategory" class="form-select">
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

                                <!-- Date -->
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Date</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                                        <input type="date" name="date" id="buyerOrderDate" class="form-control"
                                            value="{{ $filters['date'] ?? '' }}">
                                    </div>
                                </div>

                                <!-- City -->
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">City</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                        <input type="text" name="city" id="buyerOrderCity" class="form-control" placeholder="City"
                                            value="{{ $filters['city'] ?? '' }}">
                                    </div>
                                </div>

                                <!-- Quantity -->
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Quantity</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                                        <input type="number" name="quantity" id="buyerOrderQuantity" class="form-control" placeholder="Quantity"
                                            value="{{ $filters['quantity'] ?? '' }}" min="1" step="1" inputmode="numeric">
                                    </div>
                                </div>

                                <!-- Product Name -->
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Product Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-bag"></i></span>
                                        <input type="text" name="product_name" id="buyerOrderProduct" class="form-control" placeholder="Product Name"
                                            value="{{ $filters['product_name'] ?? '' }}">
                                    </div>
                                </div>

                                <!-- Quotation ID -->
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Quotation ID</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-file-earmark-text"></i></span>
                                        <input type="text" name="qutation_id" id="buyerOrderQuotation" class="form-control" placeholder="Quotation ID"
                                            value="{{ $filters['qutation_id'] ?? '' }}">
                                    </div>
                                </div>

                                <!-- Records Per Page -->
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Records Per Page</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-list-ol"></i></span>
                                        @php $perPage = (int) ($filters['r_page'] ?? 25); @endphp
                                        <select name="r_page" id="buyerOrderRecords" class="form-select">
                                            <option value="25" {{ $perPage === 25 ? 'selected' : '' }}>25</option>
                                            <option value="50" {{ $perPage === 50 ? 'selected' : '' }}>50</option>
                                            <option value="100" {{ $perPage === 100 ? 'selected' : '' }}>100</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label d-none d-lg-block">&nbsp;</label>
                                    <div class="d-flex flex-column flex-sm-row flex-lg-column flex-xl-row gap-2">
                                        <button type="submit" class="btn btn-primary w-100 flex-fill">
                                            <i class="bi bi-funnel-fill me-2"></i>Apply
                                        </button>
                                        <button type="button" id="resetBuyerOrderFilters" class="btn btn-outline-secondary w-100 flex-fill">
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
@push('scripts')
<script type="text/javascript">
    (function ($) {
        const $form = $('#buyerOrderFiltersForm');
        const $recordsPerPage = $('#buyerOrderRecords');
        const $resetButton = $('#resetBuyerOrderFilters');

        $recordsPerPage.on('change', function () {
            $form.trigger('submit');
        });

        $resetButton.on('click', function () {
            $form.find('input[type="text"], input[type="date"], input[type="number"]').val('');
            $form.find('select').prop('selectedIndex', 0);
            $form.trigger('submit');
        });

        // Fill modal fields on "Bid" button click
        $(document).on('click', '.mdl_btn', function () {
            const $button = $(this);
            $('.product_id').val($button.data('product_id'));
            $('.product_name').val($button.data('product_name'));
            $('.user_email').val($button.data('user_email'));
            $('.data_id').val($button.data('data_id'));
        });
    })(jQuery);
</script>
@endpush
@endsection
