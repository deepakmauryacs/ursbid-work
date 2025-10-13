@extends ('seller.layout')
@section('title', 'Enquiry List')
<!--  -->
@section('content')
<style>
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 18px;
    font-family: Arial, sans-serif;
    text-align: left;
}

table th,
table td {
    padding: 12px 15px;
    border: 1px solid #ddd;
}

table th {
    background-color: #f4f4f4;
    font-weight: bold;
}

table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

table tbody tr:hover {
    background-color: #f1f1f1;
}

table tbody th:last-child {
    text-align: right;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">

                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Enquiry List</h4>
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <!-- <div class="action-btn">

                                        <div class="form-group mb-0">
                                            <div class="input-container icon-left position-relative">
                                                <span class="input-icon icon-left">
                                                    <span data-feather="calendar"></span>
                                                </span>
                                                <input type="text" class="form-control form-control-default date-ranger" name="date-ranger" placeholder="Oct 30, 2019 - Nov 30, 2019">
                                                <span class="input-icon icon-right">
                                                    <span data-feather="chevron-down"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div> -->


                        <!-- <div class="action-btn">
                            <a href="{{ url('admin/Buyer/add') }}" class="btn btn-sm btn-primary btn-add">
                                <i class="la la-plus"></i> Add New Buyer</a>
                        </div> -->
                    </div>
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

                        <div class="userDatatable projectDatatable project-table bg-white w-100 border-0">
                            <div class="table-responsive">
                                <h3>Summary</h3>


                                @php

                                $rate = $data['amount'];
                                $quantity = $data['product_quantity'];
                                $totalAmount = $rate * $quantity;
                                $platformFee = $totalAmount * 0.005;
                                $discount = $platformFee * 0.9;
                                
                                $grandTotal =   $platformFee - $discount ;
                                @endphp

                                <table>
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

                                        @php 

                                        @if(isset($data['filename']) && $data['filename'])
    <tr>
        <th>Quotation File</th>
        <th><a href="{{ url('public/uploads/'.$data['filename']) }}" target="_blank">View</a></th>
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
                                   
                                    <button type="submit" class="btn btn-primary mt-2">Pay Now</button>
                                </form>

                            </div>
                        </div><!-- End: .userDatatable -->

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