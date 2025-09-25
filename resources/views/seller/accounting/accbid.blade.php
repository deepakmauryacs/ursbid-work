@extends('seller.layouts.app')
@section('title', 'Accepted List')
<!--  -->
@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">

                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Accepted List </h4>
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
                                <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                                    <thead>
                                        <tr class="userDatatable-header">
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
                                                <span class="projectDatatable-title">Total Price</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Plateform Fee</span>
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
                                        @foreach ($blogs as $all)

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