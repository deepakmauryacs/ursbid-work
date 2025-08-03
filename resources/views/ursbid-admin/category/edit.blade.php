@extends('ursbid-admin.layouts.app')
@section('title', 'Edit Category')
@section('content')
<div class="container-fluid">

    <!-- ========== Page Title Start ========== -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Edit Category</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Edit Category</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- ========== Page Title End ========== -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="categoryForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Title</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="title" class="form-control" value="{{ $category->title }}" placeholder="Enter Title">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Category ID</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="cat_id" class="form-control" value="{{ $category->cat_id }}" placeholder="Enter Category ID">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Post Date</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="post_date" id="post_date" class="form-control" value="{{ $category->post_date }}" placeholder="dd-mm-yyyy">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Image</label>
                            </div>
                            <div class="col-md-8">
                                @if($category->image)
                                <div class="mb-2">
                                    <img src="{{ asset('public/'.$category->image) }}" alt="Category Image" height="80" style="border:1px solid #ccc;">
                                </div>
                                @endif
                                <input type="file" name="image" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Meta Title</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="meta_title" class="form-control" value="{{ $category->meta_title }}" placeholder="Enter Meta Title">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Meta Keywords</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="meta_keywords" class="form-control" value="{{ $category->meta_keywords }}" placeholder="Enter Meta Keywords">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Meta Description</label>
                            </div>
                            <div class="col-md-8">
                                <textarea name="meta_description" class="form-control" rows="3" placeholder="Enter Meta Description">{{ $category->meta_description }}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" id="updateBtn" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
$(function(){
    $('#post_date').datepicker({ dateFormat: 'dd-mm-yy' });

    $.validator.addMethod('dmy', function(value){
        return /^\d{2}-\d{2}-\d{4}$/.test(value);
    }, 'Please enter a date in the format dd-mm-yyyy');

    $('#categoryForm').validate({
        rules:{
            title:{ required:true },
            post_date:{ required:true, dmy:true }
        },
        submitHandler:function(form){
            $('#updateBtn').prop('disabled',true).text('Updating...');
            var formData = new FormData(form);
            $.ajax({
                url: '{{ route('super-admin.categories.update', $category->id) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res){
                    toastr.success(res.message);
                    $('#updateBtn').prop('disabled',false).text('Update');
                },
                error: function(xhr){
                    let err = 'An error occurred';
                    if(xhr.responseJSON && xhr.responseJSON.errors){
                        err = Object.values(xhr.responseJSON.errors).map(e=>e.join('<br>')).join('<br>');
                    }
                    toastr.error(err);
                    $('#updateBtn').prop('disabled',false).text('Update');
                }
            });
            return false;
        }
    });
});
</script>
@endpush
