@extends('seller.layouts.app')
@section('title', 'Closed Enquiry List')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div class="container-fluid">
   <div class="social-dash-wrap">
      <div class="row">
         <div class="col-lg-12">
            <div class="breadcrumb-main">
               <h4 class="text-capitalize breadcrumb-title">Closed Enquiry List</h4>
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-lg-12 mb-30">
            <div class="card mb-4 shadow-sm">
               <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                  <h5 class="mb-0">Filter Closed Enquiries</h5>
                  <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#closedEnquiryFilters" aria-expanded="true" aria-controls="closedEnquiryFilters">
                     Toggle Filters
                  </button>
               </div>
               <div class="collapse show" id="closedEnquiryFilters">
                  <div class="card-body">
                     <form id="closedEnquiryFiltersForm" class="row g-3 align-items-end" method="get" action="{{ route('seller.enquiry.closed') }}">
                        <div class="col-12 col-sm-6 col-lg-3">
                           <label class="form-label">Category</label>
                           <select name="category" class="form-select">
                              <option value="">Select Category</option>
                              @foreach($category_data as $cat)
                                 <option value="{{ $cat->id }}" {{ $data['category'] == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name ?? $cat->title ?? '' }}
                                 </option>
                              @endforeach
                           </select>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                           <label class="form-label">Date</label>
                           <input type="text" name="date" class="form-control" placeholder="Date" value="{{ $data['date'] ?? '' }}">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                           <label class="form-label">City</label>
                           <input type="text" name="city" class="form-control" placeholder="City" value="{{ $data['city'] ?? '' }}">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                           <label class="form-label">Quantity</label>
                           <input type="text" name="quantity" class="form-control" placeholder="Quantity" value="{{ $data['quantity'] ?? '' }}">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                           <label class="form-label">Product Name</label>
                           <input type="text" name="product_name" class="form-control" placeholder="Product Name" value="{{ $data['product_name'] ?? '' }}">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                           <label class="form-label">Quotation ID</label>
                           <input type="text" name="qutation_id" class="form-control" placeholder="Quotation ID" value="{{ $data['qutation_id'] ?? '' }}">
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                           <label class="form-label">Records Per Page</label>
                           <select name="r_page" class="form-select">
                              <option value="25" {{ $data['r_page'] == 25 ? 'selected' : '' }}>25</option>
                              <option value="50" {{ $data['r_page'] == 50 ? 'selected' : '' }}>50</option>
                              <option value="100" {{ $data['r_page'] == 100 ? 'selected' : '' }}>100</option>
                           </select>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                           <label class="form-label d-none d-lg-block">&nbsp;</label>
                           <div class="d-flex flex-column flex-sm-row flex-lg-column flex-xl-row gap-2">
                              <button type="submit" class="btn btn-primary w-100 flex-fill">
                                 <i class="bi bi-funnel-fill me-2"></i>Apply
                              </button>
                              <button type="button" id="resetClosedEnquiryFilters" class="btn btn-outline-secondary w-100 flex-fill">
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
               <div class="card-body" id="closedEnquiryTable">
                  @include('ursdashboard.closed-enquiry.partials.table', ['blogs' => $blogs, 'data' => $data])
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
   (function ($) {
      const $form = $('#closedEnquiryFiltersForm');
      const $tableWrapper = $('#closedEnquiryTable');
      const baseUrl = "{{ route('seller.enquiry.closed') }}";

      function updateHistory(url, serializedForm) {
         const params = new URLSearchParams(serializedForm);
         const requestUrl = new URL(url, window.location.origin);
         const page = requestUrl.searchParams.get('page');

         if (page) {
            params.set('page', page);
         } else {
            params.delete('page');
         }

         const finalUrl = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
         window.history.replaceState({}, '', finalUrl);
      }

      function showLoader() {
         $tableWrapper.addClass('position-relative');
         if (!$tableWrapper.find('.table-loader').length) {
            const loader = $('<div class="table-loader position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-white bg-opacity-75"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            $tableWrapper.append(loader);
         }
      }

      function hideLoader() {
         $tableWrapper.find('.table-loader').remove();
      }

      function fetchClosedEnquiries(url) {
         const requestUrl = url || baseUrl;
         const formData = $form.serialize();

         $.ajax({
            url: requestUrl,
            data: formData,
            type: 'GET',
            beforeSend: showLoader,
            success: function (response) {
               $tableWrapper.html(response);
               updateHistory(requestUrl, formData);
            },
            error: function () {
               alert('Unable to load closed enquiries. Please try again.');
            },
            complete: hideLoader,
         });
      }

      $form.on('submit', function (event) {
         event.preventDefault();
         fetchClosedEnquiries();
      });

      $tableWrapper.on('click', '.pagination a', function (event) {
         event.preventDefault();
         const url = $(this).attr('href');
         if (url) {
            fetchClosedEnquiries(url);
         }
      });

      $('#resetClosedEnquiryFilters').on('click', function () {
         $form[0].reset();
         fetchClosedEnquiries(baseUrl);
      });
   })(jQuery);
</script>
@endsection
