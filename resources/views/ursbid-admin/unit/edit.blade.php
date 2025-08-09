@extends('ursbid-admin.layouts.app')
@section('title', 'Edit Unit')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Edit Unit</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Edit Unit</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="unitForm">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Category</label>
                            </div>
                            <div class="col-md-8">
                                <select name="cat_id" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $unit->cat_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Sub Category</label>
                            </div>
                            <div class="col-md-8">
                                <select name="sub_id" class="form-control">
                                    <option value="">Select Sub Category</option>
                                    @foreach($subs as $sub)
                                        <option value="{{ $sub->id }}" {{ $unit->sub_id == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Title</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="title" class="form-control" value="{{ $unit->title }}" placeholder="Enter Title">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status</label>
                            </div>
                            <div class="col-md-8">
                                <select name="status" class="form-control">
                                    <option value="1" {{ $unit->status == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="2" {{ $unit->status == '2' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="submit" id="saveBtn" class="btn btn-primary">Update</button>
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
    $('#unitForm').validate({
        rules:{
            cat_id:{required:true},
            sub_id:{required:true},
            title:{required:true},
            status:{required:true}
        },
        submitHandler:function(form){
            $('#saveBtn').prop('disabled',true).text('Updating...');
            $.ajax({
                url: '{{ route('super-admin.units.update', $unit->id) }}',
                type: 'POST',
                data: $(form).serialize(),
                success: function(res){
                    if(res.status === 'success'){
                        toastr.success(res.message);
                        window.location.href = '{{ route('super-admin.units.index') }}';
                    } else {
                        toastr.error(res.message || 'An error occurred');
                        $('#saveBtn').prop('disabled',false).text('Update');
                    }
                },
                error: function(xhr){
                    let err = 'An error occurred';
                    if(xhr.responseJSON && xhr.responseJSON.errors){
                        err = Object.values(xhr.responseJSON.errors).map(e=>e.join('<br>')).join('<br>');
                    }
                    toastr.error(err);
                    $('#saveBtn').prop('disabled',false).text('Update');
                }
            });
            return false;
        }
    });
});
</script>
@endpush
