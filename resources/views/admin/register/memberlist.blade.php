@extends ('admin.layout')
@section('title', 'Members List')
<!--  -->
@section('content')

<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">

                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Members List</h4>
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


                        <div class="action-btn">
                            <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary btn-add">
                                <i class="la la-arrow-left"></i> Back
                            </a>
                        </div>

                    </div>
                </div>

            </div>

        </div>
        <div class="row">
            <div class="col-lg-12 mb-30">
                <div class="card">
                    <div class="col-md-12 mt-2">
                        <form class="row" method="get" action="">
                            <div class="col-md-4 no-margin-left">
                                <div class="form-group">
                                    <input type="text" name="keyword" class="form-control" placeholder="Keywords"
                                        value="{{ isset($data['keyword']) ? $data['keyword'] : '' }}">
                                </div>
                            </div>

                            <div class="col-md-2 no-padding-left">
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
                                                <span class="projectDatatable-title">Email</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Phone</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">GST</span>
                                            </th>
                                            <th>
                                                <span class="projectDatatable-title">Product/Services</span>
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
                                                    {{ $blog->email }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $blog->phone }}
                                                </div>
                                            </td>

                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $blog->gst }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $blog->pro_ser }}
                                                </div>
                                            </td>

                                            <td>

                                                <div class="d-inline-block">
                                                    @if($blog->status==1)
                                                    <a href="{{ url('admin/userfinal/active/'.$blog->id) }}">
                                                        <span class="media-badge color-white bg-primary">Active</span>
                                                    </a>
                                                    @else
                                                    <a href="{{ url('admin/userfinal/deactive/'.$blog->id) }}">
                                                        <span class="media-badge color-white bg-danger">De-active</span>
                                                    </a>
                                                    @endif

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


                            </div>
                        </div><!-- End: .userDatatable -->

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
@endsection