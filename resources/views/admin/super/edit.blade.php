@extends ('admin.layout')
@section('title', 'Super category')

@section('content')
<script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>

<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">

                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Update Brand category</h4>
                    <div class="breadcrumb-action justify-content-center flex-wrap">




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
                            <h6>Add Brand category</h6>
                        </div>
                        <div class="card-body py-md-25">
                            <form method="post" action="{{ url('admin/super/update/'.$blog->id) }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="a8" class="il-gray fs-14 fw-500 align-center">
                                                Category</label>
                                            <select name="cat_id" id="cat_id"
                                                class="form-control ih-medium  cat_id ip-light radius-xs b-light px-15">
                                                <option value="">Select Category</option>
                                                @php
                                                $category = DB::select("SELECT * FROM category WHERE status = 1");
                                                foreach ($category as $cat) {
                                                $selected = $cat->id == $blog->cat_id ? 'selected' : '';
                                                @endphp
                                                <option value="{{ $cat->id }}" {{ $selected }}>{{ $cat->title }}
                                                </option>
                                                @php } @endphp
                                            </select>

                                        </div>
                                        @if ($errors->has('cat_id'))
                                        <span class="text-danger">The Category field is required.</span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="a8" class="il-gray fs-14 fw-500 align-center">
                                                Sub Category</label>
                                            <select name="sub_id" id="sub_category"
                                                class="form-control ih-medium ip-light radius-xs b-light px-15">
                                                <option value="">Select Sub Category</option>
                                                @php
                                                $subcategory = DB::select("SELECT * FROM sub WHERE status = 1");
                                                foreach ($subcategory as $sub) {
                                                $selected = $sub->id == $blog->sub_id ? 'selected' : '';
                                                @endphp
                                                <option value="{{ $sub->id }}" {{ $selected }}>{{ $sub->title }}
                                                </option>
                                                @php } @endphp
                                            </select>

                                        </div>
                                        @if ($errors->has('sub_id'))
                                        <span class="text-danger">{{ $errors->first('sub_id') }}</span>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="a8" class="il-gray fs-14 fw-500 align-center">
                                                Product</label>
                                            <select name="super_id" id="super_category"
                                                class="form-control ih-medium ip-light radius-xs b-light px-15">
                                                <option value="">Select Product</option>
                                                @php
                                                $supcategory = DB::select("SELECT * FROM product WHERE status = 1");
                                                foreach ($supcategory as $super) {
                                                $selected = $super->id == $blog->super_id ? 'selected' : '';
                                                @endphp
                                                <option value="{{ $super->id }}" {{ $selected }}>{{ $super->title }}
                                                </option>
                                                @php } @endphp
                                            </select>

                                        </div>
                                        @if ($errors->has('super_id'))
                                        <span class="text-danger">{{ $errors->first('super_id') }}</span>
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="a9" class="il-gray fs-14 fw-500 align-center">Image</label>
                                            <input type="file"
                                                class="form-control ih-medium ip-light radius-xs b-light px-15" id="a9"
                                                name="image" placeholder="Date" value="{{ $blog->image }}">
                                        </div>
                                        @if ($errors->has('image'))
                                        <span class="text-danger">{{ $errors->first('image') }}</span>
                                        @endif
                                    </div>

                                    <!-- <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="a9"
                                                class="il-gray fs-14 fw-500 align-center">Description</label>
                                            <textarea name="description"  rows="5" class="form-control  ip-light radius-xs b-light px-15" id="description">{{ $blog->description }}</textarea>
                                        </div>
                                    </div>
                                    @if ($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                        @endif -->

                                    <!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="a8" class="il-gray fs-14 fw-500 align-center">
                                           Meta Title</label>
                                        <input type="text"
                                            class="form-control ih-medium ip-light radius-xs b-light px-15" id="a8"
                                            placeholder="Meta Title" name="meta_title" value="{{ $blog->meta_title }}">
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="a8" class="il-gray fs-14 fw-500 align-center">
                                           Meta Keyword</label>
                                        <textarea class="form-control ih-medium ip-light radius-xs b-light px-15" id="a8" rows="3"
                                            placeholder="Meta keyword" name="meta_keyword">{{ $blog->meta_keyword }}</textarea>
                                    </div>
                                    
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="a8" class="il-gray fs-14 fw-500 align-center">
                                           Meta description</label>
                                        <textarea class="form-control ih-medium ip-light radius-xs b-light px-15" id="a8" rows="3"
                                            placeholder="Meta description" name="meta_description">{{ $blog->meta_description }}</textarea>
                                    </div>
                                    
                                </div> -->

                                </div>
                                <div class="text-center">

                                    <button type="submit" class="px-4 btn-sm btn-primary">Save changes</button>
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
                data: {
                    cat_id: cat_id
                },
                success: function(response) {
                    $('#sub_category').html(response);
                }
            });
        } else {
            $('#sub_category').html('<option value="">Select Sub Category</option>');
        }
    });

    $('#sub_category').change(function() {
var cat_id = $('.cat_id').val();
var sub_id = $(this).val();

if (sub_id) {
$.ajax({
type: 'GET',
url: "{{ route('get_sup_category') }}",
data: {
cat_id: cat_id,
sub_id: sub_id
},
success: function(response) {
$('#super_category').html(response);
}
});
} else {
$('#super_category').html('<option value="">Select Product</option>');
}
});
});
</script>
@endsection