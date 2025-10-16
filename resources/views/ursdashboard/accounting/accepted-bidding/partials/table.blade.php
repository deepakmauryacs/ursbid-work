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
                <th>Quotation ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Product Name</th>
                <th>Date</th>
                <th>City</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Rate</th>
                <th>Total Price</th>
                <th>Platform Fee</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recordsForDisplay as $record)
                @php
                    $record = is_array($record) ? (object) $record : $record;
                    $quotationId = $record->qutation_id ?? '';
                    $name = $record->name ?? '';
                    $category = $record->category_name ?? '';
                    $subCategory = $record->sub_name ?? '';
                    $productName = $record->requested_product_name ?? $record->product_name ?? '';
                    $dateValue = $record->date_time ?? null;
                    $formattedDate = $dateValue ? \Carbon\Carbon::parse($dateValue)->format('Y-m-d') : '';
                    $city = $record->city ?? '';
                    $quantityRaw = $record->quantity ?? '';
                    $unit = $record->unit ?? '';
                    $rate = $record->rate ?? null;
                    $platformFee = $record->platform_fee ?? null;
                    $totalPrice = $record->total_price ?? null;
                    $detailsId = $record->qutation_form_id ?? $record->product_id ?? null;
                @endphp
                <tr>
                    <td>{{ $rowNumber++ }}</td>
                    <td>{{ $quotationId ?: '-' }}</td>
                    <td>{{ $name }}</td>
                    <td>{{ $category }}</td>
                    <td>{{ $subCategory }}</td>
                    <td>{{ $productName }}</td>
                    <td>{{ $formattedDate }}</td>
                    <td>{{ $city }}</td>
                    <td>{{ $quantityRaw }}</td>
                    <td>{{ $unit }}</td>
                    <td>{{ $rate !== null ? number_format((float) $rate, 2) : '-' }}</td>
                    <td>{{ $totalPrice !== null ? number_format($totalPrice, 2) : '-' }}</td>
                    <td>{{ $platformFee !== null ? number_format((float) $platformFee, 2) : '-' }}</td>
                    <td>
                        @if($detailsId)
                            <div class="d-flex gap-2">
                                <a href="{{ url('seller/enquiry/view/' . $detailsId) }}" class="btn btn-primary btn-sm">
                                    View Details
                                </a>
                            </div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center py-4">No accepted bids found.</td>
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
