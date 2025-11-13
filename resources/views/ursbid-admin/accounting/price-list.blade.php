@extends('ursbid-admin.layouts.app')
@section('title', 'Seller List with Price')
@section('content')
<div class="container-fluid">
    
    <!-- ========== Page Title Start ========== -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Seller List with Price</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Seller List with Price</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- ========== Page Title End ========== -->
    
    <div class="social-dash-wrap">
        <!--<div class="row">-->
        <!--    <div class="col-lg-12">-->
        <!--        <div class="breadcrumb-main d-flex justify-content-between align-items-center">-->
        <!--            <h4 class="text-capitalize breadcrumb-title">Seller List with Price</h4>-->
        <!--            <a href="{{ route('super-admin.accounting.accepted-bidding-list') }}" class="btn btn-sm btn-outline-primary">Back</a>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->

        <div class="row">
            <div class="col-lg-12 mb-30">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Qutation ID</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Product Name</th>
                                    <th>Date</th>
                                    <th>File</th>
                                    <th>Unit</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
                                    <th>Total Price</th>
                                    <th>Platform Fee</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($data as $index => $record)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $record->qutation_id }}</td>
                                        <td>{{ $record->seller_name }}</td>
                                        <td>{{ $record->category_name }}</td>
                                        <td>{{ $record->sub_name }}</td>
                                        <td>{{ $record->product_name }}</td>
                                        <td>{{ $record->date_time ? \Carbon\Carbon::parse($record->date_time)->format('Y-m-d') : 'N/A' }}</td>
                                        <td>
                                            @if(!empty($record->bidding_price_filename))
                                                <a href="{{ url('public/uploads/'.$record->bidding_price_filename) }}" target="_blank">View</a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $record->unit }}</td>
                                        <td>{{ $record->quantity }}</td>
                                        <td>{{ $record->rate }}</td>
                                        <td>
                                            @php
                                                $matches = [];
                                                preg_match('/\\d+(?:\\.\\d+)?/', (string) $record->quantity, $matches);
                                                $qty = $matches[0] ?? 0;
                                            @endphp
                                            {{ number_format($qty * (float) $record->rate, 2) }}
                                        </td>
                                        <td>{{ $record->price }}</td>
                                        <td>
                                            <a href="{{ url('seller-profile/'.$record->seller_id) }}" class="btn btn-sm btn-outline-primary">View Profile</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center text-danger">Sorry, no data found!</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
