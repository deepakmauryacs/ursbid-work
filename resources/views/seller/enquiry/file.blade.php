@extends('seller.layouts.app')
@section('title', 'Enquiry List')
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div class="container-fluid">
   <div class="social-dash-wrap">
      <div class="row">
         <div class="col-lg-12">
            <div class="breadcrumb-main">
               <h4 class="text-capitalize breadcrumb-title">Quotaion </h4>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-lg-12 mb-30">
            <div class="card">
               <div class="card-body">
                  @if(Session::has('success'))
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                     {{ Session::get('success') }}
                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                  @endif
                  @if(Session::has('error'))
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                     {{ Session::get('error') }}
                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                  @endif
                  <div class="table-responsive">
                     <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                     {{-- Standard seller table class --}}
                     <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                        <thead>
                           <tr class="userDatatable-header">
                              <th>
                                 <span class="projectDatatable-title">Sr no</span>
                              </th>
                              <th>
                                 <span class="projectDatatable-title">Qutation</span>
                              </th>
                              <!-- <th>
                                 <span class="projectDatatable-title">Action</span>
                                 </th> -->
                           </tr>
                        </thead>
                        <tbody>
                           @php
                           $i=1;
                           @endphp
                           @foreach($images as $blog)
                           <tr>
                              <td>
                                 <div class="userDatatable-content text-center">
                                    {{ $i++ }}
                                 </div>
                              </td>
                              <td>
                                 <div class="userDatatable-content">
                                    <a href="{{ url('public/bidfile/'.$blog) }}"
                                       target="_blank">View</a>
                                 </div>
                              </td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
</div>
<script type="text/javascript">
   $(function() {
       $(document).on('click', '.mdl_btn', function() {
           var product_id = $(this).attr('product_id');
           var product_name = $(this).attr('product_name');
           var user_email = $(this).attr('user_email');
           var data_id = $(this).attr('data_id');
           $('.product_id').val(product_id);
           $('.product_name').val(product_name);
           $('.user_email').val(user_email);
           $('.data_id').val(data_id);
   
       })
   })
</script>
@endsection