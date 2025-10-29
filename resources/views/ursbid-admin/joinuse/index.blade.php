@extends('ursbid-admin.layouts.app')
@section('title', $userType . ' Join Users')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">{{ $userType }} Join Users</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.accounts.index', $type) }}">{{ $userType }} List</a></li>
                    <li class="breadcrumb-item active">Join Users</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header border-0 bg-white">
                    <h5 class="mb-0">Referrer Details</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <p class="mb-1 text-muted">Name</p>
                            <h6 class="mb-0">{{ \Illuminate\Support\Str::title($referrer->name) }}</h6>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1 text-muted">Email</p>
                            <h6 class="mb-0">{{ $referrer->email }}</h6>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1 text-muted">Phone</p>
                            <h6 class="mb-0">{{ $referrer->phone ?: '—' }}</h6>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1 text-muted">Referral Code</p>
                            <h6 class="mb-0">{{ $refCode }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center border-bottom flex-wrap gap-2">
                    <h4 class="card-title mb-0">Join Users ({{ $users->total() }})</h4>
                    <a href="{{ route('super-admin.accounts.index', $type) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-2"></i>Back to {{ $userType }} List
                    </a>
                </div>
                <div class="card-body border-bottom">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-sm-6 col-lg-4 col-xl-3">
                            <label class="form-label">Search Keyword</label>
                            <input type="text" name="keyword" class="form-control" value="{{ $filters['keyword'] ?? '' }}" placeholder="Name, email, phone, GST">
                        </div>
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <label class="form-label">Records Per Page</label>
                            <select name="per_page" class="form-select">
                                <option value="25" @selected(($filters['per_page'] ?? 25) == 25)>25</option>
                                <option value="50" @selected(($filters['per_page'] ?? 25) == 50)>50</option>
                                <option value="100" @selected(($filters['per_page'] ?? 25) == 100)>100</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <label class="form-label d-none d-lg-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-funnel-fill me-2"></i>Apply Filters
                            </button>
                        </div>
                        <div class="col-sm-6 col-lg-3 col-xl-2">
                            <label class="form-label d-none d-lg-block">&nbsp;</label>
                            <a href="{{ route('super-admin.accounts.join-users', [$type, $refCode]) }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle text-nowrap table-hover table-centered mb-0">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>GST</th>
                                <th>Product/Services</th>
                                <th>Joined On</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $users->firstItem() + $loop->index }}</td>
                                    <td>{{ \Illuminate\Support\Str::title($user->name) }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->gst ?: '—' }}</td>
                                    <td>
                                        @php
                                            $proSer = $user->pro_ser ?? '';
                                            $shortText = \Illuminate\Support\Str::limit($proSer, 25, '...');
                                        @endphp

                                        @if($proSer)
                                            {{ $shortText }}
                                            <i class="bi bi-info-circle ms-1 text-primary"
                                               data-bs-toggle="tooltip"
                                               data-bs-placement="top"
                                               title="{{ $proSer }}"></i>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ optional($user->created_at)->format('d-m-Y') }}</td>
                                    <td>
                                        @if($user->status == '1')
                                            <span class="badge bg-success-subtle text-success py-1 px-2 fs-13">Active</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger py-1 px-2 fs-13">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No join users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
