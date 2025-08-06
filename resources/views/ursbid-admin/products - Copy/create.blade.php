@extends('ursbid-admin.layouts.app')
@section('title', 'Add Product')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="productForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Title<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="title" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Category<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <select name="cat_id" id="cat_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Sub Category<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <select name="sub_id" id="sub_id" class="form-control" required>
                                    <option value="">Select Sub Category</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Post Date<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="post_date" id="post_date" class="form-control" placeholder="dd-mm-yyyy" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Order By</label>
                            </div>
                            <div class="col-md-8">
                                <input type="number" name="order_by" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status</label>
                            </div>
                            <div class="col-md-8">
                                <select name="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Image</label>
                            </div>
                            <div class="col-md-8">
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Description</label>
                            </div>
                            <div class="col-md-8">
                                <div id="editor" style="height: 200px; background: #fff;"></div>
                                <textarea name="description" id="description" class="d-none"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Meta Title</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="meta_title" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Meta Keywords</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="meta_keywords" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Meta Description</label>
                            </div>
                            <div class="col-md-8">
                                <textarea name="meta_description" class="form-control" rows="3"></textarea>
                            </div>
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
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
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
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>
<script>
$(function(){
    $('#post_date').datepicker({ dateFormat: 'dd-mm-yy' });

       // Initialize Quill
       var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Write product description...',
        modules: {
            toolbar: [
                [{'header': [1, 2, 3, 4, 5, 6, false]}], // Header dropdown
                [{'font': []}], // Font dropdown
                [{'size': ['small', false, 'large', 'huge']}], // Font size dropdown
                ['bold', 'italic', 'underline', 'strike'], // Text formatting
                [{'color': []}, {'background': []}], // Color and background
                [{'align': []}], // Alignment
                [{'list': 'ordered'}, {'list': 'bullet'}], // Lists
                [{'indent': '-1'}, {'indent': '+1'}], // Indentation
                [{'script': 'sub'}, {'script': 'super'}], // Subscript/superscript
                ['blockquote', 'code-block'], // Blockquote and code block
                ['link', 'image', 'video'], // Links, images, videos
                [{'direction': 'rtl'}], // Text direction
                ['clean'] // Clear formatting
            ]
         }
      });

    $.validator.addMethod('dmy', function(value){
        return /^\d{2}-\d{2}-\d{4}$/.test(value);
    }, 'Please enter a date in the format dd-mm-yyyy');

    $('#cat_id').on('change', function(){
        $('#sub_id').html('<option value="">Select Sub Category</option>');
        var cat_id = $(this).val();
        if(cat_id){
            $.get('{{ route('super-admin.products.get-subcategories') }}', {cat_id:cat_id}, function(res){
                $.each(res, function(i, item){
                    $('#sub_id').append('<option value="'+item.id+'">'+item.title+'</option>');
                });
            });
        }
    });

    $('#productForm').validate({
        rules:{
            title:{ required:true },
            cat_id:{ required:true },
            sub_id:{ required:true },
            post_date:{ required:true, dmy:true }
        },
        submitHandler:function(form){
            // Copy Quill content to textarea before submitting
            $('#description').val(quill.root.innerHTML);

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
                    quill.setContents([]);
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
