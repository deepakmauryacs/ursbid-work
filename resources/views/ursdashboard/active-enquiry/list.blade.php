@extends('seller.layouts.app')
@section('title', 'Active Enquiry List')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@php
   $activeEnquiryBaseUrl = isset($selectedCategoryId) && $selectedCategoryId !== null
       ? route('seller.enquiry.list', ['id' => $selectedCategoryId])
       : route('seller.enquiry.list');
@endphp
<div class="container-fluid">
   <div class="social-dash-wrap">
      <div class="row">
         <div class="col-lg-12">
            <div class="breadcrumb-main">
               <h4 class="text-capitalize breadcrumb-title">Active Enquiry List</h4>
            </div>
         </div>
      </div>

      <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title">Bidding Price</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  <form action="{{ url('/openqotationpage') }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <input type="hidden" name="seller_email" value="{{ $sellerEmail }}">
                     <input type="hidden" name="product_id" class="product_id form-control">
                     <input type="hidden" name="product_quantity" class="product_quantity form-control">
                     <input type="hidden" name="product_name" class="product_name form-control">
                     <input type="hidden" name="user_email" class="user_email form-control">
                     <input type="hidden" name="data_id" class="data_id form-control">

                     <div class="d-flex gap-3">
                        <div class="flex-grow-1">
                           <label class="font-weight-bold">Enter Price</label>
                           <input type="number" name="price" required class="price form-control" placeholder="Enter Price" min="1">
                        </div>
                        <div>
                           <label class="font-weight-bold">Quantity</label>
                           <input readonly class="product_quantity form-control">
                        </div>
                     </div>

                     <div class="d-flex justify-content-between mt-3 gap-3 flex-wrap">
                        <div class="d-flex align-items-center">
                           <span class="font-weight-bold">Total :- </span>
                           <span class="total ms-2">0.00</span>
                        </div>
                        <div class="flex-grow-1">
                           <label class="font-weight-bold">Quotation file (optional)</label>
                           <input type="file" name="file" class="form-control">
                        </div>
                     </div>

                     <div class="d-flex mt-3 align-items-center">
                        <input type="checkbox" id="myCheckbox" required>
                        <span class="ms-2">I accept all the</span>&nbsp;
                        <a href="#!" class="exampleModalCentersmall" data-bs-toggle="modal" data-bs-target="#exampleModalCentersmall">agreement terms & condition</a>
                     </div>

                     <button type="submit" class="btn btn-primary mt-3">Submit</button>
                  </form>
               </div>
            </div>
         </div>
      </div>

      <div class="modal fade" id="exampleModalCentersmall" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title">Terms & Condition</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  <p>This Service Agreement ("Agreement") is valid from the date of quotation acceptance until the supply of material or completion of the work (as per the quotation).</p>
                  <p class="mb-2">URSBID, a company registered under the Companies Act, 2013, having its registered address at Parewpur, Dharshawa, Shrawasti 271835</p>
                  <div class="go-to-btn mb-3">
                     <a href="{{ url('/accept-terms-condition') }}"><small>Read More</small></a>
                  </div>
                  <div class="input-item mb-2 d-flex gap-2">
                     <input type="checkbox" data-bs-dismiss="modal" value="term_and_cond_acc" required>
                     I have read and accept the terms and conditions
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class="row">
         <div class="col-lg-12 mb-30">
            <div class="card mb-4 shadow-sm">
               <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                  <h5 class="mb-0">Filter Enquiries</h5>
                  <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#activeEnquiryFilters" aria-expanded="true" aria-controls="activeEnquiryFilters">
                     Toggle Filters
                  </button>
               </div>

               <div class="collapse show" id="activeEnquiryFilters">
                  <div class="card-body">
                     <form id="activeEnquiryFiltersForm" class="row g-3 align-items-end" method="get" action="{{ $activeEnquiryBaseUrl }}">
                        <div class="col-12 col-sm-6 col-lg-3">
                           <label class="form-label">Category</label>
                           <select name="category" id="category" class="form-select">
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
                           <input type="text" name="date" id="filterDate" class="form-control" placeholder="Date" value="{{ $data['date'] ?? '' }}">
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                           <label class="form-label">City</label>
                           <input type="text" name="city" id="filterCity" class="form-control" placeholder="City" value="{{ $data['city'] ?? '' }}">
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                           <label class="form-label">Quantity</label>
                           <input type="text" name="quantity" id="filterQuantity" class="form-control" placeholder="Quantity" value="{{ $data['quantity'] ?? '' }}">
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                           <label class="form-label">Product Name</label>
                           <input type="text" name="product_name" id="filterProduct" class="form-control" placeholder="Product Name" value="{{ $data['product_name'] ?? '' }}">
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                           <label class="form-label">Quotation ID</label>
                           <input type="text" name="qutation_id" id="filterQuotationId" class="form-control" placeholder="Quotation ID" value="{{ $data['qutation_id'] ?? '' }}">
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                           <label class="form-label">Records Per Page</label>
                           <select name="r_page" id="recordsPerPage" class="form-select">
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
                              <button type="button" id="resetActiveEnquiryFilters" class="btn btn-outline-secondary w-100 flex-fill">
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
                  <div id="activeEnquiryTable">
                     @include('ursdashboard.active-enquiry.partials.table', ['blogs' => $blogs, 'data' => $data, 'sellerEmail' => $sellerEmail])
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
   (function ($) {
      const $form = $('#activeEnquiryFiltersForm');
      const $tableWrapper = $('#activeEnquiryTable');
      const baseUrl = "{{ $activeEnquiryBaseUrl }}";

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

      function fetchActiveEnquiries(url) {
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
               $tableWrapper.html('<div class="alert alert-danger mb-0">Unable to load enquiries. Please try again.</div>');
            },
            complete: hideLoader
         });
      }

      $form.on('submit', function (event) {
         event.preventDefault();
         fetchActiveEnquiries();
      });

      $form.on('change', '#recordsPerPage', function () {
         $form.trigger('submit');
      });

      $('#resetActiveEnquiryFilters').on('click', function () {
         $form[0].reset();
         $form.find('select').each(function () {
            $(this).val('').trigger('change');
         });
         $form.find('input[type="text"]').val('');
         fetchActiveEnquiries(baseUrl);
      });

      $(document).on('click', '#activeEnquiryTable .pagination a', function (event) {
         event.preventDefault();
         const url = $(this).attr('href');
         if (url) {
            fetchActiveEnquiries(url);
         }
      });

      $(document).on('click', '#activeEnquiryTable .mdl_btn', function () {
         const $btn = $(this);
         const $modal = $('#exampleModalCenter');

         $modal.find('.product_quantity').val($btn.data('product-quantity'));
         $modal.find('.product_id').val($btn.data('product-id'));
         $modal.find('.product_name').val($btn.data('product-name'));
         $modal.find('.user_email').val($btn.data('user-email'));
         $modal.find('.data_id').val($btn.data('data-id'));
         $modal.find('.price').val('');
         $modal.find('.total').text('0.00');
      });

      $('#exampleModalCenter').on('input', '.price, .product_quantity', function () {
         const price = parseFloat($('#exampleModalCenter .price').val()) || 0;
         const quantity = parseFloat($('#exampleModalCenter .product_quantity').val()) || 0;
         $('#exampleModalCenter .total').text((price * quantity).toFixed(2));
      });

      $(document).on('click', '.exampleModalCentersmall', function () {
         $('#myCheckbox').prop('checked', true);
      });
   })(jQuery);
</script>
@endsection
