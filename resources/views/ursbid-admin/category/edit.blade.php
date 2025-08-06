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
                                <label class="form-label fw-semibold">Name</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="name" class="form-control" value="{{ $category->name }}" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Description</label>
                            </div>
                            <div class="col-md-8">
                                <textarea name="description" class="form-control" rows="3" placeholder="Enter Description">{{ $category->description }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Tags</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="tags" class="form-control" value="{{ implode(',', $category->tags ?? []) }}" placeholder="tag1, tag2">
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
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status</label>
                            </div>
                            <div class="col-md-8">
                                <select name="status" class="form-control">
                                    <option value="1" {{ $category->status == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="2" {{ $category->status == '2' ? 'selected' : '' }}>Inactive</option>
                                </select>
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
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
$(function(){
    $('#categoryForm').validate({
        rules:{
            name:{ required:true },
            description:{ required:true },
            status:{ required:true }
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
                    if(res.status === 'success'){
                        toastr.success(res.message);
                        window.location.href = '{{ route('super-admin.categories.index') }}';
                    } else {
                        toastr.error(res.message || 'An error occurred');
                        $('#updateBtn').prop('disabled',false).text('Update');
                    }
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
