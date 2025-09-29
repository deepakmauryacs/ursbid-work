@extends('seller.layouts.app')
@section('title', 'Enquiry List')
@section('content')
<style>
.table-centered td, .table-centered th {
    vertical-align: middle !important;
    font-size: 18px !important;
    font-weight: 500 !important;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Quotation Summary</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-30">
                <div class="card">
                    <div class="card-body">
                        @if(Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        @if(Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ Session::get('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                       <div class="table-responsive">
                           <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                                <h3>Summary</h3>
                                @php
                                    $rate = $data['amount'];
                                    $quantity = $data['product_quantity'];
                                    $totalAmount = $rate * $quantity;
                                    $platformFee = $totalAmount * 0.005;
                                    $discount = $platformFee * 0.9;

                                    $grandTotal = $platformFee - $discount;
                                @endphp

                                {{-- Standard seller table class --}}
                                <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                                    <tbody>
                                        <tr>
                                            <th>Quotation Rate</th>
                                            <th>{{ $rate }}</th> <!-- A -->
                                        </tr>
                                        <tr>
                                            <th>Quotation Quantity</th>
                                            <th>{{ $quantity }}</th> <!-- B -->
                                        </tr>
                                        <tr>
                                            <th>Amount</th>
                                            <th>{{ number_format($totalAmount, 2) }}</th> <!-- A * B -->
                                        </tr>
                                        <tr>
                                            <th>Platform Fee</th>
                                            <th>{{ number_format($platformFee, 2) }}</th> <!-- A * B * 0.5% -->
                                        </tr>
                                        <tr>
                                            <th>Discount</th>
                                            <th>{{ number_format($discount, 2) }}</th> <!-- A * B * 0.5% -->
                                        </tr>
                                        <tr>
                                            <th>Grand Total</th>
                                            <th>{{ number_format($grandTotal, 2) }}</th>
                                        </tr>

                                        @if (!empty($data['filename']))
                                            <tr>
                                                <th>Quotation File</th>
                                                <th>
                                                    <a href="{{ url('public/uploads/' . $data['filename']) }}"
                                                        target="_blank">View</a>
                                                </th>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>



                                <form action="{{ url('/bidding_price') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $data['seller_email'] }}" name="seller_email">

                                    <input type="hidden" name="filename" class="filename form-control"
                                        value="{{ $data['filename'] ?? '' }}">

                                    <input type="hidden" name="rate" class="rate form-control"
                                        value="{{ $rate }}">
                                    <input type="hidden" name="product_id" class="product_id form-control"
                                        value="{{ $data['product_id'] }}">

                                    <input type="hidden" name="product_quantity" class="product_quantity form-control"
                                        value="{{ $data['product_quantity'] }}">

                                    <input type="hidden" name="product_name" class="product_name form-control"
                                        value="{{ $data['product_name'] }}">

                                    <input type="hidden" name="user_email" class="user_email form-control"
                                        value="{{ $data['user_email'] }}">

                                    <input type="hidden" name="data_id" class="data_id form-control"
                                        value="{{ $data['data_id'] }}">
                                    <input type="hidden" name="price" class="amount form-control"
                                        value="{{ number_format($grandTotal, 2) }}">
                                   
                                    <div class="row justify-content-end">
                                        <div class="col-lg-2">
                                            <button type="submit" class="btn btn-outline-primary w-100 mt-3">Submit</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    document.getElementById("cancel-yes").addEventListener("click", function(event) {
        event.preventDefault(); // Prevent default link behavior
        window.location.href = "{{ url('seller/enquiry/list') }}"; // Redirect
    });
</script>
@endsection