@extends('ursbid-admin.layouts.app')
@section('title', 'Change Password')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title mb-0">Change Password</h4>
                </div>
                <form id="passwordForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                            <div class="invalid-feedback" data-field="current_password"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" required>
                            <div class="invalid-feedback" data-field="password"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                            <div class="invalid-feedback" data-field="password_confirmation"></div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Update</button>
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
    $('#passwordForm').on('submit', function(e){
        e.preventDefault();
        $('.invalid-feedback').text('');
        $.ajax({
            url: '{{ route('super-admin.password.update') }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
                toastr.success(res.message);
                $('#passwordForm')[0].reset();
            },
            error: function(xhr){
                if(xhr.status === 422){
                    let errors = xhr.responseJSON.errors;
                    for (let field in errors) {
                        $('[data-field="'+field+'"]').text(errors[field][0]);
                    }
                }else{
                    toastr.error('Update failed');
                }
            }
        });
    });
});
</script>
@endpush
