@extends('ursbid-admin.layouts.app')
@section('title', 'Add Category')
@section('content')
<div class="container-fluid">

    <!-- ========== Page Title Start ========== -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Add Category</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.categories.index') }}">Category List</a></li>
                    <li class="breadcrumb-item active">Add</li>
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
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Name</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="name" class="form-control" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Description</label>
                            </div>
                            <div class="col-md-8">
                                <textarea name="description" class="form-control" rows="3" placeholder="Enter Description"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Tags</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="tags"   data-role="tagsinput"  class="form-control" placeholder="tag1, tag2">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Image</label>
                            </div>
                            <div class="col-md-8">
                                <input type="file" name="image" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Meta Title</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="meta_title" class="form-control" placeholder="Enter Meta Title">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Meta Keywords</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="meta_keywords" class="form-control" placeholder="Enter Meta Keywords">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Meta Description</label>
                            </div>
                            <div class="col-md-8">
                                <textarea name="meta_description" class="form-control" rows="3" placeholder="Enter Meta Description"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status</label>
                            </div>
                            <div class="col-md-8">
                                <select name="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" id="saveBtn" class="btn btn-primary">Save</button>
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
<style>
/* ===== Light Theme ===== */
.bootstrap-tagsinput {
    width: 100%;
    padding: 6px 12px;
    line-height: 22px;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 6px;
}
.bootstrap-tagsinput .tag {
    background: #614ce1;
    padding: 5px 8px;
    border-radius: 4px;
    margin-right: 4px;
    color: #fff;
    font-size: 13px;
}

/* ===== Dark Theme (class-based) ===== */
.dark-theme .bootstrap-tagsinput {
    background-color: #1e1e1e;
    border: 1px solid #444;
    color: #ddd;
}
.dark-theme .bootstrap-tagsinput input {
    color: #ddd;
}
.dark-theme .bootstrap-tagsinput .tag {
    background: #8a73ff;
    color: #fff;
}

/* ===== Dark Mode (automatic using media query) ===== */
@media (prefers-color-scheme: dark) {
    .bootstrap-tagsinput {
        background-color: #1e1e1e;
        border: 1px solid #444;
        color: #ddd;
    }
    .bootstrap-tagsinput input {
        color: #ddd;
    }
    .bootstrap-tagsinput .tag {
        background: #8a73ff;
        color: #fff;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
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
            $('#saveBtn').prop('disabled',true).text('Saving...');
            var formData = new FormData(form);
            $.ajax({
                url: '{{ route('super-admin.categories.store') }}',
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
                        $('#saveBtn').prop('disabled',false).text('Save');
                    }
                },
                error: function(xhr){
                    let err = 'An error occurred';
                    if(xhr.responseJSON && xhr.responseJSON.errors){
                        err = Object.values(xhr.responseJSON.errors).map(e=>e.join('<br>')).join('<br>');
                    }
                    toastr.error(err);
                    $('#saveBtn').prop('disabled',false).text('Save');
                }
            });
            return false;
        }
    });
});
</script>
@endpush
