@extends('seller.layouts.app')
@section('title', 'Detail')

@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main d-flex align-items-center justify-content-between">
                    <h4 class="text-capitalize breadcrumb-title">Accepted List</h4>
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                    <div class="action-btn">
                      <a href="{{ url('buyer/bidding-received/list') }}" class="btn btn-sm btn-primary btn-add">
                        <i class="la la-plus"></i> Back
                      </a>
                    </div>
                  </div>
                </div>
            </div>
        </div>

        @php
            // Normalize inputs: expect $records (Collection or Paginator) + optional $filters
            $records = $records ?? (isset($data) ? collect($data) : collect());
            $filters = $filters ?? [];
            $isPaginated = $records instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
            $startIndex = $isPaginated ? $records->firstItem() : 1;
        @endphp

        <div class="row">
            <div class="col-lg-12 mb-30">
                <div class="card">
                    <div class="card-body">
                        <div class="userDatatable projectDatatable project-table bg-white w-100 border-0">
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
                                        @forelse($records as $index => $record)
                                            @php
                                                // Field fallbacks to support older keys
                                                $name            = $record->name ?? $record->seller_name ?? '';
                                                $category_name   = $record->category_name ?? '';
                                                $sub_name        = $record->sub_name ?? '';
                                                $product_name    = $record->product_name ?? '';
                                                $date_val        = $record->date_time ?? null;
                                                $unit            = $record->unit ?? '-';
                                                $quantity        = $record->quantity ?? $record->qty ?? 0;
                                                $rate            = $record->rate ?? $record->price_per_unit ?? '';
                                                $platform_fee    = $record->price ?? $record->platform_fee ?? '';
                                                $total_calc      = $record->calculated_total_price
                                                                    ?? (($rate && $quantity) ? (float)$rate * (float)$quantity : 0);
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $startIndex + $index }}</td>
                                                <td>{{ $name }}</td>
                                                <td>{{ $category_name }}</td>
                                                <td>{{ $sub_name }}</td>
                                                <td>{{ $product_name }}</td>
                                                <td>{{ $date_val ? \Carbon\Carbon::parse($date_val)->format('d-m-Y') : '' }}</td>
                                                <td>{{ $unit }}</td>
                                                <td>{{ $quantity }}</td>
                                                <td>{{ $rate }}</td>
                                                <td>{{ number_format($total_calc ?? 0, 2) }}</td>
                                                <td>{{ $platform_fee }}</td>
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

                            @if($isPaginated && $records->hasPages())
                                <div class="gmz-pagination mt-3">
                                    {!! $records->appends($filters)->links('pagination::bootstrap-4') !!}
                                </div>
                            @endif
                        </div><!-- End: .userDatatable -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
