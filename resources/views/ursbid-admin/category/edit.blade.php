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
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.categories.index') }}">Category List</a></li>
                    <li class="breadcrumb-item active">Edit</li>
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

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $category->name }}" placeholder="Enter Name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            {{-- CKEditor binds to this id --}}
                            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Enter Description" required>{{ $category->description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tags</label>
                            <input type="text" name="tags" data-role="tagsinput" class="form-control"
                                   value="{{ is_array($category->tags) ? implode(',', $category->tags) : $category->tags }}"
                                   placeholder="tag1, tag2">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Image</label>
                            @if($category->image)
                                <div class="mb-2">
                                    <img src="{{ asset('public/'.$category->image) }}" alt="Category Image" height="80" style="border:1px solid #ccc;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" value="{{ $category->meta_title }}" placeholder="Enter Meta Title">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meta Keywords</label>
                            <input type="text" name="meta_keywords" class="form-control" value="{{ $category->meta_keywords }}" placeholder="Enter Meta Keywords">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="3" placeholder="Enter Meta Description">{{ $category->meta_description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-control">
                                <option value="1" {{ $category->status == '1' ? 'selected' : '' }}>Active</option>
                                <option value="2" {{ $category->status == '2' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="submit" id="updateBtn" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Bootstrap Tags Input CSS -->
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
.dark-theme .bootstrap-tagsinput { background-color: #1e1e1e; border: 1px solid #444; color: #ddd; }
.dark-theme .bootstrap-tagsinput input { color: #ddd; }
.dark-theme .bootstrap-tagsinput .tag { background: #8a73ff; color: #fff; }

/* ===== Dark Mode (automatic) ===== */
@media (prefers-color-scheme: dark) {
    .bootstrap-tagsinput { background-color: #1e1e1e; border: 1px solid #444; color: #ddd; }
    .bootstrap-tagsinput input { color: #ddd; }
    .bootstrap-tagsinput .tag { background: #8a73ff; color: #fff; }
}
</style>
@endpush

@push('scripts')
<!-- Bootstrap Tags Input JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

{{-- CKEditor (same setup as other pages) --}}
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
    $('input[data-role="tagsinput"]').tagsinput();

    $('#categoryForm').validate({
        rules:{
            name:{ required:true },
            description:{ required:true },
            status:{ required:true }
        },
        submitHandler:function(form){
            // Sync CKEditor -> textarea
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            $('#updateBtn').prop('disabled',true).text('Updating...');
            var formData = new FormData(form);
            $.ajax({
                url: '{{ route('super-admin.categories.update', $category->id) }}',
                type: 'POST', // _method=PUT is included above
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
