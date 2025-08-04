@extends('ursbid-admin.layouts.app')
@section('title', 'Add Youtube Link')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="ytForm">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Youtube Link<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="youtube_link" class="form-control" placeholder="Enter YouTube URL" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status</label>
                            </div>
                            <div class="col-md-8">
                                <select name="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
$(function(){
    $('#ytForm').validate({
        rules:{
            youtube_link:{required:true, url:true},
            status:{required:true}
        },
        submitHandler:function(form){
            $('#saveBtn').prop('disabled',true).text('Saving...');
            $.ajax({
                url: "{{ route('super-admin.youtube-links.store') }}",
                type: 'POST',
                data: $(form).serialize(),
                success: function(res){
                    toastr.success(res.message);
                    form.reset();
                    $('#saveBtn').prop('disabled',false).text('Save');
                },
                error: function(xhr){
                    let err = 'Error saving data';
                    if(xhr.responseJSON && xhr.responseJSON.errors){
                        err = Object.values(xhr.responseJSON.errors).map(e=>e.join(', ')).join('<br>');
                    }
                    toastr.error(err);
                    $('#saveBtn').prop('disabled',false).text('Save');
                }
            });
            return false;
        }
    });
});
</script>
@endpush
