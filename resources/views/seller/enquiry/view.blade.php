@extends('seller.layouts.app')
@section('title', 'Detail ')
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div class="container-fluid">
   <div class="social-dash-wrap">
      <div class="row">
         <div class="col-lg-12">
            <div class="breadcrumb-main">
               <h4 class="text-capitalize breadcrumb-title">Detail</h4>
            </div>
         </div>
      </div>
      <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Bidding Price</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                  <form class="row g-3">
                     <div class="col-md-6">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" class="form-control" value="{{ $query->seller_name }}" readonly>
                     </div>
                     <div class="col-md-6">
                        <label class="form-label fw-semibold">Category Name</label>
                        <input type="text" class="form-control" value="{{ $query->category_name }}" readonly>
                     </div>
                     <div class="col-md-6">
                        <label class="form-label fw-semibold">SubCategory Name</label>
                        <input type="text" class="form-control" value="{{ $query->sub_name }}" readonly>
                     </div>
                     <div class="col-md-6">
                        <label class="form-label fw-semibold">Product Name</label>
                        <input type="text" class="form-control" value="{{ $query->product_name }}" readonly>
                     </div>
                     <div class="col-md-6">
                        <label class="form-label fw-semibold">Brand</label>
                        <input type="text" class="form-control" value="{{ $query->qutation_form_product_brand }}" readonly>
                     </div>
                     <div class="col-12">
                        <label class="form-label fw-semibold">Message</label>
                        <textarea class="form-control" rows="3" readonly>{{ $query->qutation_form_message }}</textarea>
                     </div>
                     <div class="col-12">
                        <label class="form-label fw-semibold">Address</label>
                        <textarea class="form-control" rows="2" readonly>{{ $query->qutation_form_address }}</textarea>
                     </div>
                     <div class="col-md-4">
                        <label class="form-label fw-semibold">Zipcode</label>
                        <input type="text" class="form-control" value="{{ $query->qutation_form_zipcode }}" readonly>
                     </div>
                     <div class="col-md-4">
                        <label class="form-label fw-semibold">State</label>
                        <input type="text" class="form-control" value="{{ $query->qutation_form_state }}" readonly>
                     </div>
                     <div class="col-md-4">
                        <label class="form-label fw-semibold">City</label>
                        <input type="text" class="form-control" value="{{ $query->qutation_form_city }}" readonly>
                     </div>
                     <div class="col-md-4">
                        <label class="form-label fw-semibold">Bid Time (Days)</label>
                        <input type="text" class="form-control" value="{{ $query->qutation_form_bid_time }}" readonly>
                     </div>
                     <div class="col-md-4">
                        <label class="form-label fw-semibold">Material</label>
                        <input type="text" class="form-control" value="{{ $query->qutation_form_material }}" readonly>
                     </div>
                     <div class="col-md-4">
                        <label class="form-label fw-semibold">Quantity</label>
                        <input type="text" class="form-control" value="{{ $query->qutation_form_quantity }}" readonly>
                     </div>
                     <div class="col-md-4">
                        <label class="form-label fw-semibold">Unit</label>
                        <input type="text" class="form-control" value="{{ $query->qutation_form_date_unit }}" readonly>
                     </div>
                     <div class="col-md-4">
                        <label class="form-label fw-semibold">Profession</label>
                        <input type="text" class="form-control" value="{{ $query->seller_pro_ser }}" readonly>
                     </div>
                     <div class="col-md-4">
                        <label class="form-label fw-semibold">Quotation Type</label>
                        <input type="text" class="form-control" value="{{ $query->qutation_form_material }}" readonly>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

