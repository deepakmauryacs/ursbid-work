@php
    $records = $records ?? collect();
    if (!($records instanceof \Illuminate\Support\Collection)) {
        $records = collect($records);
    }

    $filters = $filters ?? [
        'category' => request('category'),
        'sub_category' => request('sub_category'),
        'product_name' => request('product_name'),
        'quotation_id' => request('quotation_id'),
        'date' => request('date'),
        'city' => request('city'),
        'quantity' => request('quantity'),
    ];

    $filterOptions = $filterOptions ?? [
        'categories' => collect(),
        'subCategories' => collect(),
        'products' => collect(),
        'dates' => collect(),
        'cities' => collect(),
        'quantities' => collect(),
    ];

    $hasRecords = $records->isNotEmpty();
@endphp
<div class="card mb-4 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center border-bottom">
        <h5 class="mb-0">Filter Bids</h5>
        <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#priceListFilters" aria-expanded="true" aria-controls="priceListFilters">
            Toggle Filters
        </button>
    </div>

    <div class="collapse show" id="priceListFilters">
        <div class="card-body">
            <form class="row g-3 align-items-end" method="GET" action="{{ route('buyer.price-list', $enquiryId) }}">
                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label">Category</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-tags"></i></span>
                        <select name="category" class="form-select">
                            <option value="">Select Category</option>
                            @foreach ($filterOptions['categories'] as $category)
                                <option value="{{ $category['id'] }}" {{ $filters['category'] == $category['id'] ? 'selected' : '' }}>
                                    {{ $category['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label">Sub Category</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-diagram-3"></i></span>
                        <select name="sub_category" class="form-select">
                            <option value="">Select Sub Category</option>
                            @foreach ($filterOptions['subCategories'] as $subCategory)
                                <option value="{{ $subCategory['id'] }}" {{ $filters['sub_category'] == $subCategory['id'] ? 'selected' : '' }}>
                                    {{ $subCategory['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label">Product Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-bag"></i></span>
                        <input type="text" name="product_name" class="form-control" placeholder="Product Name"
                            value="{{ $filters['product_name'] }}">
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label">Quotation ID</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-file-earmark-text"></i></span>
                        <input type="text" name="quotation_id" value="{{ $filters['quotation_id'] }}" class="form-control"
                            placeholder="Quotation ID">
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label">Date</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                        <select name="date" class="form-select">
                            <option value="">Select Date</option>
                            @foreach ($filterOptions['dates'] as $date)
                                <option value="{{ $date }}" {{ $filters['date'] == $date ? 'selected' : '' }}>{{ $date }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label">City</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                        <select name="city" class="form-select">
                            <option value="">Select City</option>
                            @foreach ($filterOptions['cities'] as $city)
                                <option value="{{ $city }}" {{ $filters['city'] == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label">Quantity</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                        <select name="quantity" class="form-select">
                            <option value="">Select Quantity</option>
                            @foreach ($filterOptions['quantities'] as $quantity)
                                <option value="{{ $quantity }}" {{ $filters['quantity'] == $quantity ? 'selected' : '' }}>
                                    {{ $quantity }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label d-none d-lg-block">&nbsp;</label>
                    <div class="d-flex flex-column flex-sm-row flex-lg-column flex-xl-row gap-2">
                        <button type="submit" class="btn btn-primary w-100 flex-fill">
                            <i class="bi bi-funnel-fill me-2"></i>Apply
                        </button>
                        <a href="{{ route('buyer.price-list', $enquiryId) }}" class="btn btn-outline-secondary w-100 flex-fill">
                            <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table align-middle text-nowrap table-hover table-centered mb-0">
        <thead class="table-light">
            <tr>
                <th>Sr.No</th>
                <th class="text-center">Action</th>
                <th>Seller</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Product Name</th>
                <th>Date</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Total Price</th>
                <th>Platform Fee</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $index => $record)
                @php
                    $rowNumber = $index + 1;
                    $fileUrl = !empty($record->bidding_price_filename)
                        ? url('public/uploads/' . ltrim($record->bidding_price_filename, '/'))
                        : null;
                    $statusClass = match ($record->status_badge) {
                        'accepted' => 'badge bg-success-subtle text-success',
                        'rejected' => 'badge bg-danger-subtle text-danger',
                        default => 'badge bg-warning-subtle text-warning',
                    };
                @endphp
                <tr>
                    <td>{{ $rowNumber }}</td>
                    <td class="text-center">
                        <div class="d-inline-flex flex-wrap gap-2 justify-content-center">
                            @if ($record->can_accept)
                                <a
                                    href="{{ url('accepet/' . $record->bidding_price_id . '/' . $record->data_id) }}"
                                    class="btn btn-sm btn-success"
                                    onclick="return confirm('Are you sure you want to accept this bid?')"
                                >
                                    Accept
                                </a>
                            @endif
                            <a href="{{ url('seller-profile/' . $record->seller_id) }}" class="btn btn-sm btn-primary">
                                View Profile
                            </a>
                            @if ($fileUrl)
                                <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    View File
                                </a>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">{{ $record->seller_name }}</span>
                            <small class="text-muted">{{ $record->seller_email }}</small>
                            <small class="text-muted">{{ $record->seller_phone }}</small>
                        </div>
                    </td>
                    <td>{{ $record->category_name ?? '-' }}</td>
                    <td>{{ $record->sub_name ?? '-' }}</td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">{{ $record->product_title ?? $record->bidding_price_product_name }}</span>
                            @if(!empty($record->product_brand))
                                <small class="text-muted">Brand: {{ $record->product_brand }}</small>
                            @endif
                        </div>
                    </td>
                    <td>{{ $record->formatted_date ?? '-' }}</td>
                    <td>{{ $record->unit ?? '-' }}</td>
                    <td>
                        <div class="d-flex flex-column">
                            <span>{{ $record->quantity ?? '-' }}</span>
                            <small class="text-muted">{{ number_format($record->numeric_quantity, 2) }}</small>
                        </div>
                    </td>
                    <td>{{ number_format((float) ($record->rate ?? 0), 2) }}</td>
                    <td>{{ number_format((float) $record->calculated_total, 2) }}</td>
                    <td>{{ number_format((float) ($record->platform_fee ?? 0), 2) }}</td>
                    <td><span class="{{ $statusClass }}">{{ ucfirst($record->status_badge) }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center py-4">No seller bids found for this enquiry.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
