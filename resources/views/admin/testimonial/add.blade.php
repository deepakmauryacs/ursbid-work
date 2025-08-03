@extends ('admin.layout')
@section('title', 'testimonial')

@section('content')
<script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>

<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">

                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Add testimonial</h4>
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
                                        <a href="#" class="btn btn-sm btn-primary btn-add">
                                            <i class="la la-plus"></i> </a>
                                    </div> -->
                    </div>
                </div>

            </div>

        </div>
        <div class="form-element">
            @if(Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <!-- @if(Session::has('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ Session::get('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-default card-md mb-4">
                        <div class="card-header">
                            <h6>Add testimonial </h6>
                        </div>
                        <div class="card-body py-md-25">
                            <form method="post" action="{{ route('admin.testimonial.create') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}

<div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="a8" class="il-gray fs-14 fw-500 align-center">
                                            Name</label>
                                        <input type="text"
                                            class="form-control ih-medium ip-light radius-xs b-light px-15" id="a8"
                                            placeholder="Name" name="name" value="{{ old('name') }}">
                                    </div>
                                    @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="a8" class="il-gray fs-14 fw-500 align-center">
                                            position</label>
                                        <input type="text"
                                            class="form-control ih-medium ip-light radius-xs b-light px-15" id="a8"
                                            placeholder="position" name="position" value="{{ old('position') }}">
                                    </div>
                                    @if ($errors->has('position'))
                                    <span class="text-danger">{{ $errors->first('position') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="a8" class="il-gray fs-14 fw-500 align-center">
                                            Description</label>
                                        <textarea rows="5" class="form-control  ip-light radius-xs b-light px-15"
                                            id="a8" placeholder="Description" name="description"
                                            >{{ old('description') }}</textarea>
                                    </div>
                                    @if ($errors->has('description'))
                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>



                                <div class="col-md-8">

                                    <button type="submit" class="px-4 btn-sm btn-primary">Submit</button>
                                </div>

                                </div>
                            </form>
                        </div>

                    </div>
                </div>
                <!-- ends: .card -->
            </div>



        </div>
    </div>
</div>
</div>

@endsection