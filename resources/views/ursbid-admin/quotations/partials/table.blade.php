<div class="table-responsive">
    <table class="table align-middle text-nowrap table-hover table-centered mb-0">
        <thead class="bg-light-subtle">
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Product Name</th>
                <th>Time</th>
                <th>Date</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Qutation</th>
                <th>Status</th>
                <th style="width: 130px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
            $start = ($quotations->currentPage() - 1) * $quotations->perPage();
            @endphp
    
            @forelse($quotations as $index => $quotation)
                @php
                    // Ensure post_date and bid_time are set and valid
                    if (isset($blog->date_time) && isset($blog->bid_time)) {
                        $postDate       = \Carbon\Carbon::parse($blog->date_time);
                        $expirationDate = $postDate->addDays($blog->bid_time);
                        $currentDate    = \Carbon\Carbon::now();
                        $status = $currentDate->lessThanOrEqualTo($expirationDate) ? 'active' : 'deactive';
                    } else {
                        $status = 'deactive'; // Default status if post_date or bid_time is not set
                    }
                @endphp
                <tr id="row-{{ $quotation->id }}">
                    <td>{{ $start + $index + 1 }}</td>
                    <td>{{ $quotation->name }}</td>
                    <td>{{ $quotation->category_name }}</td>
                    <td>{{ $quotation->sub_name }}</td>
                    <td>{{ $quotation->product_name }}</td>
                    <td>{{ $quotation->bid_time }}</td>
                    <td>{{ date('Y-m-d', strtotime($quotation->date_time)) }}</td>
                    <td>{{ $quotation->quantity }}</td>
                    <td>{{ $quotation->unit }}</td>
                    <td>
                        @if (!empty($quotation->qutation_form_image))
                            <div class="userDatatable-content">
                                <a href="{{ route('super-admin.quotation.file', $quotation->id) }}" target="_blank">View</a>
                            </div>
                        @else
                            No file found
                        @endif
                    </td>
                    <td>{{ $status }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('super-admin.quotation.view', $quotation->id) }}" class="btn btn-soft-primary btn-sm">
                                <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No quotations found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<x-paginationwithlength :paginator="$quotations" />

