@extends('seller.layouts.app')
@section('title', 'Active Enquiry List')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="container-fluid">
   <div class="social-dash-wrap">

      <!-- Page Header -->
      <div class="row">
         <div class="col-lg-12">
            <div class="breadcrumb-main">
               <h4 class="text-capitalize breadcrumb-title">Active Enquiry List</h4>
            </div>
         </div>
      </div>

      <!-- ======================= Bidding Price Modal ======================= -->
      <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title">Bidding Price</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  <form action="{{ url('/openqotationpage') }}" method="POST" enctype="multipart/form-data">
                     @csrf

                     <input type="hidden" name="seller_email" value="{{ session('seller')->email }}">
                     <input type="hidden" name="product_id" class="product_id form-control">
                     <input type="hidden" name="product_quantity" class="product_quantity form-control">
                     <input type="hidden" name="product_name" class="product_name form-control">
                     <input type="hidden" name="user_email" class="user_email form-control">
                     <input type="hidden" name="data_id" class="data_id form-control">

                     <div class="d-flex gap-3">
                        <div>
                           <label class="font-weight-bold">Enter Price</label>
                           <input type="number" name="price" required class="price form-control"
                              placeholder="Enter Price" min="1">
                        </div>
                        <div>
                           <label class="font-weight-bold">Quantity</label>
                           <input readonly class="product_quantity form-control">
                        </div>
                     </div>

                     <div class="d-flex justify-content-between mt-3">
                        <div class="d-flex align-items-center">
                           <span class="font-weight-bold">Total :- </span>
                           <span class="total ms-2">0.00</span>
                        </div>
                        <div>
                           <label class="font-weight-bold">Quotation file (optional)</label>
                           <input type="file" name="file" class="form-control">
                        </div>
                     </div>

                     <div class="d-flex mt-3">
                        <input type="checkbox" id="myCheckbox" required>
                        <span class="ms-2">I accept all the</span>&nbsp; 
                        <a href="#!" class="exampleModalCentersmall" data-bs-toggle="modal"
                           data-bs-target="#exampleModalCentersmall">agreement terms & condition</a>
                     </div>

                     <button type="submit" class="btn btn-primary mt-3">Submit</button>
                  </form>
               </div>
            </div>
         </div>
      </div>

      <!-- ======================= Terms & Condition Modal ======================= -->
      <div class="modal fade" id="exampleModalCentersmall" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title">Terms & Condition</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  <p>This Service Agreement ("Agreement") is valid from the date of quotation acceptance 
                     until the supply of material or completion of the work (as per the quotation).</p>
                  <p class="mb-2">URSBID, a company registered under the Companies Act, 2013, 
                     having its registered address at Parewpur, Dharshawa, Shrawasti 271835</p>
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

      <!-- ======================= Filter Section ======================= -->
      <div class="row">
         <div class="col-lg-12 mb-30">
            <div class="card mb-4 shadow-sm">
               <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                  <h5 class="mb-0">Filter Enquiries</h5>
                  <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" data-bs-toggle="collapse"
                     data-bs-target="#activeEnquiryFilters" aria-expanded="true" aria-controls="activeEnquiryFilters">
                     Toggle Filters
                  </button>
               </div>

               <div class="collapse show" id="activeEnquiryFilters">
                  <div class="card-body">
                     <form class="row g-3 align-items-end" method="get" action="">
                        <!-- Category -->
                        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
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

                        <!-- Date -->
                        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                           <label class="form-label">Date</label>
                           <input type="text" name="date" id="filterDate" class="form-control"
                              placeholder="Date" value="{{ $data['date'] ?? '' }}">
                        </div>

                        <!-- City -->
                        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                           <label class="form-label">City</label>
                           <input type="text" name="city" id="filterCity" class="form-control"
                              placeholder="City" value="{{ $data['city'] ?? '' }}">
                        </div>

                        <!-- Quantity -->
                        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                           <label class="form-label">Quantity</label>
                           <input type="text" name="quantity" id="filterQuantity" class="form-control"
                              placeholder="Quantity" value="{{ $data['quantity'] ?? '' }}">
                        </div>

                        <!-- Product Name -->
                        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                           <label class="form-label">Product Name</label>
                           <input type="text" name="product_name" id="filterProduct" class="form-control"
                              placeholder="Product Name" value="{{ $data['product_name'] ?? '' }}">
                        </div>

                        <!-- Records Per Page -->
                        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                           <label class="form-label">Records Per Page</label>
                           <select name="r_page" id="recordsPerPage" class="form-select select2">
                              <option value="25" {{ $data['r_page'] == 25 ? 'selected' : '' }}>25</option>
                              <option value="50" {{ $data['r_page'] == 50 ? 'selected' : '' }}>50</option>
                              <option value="100" {{ $data['r_page'] == 100 ? 'selected' : '' }}>100</option>
                           </select>
                        </div>

                        <!-- Apply Button -->
                        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                           <label class="form-label d-none d-lg-block">&nbsp;</label>
                           <button type="submit" class="btn btn-primary w-100">
                              <i class="bi bi-funnel-fill me-2"></i>Apply Filters
                           </button>
                        </div>

                        <!-- Reset Button -->
                        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                           <label class="form-label d-none d-lg-block">&nbsp;</label>
                           <a href="{{ url('seller/active-enquiry/list') }}" class="btn btn-outline-secondary w-100">
                              <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Filters
                           </a>
                        </div>
                     </form>
                  </div>
               </div>
            </div>

            <!-- ======================= Enquiry Table ======================= -->
            <div class="card shadow-sm">
               <div class="card-body">
                  
                  {{-- Success/Error Alerts --}}
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
                        <ul>
                           @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                           @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                     </div>
                  @endif

                  <div class="table-responsive">
                     <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                        <thead>
                           <tr>
                              <th>Sr no</th>
                              <th>Action</th>
                              <th>Name</th>
                              <th>Category</th>
                              <th>Sub Category</th>
                              <th>Product Name</th>
                              <th>Time</th>
                              <th>Date</th>
                              <th>Quantity</th>
                              <th>Unit</th>
                              <th>Quotation</th>
                              <th>Status</th>
                           </tr>
                        </thead>
                        <tbody>
                           @php $i = 1; @endphp
                           @foreach ($blogs as $blog)
                              @php
                                 $status = 'active';
                                 if (isset($blog->date_time) && isset($blog->bid_time)) {
                                    $postDate = \Carbon\Carbon::parse($blog->date_time);
                                    $expirationDate = $postDate->addDays($blog->bid_time);
                                    $currentDate = \Carbon\Carbon::now();
                                    $status = $currentDate->greaterThanOrEqualTo($expirationDate) ? 'deactive' : 'active';
                                 }
                              @endphp
                              <tr>
                                 <td>{{ $i++ }}</td>
                                 <td>
                                    <div class="d-flex gap-2">
                                       <a href="{{ url('seller/enquiry/view/'.$blog->id) }}" class="btn btn-primary">
                                          <i class="bi bi-eye me-1"></i>View
                                       </a>
                                       @php
                                          $showBiddingButton = false;
                                          if ($status == 'active') {
                                             $seller_email_id = session('seller')->email;
                                             $checkk22 = DB::table('bidding_price')
                                                ->where('data_id', $blog->id)
                                                ->where('payment_status','success')
                                                ->where('action','1')
                                                ->where('hide','1')
                                                ->first();
                                             $checkkk = DB::table('bidding_price')
                                                ->where('seller_email', $seller_email_id)
                                                ->where('data_id', $blog->id)
                                                ->where('payment_status','success')
                                                ->first();
                                             if(!$checkk22 && !$checkkk){
                                                $showBiddingButton = true;
                                             }
                                          }
                                       @endphp

                                       @if($showBiddingButton)
                                          <a href="#!" class="btn btn-outline-primary mdl_btn"
                                             data-bs-toggle="modal" data-bs-target="#exampleModalCenter"
                                             product_id="{{ $blog->product_id }}"
                                             product_quantity="{{ $blog->quantity }}"
                                             product_name="{{ $blog->product_name }}"
                                             data_id="{{ $blog->id }}"
                                             user_email="{{ $blog->email }}">
                                             <i class="bi bi-gavel me-1"></i>Bidding
                                          </a>
                                       @else
                                          <span class="btn btn-outline-danger disabled">
                                             <i class="bi bi-lock-fill me-1"></i>Closed
                                          </span>
                                       @endif
                                    </div>
                                 </td>
                                 <td>{{ $blog->name }}</td>
                                 <td>{{ $blog->category_name }}</td>
                                 <td>{{ $blog->sub_name }}</td>
                                 <td>{{ $blog->product_name }}</td>
                                 <td>{{ $blog->bid_time }} day</td>
                                 <td>{{ date('Y-m-d', strtotime($blog->date_time)) }}</td>
                                 <td>{{ $blog->quantity }}</td>
                                 <td>{{ $blog->unit }}</td>
                                 <td>
                                    @if(!empty($blog->qutation_form_image))
                                       <a href="{{ url('seller/enquiry/file/'.$blog->id) }}" target="_blank">View</a>
                                    @else
                                       No file found
                                    @endif
                                 </td>
                                 <td>
                                    <span class="badge bg-success-subtle text-success py-1 px-2 fs-13">{{ $status }}</span>
                                 </td>
                              </tr>
                           @endforeach
                        </tbody>
                     </table>

                     {{-- Pagination --}}
                     <div class="mt-3">
                        @if(isset($data['keyword']) || isset($data['r_page']))
                           {{ $blogs->appends($data)->links('pagination::bootstrap-4') }}
                        @else
                           {!! $blogs->links('pagination::bootstrap-4') !!}
                        @endif
                     </div>
                  </div>
               </div>
            </div>

         </div>
      </div>
   </div>
</div>

<!-- ======================= Scripts ======================= -->
<script>
   // Calculate total
   function calculateTotal() {
      const price = parseFloat(document.querySelector('.price').value) || 0;
      const quantity = parseInt(document.querySelector('.product_quantity').value) || 0;
      document.querySelector('.total').textContent = (price * quantity).toFixed(2);
   }

   document.querySelector('.price').addEventListener('input', calculateTotal);
   document.querySelector('.product_quantity').addEventListener('input', calculateTotal);

   // Populate modal fields
   $(document).on('click', '.mdl_btn', function() {
      $('.product_quantity').val($(this).attr('product_quantity'));
      $('.product_id').val($(this).attr('product_id'));
      $('.product_name').val($(this).attr('product_name'));
      $('.user_email').val($(this).attr('user_email'));
      $('.data_id').val($(this).attr('data_id'));

      $(".price").val('');
      $(".total").text('0.00');
   });

   // Auto-check main checkbox when terms modal is clicked
   document.querySelector(".exampleModalCentersmall").addEventListener("click", function() {
      document.getElementById("myCheckbox").checked = true;
   });
</script>
@endsection
