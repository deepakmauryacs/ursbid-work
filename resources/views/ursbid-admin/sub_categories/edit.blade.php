@extends('ursbid-admin.layouts.app')
@section('title', 'Edit Sub Category')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="subCategoryForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Title<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="title" class="form-control" value="{{ $sub->title }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Category<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <select name="cat_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $sub->cat_id == $cat->id ? 'selected' : '' }}>{{ $cat->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Post Date<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="post_date" id="post_date" class="form-control" value="{{ $sub->post_date }}" placeholder="dd-mm-yyyy" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Order By</label>
                            </div>
                            <div class="col-md-8">
                                <input type="number" name="order_by" class="form-control" value="{{ $sub->order_by }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status</label>
                            </div>
                            <div class="col-md-8">
                                <select name="status" class="form-control">
                                    <option value="1" {{ $sub->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $sub->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Image</label>
                            </div>
                            <div class="col-md-8">
                                @if(!empty($sub->image))
                                    <div class="mb-2">
                                        <img src="{{ asset('public/uploads/' . $sub->image) }}" alt="Image" height="80" style="border:1px solid #ccc;">
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
                                <input type="text" name="meta_title" class="form-control" value="{{ $sub->meta_title }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Meta Keywords</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="meta_keywords" class="form-control" value="{{ $sub->meta_keywords }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Meta Description</label>
                            </div>
                            <div class="col-md-8">
                                <textarea name="meta_description" class="form-control" rows="3">{{ $sub->meta_description }}</textarea>
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
@push('scripts')
<script>
$(function(){
    $('#subCategoryForm').on('submit', function(e){
        e.preventDefault();
        if(!validateForm()) return;
        $('#saveBtn').attr('disabled', true).text('Updating...');
        $.ajax({
            url: "{{ route('super-admin.sub-categories.update', $sub->id) }}",
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(res){
                toastr.success(res.message);
                window.location.href = "{{ route('super-admin.sub-categories.index') }}";
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
    });

    function validateForm(){
        let title = $('input[name="title"]').val().trim();
        let cat = $('select[name="cat_id"]').val();
        let date = $('#post_date').val().trim();
        let dateRegex = /^\d{2}-\d{2}-\d{4}$/;
        if(title === '' || cat === '' || date === '' || !dateRegex.test(date)){
            toastr.error('Please fill required fields with valid data');
            return false;
        }
        return true;
    }
});
</script>
@endpush
@endsection
