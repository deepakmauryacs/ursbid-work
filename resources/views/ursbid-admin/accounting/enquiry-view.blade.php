@extends('ursbid-admin.layouts.app')
@section('title', 'Enquiry Detail')
@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main d-flex justify-content-between align-items-center">
                    <h4 class="text-capitalize breadcrumb-title">Enquiry Detail</h4>
                    <a href="{{ route('super-admin.accounting.accounting-list') }}" class="btn btn-sm btn-outline-primary">Back</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-30">
                <div class="card">
                    <div class="card-body">
                        @if($query)
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <tbody>
                                    <tr>
                                        <th scope="row">Name</th>
                                        <td>{{ $query->seller_name }}</td>
                                    </tr>
                                <tr>
                                    <th scope="row">Email</th>
                                    <td>{{ $query->seller_email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Phone</th>
                                    <td>{{ $query->seller_phone }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Category</th>
                                    <td>{{ $query->category_name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Sub Category</th>
                                    <td>{{ $query->sub_name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Product Name</th>
                                    <td>{{ $query->product_name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Brand</th>
                                    <td>{{ $query->qutation_form_product_brand }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Message</th>
                                    <td>{{ $query->qutation_form_message }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Address</th>
                                    <td>{{ $query->qutation_form_address }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Zipcode</th>
                                    <td>{{ $query->qutation_form_zipcode }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">State</th>
                                    <td>{{ $query->qutation_form_state }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">City</th>
                                    <td>{{ $query->qutation_form_city }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Bid Time</th>
                                    <td>{{ $query->qutation_form_bid_time }} Day</td>
                                </tr>
                                <tr>
                                    <th scope="row">Material</th>
                                    <td>{{ $query->qutation_form_material }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Quantity</th>
                                    <td>{{ $query->qutation_form_quantity }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Unit</th>
                                    <td>{{ $query->qutation_form_date_unit }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Profession</th>
                                    <td>{{ $query->seller_pro_ser }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Quotation Type</th>
                                    <td>{{ $query->qutation_form_material }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Location</th>
                                    <td>{{ $query->qutation_form_location }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Bid Area</th>
                                    <td>{{ $query->qutation_form_bid_area }}</td>
                                </tr>
                                    <tr>
                                        <th scope="row">Images</th>
                                        <td>
                                            @if(!empty($query->qutation_form_image))
                                                @foreach(explode(',', $query->qutation_form_image) as $image)
                                                    <a href="{{ url('public/uploads/'.$image) }}" target="_blank" class="d-inline-block me-2">View</a>
                                                @endforeach
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">Enquiry details not found.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
