@extends('ursbid-admin.layouts.app')
@section('title', 'Add Product')
@section('content')
<div class="container-fluid">

    <!-- ========== Page Title Start ========== -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Add Product</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.products.index') }}">Product List</a></li>
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
                    <form id="productForm" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="cat_id" id="cat_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sub Category <span class="text-danger">*</span></label>
                            <select name="sub_id" id="sub_id" class="form-control" required>
                                <option value="">Select Sub Category</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Order By</label>
                            <input type="number" name="order_by" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="6"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meta Keywords</label>
                            <input type="text" name="meta_keywords" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" id="saveBtn" class="btn btn-primary">Save</button>
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

{{-- CKEditor (same setup as Edit/Product Category pages) --}}
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}?v={{ config('app.asset_version', '1.0.0') }}"></script>
<script>
CKEDITOR.replace('description', {
    height: 300,
    contentsCss: [
        'https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap',
        CKEDITOR.basePath + 'contents.css'
    ],
    bodyClass: 'dm-sans-body',
    font_names: 'DM Sans/DM Sans;' + CKEDITOR.config.font_names,
    font_defaultLabel: 'DM Sans',
});
</script>

<script>
$(function(){
    // Populate sub-categories when category changes
    $('#cat_id').on('change', function(){
        $('#sub_id').html('<option value="">Select Sub Category</option>');
        var cat_id = $(this).val();
        if(cat_id){
            $.get('{{ route('super-admin.products.get-subcategories') }}', {cat_id:cat_id}, function(res){
                $.each(res, function(i, item){
                    $('#sub_id').append('<option value="'+item.id+'">'+item.name+'</option>');
                });
            });
        }
    });

    // Validate + submit
    $('#productForm').validate({
        rules:{
            title:{ required:true },
            cat_id:{ required:true },
            sub_id:{ required:true }
        },
        submitHandler:function(form){
            // Sync CKEditor back to the textarea
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            $('#saveBtn').attr('disabled', true).text('Saving...');
            $.ajax({
                url: '{{ route('super-admin.products.store') }}',
                type: 'POST',
                data: new FormData(form),
                processData:false,
                contentType:false,
                success: function(res){
                    toastr.success(res.message);
                    form.reset();
                    // Clear CKEditor content
                    if (CKEDITOR.instances.description) {
                        CKEDITOR.instances.description.setData('');
                    }
                    $('#saveBtn').attr('disabled', false).text('Save');
                },
                error: function(xhr){
                    let err = 'Error saving data';
                    if(xhr.responseJSON && xhr.responseJSON.errors){
                        err = Object.values(xhr.responseJSON.errors).map(e => e.join(', ')).join('<br>');
                    }
                    toastr.error(err);
                    $('#saveBtn').attr('disabled', false).text('Save');
                }
            });
        }
    });
});
</script>
@endpush
