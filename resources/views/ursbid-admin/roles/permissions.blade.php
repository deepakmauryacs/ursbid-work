@extends('ursbid-admin.layouts.app')
@section('title', 'Permissions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Permissions - {{ $role->role_name }}</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.roles.index') }}">Role List</a></li>
                    <li class="breadcrumb-item active">Permissions</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title mb-0">Manage Permissions</h4>
                </div>
                <form id="permissionForm">
                    @csrf
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Module/Submodule</th>
                                    <th>Add</th>
                                    <th>Edit</th>
                                    <th>View</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modules as $module)
                                    <tr>
                                        <td><strong>{{ $module->name }}</strong></td>
                                        <td colspan="4"></td>
                                    </tr>
                                    @foreach($module->children as $child)
                                        @php $perm = $permissions[$child->id] ?? null; @endphp
                                        <tr>
                                            <td class="ps-4">{{ $child->name }}</td>
                                            <td><input type="checkbox" name="permissions[{{ $child->id }}][can_add]" {{ $perm && $perm->can_add ? 'checked' : '' }}></td>
                                            <td><input type="checkbox" name="permissions[{{ $child->id }}][can_edit]" {{ $perm && $perm->can_edit ? 'checked' : '' }}></td>
                                            <td><input type="checkbox" name="permissions[{{ $child->id }}][can_view]" {{ $perm && $perm->can_view ? 'checked' : '' }}></td>
                                            <td><input type="checkbox" name="permissions[{{ $child->id }}][can_delete]" {{ $perm && $perm->can_delete ? 'checked' : '' }}></td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
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
    $('#permissionForm').on('submit', function(e){
        e.preventDefault();
        if($('#permissionForm input[type="checkbox"]:checked').length === 0){
            toastr.error('Please select at least one permission.');
            return;
        }
        $.ajax({
            url: '{{ route('super-admin.roles.permissions.update', $role->id) }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
                toastr.success(res.message);
            },
            error: function(xhr){
                if(xhr.status === 422){
                    toastr.error('Validation failed.');
                } else {
                    toastr.error('Unable to save permissions.');
                }
            }
        });
    });
});
</script>
@endpush
