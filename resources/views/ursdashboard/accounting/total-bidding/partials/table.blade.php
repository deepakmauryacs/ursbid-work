@php
    $filters = $filters ?? [];
    $records = $records ?? collect();
    if (is_array($records)) {
        $records = collect($records);
    }
    $isPaginator = $records instanceof \Illuminate\Contracts\Pagination\Paginator;
    $recordsForDisplay = $isPaginator ? collect($records->items()) : ($records instanceof \Illuminate\Support\Collection ? $records : collect());
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
                <th>Unit</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Total Price</th>
                <th>Platform Fee</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recordsForDisplay as $record)
                @php
                    $record = is_array($record) ? (object) $record : $record;
                    $quotationId = $record->qutation_id ?? '';
                    $name = $record->qutation_form_name ?? $record->buyer_name ?? '';
                    $category = $record->category_name ?? '';
                    $subCategory = $record->sub_name ?? '';
                    $productName = $record->requested_product_name ?? $record->product_name ?? '';
                    $dateValue = $record->date_time ?? null;
                    $formattedDate = $dateValue ? \Carbon\Carbon::parse($dateValue)->format('Y-m-d') : '';
                    $city = $record->city ?? '';
                    $unit = $record->unit ?? '';
                    $quantityRaw = $record->quantity ?? '';
                    $rate = $record->rate ?? null;
                    $totalPrice = $record->total_price ?? null;
                    $platformFee = $record->platform_fee ?? null;
                    $statusBadge = $record->status_badge ?? 'secondary';
                    $qutationFormId = $record->qutation_form_id ?? null;
                @endphp
                <tr>
                    <td>{{ $rowNumber++ }}</td>
                    <td>{{ $quotationId ?: '-' }}</td>
                    <td>{{ $name ?: '-' }}</td>
                    <td>{{ $category ?: '-' }}</td>
                    <td>{{ $subCategory ?: '-' }}</td>
                    <td>{{ $productName ?: '-' }}</td>
                    <td>{{ $formattedDate ?: '-' }}</td>
                    <td>{{ $city ?: '-' }}</td>
                    <td>{{ $unit ?: '-' }}</td>
                    <td>{{ $quantityRaw ?: '-' }}</td>
                    <td>{{ $rate !== null ? number_format((float) $rate, 2) : '-' }}</td>
                    <td>{{ $totalPrice !== null ? number_format($totalPrice, 2) : '-' }}</td>
                    <td>{{ $platformFee !== null ? number_format((float) $platformFee, 2) : '-' }}</td>
                    <td>
                        <span class="badge bg-{{ $statusBadge }} text-capitalize">
                            {{ $statusBadge }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            @if($qutationFormId)
                                <a href="{{ url('seller/enquiry/view/' . $qutationFormId) }}" class="btn btn-primary btn-sm">
                                    View Details
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="15" class="text-center py-4">No bids found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($isPaginator && $records->hasPages())
    <div class="gmz-pagination mt-3">
        @if(!empty($filters))
            {{ $records->appends($filters)->links('pagination::bootstrap-4') }}
        @else
            {{ $records->links('pagination::bootstrap-4') }}
        @endif
    </div>
@endif
