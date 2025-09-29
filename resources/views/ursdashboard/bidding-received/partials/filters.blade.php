@php
    $filters = $filters ?? [];
    $categories = $categories ?? [];
@endphp
<form id="buyerOrderFiltersForm" class="row g-3 align-items-end" method="get" action="{{ $action }}">
    <div class="col-12 col-sm-6 col-lg-3">
        <label class="form-label">Category</label>
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

    <div class="col-12 col-sm-6 col-lg-3">
        <label class="form-label">Date</label>
        <input type="text" name="date" id="buyerOrderDate" class="form-control" placeholder="Date"
            value="{{ $filters['date'] ?? '' }}">
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <label class="form-label">City</label>
        <input type="text" name="city" id="buyerOrderCity" class="form-control" placeholder="City"
            value="{{ $filters['city'] ?? '' }}">
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <label class="form-label">Quantity</label>
        <input type="text" name="quantity" id="buyerOrderQuantity" class="form-control" placeholder="Quantity"
            value="{{ $filters['quantity'] ?? '' }}">
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="product_name" id="buyerOrderProduct" class="form-control" placeholder="Product Name"
            value="{{ $filters['product_name'] ?? '' }}">
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <label class="form-label">Quotation ID</label>
        <input type="text" name="qutation_id" id="buyerOrderQuotation" class="form-control" placeholder="Quotation ID"
            value="{{ $filters['qutation_id'] ?? '' }}">
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <label class="form-label">Records Per Page</label>
        <select name="r_page" id="buyerOrderRecords" class="form-select">
            @php
                $perPage = (int) ($filters['r_page'] ?? 25);
            @endphp
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
            <button type="button" id="resetBuyerOrderFilters" class="btn btn-outline-secondary w-100 flex-fill">
                <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
            </button>
        </div>
    </div>
</form>
