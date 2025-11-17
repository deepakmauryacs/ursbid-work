@extends('ursbid-admin.layouts.app')
@section('title', 'Seller List with Price')
@section('content')
<div class="container-fluid">
    
    <!-- ========== Page Title Start ========== -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Seller List with Price</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Seller List with Price</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- ========== Page Title End ========== -->

    <div class="social-dash-wrap">
        <div class="row mb-3">
            <div class="col-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0">Filter Price List</h5>
                        <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#priceListFilters" aria-expanded="true" aria-controls="priceListFilters">
                            Toggle Filters
                        </button>
                    </div>
                    <div class="collapse show" id="priceListFilters">
                        <div class="card-body">
                            <form class="row g-3 align-items-end" method="get" action="">
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                    <label class="form-label">Category</label>
                                    <select name="category" id="categoryFilter" class="form-select">
                                        <option value="">All</option>
                                        @foreach($categoryData as $cat)
                                            <option value="{{ $cat->id }}" {{ ($datas['category'] ?? '') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                    <label class="form-label">Sub Category</label>
                                    <select name="subcategory" id="subcategoryFilter" class="form-select">
                                        <option value="">All</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" name="product_name" class="form-control" placeholder="Product Name" value="{{ $datas['product_name'] ?? '' }}">
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                    <label class="form-label">Quotation ID</label>
                                    <input type="text" name="qutation_id" class="form-control" placeholder="Quotation ID" value="{{ $datas['qutation_id'] ?? '' }}">
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                    <label class="form-label">Date</label>
                                    <input type="date" name="date" class="form-control" placeholder="Date" value="{{ $datas['date'] ?? '' }}">
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control" placeholder="City" value="{{ $datas['city'] ?? '' }}">
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                    <label class="form-label">Quantity</label>
                                    <input type="text" name="quantity" class="form-control" placeholder="Quantity" value="{{ $datas['quantity'] ?? '' }}">
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                    <label class="form-label d-none d-lg-block">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-funnel-fill me-2"></i>Apply Filters
                                    </button>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                    <label class="form-label d-none d-lg-block">&nbsp;</label>
                                    <a href="{{ request()->url() }}" class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Filters
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-30">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Qutation ID</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Product Name</th>
                                    <th>Date</th>
                                    <th>File</th>
                                    <th>Unit</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
                                    <th>Total Price</th>
                                    <th>Platform Fee</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($data as $index => $record)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $record->qutation_id }}</td>
                                        <td>{{ $record->seller_name }}</td>
                                        <td>{{ $record->category_name }}</td>
                                        <td>{{ $record->sub_name }}</td>
                                        <td>{{ $record->product_name }}</td>
                                        <td>{{ $record->date_time ? \Carbon\Carbon::parse($record->date_time)->format('Y-m-d') : 'N/A' }}</td>
                                        <td>
                                            @if(!empty($record->bidding_price_filename))
                                                <a href="{{ url('public/uploads/'.$record->bidding_price_filename) }}" target="_blank">View</a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $record->unit }}</td>
                                        <td>{{ $record->quantity }}</td>
                                        <td>{{ $record->rate }}</td>
                                        <td>
                                            @php
                                                $matches = [];
                                                preg_match('/\\d+(?:\\.\\d+)?/', (string) $record->quantity, $matches);
                                                $qty = $matches[0] ?? 0;
                                            @endphp
                                            {{ number_format($qty * (float) $record->rate, 2) }}
                                        </td>
                                        <td>{{ $record->price }}</td>
                                        <td>
                                            <a href="{{ url('seller-profile/'.$record->seller_id) }}" class="btn btn-sm btn-outline-primary">View Profile</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center text-danger">Sorry, no data found!</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const $category = $('#categoryFilter');
        const $subcategory = $('#subcategoryFilter');
        const preselectedSub = @json($datas['subcategory'] ?? '');

        function loadSubCategories(catId, preselect = '') {
            $subcategory.html('<option value="">All</option>');
            if (!catId) {
                return;
            }

            $.get('{{ route('super-admin.products.get-subcategories') }}', {cat_id: catId}, function (res) {
                $subcategory.html('<option value="">All</option>');
                (res || []).forEach(function (item) {
                    const selected = String(preselect) === String(item.id) ? 'selected' : '';
                    $subcategory.append(`<option value="${item.id}" ${selected}>${item.name}</option>`);
                });
            });
        }

        const initialCategory = $category.val();
        if (initialCategory) {
            loadSubCategories(initialCategory, preselectedSub);
        }

        $category.on('change', function () {
            loadSubCategories($(this).val(), '');
        });
    })();
</script>
@endpush
