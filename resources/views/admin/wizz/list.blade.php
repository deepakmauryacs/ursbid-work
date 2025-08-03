@extends ('admin.layout')
@section('title', 'wizz List')
<!--  -->
@section('content')

<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">

                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">wizz List</h4>
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
                            <a href="{{ url('admin/wizz/add') }}" class="btn btn-sm btn-primary btn-add">
                                <i class="la la-plus"></i> Add New wizz</a>
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
                                        <option value="2" {{ $data['r_page'] == 2 ? 'selected' : '' }}> 2 Records Per
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
                                                <span class="projectDatatable-title">Title</span>
                                            </th>
                                            

                                            <th>
                                                <span class="projectDatatable-title">status</span>
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
                                                    {{ $blog->title }}
                                                </div>
                                            </td>
                                            
                                            <td>

                                                <div class="d-inline-block">
                                                    @if($blog->status==1)
                                                    <a href="{{ url('admin/category/active/'.$blog->id) }}">
                                                        <span class="media-badge color-white bg-primary">Active</span>
                                                    </a>
                                                    @else
                                                    <a href="{{ url('admin/category/deactive/'.$blog->id) }}">
                                                        <span class="media-badge color-white bg-danger">De-active</span>
                                                    </a>
                                                    @endif

                                                </div>
                                            </td>

                                            <td>
                                                <div class="project-progress text-left">


                                                    <div class="dropdown  dropdown-click ">

                                                        <button class="btn-link border-0 bg-transparent p-0"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <span data-feather=more-horizontal></span>
                                                        </button>


                                                        <div
                                                            class="dropdown-default dropdown-bottomLeft dropdown-menu-right dropdown-menu">
                                                            <a class="dropdown-item"
                                                                href="{{ url('admin/category/edit/'.$blog->id) }}">Edit</a>
                                                            <a class="dropdown-item"
                                                                href="{{ url('admin/category/delete/'.$blog->id) }}"
                                                                onclick="return confirm('Are you sure you want to delete this blog?')">Delete</a>



                                                        </div>
                                                    </div>


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
@endsection