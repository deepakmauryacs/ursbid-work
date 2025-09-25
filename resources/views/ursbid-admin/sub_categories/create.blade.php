@extends('ursbid-admin.layouts.app')
@section('title', 'Add Sub Category')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Add Sub Category</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.sub-categories.index') }}">Sub Category</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="subCategoryForm" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name ?? $cat->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            {{-- CKEditor binds to this id --}}
                            <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tags</label>
                            <input type="text" name="tags" 
                                   class="form-control" 
                                   data-role="tagsinput"
                                   placeholder="Add tags" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Order By</label>
                            <input type="number" name="order_by" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
<style>
/* ===== Bootstrap Tagsinput custom style ===== */
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

/* Dark theme */
.dark-theme .bootstrap-tagsinput {
    background-color: #1e1e1e;
    border: 1px solid #444;
    color: #ddd;
}
.dark-theme .bootstrap-tagsinput input { color: #ddd; }
.dark-theme .bootstrap-tagsinput .tag {
    background: #8a73ff;
    color: #fff;
}

/* Auto dark mode */
@media (prefers-color-scheme: dark) {
    .bootstrap-tagsinput {
        background-color: #1e1e1e;
        border: 1px solid #444;
        color: #ddd;
    }
    .bootstrap-tagsinput input { color: #ddd; }
    .bootstrap-tagsinput .tag {
        background: #8a73ff;
        color: #fff;
    }
}
</style>
@endpush

@push('scripts')
{{-- CKEditor --}}
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
$(function(){
    $('#subCategoryForm').validate({
        rules:{
            name:{ required:true },
            category_id:{ required:true },
            description:{ required:true },
            status:{ required:true }
        },
        submitHandler:function(form){
            // Sync CKEditor back to textarea
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            $('#saveBtn').attr('disabled', true).text('Saving...');
            $.ajax({
                url: "{{ route('super-admin.sub-categories.store') }}",
                type: 'POST',
                data: new FormData(form),
                processData: false,
                contentType: false,
                success: function(res){
                    toastr.success(res.message);
                    form.reset();
                    if (CKEDITOR.instances.description) {
                        CKEDITOR.instances.description.setData('');
                    }
                    $('.bootstrap-tagsinput .tag').remove(); // clear tags visually
                    $('#saveBtn').attr('disabled', false).text('Save');
                },
                error: function(xhr){
                    let err = 'Error saving data';
                    if(xhr.responseJSON && xhr.responseJSON.errors){
                        err = Object.values(xhr.responseJSON.errors).map(e => e.join(', ')).join('<br>');
                    }
                    toastr.error(err, 'Error');
                    $('#saveBtn').attr('disabled', false).text('Save');
                }
            });
        }
    });
});
</script>
@endpush
