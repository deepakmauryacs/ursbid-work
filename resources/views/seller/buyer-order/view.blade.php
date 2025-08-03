@extends ('seller.layout')
@section('title', 'Detail ')
<!--  -->
@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">

                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Seller List with Price</h4>
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


                        <div class="action-btn">
                            <a href="{{ url('/buyer-order') }}" class="btn btn-sm btn-primary btn-add">
                                <i class="la la-plus"></i> Back</a>
                        </div>
                    </div>
                </div>

            </div>

        </div>


        <div class="row">
            <div class="col-lg-12 mb-30">
                <div class="card">
                    <div class="col-md-12 mt-2">


                    </div>
                    <div class="card-body">


                        <div class="userDatatable projectDatatable project-table bg-white w-100 border-0">
                            <div class="table-responsive">
                                <div class="col-sm-12">
                                    <div class="padd">

                                        @php
                                        if($total < 1){ echo "<div class='text-danger'>Sorry, No data Found!</div>" ;
                                            }else{ @endphp <div class="pro">
                                            <table class="table table-border ">

                                                <head>
                                                    <tr>
                                                        <th>Sr.No</th>
                                                        <th>
                                                <span class="projectDatatable-title">Name</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Category</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Sub_Category</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Product_name</span>
                                            </th>
                                            
                                            <th>
                                                <span class="projectDatatable-title">Date</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">File</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Unit</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Quantity</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Rate</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Total_Price</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Plateform_Fee</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Action</span>
                                            </th>
                                                        <!-- <th></th>
                                                     
                                                        <th></th> -->
                                                    </tr>
                                                </head>
                                                <tbody>
                                                    @php
                                                    $i= 1;
                                                    foreach ($data as $all) {
                                                    $all_id = $all->bidding_price_id;

                                                    @endphp
                                                    <tr>
                                                       
                                                        <td>
                                                <div class="userDatatable-content text-center">
                                                {{ $i++ }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $all->seller_name }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $all->category_name }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $all->sub_name }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $all->product_name }}
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ date('Y-m-d', strtotime($all->date_time)) }}

                                                </div>
                                            </td>

                                            <td>
                                                @if (!empty($all->bidding_price_filename))
                                                <div class="userDatatable-content">
                                                    <a href="{{ url('public/uploads/'.$all->bidding_price_filename) }}"
                                                        target="_blank">View</a>
                                                </div>
                                                @else
                                                No file found
                                                @endif
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $all->unit }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $all->quantity }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $all->rate }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                @php
    $raw = $all->quantity; 
    preg_match('/\d+(\.\d+)?/', $raw, $matches);
    $qty = isset($matches[0]) ? (float)$matches[0] : 0;
    $total = $qty * $all->rate;
@endphp

{{ number_format($total, 2) }}


                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $all->price }}
                                                </div>
                                            </td>
                                                        <td class="d-flex"> 
                                                            @if($all->action == '0')
                                                            <a class="btn-success btn btn-sm mx-3" href="{{ url('accepet/'.$all->bidding_price_id.'/'.$all->data_id) }}" onclick="return confirm('Are you sure?')">Accept</a>

                                                            @endif

                                                            <a href="{{ url('seller-profile/'.$all->seller_id) }}"
                                                                class="btn-primary btn btn-sm">Veiw Profile</a>
                                                               
                                                        </td>

                                                    </tr>
                                                    @php } @endphp
                                                </tbody>
                                            </table>

                                    </div>
                                    @php
                                    }
                                    @endphp
                                </div>

                            </div>
                        </div><!-- End: .userDatatable -->

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>


@endsection