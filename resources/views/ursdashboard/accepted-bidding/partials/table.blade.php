@php
    $records = $records ?? collect();
    $filters = $filters ?? [];
    $isPaginated = $records instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    $startIndex = $isPaginated ? $records->firstItem() : 1;
@endphp
<div class="table-responsive">
    <table class="table align-middle text-nowrap table-hover table-centered mb-0">
        <thead>
            <tr>
                <th>Sr. No</th>
                <th>Quotation ID</th>
                <th>Name</th>
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
            @forelse($records as $index => $record)
                @php
                    $quotationId = $record->qutation_id ?? '';
                @endphp
                <tr>
                    <td class="text-center">{{ $startIndex + $index }}</td>
                    <td>{{ $quotationId !== '' ? $quotationId : '-' }}</td>
                    <td>{{ $record->name }}</td>
                    <td>{{ $record->category_name }}</td>
                    <td>{{ $record->sub_name }}</td>
                    <td>{{ $record->product_name }}</td>
                    <td>{{ $record->date_time ? \Carbon\Carbon::parse($record->date_time)->format('Y-m-d') : '' }}</td>
                    <td>{{ $record->unit }}</td>
                    <td>{{ $record->quantity }}</td>
                    <td>{{ $record->rate }}</td>
                    <td>{{ number_format($record->calculated_total_price ?? 0, 2) }}</td>
                    <td>{{ $record->price }}</td>
                    <td class="text-success">Confirm</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center text-muted py-4">No accepted bids found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($isPaginated && $records->hasPages())
    <div class="gmz-pagination mt-3">
        {!! $records->appends($filters)->links('pagination::bootstrap-4') !!}
    </div>
@endif
