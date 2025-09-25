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
                    <h4 class="text-capitalize breadcrumb-title">Detail</h4>
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

        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Bidding Price</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('/bidding_price') }}" method="POST">
                            @csrf
                            <input type="hidden" value="{{ session('seller')->email }}" name="seller_email">
                            <input type='hidden' name='product_id' class='product_id form-control'>
                            <input type='hidden' name='product_name' class='product_name form-control'>
                            <input type='hidden' name='user_email' class='user_email form-control'>
                            <input type='hidden' name='data_id' class='data_id form-control'>
                            <label>Enter Price </label>
                            <input type='text' name='price' required class='price form-control'
                                placeholder="Enter Price">
                            <button type="submit" class="btn btn-primary mt-2">Submit</button>
                        </form>
                    </div>
                    <div class="modal-footer">


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
                                <table class="table align-middle text-nowrap table-hover table-centered mb-0">

                                    <tr class="userDatatable-header">
                                        <td>
                                            <span class="projectDatatable-title">Name</span>
                                        </td>
                                        <td>
                                            <span class="projectDatatable-title">{{ $query->seller_name }}</span>
                                        </td>
                                    </tr>
                                    <!-- <tr class="userDatatable-header">
                                        <td>
                                            <span class="projectDatatable-title">Email</span>
                                        </td>
                                        <td>
                                            <span class="projectDatatable-title">{{ $query->seller_email }}</span>
                                        </td>
                                    </tr> -->
                                    <!-- <tr class="userDatatable-header">
                                        <td>
                                            <span class="projectDatatable-title">Phone</span>
                                        </td>
                                        <td>
                                            <span class="projectDatatable-title">{{ $query->seller_phone }}</span>
                                        </td>
                                    </tr> -->
                                    <tr class="userDatatable-header">
                                        <td>
                                            <span class="projectDatatable-title">Category Name</span>
                                        </td>
                                        <td>
                                            <span class="projectDatatable-title">{{ $query->category_name }}</span>
                                        </td>
                                    </tr>
                                    <tr class="userDatatable-header">
                                        <td>
                                            <span class="projectDatatable-title">SubCategory Name</span>
                                        </td>
                                        <td>
                                            <span class="projectDatatable-title">{{ $query->sub_name }}</span>
                                        </td>
                                    </tr>
                                    <tr class="userDatatable-header">
                                        <td>
                                            <span class="projectDatatable-title">Product Name</span>
                                        </td>
                                        <td>
                                            <span class="projectDatatable-title">{{ $query->product_name }}</span>
                                        </td>
                                    </tr>
                                    <tr class="userDatatable-header">
                                        <td>
                                            <span class="projectDatatable-title">Brand</span>
                                        </td>
                                        <td>
                                            <span class="projectDatatable-title">{{ $query->qutation_form_product_brand }}</span>
                                        </td>
                                    </tr>
                                    <tr class="userDatatable-header">
                                        <td>
                                            <span class="projectDatatable-title">Message</span>
                                        </td>
                                        <td>
                                            <span class="projectDatatable-title">{{ $query->qutation_form_message }}</span>
                                        </td>
                                    </tr>
                                    <tr class="userDatatable-header">
                                        <td>
                                            <span class="projectDatatable-title">Address</span>
                                        </td>
                                        <td>
                                            <span class="projectDatatable-title">{{ $query->qutation_form_address }}</span>
                                        </td>
                                    </tr>
                                    <tr class="userDatatable-header">
                                        <td>
                                            <span class="projectDatatable-title">Zipcode</span>
                                        </td>
                                        <td>
                                            <span class="projectDatatable-title">{{ $query->qutation_form_zipcode }}</span>
                                        </td>
                                    </tr>
                                    <tr class="userDatatable-header">
                                        <td>
                                            <span class="projectDatatable-title">State</span>
                                        </td>
                                        <td>
                                            <span class="projectDatatable-title">{{ $query->qutation_form_state }}</span>
                                        </td>
                                    </tr>
                                    <tr class="userDatatable-header">
                                        <td>
                                            <span class="projectDatatable-title">City</span>
                                        </td>
                                        <td>
                                            <span class="projectDatatable-title">{{ $query->qutation_form_city }}</span>
                                        </td>
                                    </tr>
                                    <tr class="userDatatable-header">
                                        <td>
                                            <span class="projectDatatable-title">Time</span>
                                        </td>
                                        <td>
                                            <span class="projectDatatable-title">{{ $query->qutation_form_bid_time }} Day</span>
                                        </td>
                                    </tr>
                                    <tr class="userDatatable-header">
                                        <td>
                                            <span class="projectDatatable-title">Material</span>
                                        </td>
                                        <td>
                                            <span class="projectDatatable-title">{{ $query->qutation_form_material }}</span>
                                        </td>
                                    </tr>
                                    <tr class="userDatatable-header">
                                        <td>
                                            <span class="projectDatatable-title">Qty</span>
                                        </td>
                                        <td>
                                            <span class="projectDatatable-title">{{ $query->qutation_form_quantity }}</span>
                                        </td>
                                    </tr>
                                    <tr class="userDatatable-header">
                                        <td>
                                            <span class="projectDatatable-title">Unit</span>
                                        </td>
                                        <td>
                                            <span class="projectDatatable-title">{{ $query->qutation_form_date_unit }}</span>
                                        </td>
                                    </tr>
                                    <tr class="userDatatable-header">
                                        <td>
                                            <span class="projectDatatable-title">Profession</span>
                                        </td>
                                        <td>
                                            <span class="projectDatatable-title">{{ $query->seller_pro_ser }}</span>
                                        </td>
                                    </tr>
                                    <tr class="userDatatable-header">
                                        <td>
                                            <span class="projectDatatable-title">Quotation Type</span>
                                        </td>
                                        <td>
                                            <span class="projectDatatable-title">{{ $query->qutation_form_material }}</span>
                                        </td>
                                    </tr>








                                </table><!-- End: .table -->

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