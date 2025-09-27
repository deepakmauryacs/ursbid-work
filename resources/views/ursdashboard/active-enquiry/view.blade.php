@extends('seller.layouts.app')
@section('title', 'Enquiry Details')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div class="container-fluid">
   <div class="social-dash-wrap">
      <div class="row">
         <div class="col-lg-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="breadcrumb-main">
               <h4 class="text-capitalize breadcrumb-title">Enquiry Details</h4>
            </div>
            <a href="{{ route('seller.enquiry.list') }}" class="btn btn-outline-secondary">
               <i class="bi bi-arrow-left-short me-1"></i>Back to List
            </a>
         </div>
      </div>

      <div class="row mt-3">
         <div class="col-lg-12">
            <div class="card shadow-sm">
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-bordered mb-0">
                        <tbody>
                           <tr>
                              <th class="w-25">Name</th>
                              <td>{{ $query->seller_name }}</td>
                           </tr>
                           <tr>
                              <th>Category Name</th>
                              <td>{{ $query->category_name }}</td>
                           </tr>
                           <tr>
                              <th>Sub Category Name</th>
                              <td>{{ $query->sub_name }}</td>
                           </tr>
                           <tr>
                              <th>Product Name</th>
                              <td>{{ $query->product_name }}</td>
                           </tr>
                           <tr>
                              <th>Brand</th>
                              <td>{{ $query->qutation_form_product_brand }}</td>
                           </tr>
                           <tr>
                              <th>Message</th>
                              <td>{{ $query->qutation_form_message }}</td>
                           </tr>
                           <tr>
                              <th>Address</th>
                              <td>{{ $query->qutation_form_address }}</td>
                           </tr>
                           <tr>
                              <th>Zipcode</th>
                              <td>{{ $query->qutation_form_zipcode }}</td>
                           </tr>
                           <tr>
                              <th>State</th>
                              <td>{{ $query->qutation_form_state }}</td>
                           </tr>
                           <tr>
                              <th>City</th>
                              <td>{{ $query->qutation_form_city }}</td>
                           </tr>
                           <tr>
                              <th>Bid Time</th>
                              <td>{{ $query->qutation_form_bid_time }} Day</td>
                           </tr>
                           <tr>
                              <th>Material</th>
                              <td>{{ $query->qutation_form_material }}</td>
                           </tr>
                           <tr>
                              <th>Quantity</th>
                              <td>{{ $query->qutation_form_quantity }}</td>
                           </tr>
                           <tr>
                              <th>Unit</th>
                              <td>{{ $query->qutation_form_unit }}</td>
                           </tr>
                           <tr>
                              <th>Profession</th>
                              <td>{{ $query->seller_pro_ser }}</td>
                           </tr>
                           <tr>
                              <th>Quotation Type</th>
                              <td>{{ $query->qutation_form_material }}</td>
                           </tr>
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
