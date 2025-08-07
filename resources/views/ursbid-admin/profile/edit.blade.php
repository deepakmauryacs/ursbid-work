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
    $('#profileForm').on('submit', function(e){
        e.preventDefault();
        $('.invalid-feedback').text('');
        const datePattern = /^\\d{2}-\\d{2}-\\d{4}$/;
        const joinDate = $('input[name="created_at"]').val();
        if(!datePattern.test(joinDate)){
            $('[data-field="created_at"]').text('Date must be in dd-mm-yyyy format.');
            return;
        }
        $.ajax({
            url: '{{ route('super-admin.profile.update') }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
                toastr.success(res.message);
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
