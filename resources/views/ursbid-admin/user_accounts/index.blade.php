@extends('ursbid-admin.layouts.app')
@section('title', 'User Accounts')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">{{ $userType }} List</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">{{ $userType }} List</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <div class="card mb-4 shadow-sm">
                <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">Filter {{ $userType }}</h5>
                    <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#accountFilters" aria-expanded="true" aria-controls="accountFilters">
                        Toggle Filters
                    </button>
                </div>

                <div class="collapse show" id="accountFilters">
                    <div class="card-body">
                        <form id="filterForm" class="row g-3 align-items-end">
                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Name">
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                                <label class="form-label">Email</label>
                                <input type="text" name="email" class="form-control" placeholder="Email">
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
                    <h4 class="card-title mb-0">{{ $userType }} Accounts</h4>
                    <a href="{{ route('super-admin.accounts.create', $type) }}" class="btn btn-sm btn-primary">Add {{ $userType }}</a>
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
            url: '{{ route('super-admin.accounts.list', $type) }}',
            data: $('#filterForm').serialize(),
            success: function(res){
                $('#listContainer').html(res.html);
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
        const name = $('input[name="name"]').val();
        const email = $('input[name="email"]').val();
        const from = $('input[name="from_date"]').val();
        const to = $('input[name="to_date"]').val();
        const userTypes = $('select[name="user_types[]"]').val() || [];
        const datePattern = /^\d{2}-\d{2}-\d{4}$/;
        const validTypes = ['1','2','3','4'];
        if(name.length > 255){
            toastr.error('Name may not be greater than 255 characters.');
            return;
        }
        if(email.length > 255){
            toastr.error('Email may not be greater than 255 characters.');
            return;
        }
        if(from && !datePattern.test(from)){
            toastr.error('From date must be in dd-mm-yyyy format.');
            return;
        }
        if(to && !datePattern.test(to)){
            toastr.error('To date must be in dd-mm-yyyy format.');
            return;
        }
        for(let t of userTypes){
            if(!validTypes.includes(t)){
                toastr.error('Invalid user type selected.');
                return;
            }
        }
        loadList();
    });

    $('#resetBtn').on('click', function(){
        $('#filterForm')[0].reset();
        loadList();
    });

    loadList();
});
</script>
@endpush
