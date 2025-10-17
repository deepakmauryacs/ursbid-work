@php
$filters = $filters ?? [];
$records = $records ?? collect();
if (is_array($records)) {
$records = collect($records);
}
$isPaginator = $records instanceof \Illuminate\Contracts\Pagination\Paginator;
$recordsForDisplay = $isPaginator ? collect($records->items()) : ($records instanceof \Illuminate\Support\Collection ?
$records : collect());
$hasRecords = $recordsForDisplay->isNotEmpty();
$rowNumber = $isPaginator ? $records->firstItem() : 1;
@endphp
<div class="table-responsive">
    <table class="table align-middle text-nowrap table-hover table-centered mb-0">
        <thead>
            <tr>
                <th>Sr.No</th>
                <th>Quotation ID</th>
                <th>Buyer / Client</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Product Name</th>
                <th>Date</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recordsForDisplay as $index => $record)
            @php
            $recordId = is_object($record) ? ($record->id ?? null) : ($record['id'] ?? null);
            $recordQuotationId = is_object($record) ? ($record->qutation_id ?? '') : ($record['qutation_id'] ?? '');
            $recordName = is_object($record) ? ($record->name ?? '') : ($record['name'] ?? '');
            $recordCategory = is_object($record) ? ($record->category_name ?? '') : ($record['category_name'] ?? '');
            $recordSubCategory = is_object($record) ? ($record->sub_name ?? '') : ($record['sub_name'] ?? '');
            $recordProduct = is_object($record)
            ? ($record->product_name ?? $record->qutation_form_product_name ?? '')
            : ($record['product_name'] ?? $record['qutation_form_product_name'] ?? '');
            $recordDate = is_object($record)
            ? ($record->formatted_date ?? ($record->date_time ?? null))
            : ($record['formatted_date'] ?? ($record['date_time'] ?? null));
            $recordQuantity = is_object($record) ? ($record->quantity ?? '') : ($record['quantity'] ?? '');
            $recordUnit = is_object($record) ? ($record->unit ?? '') : ($record['unit'] ?? '');
            $paymentStatus = is_object($record)
            ? ($record->bidding_price_payment_status ?? '')
            : ($record['bidding_price_payment_status'] ?? '');
            if ($recordDate instanceof \Carbon\Carbon) {
            $recordDate = $recordDate->format('Y-m-d');
            }
            if (!is_string($recordDate) && $recordDate) {
            $recordDate = \Carbon\Carbon::parse($recordDate)->format('Y-m-d');
            }
            @endphp
            <tr>
                <td>{{ $rowNumber++ }}</td>
                <td>{{ $recordQuotationId ?: '-' }}</td>
                <td>{{ $recordName ?: '-' }}</td>
                <td>{{ $recordCategory ?: '-' }}</td>
                <td>{{ $recordSubCategory ?: '-' }} </td>
                <td>{{ $recordProduct ?: '-' }}</td>
                <td>{{ $recordDate ?: '-' }}</td>
                <td>{{ $recordQuantity ?: '-' }}</td>
                <td>{{ $recordUnit ?: '-' }} </td>
                <td>
                    <div class="text-success">{{ $paymentStatus ?: '-' }}</div>
                </td>
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