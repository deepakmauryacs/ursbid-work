@extends('ursbid-admin.layouts.app')
@section('title', 'Edit Role')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Edit Role</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.roles.index') }}">Role List</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title mb-0">Edit Role</h4>
                </div>
                <form id="roleForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Role Name</label>
                            <input type="text" name="role_name" class="form-control" value="{{ $role->role_name }}" required>
                            <div class="invalid-feedback" data-field="role_name"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="1" {{ $role->status == '1' ? 'selected' : '' }}>Active</option>
                                <option value="2" {{ $role->status == '2' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <div class="invalid-feedback" data-field="status"></div>
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
    $('#roleForm').on('submit', function(e){
        e.preventDefault();
        $('.invalid-feedback').text('');
        const roleName = $('input[name="role_name"]').val().trim();
        const status = $('select[name="status"]').val();
        if(!roleName){
            $('[data-field="role_name"]').text('Role name is required.');
            return;
        }
        if(!status){
            $('[data-field="status"]').text('Status is required.');
            return;
        }
        $.ajax({
            url: '{{ route('super-admin.roles.update', $role->id) }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
                toastr.success(res.message);
                window.location.href = '{{ route('super-admin.roles.index') }}';
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
