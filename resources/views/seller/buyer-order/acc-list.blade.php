@extends('seller.layouts.app')
@section('title', 'Accepted Bidding  ')
<!--  -->
@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">

                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Accepted Bidding </h4>
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
                        <form class="row" method="get" action="">
                            <div class="col-md-2 no-margin-left">
                                <div class="form-group">
                                    <!-- <input type="text" name="category" class="form-control" placeholder="Category"
                                        value="{{ isset($data['category']) ? $data['category'] : '' }}"> -->
                                    <select name="category" id="" class="form-control">
                                        <option value="">Select Category</option>
                                        @foreach($category_data as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ $datas['category'] == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 no-margin-left">
                                <div class="form-group">
                                    <input type="text" name="date" class="form-control" placeholder="Date"
                                        value="{{ isset($datas['date']) ? $datas['date'] : '' }}">
                                </div>
                            </div>
                            <div class="col-md-2 no-margin-left">
                                <div class="form-group">
                                    <input type="text" name="city" class="form-control" placeholder="City"
                                        value="{{ isset($datas['city']) ? $datas['city'] : '' }}">
                                </div>
                            </div>
                            <div class="col-md-2 no-margin-left">
                                <div class="form-group">
                                    <input type="text" name="quantity" class="form-control" placeholder="Quantity"
                                        value="{{ isset($datas['quantity']) ? $datas['quantity'] : '' }}">
                                </div>
                            </div>
                            <div class="col-md-2 no-margin-left">
                                <div class="form-group">
                                    <input type="text" name="product_name" class="form-control"
                                        placeholder="Product Name"
                                        value="{{ isset($datas['product_name']) ? $datas['product_name'] : '' }}">
                                </div>
                            </div>

                            <!-- <div class="col-md-1 no-padding-left">
                                <div class="form-group">
                                    <select class="form-control select2" name="r_page">
                                        <option value="25" {{ $datas['r_page'] == 25 ? 'selected' : '' }}> 25 Records Per
                                            Page</option>
                                        <option value="50" {{ $datas['r_page'] == 50 ? 'selected' : '' }}> 50 Records Per
                                            Page</option>
                                        <option value="100" {{ $datas['r_page'] == 100 ? 'selected' : '' }}> 100 Records
                                            Per Page</option>
                                    </select>
                                </div>
                            </div> -->

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

                        <div class="userDatatable projectDatatable project-table bg-white w-100 border-0">
                            <div class="table-responsive">
                            <div class="col-sm-12">
                        <div class="padd">
                            @php
                            if($total < 1){ echo "<div class='text-danger'>Sorry, No data Found!</div>"; }else{ @endphp 
                                <div class="pro">
                                <table class="table table-border ">

                                    <head>
                                        <tr>
                                            <!-- <th>Sr.No</th> -->
                                            <th>
                                                <span class="projectDatatable-title">Sr.No</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Name</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Category</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Sub_Category</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Product_name</span>
                                            </th>
                                            
                                            <th>
                                                <span class="projectDatatable-title">Date</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Unit</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Quantity</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Rate</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Total_Price</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Plateform_Fee</span>
                                            </th>

                                           
                                            <th>
                                                <span class="projectDatatable-title ">Action</span>
                                            </th>
                                        </tr>
                                    </head>
                                    <tbody>
                                        @php
                                        $i= 1;
                                        foreach ($data as $all) {
                                        $all_id = $all->id;

                                        @endphp
                                        <tr>
                                       


                                            <td>
                                                <div class="userDatatable-content text-center">
                                                {{ $i++ }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $all->name }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $all->category_name }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $all->sub_name }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $all->product_name }}
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ date('Y-m-d', strtotime($all->date_time)) }}

                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $all->unit }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $all->quantity }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $all->rate }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                @php
    $raw = $all->quantity; 
    preg_match('/\d+(\.\d+)?/', $raw, $matches);
    $qty = isset($matches[0]) ? (float)$matches[0] : 0;
    $total = $qty * $all->rate;
@endphp

{{ number_format($total, 2) }}


                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $all->price }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content text-success">
                                                    Confirm
                                                </div>
                                            </td>

                                         





                                            <!-- <td class="d-flex">
                                                <a href="{{ url('/price-list/'.$all_id) }}"
                                                    class=" btn btn-primary mx-3">View List</a>
                                                <a href="{{ url('/accepted-list/'.$all_id) }}"
                                                    class=" btn btn-success">Accepeted List</a>
                                                </td> -->
                                        </tr>
                                        @php } @endphp
                                    </tbody>
                                </table>
@if(isset($data['keyword']) && $data->count() > 0)
                                            <div class="pagination">
                                                {{ $data->appends($datas)->links('pagination::bootstrap-4') }}
                                            </div>
                                            @elseif(isset($datas['r_page']) && $data->count() > 0)
                                            <div class="pagination">
                                                {{ $data->appends($datas)->links('pagination::bootstrap-4') }}
                                            </div>
                                            @else

                                            <div class="gmz-pagination mt-1">
                                                {!! $data->links('pagination::bootstrap-4') !!}
                                            </div>
                                            @endif
                        </div>
                        @php
                        }
                        @endphp
                    </div>
                                
                                
                               
                            </div>
                        </div><!-- End: .userDatatable -->

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