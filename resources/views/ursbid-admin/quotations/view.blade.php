@extends('ursbid-admin.layouts.app')
@section('title', 'Quotation Detail')

@section('content')

<div class="container-fluid">
    <!-- Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Quotation Detail</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Quotation Detail</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div id="table-container">
                        <div class="table-responsive">
                            <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                                <tr class="userDatatable-header">
                                    <td>
                                        <span class="projectDatatable-title">Name</span>
                                    </td>
                                    <td>
                                        <span class="projectDatatable-title">{{ $quotation->seller_name }}</span>
                                    </td>
                                </tr>
                                <tr class="userDatatable-header">
                                    <td>
                                        <span class="projectDatatable-title">Email</span>
                                    </td>
                                    <td>
                                        <span class="projectDatatable-title">{{ $quotation->seller_email }}</span>
                                    </td>
                                </tr>
                                <tr class="userDatatable-header">
                                    <td>
                                        <span class="projectDatatable-title">Phone</span>
                                    </td>
                                    <td>
                                        <span class="projectDatatable-title">{{ $quotation->seller_phone }}</span>
                                    </td>
                                </tr>
                                <tr class="userDatatable-header">
                                    <td>
                                        <span class="projectDatatable-title">Category Name</span>
                                    </td>
                                    <td>
                                        <span class="projectDatatable-title">{{ $quotation->category_name }}</span>
                                    </td>
                                </tr>
                                <tr class="userDatatable-header">
                                    <td>
                                        <span class="projectDatatable-title">Sub Category Name</span>
                                    </td>
                                    <td>
                                        <span class="projectDatatable-title">{{ $quotation->sub_name }}</span>
                                    </td>
                                </tr>
                                <tr class="userDatatable-header">
                                    <td>
                                        <span class="projectDatatable-title">Product Name</span>
                                    </td>
                                    <td>
                                        <span class="projectDatatable-title">{{ $quotation->product_name }}</span>
                                    </td>
                                </tr>
                                <tr class="userDatatable-header">
                                    <td>
                                        <span class="projectDatatable-title">Brand</span>
                                    </td>
                                    <td>
                                        <span class="projectDatatable-title">{{ $quotation->qutation_form_product_brand }}</span>
                                    </td>
                                </tr>
                                <tr class="userDatatable-header">
                                    <td>
                                        <span class="projectDatatable-title">Message</span>
                                    </td>
                                    <td>
                                        <span class="projectDatatable-title">{{ $quotation->qutation_form_message }}</span>
                                    </td>
                                </tr>
                                <tr class="userDatatable-header">
                                    <td>
                                        <span class="projectDatatable-title">Address</span>
                                    </td>
                                    <td>
                                        <span class="projectDatatable-title">{{ $quotation->qutation_form_address }}</span>
                                    </td>
                                </tr>
                                <tr class="userDatatable-header">
                                    <td>
                                        <span class="projectDatatable-title">Zipcode</span>
                                    </td>
                                    <td>
                                        <span class="projectDatatable-title">{{ $quotation->qutation_form_zipcode }}</span>
                                    </td>
                                </tr>
                                <tr class="userDatatable-header">
                                    <td>
                                        <span class="projectDatatable-title">State</span>
                                    </td>
                                    <td>
                                        <span class="projectDatatable-title">{{ $quotation->qutation_form_state }}</span>
                                    </td>
                                </tr>
                                <tr class="userDatatable-header">
                                    <td>
                                        <span class="projectDatatable-title">City</span>
                                    </td>
                                    <td>
                                        <span class="projectDatatable-title">{{ $quotation->qutation_form_city }}</span>
                                    </td>
                                </tr>
                                <tr class="userDatatable-header">
                                    <td>
                                        <span class="projectDatatable-title">Time</span>
                                    </td>
                                    <td>
                                        <span class="projectDatatable-title">{{ $quotation->qutation_form_bid_time }} Day</span>
                                    </td>
                                </tr>
                                <tr class="userDatatable-header">
                                    <td>
                                        <span class="projectDatatable-title">Material</span>
                                    </td>
                                    <td>
                                        <span class="projectDatatable-title">{{ $quotation->qutation_form_material }}</span>
                                    </td>
                                </tr>
                                <tr class="userDatatable-header">
                                    <td>
                                        <span class="projectDatatable-title">Qty</span>
                                    </td>
                                    <td>
                                        <span class="projectDatatable-title">{{ $quotation->qutation_form_quantity }}</span>
                                    </td>
                                </tr>
                                <tr class="userDatatable-header">
                                    <td>
                                        <span class="projectDatatable-title">Unit</span>
                                    </td>
                                    <td>
                                        <span class="projectDatatable-title">{{ $quotation->qutation_form_date_unit }}</span>
                                    </td>
                                </tr>
                                <tr class="userDatatable-header">
                                    <td>
                                        <span class="projectDatatable-title">Profession</span>
                                    </td>
                                    <td>
                                        <span class="projectDatatable-title">{{ $quotation->seller_pro_ser }}</span>
                                    </td>
                                </tr>
                                <tr class="userDatatable-header">
                                    <td>
                                        <span class="projectDatatable-title">Quotation Type</span>
                                    </td>
                                    <td>
                                        <span class="projectDatatable-title">{{ $quotation->qutation_form_material }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush
