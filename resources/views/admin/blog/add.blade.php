@extends ('admin.layout')
@section('title', 'Blogs')

@section('content')
<script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>

<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">

                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Add Blog</h4>
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
                @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-default card-md mb-4">
                        <div class="card-header">
                            <h6>Add Blog data here</h6>
                        </div>
                        <div class="card-body py-md-25">
                            <form method="post" action="{{ route('admin.blog.create') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="a8" class="il-gray fs-14 fw-500 align-center">
                                                Title</label>
                                            <input type="text"
                                                class="form-control ih-medium ip-light radius-xs b-light px-15" id="a8"
                                                placeholder="Title" name="title" value="{{ old('title') }}"> 
                                        </div>
                                        @if ($errors->has('title'))
                                        <span class="text-danger">{{ $errors->first('title') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="a9" class="il-gray fs-14 fw-500 align-center">Date</label>
                                            <input type="date"
                                                class="form-control ih-medium ip-light radius-xs b-light px-15" id="a9"
                                             name="post_date" placeholder="Date" value="{{ old('post_date') }}">
                                        </div>
                                        @if ($errors->has('post_date'))
                                        <span class="text-danger">{{ $errors->first('post_date') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="a9" class="il-gray fs-14 fw-500 align-center">Image</label>
                                            <input type="file"
                                                class="form-control ih-medium ip-light radius-xs b-light px-15" id="a9"
                                                name="image" placeholder="Date" value="{{ old('image') }}">
                                        </div>
                                        @if ($errors->has('image'))
                                        <span class="text-danger">{{ $errors->first('image') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="a9"
                                                class="il-gray fs-14 fw-500 align-center">Description</label>
                                            <textarea name="description" class="description" id="description">{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                                    @if ($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                        @endif


                                </div>
                                <div class="text-center">

                                    <button type="submit" class="px-4 btn-sm btn-primary">Submit</button>
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
<script>
CKEDITOR.replace('description');
</script>
@endsection