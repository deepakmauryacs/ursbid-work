@extends('ursbid-admin.layouts.app')
@section('title', 'Roles')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Roles</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Roles</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <div class="card mb-4 shadow-sm">
                <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">Filter Roles</h5>
                    <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#roleFilters" aria-expanded="true" aria-controls="roleFilters">
                        Toggle Filters
                    </button>
                </div>

                <div class="collapse show" id="roleFilters">
                    <div class="card-body">
                        <form id="filterForm" class="row g-3 align-items-end">
                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                <label class="form-label">Role Name</label>
                                <input type="text" name="role_name" class="form-control" placeholder="Role Name">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All</option>
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                <label class="form-label">From Date</label>
                                <input type="text" name="from_date" class="form-control" placeholder="dd-mm-yyyy">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                <label class="form-label">To Date</label>
                                <input type="text" name="to_date" class="form-control" placeholder="dd-mm-yyyy">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                <label class="form-label d-none d-lg-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-funnel-fill me-2"></i>Apply Filters
                                </button>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                <label class="form-label d-none d-lg-block">&nbsp;</label>
                                <button type="button" id="resetBtn" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Filters
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <h4 class="card-title mb-0">Role List</h4>
                    <a href="{{ route('super-admin.roles.create') }}" class="btn btn-sm btn-primary">Add Role</a>
                </div>
                <div id="listContainer" class="table-responsive"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){
    function loadList(){
        $.ajax({
            url: '{{ route('super-admin.roles.list') }}',
            data: $('#filterForm').serialize(),
            success: function(res){
                $('#listContainer').html(res.html);
                bindDeleteEvents();
            },
            error: function(xhr){
                if(xhr.status === 422){
                    toastr.error('Please provide valid filter inputs.');
                }else{
                    toastr.error('Unable to load list');
                }
            }
        });
    }

    $('#filterForm').on('submit', function(e){
        e.preventDefault();
        const datePattern = /^\d{2}-\d{2}-\d{4}$/;
        const from = $('input[name="from_date"]').val();
        const to = $('input[name="to_date"]').val();
        if(from && !datePattern.test(from)){
            toastr.error('From date must be in dd-mm-yyyy format.');
            return;
        }
        if(to && !datePattern.test(to)){
            toastr.error('To date must be in dd-mm-yyyy format.');
            return;
        }
        loadList();
    });

    $('#resetBtn').on('click', function(){
        $('#filterForm')[0].reset();
        loadList();
    });

    loadList();

    function bindDeleteEvents(){
        $('.deleteBtn').on('click', function(){
            if(!confirm('Are you sure want to delete?')) return;
            const id = $(this).data('id');
            $.ajax({
                url: '/super-admin/roles/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res){
                    toastr.success(res.message);
                    loadList();
                },
                error: function(){
                    toastr.error('Unable to delete role');
                }
            });
        });
    }
});
</script>
@endpush
