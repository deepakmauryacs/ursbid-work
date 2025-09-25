@extends('seller.layouts.app')
@section('title', 'Detail ')
<!--  -->
@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">

                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Accepted List</h4>
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
                            <a href="{{ url('seller/enquiry/list') }}" class="btn btn-sm btn-primary btn-add">
                                <i class="la la-plus"></i> Back</a>
                        </div> -->
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
                                            <table class="table align-middle text-nowrap table-hover table-centered mb-0">

                                                <head>
                                                    <tr>
                                                      
                                                        <th>
                                                            <span class="projectDatatable-title"> Sr.No</span>
                                                        </th>
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
                                                            <span class="projectDatatable-title">Quantity</span>
                                                        </th>
                                                        <th>
                                                            <span class="projectDatatable-title">Unit</span>
                                                        </th>
                                                        <th>Price</th>
                                                        <!-- <th></th> -->
                                                        <th>Action</th>
                                                    </tr>
                                                </head>
                                                <tbody>
                                                    @php
                                                    $i= 1;
                                                    foreach ($data as $all) {
                                                    $all_id = $all->id;

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
                                                <div class="userDatatable-content">
                                                    {{ $all->quantity }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $all->unit }}
                                                </div>
                                            </td>
                                                        <td>{{ $all->price }}</td>
                                                        <td class="d-flex">
                                                            @if($all->action == '0')
                                                            <a class="btn-success btn btn-sm mx-3"
                                                                href="{{ url('accepet/'.$all->id.'/'.$all->data_id) }}"
                                                                onclick="return confirm('Are you sure?')">Accept</a>

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