@extends ('admin.layout')
@section('title', 'Product')

@section('content')
<script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>

<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">

                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Add Product</h4>
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
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-default card-md mb-4">
                        <div class="card-header">
                            <h6>Add Product</h6>
                        </div>
                        <div class="card-body py-md-25">
                            <form method="post" action="{{ url('admin/product/update/'.$blog->id) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="a8" class="il-gray fs-14 fw-500 align-center">
                                             Category</label>
                                        <select name="cat_id" id="cat_id"
                                            class="form-control ih-medium ip-light radius-xs b-light px-15">
                                            <option value="">Select Category</option>
                                            @php
                                            $category = DB::select("SELECT * FROM category WHERE status = 1");
                                            foreach ($category as $cat) {
                                                $selected = $blog->cat_id == $cat->id ? 'selected' : ''; 
                                            @endphp
                                            <option value="{{ $cat->id }}" {{ $selected }}>{{ $cat->title }}</option>
                                            @php } @endphp
                                        </select>

                                    </div>
                                    @if ($errors->has('cat_id'))
                                    <span class="text-danger">The Category field is required.</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="a8" class="il-gray fs-14 fw-500 align-center">
                                            Sub Category</label>
                                        <select name="sub_id" id="sub_category"
                                            class="form-control ih-medium ip-light radius-xs b-light px-15">
                                            <option value="">Select Sub Category</option>
                                            @php
                                            $subc = DB::select("SELECT * FROM sub WHERE status = 1");
                                            foreach ($subc as $sub) {
                                                $selected = $blog->sub_id == $sub->id ? 'selected' : ''; 
                                            @endphp
                                            <option value="{{ $sub->id }}" {{ $selected }}>{{ $sub->title }}</option>
                                            @php } @endphp
                                        </select>

                                    </div>
                                    @if ($errors->has('sub_id'))
                                    <span class="text-danger">{{ $errors->first('sub_id') }}</span>
                                    @endif
                                </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="a8" class="il-gray fs-14 fw-500 align-center">
                                                Title</label>
                                            <input type="text"
                                                class="form-control ih-medium ip-light radius-xs b-light px-15" id="a8"
                                                placeholder="Title" name="title" value="{{ $blog->title }}"> 
                                        </div>
                                        @if ($errors->has('title'))
                                        <span class="text-danger">{{ $errors->first('title') }}</span>
                                        @endif
                                    </div>
                                    <input type="hidden"
                                                class="form-control ih-medium ip-light radius-xs b-light px-15" id="a9"
                                             name="update_by" placeholder="Date" value="Admin">
                                        
                                   <input type="hidden"
                                                class="form-control ih-medium ip-light radius-xs b-light px-15" id="a9"
                                             name="insert_by" placeholder="" value="{{ $blog->insert_by }}">
                                       
                                    <input type="hidden"
                                                class="form-control ih-medium ip-light radius-xs b-light px-15" id="a9"
                                             name="user_type" placeholder="Date" value="{{ $blog->user_type }}">
                                        
                                    <div class="col-md-6">
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#cat_id').change(function() {
            var cat_id = $(this).val();
            if (cat_id) {
                $.ajax({
                    type: 'GET',
                    url: "{{ route('get_sub_category') }}",
                    data: {cat_id: cat_id},
                    success: function(response) {
                        $('#sub_category').html(response);
                    }
                });
            } else {
                $('#sub_category').html('<option value="">Select Sub Category</option>');
            }
        });
    });
</script>
<script>
</script>
@endsection