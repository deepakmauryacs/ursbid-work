@php
    $records = $records ?? collect();
    if (is_array($records)) {
        $records = collect($records);
    }
    $isPaginated = $records instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    $collection = $isPaginated ? $records->getCollection() : ($records instanceof \Illuminate\Support\Collection ? $records : collect());
    $startIndex = $isPaginated ? $records->firstItem() : 1;
@endphp
<div class="table-responsive">
    <table class="table align-middle text-nowrap table-hover table-centered mb-0">
        <thead>
            <tr>
                <th>Sr. No</th>
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
            @forelse ($collection as $index => $record)
                @php
                    $formattedDate = $record->date_time ? \Carbon\Carbon::parse($record->date_time)->format('d-m-Y') : '';
                    $totalPrice = $record->calculated_total_price ?? 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $startIndex + $index }}</td>
                    <td>{{ $record->name ?? $record->seller_name ?? '-' }}</td>
                    <td>{{ $record->category_name ?? '-' }}</td>
                    <td>{{ $record->sub_name ?? '-' }}</td>
                    <td>{{ $record->product_name ?? '-' }}</td>
                    <td>{{ $formattedDate }}</td>
                    <td>{{ $record->unit ?? '-' }}</td>
                    <td>{{ $record->quantity ?? '-' }}</td>
                    <td>{{ $record->rate ?? '-' }}</td>
                    <td>{{ number_format($totalPrice, 2) }}</td>
                    <td>{{ $record->price ?? '-' }}</td>
                    <td class="text-success">Confirm</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center text-muted py-4">No accepted bids found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if ($isPaginated && $records->hasPages())
    <div class="gmz-pagination mt-3">
        {!! $records->links('pagination::bootstrap-4') !!}
    </div>
@endif
