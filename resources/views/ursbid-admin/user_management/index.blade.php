@extends('ursbid-admin.layouts.app')
@section('title', 'User Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">User List</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">User List</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <h4 class="card-title mb-0">Users</h4>
                    <a href="{{ route('super-admin.user-management.create') }}" class="btn btn-sm btn-primary">Add User</a>
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
            url: '{{ route('super-admin.user-management.list') }}',
            success: function(res){
                $('#listContainer').html(res.html);
            },
            error: function(){
                toastr.error('Unable to load list');
            }
        });
    }

    $(document).on('click', '.deleteBtn', function(){
        if(!confirm('Are you sure?')) return;
        var id = $(this).data('id');
        $.ajax({
            url: '{{ url('super-admin/user-management') }}/'+id,
            type: 'DELETE',
            data: {_token: '{{ csrf_token() }}'},
            success: function(res){
                toastr.success(res.message);
                loadList();
            },
            error: function(){
                toastr.error('Delete failed');
            }
        });
    });

    loadList();
});
</script>
@endpush
