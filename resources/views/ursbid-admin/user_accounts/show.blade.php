@extends('ursbid-admin.layouts.app')
@section('title', 'View ' . $userType)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">View {{ $userType }}</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.accounts.index', $type) }}">{{ $userType }} List</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title mb-0">Account Details</h4>
                </div>
                @php
                    $accountTypeLabels = [
                        '1' => 'Vendor',
                        '2' => 'Contractor',
                        '3' => 'Client',
                        '4' => 'Buyer',
                    ];
                @endphp
                <div class="card-body">
                    <div class="mb-2"><strong>Name:</strong> {{ $user->name }}</div>
                    <div class="mb-2"><strong>Email:</strong> {{ $user->email }}</div>
                    <div class="mb-2"><strong>Phone:</strong> {{ $user->phone }}</div>
                    <div class="mb-2"><strong>Account Type:</strong> {{ $accountTypeLabels[$user->acc_type] ?? 'N/A' }}</div>
                    <div class="mb-2"><strong>Created Date:</strong> {{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y') }}</div>
                    <div class="mb-2"><strong>Status:</strong> {{ $user->status == '1' ? 'Active' : 'Inactive' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
