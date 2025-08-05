@extends('ursbid-admin.layouts.app')
@section('title', 'Add Advertisement')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Add Advertisement</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Add Advertisement</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="adForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Category</label>
                            </div>
                            <div class="col-md-8">
                                <select name="category_id" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                                    @endforeach
                                </select>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
$(function(){
    $('#adForm').validate({
        rules:{
            category_id:{required:true},
            image:{required:true},
            status:{required:true}
        },
        submitHandler:function(form){
            var formData = new FormData(form);
            $('#saveBtn').prop('disabled',true).text('Saving...');
            $.ajax({
                url: '{{ route('super-admin.advertisements.store') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res){
                    if(res.status === 'success'){
                        toastr.success(res.message);
                        window.location.href = '{{ route('super-admin.advertisements.index') }}';
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
