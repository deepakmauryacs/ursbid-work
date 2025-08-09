@extends('ursbid-admin.layouts.app')
@section('title', 'Edit Product Brand')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Edit Product Brand</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Edit Product Brand</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="brandForm">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Category</label>
                            </div>
                            <div class="col-md-8">
                                <select name="category_id" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == $brand->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Sub Category</label>
                            </div>
                            <div class="col-md-8">
                                <select name="sub_category_id" class="form-control">
                                    <option value="">Select Sub Category</option>
                                    @foreach($subs as $sub)
                                        <option value="{{ $sub->id }}" {{ $sub->id == $brand->sub_category_id ? 'selected' : '' }}>{{ $sub->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Product</label>
                            </div>
                            <div class="col-md-8">
                                <select name="product_id" class="form-control">
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ $product->id == $brand->product_id ? 'selected' : '' }}>{{ $product->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Brand Name</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="brand_name" class="form-control" value="{{ $brand->brand_name }}" placeholder="Enter Brand Name">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Description</label>
                            </div>
                            <div class="col-md-8">
                                <textarea name="description" class="form-control" rows="4" placeholder="Enter Description">{{ $brand->description }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status</label>
                            </div>
                            <div class="col-md-8">
                                <select name="status" class="form-control">
                                    <option value="1" {{ $brand->status == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="2" {{ $brand->status == '2' ? 'selected' : '' }}>Inactive</option>
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
    $('#brandForm').validate({
        rules:{
            category_id:{required:true},
            sub_category_id:{required:true},
            product_id:{required:true},
            brand_name:{required:true},
            status:{required:true}
        },
        submitHandler:function(form){
            $('#saveBtn').prop('disabled',true).text('Updating...');
            $.ajax({
                url: '{{ route('super-admin.product-brands.update', $brand->id) }}',
                type: 'POST',
                data: $(form).serialize(),
                success: function(res){
                    if(res.status === 'success'){
                        toastr.success(res.message);
                        window.location.href = '{{ route('super-admin.product-brands.index') }}';
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
