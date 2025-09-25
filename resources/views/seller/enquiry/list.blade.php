@extends('seller.layouts.app')
@section('title', 'Active Enquiry List')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">

                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Active Enquiry List</h4>
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <!-- <div class="action-btn">

                                        <div class="form-group mb-0">
                                            <div class="input-container icon-left position-relative">
                                                <span class="input-icon icon-left">
                                                    <span data-feather="calendar"></span>
                                                </span>
                                                <input type="text" class="form-control form-control-default date-ranger" name="date-ranger" placeholder="Oct 30, 2019 - Nov 30, 2019">
                                                <span class="input-icon icon-right">
                                                    <span data-feather="chevron-down"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div> -->


                        <!-- <div class="action-btn">
                            <a href="{{ url('admin/Buyer/add') }}" class="btn btn-sm btn-primary btn-add">
                                <i class="la la-plus"></i> Add New Buyer</a>
                        </div> -->
                    </div>
                </div>

            </div>

        </div>

        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Bidding Price</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('/openqotationpage') }}" method="POST" enctype="multipart/form-data">
                            <!-- <form action="{{ url('/bidding_price') }}" method="POST"> -->
                            @csrf
                            <input type="hidden" value="{{ session('seller')->email }}" name="seller_email">
                            <input type='hidden' name='product_id' class='product_id form-control'>
                            <input type='hidden' name='product_quantity' class='product_quantity form-control'>
                            <input type='hidden' name='product_name' class='product_name form-control'>
                            <input type='hidden' name='user_email' class='user_email form-control'>
                            <input type='hidden' name='data_id' class='data_id form-control'>

                            <div class="d-flex">
                                <div>
                                    <label class="font-weight-bold">Enter Price </label>

                                    <input type='number' name='price' required class='price form-control'
                                        placeholder="Enter Price" min="1">
                                </div>

                                <div>
                                    <label class="font-weight-bold">Quantity </label>
                                    <input readonly class='product_quantity form-control'>
                                </div>

                            </div>
                            <div class="d-flex justify-content-between ">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="font-weight-bold">Total :- </div>
                                    <div class="total"> 0.00</div>
                                </div>
                                <div>
                                    <div>
                                        <label class="font-weight-bold">Quotation file (optional)</label>
                                        <input type="file" class=' form-control' name="file">
                                    </div>
                                </div>

                            </div>
                            <div class="d-flex  ">

                                <input type="checkbox" id="myCheckbox" required> &nbsp; I accept all the  &nbsp; 
                                <a data-toggle="modal" href="#!" class="exampleModalCentersmall" data-target="#exampleModalCentersmall"> agreement terms & condition </a>
                            </div>

                            <button type="submit" class="btn btn-primary mt-2">Submit</button>
                        </form>
                    </div>
                    <div class="modal-footer">


                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="exampleModalCentersmall" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Terms & Condition</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">This Service Agreement (hereinafter referred to as "Agreement") has been entered from the date of quotation accepting date & valid till the supply of material or completion of the work (quantity referred from quotation page) is not completed. </p>
             <p class="mb-2">URSBID, a company registered under the Companies Act, 2013, having its registered address at Parewpur, Dharshawa, Shrawasti 271835 </p>
                        
                        
                        <div class="go-to-btn mb-4">
                                <a href="{{url('/accept-terms-condition')}}"><small>Read More</small></a>
                            </div>
                            
                           
                                    <div class="input-item input-item-textarea ltn__custom-icon mb-4 d-flex gap-3" >

                                        <input type="checkbox" class="close" data-dismiss="modal" aria-label="Close" name="term_and_condition" required="" value="term_and_cond_acc"> I have read and accept the terms and
                                        conditions
                                  
                                                                    </div>
                        
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
                        <form class="row" method="get" action="">
                            <div class="col-md-2 no-margin-left">
                                <div class="form-group">
                                    <!-- <input type="text" name="category" class="form-control" placeholder="Category"
                                        value="{{ isset($data['category']) ? $data['category'] : '' }}"> -->
                                    <select name="category" id="" class="form-control">
                                        <option value="">Select Category</option>
                                        @foreach($category_data as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ $data['category'] == $cat->id ? 'selected' : '' }}>{{ $cat->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 no-margin-left">
                                <div class="form-group">
                                    <input type="text" name="date" class="form-control" placeholder="Date"
                                        value="{{ isset($data['date']) ? $data['date'] : '' }}">
                                </div>
                            </div>
                            <div class="col-md-2 no-margin-left">
                                <div class="form-group">
                                    <input type="text" name="city" class="form-control" placeholder="City"
                                        value="{{ isset($data['city']) ? $data['city'] : '' }}">
                                </div>
                            </div>
                            <div class="col-md-2 no-margin-left">
                                <div class="form-group">
                                    <input type="text" name="quantity" class="form-control" placeholder="Quantity"
                                        value="{{ isset($data['quantity']) ? $data['quantity'] : '' }}">
                                </div>
                            </div>
                            <div class="col-md-2 no-margin-left">
                                <div class="form-group">
                                    <input type="text" name="product_name" class="form-control"
                                        placeholder="Product Name"
                                        value="{{ isset($data['product_name']) ? $data['product_name'] : '' }}">
                                </div>
                            </div>

                            <div class="col-md-1 no-padding-left">
                                <div class="form-group">
                                    <select class="form-control select2" name="r_page">
                                        <option value="25" {{ $data['r_page'] == 25 ? 'selected' : '' }}> 25 Records Per
                                            Page</option>
                                        <option value="50" {{ $data['r_page'] == 50 ? 'selected' : '' }}> 50 Records Per
                                            Page</option>
                                        <option value="100" {{ $data['r_page'] == 100 ? 'selected' : '' }}> 100 Records
                                            Per Page</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-1 no-padding-left">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>

                    </div>
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
                        @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <div class="userDatatable projectDatatable project-table bg-white w-100 border-0">
                            <div class="table-responsive">
                            <table class="table mb-0">
                                    <thead>
                                        <tr class="userDatatable-header">
                                            <th>
                                                <span class="projectDatatable-title">Sr no</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Name</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Category</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Sub Category</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Product_name</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Time</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Date</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Quantity</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Unit</span>
                                            </th>

                                            <th>
                                                <span class="projectDatatable-title">Qutation</span>
                                            </th>

                                            <th>
                                                <span class="projectDatatable-title">Status</span>
                                            </th>

                                            <th>
                                                <span class="projectDatatable-title">Action</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $i=1;
                                        @endphp
                                        @foreach ($blogs as $blog)
                                        @if (isset($blog->date_time) && isset($blog->bid_time))
                                        @php
                                        // Use the Carbon namespace
                                        $postDate = \Carbon\Carbon::parse($blog->date_time);
                                        $expirationDate = $postDate->addDays($blog->bid_time);
                                        $currentDate = \Carbon\Carbon::now();
                                        $status = $currentDate->greaterThanOrEqualTo($expirationDate) ? 'deactive' :
                                        'active';
                                        @endphp
                                        @else
                                        @php
                                        $status = 'active';
                                        @endphp
                                        @endif
                                        <tr>
                                            <td>
                                                <div class="userDatatable-content text-center">
                                                    {{ $i++ }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $blog->name }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $blog->category_name }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $blog->sub_name }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $blog->product_name }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $blog->bid_time }} day
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ date('Y-m-d', strtotime($blog->date_time)) }}

                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $blog->quantity }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $blog->unit }}
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                if(!empty($blog->qutation_form_image)){
                                                @endphp
                                                <div class="userDatatable-content">
                                                    <a href="{{ url('seller/enquiry/file/'.$blog->id) }}"
                                                        target="_blank">View</a>
                                                </div>
                                                @php
                                                }else{
                                                @endphp
                                                No file found
                                                @php
                                                }
                                                @endphp
                                            </td>

                                            <td>
                                                <div class="userDatatable-content">
                                                    <span class="media-badge color-white bg-success">
                                                        {{ $status }}</span>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="d-inline-block">

                                                    <a href="{{ url('seller/enquiry/view/'.$blog->id) }}">
                                                        <span class="media-badge color-white bg-primary">View</span>
                                                    </a>

                                                    @php
                                                        if($status == 'active'){
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

                                                            if(!$checkk22){
                                                                if($checkkk){
                                                    @endphp

                                                                    <a href="#!">
                                                                        <span class="media-badge color-white bg-danger">Closed</span>
                                                                    </a>

                                                    @php
                                                                } else {
                                                    @endphp

                                                    <a href="#!" class="mdl_btn" data-toggle="modal"
                                                        data-target="#exampleModalCenter"
                                                        product_id='{{ $blog->product_id }}'
                                                        product_quantity='{{ $blog->quantity }}'
                                                        product_name='{{ $blog->product_name }}'
                                                        data_id='{{ $blog->id }}' user_email='{{ $blog->email }}'>
                                                        <span class="media-badge color-white bg-primary">Bidding</span>
                                                    </a>

                                                    @php
                                                                }
                                                            } else {
                                                    @endphp

                                                                <a href="#!">
                                                                    <span class="media-badge color-white bg-danger">Closed</span>
                                                                </a>

                                                    @php
                                                            }
                                                        } else {
                                                    @endphp

                                                            <a href="#!">
                                                                <span class="media-badge color-white bg-danger">Closed</span>
                                                            </a>

                                                    @php
                                                        }
                                                    @endphp

                                                </div>
                                            </td>


                                        </tr>
                                        @endforeach



                                    </tbody>
                                </table><!-- End: .table -->
                                @if(isset($data['keyword']) && $blogs->count() > 0)
                                <div class="pagination">
                                    {{ $blogs->appends($data)->links('pagination::bootstrap-4') }}
                                </div>
                                @elseif(isset($data['r_page']) && $blogs->count() > 0)
                                <div class="pagination">
                                    {{ $blogs->appends($data)->links('pagination::bootstrap-4') }}
                                </div>
                                @else

                                <div class="gmz-pagination mt-1">
                                    {!! $blogs->links('pagination::bootstrap-4') !!}
                                </div>
                                @endif

                                <!-- <div class="gmz-pagination mt-1">
                                {!! $blogs->links('pagination::bootstrap-4') !!}
                            </div> -->
                            </div>
                        </div><!-- End: .userDatatable -->

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>

<script>
// Get references to the input fields and total display
const priceInput = document.querySelector('.price');
const quantityInput = document.querySelector('.product_quantity');
const totalDisplay = document.querySelector('.total');

// Function to calculate and display the total
function calculateTotal() {
    const price = parseFloat(priceInput.value) || 0; // Get price value or default to 0
    const quantity = parseInt(quantityInput.value) || 0; // Get quantity value or default to 0
    const total = price * quantity; // Calculate total
    totalDisplay.textContent = total.toFixed(2); // Display total (formatted to 2 decimal places)
}

// Event listeners to trigger calculation
priceInput.addEventListener('input', calculateTotal);
quantityInput.addEventListener('input', calculateTotal);
</script>

<script type="text/javascript">
$(function() {
    $(document).on('click', '.mdl_btn', function() {
        var product_quantity = $(this).attr('product_quantity');
        var product_id = $(this).attr('product_id');
        var product_name = $(this).attr('product_name');
        var user_email = $(this).attr('user_email');
        var data_id = $(this).attr('data_id');
        $('.product_quantity').val(product_quantity);
        $('.product_id').val(product_id);
        $('.product_name').val(product_name);
        $('.user_email').val(user_email);
        $('.data_id').val(data_id);

    })
})

$(document).ready(function() {
    $(".mdl_btn").click(function() {

        $(".price").val('');
        $(".total").text('0.00');
    });
});
</script>
<script>
  document.querySelector(".exampleModalCentersmall").addEventListener("click", function() {
      let checkbox = document.getElementById("myCheckbox");
      checkbox.checked = true; // Always set to checked
  });
</script>


@endsection