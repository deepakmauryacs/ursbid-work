@extends('ursbid-admin.layouts.app')
@section('title', 'Edit Blog')
@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Edit Blog</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.blogs.index') }}">Blogs</a></li>
                    <li class="breadcrumb-item active">Edit Blog</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="blogForm" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ $blog->title }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" rows="10" required>{{ $blog->description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-control">
                                <option value="1" {{ $blog->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $blog->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Image</label>
                            @if (!empty($blog->image))
                                <div class="mb-2">
                                    <img src="{{ asset('public/' . $blog->image) }}" alt="Image" height="80" style="border:1px solid #ccc;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" value="{{ $blog->meta_title }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meta Keywords</label>
                            <input type="text" name="meta_keywords" class="form-control" value="{{ $blog->meta_keywords }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meta Description</label>
                            <textarea name="meta_description" class="form-control" rows="3">{{ $blog->meta_description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Custom Header Code</label>
                            <textarea name="custom_header_code" class="form-control" rows="3">{{ $blog->custom_header_code }}</textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" id="saveBtn" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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


<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<script>
$(function(){
    

    // jQuery Validate Form Submit
    $('#blogForm').validate({
        rules: {
            title: { required: true },
            description: { required: true }
        },
        submitHandler: function(form) {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            $('#saveBtn').attr('disabled', true).text('Updating...');
            $.ajax({
                url: "{{ route('super-admin.blogs.update', $blog->id) }}",
                type: 'POST',
                data: new FormData(form),
                processData: false,
                contentType: false,
                success: function(res){
                    toastr.success(res.message);
                    window.location.href = "{{ route('super-admin.blogs.index') }}";
                },
                error: function(xhr){
                    let err = 'Error updating data';
                    if(xhr.responseJSON && xhr.responseJSON.errors){
                        err = Object.values(xhr.responseJSON.errors).map(e => e.join(', ')).join('<br>');
                    }
                    toastr.error(err, 'Error');
                    $('#saveBtn').attr('disabled', false).text('Update');
                }
            });
        }
    });
});
</script>
@endpush
