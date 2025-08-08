@extends('ursbid-admin.layouts.app')
@section('title', 'Permissions')

@section('content')
<div class="container-fluid py-3" id="permissionsRoot">
    <div class="row align-items-center mb-3">
        <div class="col">
            <h4 class="mb-1 fw-semibold">Permissions — {{ $role->role_name }}</h4>
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('super-admin.roles.index') }}">Role List</a></li>
                <li class="breadcrumb-item active">Permissions</li>
            </ol>
        </div>
        <div class="col-auto d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary btn-sm" id="presetNone">None</button>
            <button class="btn btn-outline-secondary btn-sm" id="presetReadOnly">Read Only</button>
            <button class="btn btn-primary btn-sm" id="presetFull">Full Access</button>
        </div>
    </div>

    <div class="card border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-body border-0 pb-2">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-lg-7 d-flex align-items-center flex-wrap gap-3">
                    <label class="d-inline-flex align-items-center gap-2 m-0">
                        <input class="form-check-input m-0" type="checkbox" id="checkAll">
                        <span class="small">Toggle All</span>
                    </label>

                    <div class="vr d-none d-md-block"></div>

                    <label class="d-inline-flex align-items-center gap-2 m-0">
                        <input class="form-check-input column-check m-0" type="checkbox" id="colAdd" data-perm="can_add">
                        <span class="small">Add</span>
                    </label>
                    <label class="d-inline-flex align-items-center gap-2 m-0">
                        <input class="form-check-input column-check m-0" type="checkbox" id="colEdit" data-perm="can_edit">
                        <span class="small">Edit</span>
                    </label>
                    <label class="d-inline-flex align-items-center gap-2 m-0">
                        <input class="form-check-input column-check m-0" type="checkbox" id="colView" data-perm="can_view">
                        <span class="small">View</span>
                    </label>
                    <label class="d-inline-flex align-items-center gap-2 m-0">
                        <input class="form-check-input column-check m-0" type="checkbox" id="colDelete" data-perm="can_delete">
                        <span class="small">Delete</span>
                    </label>
                </div>
                <div class="col-12 col-lg-5 d-flex justify-content-lg-end">
                    <div class="input-group input-group-sm" style="max-width:380px;">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" id="moduleSearch" class="form-control" placeholder="Search modules…">
                    </div>
                </div>
            </div>
        </div>

        <form id="permissionForm">
            @csrf

            @php
                $flat = $modules->filter(fn($m) => $m->children->isEmpty());
                $parents = $modules->filter(fn($m) => $m->children->isNotEmpty());

                $hiddenPermissions = [
                    'vendor-list'      => ['can_edit','can_delete'],
                    'on-page-seo'      => ['can_delete'],
                    'general-settings' => ['can_delete'],
                ];

                $allPermissions = [
                    'can_add'    => 'Add/Save',
                    'can_edit'   => 'Edit/Update',
                    'can_view'   => 'View',
                    'can_delete' => 'Delete',
                ];
            @endphp

            @if($flat->count())
            <div class="px-3 pt-2">
                <div class="section-title">Single Modules</div>
                <div class="row g-2" id="flatContainer">
                    @foreach($flat as $module)
                        @php $perm = $permissions[$module->id] ?? null; @endphp
                        <div class="col-12 col-md-6 col-xl-4 module-card" data-name="{{ strtolower($module->name) }}">
                            <div class="simple-card p-3">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="title">{{ $module->name }}</div>
                                        <div class="meta">Module</div>
                                    </div>
                                    <div class="ms-auto d-inline-flex align-items-center gap-2">
                                        <span class="meta">Row</span>
                                        <input type="checkbox" class="form-check-input row-check m-0">
                                    </div>
                                </div>

                                <div class="perm-row">
                                    @foreach($allPermissions as $permKey => $permLabel)
                                        @if(!in_array($permKey, $hiddenPermissions[$module->slug] ?? []))
                                            <label class="perm-item">
                                                <input type="checkbox"
                                                       class="form-check-input permission-checkbox m-0"
                                                       data-perm="{{ $permKey }}"
                                                       value="1"
                                                       name="permissions[{{ $module->id }}][{{ $permKey }}]"
                                                       {{ $perm && $perm->{$permKey} ? 'checked' : '' }}>
                                                <span>{{ $permLabel }}</span>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($parents->count())
            <div class="px-3 py-2">
                <div class="section-title">Modules with Submodules</div>
                <div class="accordion" id="permAccordion">
                    @foreach($parents as $module)
                        <div class="accordion-item simple-acc module-card" data-name="{{ strtolower($module->name) }}">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#group-{{ $module->id }}">
                                    <span class="title">{{ $module->name }}</span>
                                    <span class="ms-auto d-inline-flex align-items-center gap-2">
                                        <span class="meta d-none d-sm-inline">Toggle Group</span>
                                        <input class="form-check-input module-check m-0" type="checkbox" data-module="{{ $module->id }}" id="modSwitch{{ $module->id }}">
                                    </span>
                                </button>
                            </h2>
                            <div id="group-{{ $module->id }}" class="accordion-collapse collapse" data-bs-parent="#permAccordion">
                                <div class="accordion-body p-0">
                                    <div class="list-group list-group-flush">
                                        @foreach($module->children as $child)
                                            @php $perm = $permissions[$child->id] ?? null; @endphp
                                            <div class="list-group-item simple-row child-row" data-parent="{{ $module->id }}" data-name="{{ strtolower($child->name) }}">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-auto">
                                                        <div class="sub-title">{{ $child->name }}</div>
                                                        <div class="meta">Submodule</div>
                                                    </div>
                                                    <div class="d-inline-flex align-items-center gap-2">
                                                        <span class="meta">Row</span>
                                                        <input type="checkbox" class="form-check-input row-check m-0">
                                                    </div>
                                                </div>
                                                <div class="perm-row mt-2">
                                                    @foreach($allPermissions as $permKey => $permLabel)
                                                        @if(!in_array($permKey, $hiddenPermissions[$child->slug] ?? []))
                                                            <label class="perm-item">
                                                                <input type="checkbox"
                                                                       class="form-check-input permission-checkbox m-0"
                                                                       data-perm="{{ $permKey }}"
                                                                       value="1"
                                                                       name="permissions[{{ $child->id }}][{{ $permKey }}]"
                                                                       {{ $perm && $perm->{$permKey} ? 'checked' : '' }}>
                                                                <span>{{ $permLabel }}</span>
                                                            </label>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="card-footer bg-body border-0 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light border" id="resetForm">Reset</button>
                <button type="submit" class="btn btn-primary px-4">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
/* ===== Minimal tokens (auto light/dark) ===== */
:root{
  --surface-0: var(--bs-body-bg);
  --surface-1: color-mix(in oklab, var(--bs-body-bg) 94%, transparent);
  --border-1: color-mix(in oklab, var(--bs-border-color) 75%, transparent);
  --border-2: color-mix(in oklab, var(--bs-border-color) 55%, transparent);
  --text-muted: var(--bs-secondary-color);
  --radius-lg: 12px;
  --radius-md: 10px;
}
.container-fluid{max-width:1280px;}
.vr{width:1px;height:18px;background:var(--border-2);}

/* ===== Headers ===== */
h4{font-weight:600;letter-spacing:.2px;}
.breadcrumb a{color:inherit;text-decoration:none;}
.breadcrumb a:hover{color:var(--bs-primary);}

/* ===== Simple card ===== */
.simple-card{
  background: var(--surface-0);
  border:1px solid var(--border-1);
  border-radius: var(--radius-lg);
}
.simple-card + .simple-card{margin-top:.5rem;}
.title{font-weight:600; text-transform:uppercase; letter-spacing:.3px;}
.sub-title{font-weight:600;}
.meta{color:var(--text-muted); font-size:.825rem;}

.section-title{
  font-size:.78rem; text-transform:uppercase; letter-spacing:.18rem;
  color:var(--text-muted); padding: .25rem 0 .5rem;
}

/* Accordion minimal */
.simple-acc{border:1px solid var(--border-1);}
.accordion-button{background:var(--surface-0); border-radius: var(--radius-lg) var(--radius-lg) 0 0!important;}
.accordion-button:not(.collapsed){background: var(--surface-1);}
.accordion-item{border-radius: var(--radius-lg)!important; overflow:hidden; margin-bottom:.5rem;}
.simple-row{background:var(--surface-0); border-top:1px solid var(--border-1);}

/* Permission row & items */
.perm-row{
  display:flex; flex-wrap:wrap; gap:.5rem .75rem; margin-top:.5rem;
}
.perm-item{
  display:inline-flex; align-items:center; gap:.5rem;
  padding:.35rem .6rem;
  border-radius: var(--radius-md);
  background: var(--surface-1);
  border:1px solid var(--border-2);
  font-size:.9rem;
}
.perm-item:hover{border-color: var(--border-1);}

/* Switches – medium, unobtrusive */
.form-check-input{ width:2.2em; height:1.2em; cursor:pointer; }
.form-check-input:focus{ box-shadow: 0 0 0 .16rem rgba(var(--bs-primary-rgb), .15); }
.form-check-input:not(:checked){ background-color: color-mix(in oklab, var(--bs-secondary-bg) 70%, transparent);}

/* Inputs */
.input-group-text{background:var(--surface-1); border-color:var(--border-1);}
#moduleSearch{border-color:var(--border-1); border-left:0;}
#moduleSearch:focus{box-shadow:0 0 0 .2rem rgba(var(--bs-primary-rgb), .12);}

/* Spacing tweaks */
.card-header{border-bottom:1px solid var(--border-1)!important;}
.list-group-item{border-color:var(--border-1)!important;}
</style>
@endpush

@push('scripts')
<script>
$(function(){
    const $form = $('#permissionForm');

    function setIndeterminate($checkbox, state){
        const el = $checkbox.get(0);
        if(!el) return;
        el.indeterminate = !!state;
    }

    function updateRowChecks(){
        $('[data-parent], .module-card').each(function(){
            const $scope = $(this);
            const $perm = $scope.find('.permission-checkbox');
            if(!$perm.length) return;
            const checked = $perm.filter(':checked').length;
            const $row = $scope.find('.row-check');
            $row.prop('checked', checked === $perm.length);
            setIndeterminate($row, checked > 0 && checked < $perm.length);
        });
    }

    function updateColumnChecks(){
        $('.column-check').each(function(){
            const perm = $(this).data('perm');
            const $boxes = $('.permission-checkbox[data-perm="'+perm+'"]');
            const checked = $boxes.filter(':checked').length;
            $(this).prop('checked', checked === $boxes.length);
            setIndeterminate($(this), checked > 0 && checked < $boxes.length);
        });
        const $all = $('.permission-checkbox');
        const allChecked = $all.length && $all.length === $all.filter(':checked').length;
        $('#checkAll').prop('checked', allChecked);
        setIndeterminate($('#checkAll'), !allChecked && $all.filter(':checked').length > 0);
    }

    function updateModuleChecks(){
        $('.module-check').each(function(){
            const id = $(this).data('module');
            const $scope = $('#group-'+id);
            const $perm = $scope.find('.permission-checkbox');
            const checked = $perm.filter(':checked').length;
            $(this).prop('checked', checked && checked === $perm.length);
            setIndeterminate($(this), checked > 0 && checked < $perm.length);
        });
    }

    function refreshAll(){ updateRowChecks(); updateColumnChecks(); updateModuleChecks(); }

    // Init
    refreshAll();

    // Master toggles
    $('#checkAll').on('change', function(){
        const c = this.checked;
        $('.permission-checkbox, .row-check, .module-check').prop('checked', c).each(function(){
            setIndeterminate($(this), false);
        });
    });

    $('.column-check').on('change', function(){
        const perm = $(this).data('perm');
        $('.permission-checkbox[data-perm="'+perm+'"]').prop('checked', this.checked);
        refreshAll();
    });

    // Row / Group toggles
    $(document).on('change', '.row-check', function(){
        const $card = $(this).closest('.module-card, .child-row');
        $card.find('.permission-checkbox').prop('checked', this.checked);
        refreshAll();
    });

    $(document).on('change', '.module-check', function(){
        const id = $(this).data('module');
        const c = this.checked;
        $('#group-'+id+' .permission-checkbox, #group-'+id+' .row-check').prop('checked', c).each(function(){
            setIndeterminate($(this), false);
        });
        refreshAll();
    });

    // Individual permission changed
    $(document).on('change', '.permission-checkbox', refreshAll);

    // Search filter
    $('#moduleSearch').on('input', function(){
        const q = $(this).val().toLowerCase().trim();
        $('.module-card, .child-row').each(function(){
            const name = $(this).data('name') || '';
            $(this).toggle(name.includes(q));
        });
    });

    // Presets
    $('#presetNone').on('click', function(){
        $('.permission-checkbox, .row-check, .module-check, #checkAll, .column-check').prop('checked', false);
        $('input[type=checkbox]').each(function(){ setIndeterminate($(this), false); });
    });

    $('#presetReadOnly').on('click', function(){
        $('.permission-checkbox').prop('checked', false);
        $('.permission-checkbox[data-perm="can_view"]').prop('checked', true);
        refreshAll();
    });

    $('#presetFull').on('click', function(){
        $('.permission-checkbox, .row-check, .module-check, #checkAll, .column-check').prop('checked', true);
        $('input[type=checkbox]').each(function(){ setIndeterminate($(this), false); });
    });

    // Reset (unsaved)
    $('#resetForm').on('click', function(){
        $('.permission-checkbox, .row-check, .module-check, #checkAll, .column-check').prop('checked', false);
        $('input[type=checkbox]').each(function(){ setIndeterminate($(this), false); });
        if (window.toastr) toastr.info('Cleared current selections (not saved).');
    });

    // Submit
    $form.on('submit', function(e){
        e.preventDefault();
        if($form.find('.permission-checkbox:checked').length === 0){
            if (window.toastr) toastr.error('Please select at least one permission.');
            return;
        }
        $.ajax({
            url: '{{ route('super-admin.roles.permissions.update', $role->id) }}',
            type: 'POST',
            data: $form.serialize(),
            success: (res) => window.toastr ? toastr.success(res.message || 'Permissions updated.') : alert('Permissions updated.'),
            error: (xhr) => window.toastr ? toastr.error(xhr.status === 422 ? 'Validation failed.' : 'Unable to save permissions.') : alert('Unable to save permissions.')
        });
    });
});
</script>
@endpush
