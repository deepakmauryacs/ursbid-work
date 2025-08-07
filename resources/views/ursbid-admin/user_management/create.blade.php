@extends('ursbid-admin.layouts.app')
@section('title', 'Add User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Add User</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.user-management.index') }}">User List</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title mb-0">Add User</h4>
                </div>
                <form id="userForm">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                            <div class="invalid-feedback" data-field="name"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                            <div class="invalid-feedback" data-field="email"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">User Type</label>
                            <select name="user_type" class="form-select" required>
                                <option value="1">Super Admin</option>
                                <option value="2">Admin</option>
                            </select>
                            <div class="invalid-feedback" data-field="user_type"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Roles</label>
                            <select name="roles[]" class="form-select" multiple>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" data-field="roles"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control">
                            <div class="invalid-feedback" data-field="address"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Joining Date</label>
                            <input type="text" name="created_at" class="form-control" placeholder="dd-mm-yyyy" required>
                            <div class="invalid-feedback" data-field="created_at"></div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Save</button>
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
    $('#userForm').on('submit', function(e){
        e.preventDefault();
        $('.invalid-feedback').text('');
        const datePattern = /^\d{2}-\d{2}-\d{4}$/;
        const joinDate = $('input[name="created_at"]').val();
        if(!datePattern.test(joinDate)){
            $('[data-field="created_at"]').text('Date must be in dd-mm-yyyy format.');
            return;
        }
        const userType = $('select[name="user_type"]').val();
        if(!['1','2'].includes(userType)){
            $('[data-field="user_type"]').text('Invalid user type selected.');
            return;
        }
        const roles = $('select[name="roles[]"]').val() || [];
        for(let r of roles){
            if(!/^\d+$/.test(r)){
                $('[data-field="roles"]').text('Invalid role selected.');
                return;
            }
        }
        const address = $('input[name="address"]').val();
        if(address.length > 255){
            $('[data-field="address"]').text('Address may not be greater than 255 characters.');
            return;
        }
        $.ajax({
            url: '{{ route('super-admin.user-management.store') }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
                toastr.success(res.message);
                $('#userForm')[0].reset();
            },
            error: function(xhr){
                if(xhr.status === 422){
                    let errors = xhr.responseJSON.errors;
                    for(let field in errors){
                        $('[data-field="'+field+'"]').text(errors[field][0]);
                    }
                }else{
                    toastr.error('Creation failed');
                }
            }
        });
    });
});
</script>
@endpush
