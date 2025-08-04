@extends('ursbid-admin.layouts.app')
@section('title', 'Edit Testimonial')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="testimonialForm">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Title<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="title" class="form-control" value="{{ $testimonial->title }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Position<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="position" class="form-control" value="{{ $testimonial->position }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Description<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <textarea name="description" class="form-control" rows="4" required>{{ $testimonial->description }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status</label>
                            </div>
                            <div class="col-md-8">
                                <select name="status" class="form-control">
                                    <option value="1" {{ $testimonial->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="2" {{ $testimonial->status == 2 ? 'selected' : '' }}>Inactive</option>
                                </select>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
$(function(){
    $('#testimonialForm').validate({
        rules:{
            title:{required:true},
            position:{required:true},
            description:{required:true}
        },
        submitHandler:function(form){
            $('#saveBtn').prop('disabled',true).text('Updating...');
            $.ajax({
                url: "{{ route('super-admin.testimonials.update', $testimonial->id) }}",
                type: 'POST',
                data: $(form).serialize(),
                success: function(res){
                    toastr.success(res.message);
                    $('#saveBtn').prop('disabled',false).text('Update');
                },
                error: function(xhr){
                    let err = 'Error updating data';
                    if(xhr.responseJSON && xhr.responseJSON.errors){
                        err = Object.values(xhr.responseJSON.errors).map(e=>e.join(', ')).join('<br>');
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
