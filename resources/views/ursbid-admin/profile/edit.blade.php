@extends('ursbid-admin.layouts.app')
@section('title', 'Edit Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title mb-0">Edit Profile</h4>
                </div>
                <form id="profileForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                            <div class="invalid-feedback" data-field="name"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            <div class="invalid-feedback" data-field="email"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" value="{{ $user->address }}">
                            <div class="invalid-feedback" data-field="address"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Joining Date</label>
                            <input type="text" name="created_at" class="form-control" value="{{ optional($user->created_at)->format('d-m-Y') }}" placeholder="dd-mm-yyyy" required>
                            <div class="invalid-feedback" data-field="created_at"></div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" id="saveBtn" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    $('#profileForm').validate({
        rules:{
            name:{ required:true },
            email:{ required:true, email:true },
            address:{},
            created_at:{ required:true, pattern:/^\\d{2}-\\d{2}-\\d{4}$/ }
        },
        messages:{
            created_at:{ pattern:'Date must be in dd-mm-yyyy format.' }
        },
        errorPlacement:function(error,element){
            element.closest('.mb-3').find('.invalid-feedback').html(error.text());
        },
        submitHandler:function(form){
            $('#saveBtn').prop('disabled',true).text('Saving...');
            var formData = new FormData(form);
            $.ajax({
                url: '{{ route('super-admin.profile.update') }}',
                type: 'POST',
                data: formData,
                processData:false,
                contentType:false,
                success:function(res){
                    if(res.status === 'success'){
                        toastr.success(res.message);
                    }else{
                        toastr.error(res.message || 'Update failed');
                    }
                    $('#saveBtn').prop('disabled',false).text('Update');
                },
                error:function(xhr){
                    $('#saveBtn').prop('disabled',false).text('Update');
                    if(xhr.status === 422){
                        let errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            $('[data-field="'+field+'"]').html(errors[field][0]);
                        }
                    }else{
                        toastr.error('Update failed');
                    }
                }
            });
            return false;
        }
    });
});
</script>
@endpush
