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
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Title<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="title" class="form-control" value="{{ $blog->title }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Description<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <div id="editor" style="height:200px; background: #fff;"></div>
                                <textarea name="description" id="description" class="d-none" required>{{ $blog->description }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status</label>
                            </div>
                            <div class="col-md-8">
                                <select name="status" class="form-control">
                                    <option value="1" {{ $blog->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $blog->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Image</label>
                            </div>
                            <div class="col-md-8">
                                @if (!empty($blog->image))
                                    <div class="mb-2">
                                        <img src="{{ asset('public/' . $blog->image) }}" alt="Image" height="80" style="border:1px solid #ccc;">
                                    </div>
                                @endif
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Meta Title</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="meta_title" class="form-control" value="{{ $blog->meta_title }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Meta Keywords</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="meta_keywords" class="form-control" value="{{ $blog->meta_keywords }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Meta Description</label>
                            </div>
                            <div class="col-md-8">
                                <textarea name="meta_description" class="form-control" rows="3">{{ $blog->meta_description }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Custom Header Code</label>
                            </div>
                            <div class="col-md-8">
                                <textarea name="custom_header_code" class="form-control" rows="3">{{ $blog->custom_header_code }}</textarea>
                            </div>
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

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow {
        border-radius: 5px 5px 0 0;
    }
    .ql-container.ql-snow {
        border-radius: 0 0 5px 5px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>
<script>
$(function(){
    // Initialize Quill (full toolbar)
    var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Write blog description...',
        modules: {
            toolbar: [
                [{'header': [1, 2, 3, 4, 5, 6, false]}],
                [{'font': []}],
                [{'size': ['small', false, 'large', 'huge']}],
                ['bold', 'italic', 'underline', 'strike'],
                [{'color': []}, {'background': []}],
                [{'align': []}],
                [{'list': 'ordered'}, {'list': 'bullet'}],
                [{'indent': '-1'}, {'indent': '+1'}],
                [{'script': 'sub'}, {'script': 'super'}],
                ['blockquote', 'code-block'],
                ['link', 'image', 'video'],
                [{'direction': 'rtl'}],
                ['clean']
            ]
        }
    });

    // Set Quill initial content from the textarea
    var oldDesc = document.getElementById('description').value;
    if (oldDesc) {
        quill.root.innerHTML = oldDesc;
    }

    $('#blogForm').validate({
        rules:{
            title:{ required:true },
            description:{ required:true }
        },
        submitHandler:function(form){
            // Copy Quill content to textarea before submitting
            $('#description').val(quill.root.innerHTML);

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
