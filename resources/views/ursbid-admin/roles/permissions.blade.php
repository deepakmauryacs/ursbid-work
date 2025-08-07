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
                                    <th>
                                        <input type="checkbox" id="checkAll"> Module/Submodule
                                    </th>
                                    <th>
                                        <input type="checkbox" class="column-check" data-perm="can_add"> Add
                                    </th>
                                    <th>
                                        <input type="checkbox" class="column-check" data-perm="can_edit"> Edit
                                    </th>
                                    <th>
                                        <input type="checkbox" class="column-check" data-perm="can_view"> View
                                    </th>
                                    <th>
                                        <input type="checkbox" class="column-check" data-perm="can_delete"> Delete
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modules as $module)
                                    @if($module->children->isEmpty())
                                        @php $perm = $permissions[$module->id] ?? null; @endphp
                                        <tr data-module="{{ $module->id }}">
                                            <td>
                                                <input type="checkbox" class="row-check">
                                                <strong>{{ $module->name }}</strong>
                                            </td>
                                            <td><input type="checkbox" class="permission-checkbox" data-perm="can_add" value="1" name="permissions[{{ $module->id }}][can_add]" {{ $perm && $perm->can_add ? 'checked' : '' }}></td>
                                            <td><input type="checkbox" class="permission-checkbox" data-perm="can_edit" value="1" name="permissions[{{ $module->id }}][can_edit]" {{ $perm && $perm->can_edit ? 'checked' : '' }}></td>
                                            <td><input type="checkbox" class="permission-checkbox" data-perm="can_view" value="1" name="permissions[{{ $module->id }}][can_view]" {{ $perm && $perm->can_view ? 'checked' : '' }}></td>
                                            <td><input type="checkbox" class="permission-checkbox" data-perm="can_delete" value="1" name="permissions[{{ $module->id }}][can_delete]" {{ $perm && $perm->can_delete ? 'checked' : '' }}></td>
                                        </tr>
                                    @else
                                        <tr class="module-row" data-module="{{ $module->id }}">
                                            <td>
                                                <input type="checkbox" class="module-check" data-module="{{ $module->id }}">
                                                <strong>{{ $module->name }}</strong>
                                            </td>
                                            <td colspan="4"></td>
                                        </tr>
                                        @foreach($module->children as $child)
                                            @php $perm = $permissions[$child->id] ?? null; @endphp
                                            <tr data-parent="{{ $module->id }}">
                                                <td class="ps-4">
                                                    <input type="checkbox" class="row-check">
                                                    {{ $child->name }}
                                                </td>
                                                <td><input type="checkbox" class="permission-checkbox" data-perm="can_add" value="1" name="permissions[{{ $child->id }}][can_add]" {{ $perm && $perm->can_add ? 'checked' : '' }}></td>
                                                <td><input type="checkbox" class="permission-checkbox" data-perm="can_edit" value="1" name="permissions[{{ $child->id }}][can_edit]" {{ $perm && $perm->can_edit ? 'checked' : '' }}></td>
                                                <td><input type="checkbox" class="permission-checkbox" data-perm="can_view" value="1" name="permissions[{{ $child->id }}][can_view]" {{ $perm && $perm->can_view ? 'checked' : '' }}></td>
                                                <td><input type="checkbox" class="permission-checkbox" data-perm="can_delete" value="1" name="permissions[{{ $child->id }}][can_delete]" {{ $perm && $perm->can_delete ? 'checked' : '' }}></td>
                                            </tr>
                                        @endforeach
                                    @endif
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
    function updateRowChecks(){
        $('#permissionForm tbody tr').each(function(){
            var row = $(this);
            var boxes = row.find('.permission-checkbox');
            if(boxes.length){
                var allChecked = boxes.length === boxes.filter(':checked').length;
                row.find('.row-check').prop('checked', allChecked);
            }
        });
    }

    function updateColumnChecks(){
        $('.column-check').each(function(){
            var perm = $(this).data('perm');
            var boxes = $('.permission-checkbox[data-perm="'+perm+'"]');
            var allChecked = boxes.length === boxes.filter(':checked').length;
            $(this).prop('checked', allChecked);
        });
        var allBoxes = $('.permission-checkbox');
        var allChecked = allBoxes.length === allBoxes.filter(':checked').length;
        $('#checkAll').prop('checked', allChecked);
    }

    function updateModuleChecks(){
        $('.module-check').each(function(){
            var moduleId = $(this).data('module');
            var boxes = $('tr[data-parent="'+moduleId+'"] .permission-checkbox');
            var allChecked = boxes.length && boxes.length === boxes.filter(':checked').length;
            $(this).prop('checked', allChecked);
        });
    }

    updateRowChecks();
    updateColumnChecks();
    updateModuleChecks();

    $('#checkAll').on('change', function(){
        var checked = this.checked;
        $('.column-check, .row-check, .module-check, .permission-checkbox').prop('checked', checked);
    });

    $('.column-check').on('change', function(){
        var perm = $(this).data('perm');
        $('.permission-checkbox[data-perm="'+perm+'"]').prop('checked', this.checked);
        updateRowChecks();
        updateModuleChecks();
        updateColumnChecks();
    });

    $('.row-check').on('change', function(){
        var row = $(this).closest('tr');
        row.find('.permission-checkbox').prop('checked', this.checked);
        updateColumnChecks();
        updateModuleChecks();
    });

    $('.module-check').on('change', function(){
        var moduleId = $(this).data('module');
        $('tr[data-parent="'+moduleId+'"] .permission-checkbox, tr[data-parent="'+moduleId+'"] .row-check').prop('checked', this.checked);
        updateColumnChecks();
    });

    $('.permission-checkbox').on('change', function(){
        updateRowChecks();
        updateColumnChecks();
        updateModuleChecks();
    });

    $('#permissionForm').on('submit', function(e){
        e.preventDefault();
        if($('#permissionForm .permission-checkbox:checked').length === 0){
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
