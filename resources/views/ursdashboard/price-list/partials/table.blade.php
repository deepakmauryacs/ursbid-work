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
<div class="card mb-4 shadow-none border">
    <div class="card-body pb-0">
        <form class="row gy-3" method="GET" action="{{ route('buyer.price-list', $enquiryId) }}">
            <div class="col-xl-3 col-lg-4 col-md-6">
                <label class="form-label fw-semibold">Category</label>
                <select name="category" class="form-select">
                    <option value="">All</option>
                    @foreach ($filterOptions['categories'] as $category)
                        <option value="{{ $category['id'] }}" {{ $filters['category'] == $category['id'] ? 'selected' : '' }}>
                            {{ $category['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6">
                <label class="form-label fw-semibold">Sub Category</label>
                <select name="sub_category" class="form-select">
                    <option value="">All</option>
                    @foreach ($filterOptions['subCategories'] as $subCategory)
                        <option value="{{ $subCategory['id'] }}" {{ $filters['sub_category'] == $subCategory['id'] ? 'selected' : '' }}>
                            {{ $subCategory['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6">
                <label class="form-label fw-semibold">Product Name</label>
                <select name="product_name" class="form-select">
                    <option value="">All</option>
                    @foreach ($filterOptions['products'] as $product)
                        <option value="{{ $product['name'] }}" {{ $filters['product_name'] == $product['name'] ? 'selected' : '' }}>
                            {{ $product['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6">
                <label class="form-label fw-semibold">Quotation ID</label>
                <input type="text" name="quotation_id" value="{{ $filters['quotation_id'] }}" class="form-control"
                    placeholder="Enter quotation id">
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6">
                <label class="form-label fw-semibold">Date</label>
                <select name="date" class="form-select">
                    <option value="">All</option>
                    @foreach ($filterOptions['dates'] as $date)
                        <option value="{{ $date }}" {{ $filters['date'] == $date ? 'selected' : '' }}>{{ $date }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6">
                <label class="form-label fw-semibold">City</label>
                <select name="city" class="form-select">
                    <option value="">All</option>
                    @foreach ($filterOptions['cities'] as $city)
                        <option value="{{ $city }}" {{ $filters['city'] == $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6">
                <label class="form-label fw-semibold">Quantity</label>
                <select name="quantity" class="form-select">
                    <option value="">All</option>
                    @foreach ($filterOptions['quantities'] as $quantity)
                        <option value="{{ $quantity }}" {{ $filters['quantity'] == $quantity ? 'selected' : '' }}>
                            {{ $quantity }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 d-flex gap-2 justify-content-end">
                <button type="submit" class="btn btn-primary">Apply</button>
                <a href="{{ route('buyer.price-list', $enquiryId) }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
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
