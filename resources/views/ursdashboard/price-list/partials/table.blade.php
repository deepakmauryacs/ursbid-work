@php
    $records = $records ?? collect();
    if (!($records instanceof \Illuminate\Support\Collection)) {
        $records = collect($records);
    }
    $hasRecords = $records->isNotEmpty();
@endphp
<div class="table-responsive">
    <table class="table align-middle text-nowrap table-hover table-centered mb-0">
        <thead>
            <tr>
                <th>Sr.No</th>
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
                <th class="text-end">Action</th>
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
                    <td class="text-end">
                        <div class="btn-group" role="group">
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
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center py-4">No seller bids found for this enquiry.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
