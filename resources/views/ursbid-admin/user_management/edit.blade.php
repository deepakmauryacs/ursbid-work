@extends('ursbid-admin.layouts.app')
@section('title', 'Edit User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Edit User</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.user-management.index') }}">User List</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title mb-0">Edit User</h4>
                </div>
                <form id="userForm">
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
                            <label class="form-label">User Type</label>
                            <select name="user_type" class="form-select" required>
                                <option value="1" @if($user->user_type == 1) selected @endif>Super Admin</option>
                                <option value="2" @if($user->user_type == 2) selected @endif>Admin</option>
                            </select>
                            <div class="invalid-feedback" data-field="user_type"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Roles</label>
                            <select name="roles[]" class="form-select" multiple>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->roles->contains($role->id) ? 'selected' : '' }}>{{ $role->role_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" data-field="roles"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" value="{{ $user->address }}">
                            <div class="invalid-feedback" data-field="address"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Joining Date</label>
                            <input type="text" name="created_at" class="form-control" value="{{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y') }}" placeholder="dd-mm-yyyy" required>
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
            url: '{{ route('super-admin.user-management.update', $user->id) }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
                toastr.success(res.message);
            },
            error: function(xhr){
                if(xhr.status === 422){
                    let errors = xhr.responseJSON.errors;
                    for(let field in errors){
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
