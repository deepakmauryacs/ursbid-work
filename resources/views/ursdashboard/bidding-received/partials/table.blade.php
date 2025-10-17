@php
    $filters = $filters ?? [];
    $records = $records ?? collect();
    if (is_array($records)) {
        $records = collect($records);
    }
    $isPaginator = $records instanceof \Illuminate\Contracts\Pagination\Paginator;
    $recordsForDisplay = $isPaginator ? collect($records->items()) : ($records instanceof \Illuminate\Support\Collection ? $records : collect());
    $hasRecords = $recordsForDisplay->isNotEmpty();
    $rowNumber = $isPaginator ? $records->firstItem() : 1;
@endphp
<div class="table-responsive">
    <table class="table align-middle text-nowrap table-hover table-centered mb-0">
        <thead>
            <tr>
                <th>Sr.No</th>
                <th>Action</th>
                <th>Quotation ID</th>
                <th>Buyer / Client</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Product Name</th>
                <th>Date</th>
                <th>Quantity</th>
                <th>Unit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recordsForDisplay as $index => $record)
                @php
                    $productId = is_object($record) ? ($record->product_id ?? null) : ($record['product_id'] ?? null);
                    $recordId = is_object($record) ? ($record->id ?? null) : ($record['id'] ?? null);
                    $recordName = is_object($record) ? ($record->name ?? '') : ($record['name'] ?? '');
                    $recordQuotationId = is_object($record) ? ($record->qutation_id ?? '') : ($record['qutation_id'] ?? '');
                    $recordCategory = is_object($record) ? ($record->category_name ?? '') : ($record['category_name'] ?? '');
                    $recordSubCategory = is_object($record) ? ($record->sub_name ?? '') : ($record['sub_name'] ?? '');
                    $recordProduct = is_object($record) ? ($record->product_name ?? '') : ($record['product_name'] ?? '');
                    $recordDate = is_object($record) ? ($record->date_time ?? null) : ($record['date_time'] ?? null);
                    $recordQuantity = is_object($record) ? ($record->quantity ?? '') : ($record['quantity'] ?? '');
                    $recordUnit = is_object($record) ? ($record->unit ?? '') : ($record['unit'] ?? '');
                    $hasVendorPrice = is_object($record)
                        ? (bool) ($record->has_vendor_price ?? false)
                        : (bool) ($record['has_vendor_price'] ?? false);
                    $formattedDate = $recordDate ? \Carbon\Carbon::parse($recordDate)->format('d-m-Y') : '';
                @endphp
                <tr>
                    <td>{{ $rowNumber++ }}</td>
                    <td class="d-flex gap-2">
                        @if($recordId && $hasVendorPrice)
                            <a href="{{ route('buyer.price-list', $recordId) }}" class="btn btn-primary btn-sm">
                                Vendor List
                            </a>
                            <a href="{{ route('buyer.cis-details', $recordId) }}" class="btn btn-outline-primary btn-sm">
                                CIS
                            </a>
                        @else
                            <button type="button" class="btn btn-primary btn-sm" disabled>
                                Vendor List
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" disabled>
                                CIS
                            </button>
                        @endif
                        <a href="{{ $recordId ? route('accepted-list', $recordId) : '#' }}" class="btn btn-success btn-sm">
                            Selected Vendor
                        </a>
                    </td>
                    <td>{{ $recordQuotationId ?: '-' }}</td>
                    <td>{{ $recordName }} </td>
                    <td>{{ $recordCategory }}</td>
                    <td>{{ $recordSubCategory }}</td>
                    <td>{{ $recordProduct }}</td>
                    <td>{{ $formattedDate }}</td>
                    <td>{{ $recordQuantity }}</td>
                    <td>{{ $recordUnit }}</td>
                </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">No bids found.</td>
                    </tr>
                @endforelse
        </tbody>
    </table>
</div>

@if($isPaginator && $records->hasPages())
    <div class="gmz-pagination mt-3">
        @if(!empty($filters))
            {{ $records->appends($filters)->links('pagination::bootstrap-5') }}
        @else
            {{ $records->links('pagination::bootstrap-5') }}
        @endif
    </div>
@endif


