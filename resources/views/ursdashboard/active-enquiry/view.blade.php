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
            <a href="{{ route('seller.enquiry.list') }}" class="btn btn-outline-secondary btn-sm">
               <i class="bi bi-arrow-left-short me-1"></i>Back to List
            </a>
         </div>
      </div>

      <div class="row mt-3">
         <div class="col-lg-12 mb-30">
            <div class="card shadow-sm">
               <div class="card-body">
                  <form class="row g-3">
                     <div class="col-md-6">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" class="form-control" value="{{ $query->seller_name }}" disabled>
                     </div>
                     <div class="col-md-6">
                        <label class="form-label fw-semibold">Category Name</label>
                        <input type="text" class="form-control" value="{{ $query->category_name }}" disabled>
                     </div>
                     <div class="col-md-6">
                        <label class="form-label fw-semibold">Sub Category Name</label>
                        <input type="text" class="form-control" value="{{ $query->sub_name }}" disabled>
                     </div>
                     <div class="col-md-6">
                        <label class="form-label fw-semibold">Product Name</label>
                        <input type="text" class="form-control" value="{{ $query->product_name }}" disabled>
                     </div>
                     <div class="col-md-6">
                        <label class="form-label fw-semibold">Brand</label>
                        <input type="text" class="form-control" value="{{ $query->qutation_form_product_brand }}" disabled>
                     </div>
                     <div class="col-12">
                        <label class="form-label fw-semibold">Message</label>
                        <textarea class="form-control" rows="3" disabled>{{ $query->qutation_form_message }}</textarea>
                     </div>
                     <div class="col-12">
                        <label class="form-label fw-semibold">Address</label>
                        <textarea class="form-control" rows="2" disabled>{{ $query->qutation_form_address }}</textarea>
                     </div>
                     <div class="col-md-4">
                        <label class="form-label fw-semibold">Zipcode</label>
                        <input type="text" class="form-control" value="{{ $query->qutation_form_zipcode }}" disabled>
                     </div>
                     <div class="col-md-4">
                        <label class="form-label fw-semibold">State</label>
                        <input type="text" class="form-control" value="{{ $query->qutation_form_state }}" disabled>
                     </div>
                     <div class="col-md-4">
                        <label class="form-label fw-semibold">City</label>
                        <input type="text" class="form-control" value="{{ $query->qutation_form_city }}" disabled>
                     </div>
                     <div class="col-md-4">
                        <label class="form-label fw-semibold">Bid Time (Days)</label>
                        <input type="text" class="form-control" value="{{ $query->qutation_form_bid_time }}" disabled>
                     </div>
                     <div class="col-md-4">
                        <label class="form-label fw-semibold">Material</label>
                        <input type="text" class="form-control" value="{{ $query->qutation_form_material }}" disabled>
                     </div>
                     <div class="col-md-4">
                        <label class="form-label fw-semibold">Quantity</label>
                        <input type="text" class="form-control" value="{{ $query->qutation_form_quantity }}" disabled>
                     </div>
                     <div class="col-md-4">
                        <label class="form-label fw-semibold">Unit</label>
                        <input type="text" class="form-control" value="{{ $query->qutation_form_unit }}" disabled>
                     </div>
                     <div class="col-md-4">
                        <label class="form-label fw-semibold">Profession</label>
                        <input type="text" class="form-control" value="{{ $query->seller_pro_ser }}" disabled>
                     </div>
                     <div class="col-md-4">
                        <label class="form-label fw-semibold">Quotation Type</label>
                        <input type="text" class="form-control" value="{{ $query->qutation_form_material }}" disabled>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
