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

@if (!$hasRecords)
    <div class="text-danger">Sorry, no bids found!</div>
@else
    <div class="table-responsive">
        <table class="table align-middle text-nowrap table-hover table-centered mb-0">
            <thead>
                <tr>
                    <th><span class="projectDatatable-title">Sr.No</span></th>
                    <th><span class="projectDatatable-title">Name</span></th>
                    <th><span class="projectDatatable-title">Category</span></th>
                    <th><span class="projectDatatable-title">Sub Category</span></th>
                    <th><span class="projectDatatable-title">Product Name</span></th>
                    <th><span class="projectDatatable-title">Date</span></th>
                    <th><span class="projectDatatable-title">Quantity</span></th>
                    <th><span class="projectDatatable-title">Unit</span></th>
                    <th><span class="projectDatatable-title">Action</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($recordsForDisplay as $record)
                    @php
                        $recordId = is_object($record) ? ($record->id ?? null) : ($record['id'] ?? null);
                        $recordName = is_object($record) ? ($record->name ?? '') : ($record['name'] ?? '');
                        $recordCategory = is_object($record) ? ($record->category_name ?? '') : ($record['category_name'] ?? '');
                        $recordSubCategory = is_object($record) ? ($record->sub_name ?? '') : ($record['sub_name'] ?? '');
                        $recordProduct = is_object($record) ? ($record->product_name ?? '') : ($record['product_name'] ?? '');
                        $recordDate = is_object($record) ? ($record->date_time ?? null) : ($record['date_time'] ?? null);
                        $recordQuantity = is_object($record) ? ($record->quantity ?? '') : ($record['quantity'] ?? '');
                        $recordUnit = is_object($record) ? ($record->unit ?? '') : ($record['unit'] ?? '');
                        $formattedDate = $recordDate ? \Carbon\Carbon::parse($recordDate)->format('Y-m-d') : '';
                    @endphp
                    <tr>
                        <td>
                            <div class="userDatatable-content">{{ $rowNumber++ }}</div>
                        </td>
                        <td>
                            <div class="userDatatable-content">{{ $recordName }}</div>
                        </td>
                        <td>
                            <div class="userDatatable-content">{{ $recordCategory }}</div>
                        </td>
                        <td>
                            <div class="userDatatable-content">{{ $recordSubCategory }}</div>
                        </td>
                        <td>
                            <div class="userDatatable-content">{{ $recordProduct }}</div>
                        </td>
                        <td>
                            <div class="userDatatable-content">{{ $formattedDate }}</div>
                        </td>
                        <td>
                            <div class="userDatatable-content">{{ $recordQuantity }}</div>
                        </td>
                        <td>
                            <div class="userDatatable-content">{{ $recordUnit }}</div>
                        </td>
                        <td class="d-flex gap-2">
                            <a href="{{ $recordId ? url('/price-list/' . $recordId) : '#' }}" class="btn btn-primary btn-sm">
                                View List
                            </a>
                            <a href="{{ $recordId ? url('/accepted-list/' . $recordId) : '#' }}" class="btn btn-success btn-sm">
                                Accepted List
                            </a>
                            <button type="button" class="btn btn-outline-secondary btn-sm mdl_btn" data-bs-toggle="modal"
                                data-bs-target="#buyerBidModal"
                                data-product_id="{{ $recordId }}"
                                data-product_name="{{ $recordProduct }}"
                                data-user_email="{{ is_object($record) ? ($record->user_email ?? '') : ($record['user_email'] ?? '') }}"
                                data-data_id="{{ $recordId }}">
                                Bid Now
                            </button>
                        </td>
                    </tr>
                @endforeach
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
@endif

