@extends('ursbid-admin.layouts.app')
@section('title', $userType . ' List')

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

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                    <h4 class="card-title mb-0">{{ $userType }} Accounts</h4>
                    <button id="showListBtn" class="btn btn-sm btn-primary">Show List</button>
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
    $('#showListBtn').on('click', function(){
        $.get('{{ route('super-admin.accounts.list', $type) }}', function(res){
            if(res.status === 'success'){
                $('#listContainer').html(res.html);
            }
        }).fail(function(){
            toastr.error('Unable to load list');
        });
    });
});
</script>
@endpush
