@extends('seller.layouts.app')
@section('title', 'My Bidding')

@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">My Bidding</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-30">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="mb-0">Filter My Bids</h5>
                        <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" data-bs-toggle="collapse"
                            data-bs-target="#myBiddingFilters" aria-expanded="true" aria-controls="myBiddingFilters">
                            Toggle Filters
                        </button>
                    </div>

                    <div class="collapse show" id="myBiddingFilters">
                        <div class="card-body">
                            @php
                                $filters = $filters ?? [];
                                $categories = $category_data ?? [];
                            @endphp
                            <form id="myBiddingFiltersForm" class="row g-3 align-items-end" method="get" action="{{ route('buyer-order.mylist') }}">
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Category</label>
                                    <select name="category" id="myBiddingCategory" class="form-select">
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

                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Quotation ID</label>
                                    <input type="text" name="qutation_id" id="myBiddingQuotation" class="form-control" placeholder="Quotation ID"
                                        value="{{ $filters['qutation_id'] ?? '' }}">
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Date</label>
                                    <input type="text" name="date" id="myBiddingDate" class="form-control" placeholder="Date"
                                        value="{{ $filters['date'] ?? '' }}">
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" id="myBiddingCity" class="form-control" placeholder="City"
                                        value="{{ $filters['city'] ?? '' }}">
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Quantity</label>
                                    <input type="text" name="quantity" id="myBiddingQuantity" class="form-control" placeholder="Quantity"
                                        value="{{ $filters['quantity'] ?? '' }}">
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" name="product_name" id="myBiddingProduct" class="form-control" placeholder="Product Name"
                                        value="{{ $filters['product_name'] ?? '' }}">
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label">Records Per Page</label>
                                    @php
                                        $perPage = (int) ($filters['r_page'] ?? 25);
                                    @endphp
                                    <select name="r_page" id="myBiddingRecords" class="form-select">
                                        <option value="25" {{ $perPage === 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $perPage === 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ $perPage === 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="form-label d-none d-lg-block">&nbsp;</label>
                                    <div class="d-flex flex-column flex-sm-row flex-lg-column flex-xl-row gap-2">
                                        <button type="submit" class="btn btn-primary w-100 flex-fill">
                                            <i class="bi bi-funnel-fill me-2"></i>Apply
                                        </button>
                                        <button type="button" id="resetMyBiddingFilters" class="btn btn-outline-secondary w-100 flex-fill">
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
                        <div id="myBiddingTable">
                            @include('ursdashboard.my-bidding.partials.table', [
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
        const $form = $('#myBiddingFiltersForm');
        const $recordsPerPage = $('#myBiddingRecords');
        const $resetButton = $('#resetMyBiddingFilters');

        $recordsPerPage.on('change', function () {
            $form.trigger('submit');
        });

        $resetButton.on('click', function () {
            $form.find('input[type="text"]').val('');
            $form.find('select').prop('selectedIndex', 0);
            $form.trigger('submit');
        });
    })(jQuery);
</script>
@endpush
@endsection
