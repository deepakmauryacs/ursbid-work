@extends('seller.layouts.app')
@section('title', 'My Enquiry List')

@section('content')
<div class="container-fluid">
   <div class="social-dash-wrap">
      <div class="row">
         <div class="col-lg-12">
            <div class="breadcrumb-main">
               <h4 class="text-capitalize breadcrumb-title">My Enquiry List</h4>
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-lg-12 mb-30">
            <div class="card mb-4 shadow-sm">
               <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                  <h5 class="mb-0">Filter My Enquiries</h5>
                  <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#myEnquiryFilters" aria-expanded="true" aria-controls="myEnquiryFilters">
                     Toggle Filters
                  </button>
               </div>

               <div class="collapse show" id="myEnquiryFilters">
                  <div class="card-body">
                     <form id="myEnquiryFiltersForm" class="row g-3 align-items-end" method="get" action="{{ route('seller.enquiry.my-enquiry') }}">

                        <!-- Category -->
                        <div class="col-12 col-sm-6 col-lg-3">
                           <label for="myCategory" class="form-label">Category</label>
                           <div class="input-group">
                              <span class="input-group-text"><i class="bi bi-tags"></i></span>
                              <select name="category" id="myCategory" class="form-select">
                                 <option value="">Select Category</option>
                                 @foreach($category_data as $cat)
                                    <option value="{{ $cat->id }}" {{ ($data['category'] ?? null) == $cat->id ? 'selected' : '' }}>
                                       {{ $cat->name ?? $cat->title ?? '' }}
                                    </option>
                                 @endforeach
                              </select>
                           </div>
                        </div>

                        <!-- Quotation ID -->
                        <div class="col-12 col-sm-6 col-lg-3">
                           <label for="myQuotationId" class="form-label">Quotation ID</label>
                           <div class="input-group">
                              <span class="input-group-text"><i class="bi bi-file-earmark-text"></i></span>
                              <input type="text" id="myQuotationId" name="qutation_id" class="form-control" placeholder="Quotation ID" value="{{ $data['qutation_id'] ?? '' }}">
                           </div>
                        </div>

                        <!-- Date -->
                        <div class="col-12 col-sm-6 col-lg-3">
                           <label for="myDate" class="form-label">Date</label>
                           <div class="input-group">
                              <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                              <input type="text" id="myDate" name="date" class="form-control" placeholder="Date" value="{{ $data['date'] ?? '' }}">
                           </div>
                        </div>

                        <!-- City -->
                        <div class="col-12 col-sm-6 col-lg-3">
                           <label for="myCity" class="form-label">City</label>
                           <div class="input-group">
                              <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                              <input type="text" id="myCity" name="city" class="form-control" placeholder="City" value="{{ $data['city'] ?? '' }}">
                           </div>
                        </div>

                        <!-- Quantity -->
                        <div class="col-12 col-sm-6 col-lg-3">
                           <label for="myQuantity" class="form-label">Quantity</label>
                           <div class="input-group">
                              <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                              <input type="text" id="myQuantity" name="quantity" class="form-control" placeholder="Quantity" value="{{ $data['quantity'] ?? '' }}">
                           </div>
                        </div>

                        <!-- Product Name -->
                        <div class="col-12 col-sm-6 col-lg-3">
                           <label for="myProduct" class="form-label">Product Name</label>
                           <div class="input-group">
                              <span class="input-group-text"><i class="bi bi-bag"></i></span>
                              <input type="text" id="myProduct" name="product_name" class="form-control" placeholder="Product Name" value="{{ $data['product_name'] ?? '' }}">
                           </div>
                        </div>

                        <!-- Records Per Page -->
                        <div class="col-12 col-sm-6 col-lg-3">
                           <label for="myPerPage" class="form-label">Records Per Page</label>
                           <div class="input-group">
                              <span class="input-group-text"><i class="bi bi-list-ol"></i></span>
                              <select class="form-select" id="myPerPage" name="r_page">
                                 <option value="25" {{ ($data['r_page'] ?? 25) == 25 ? 'selected' : '' }}>25</option>
                                 <option value="50" {{ ($data['r_page'] ?? 25) == 50 ? 'selected' : '' }}>50</option>
                                 <option value="100" {{ ($data['r_page'] ?? 25) == 100 ? 'selected' : '' }}>100</option>
                              </select>
                           </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12 col-sm-6 col-lg-3">
                           <label class="form-label d-none d-lg-block">&nbsp;</label>
                           <div class="d-flex flex-column flex-sm-row flex-lg-column flex-xl-row gap-2">
                              <button type="submit" class="btn btn-primary w-100 flex-fill">
                                 <i class="bi bi-funnel-fill me-2"></i>Apply
                              </button>
                              <button type="button" id="resetMyEnquiryFilters" class="btn btn-outline-secondary w-100 flex-fill">
                                 <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                              </button>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>

            @if(Session::has('success'))
               <div class="alert alert-success alert-dismissible fade show">
                  {{ Session::get('success') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
               </div>
            @endif

            @if(Session::has('error'))
               <div class="alert alert-danger alert-dismissible fade show">
                  {{ Session::get('error') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
               </div>
            @endif

            @if ($errors->any())
               <div class="alert alert-danger alert-dismissible fade show">
                  <ul class="mb-0">
                     @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                     @endforeach
                  </ul>
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
               </div>
            @endif

            <div class="card shadow-sm">
               <div class="card-body">
                  <div id="myEnquiryTable">
                     @include('ursdashboard.my-enquiry.partials.table', ['blogs' => $blogs])
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
   (function () {
      const resetButton = document.getElementById('resetMyEnquiryFilters');
      const filtersForm = document.getElementById('myEnquiryFiltersForm');

      if (resetButton && filtersForm) {
         resetButton.addEventListener('click', function () {
            window.location.href = filtersForm.getAttribute('action');
         });
      }
   })();
</script>
@endsection
