@extends('ursbid-admin.layouts.app')
@section('title', 'Help & Support')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Help &amp; Support</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Help &amp; Support</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-lg-4 col-md-6">
                            <label for="search" class="form-label">Search</label>
                            <input type="search" name="search" id="search" value="{{ $searchTerm }}" class="form-control" placeholder="Search by subject or seller">
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Statuses</option>
                                @foreach($statusLabels as $value => $label)
                                    <option value="{{ $value }}" @selected($selectedStatus === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-5 col-md-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                            <a href="{{ route('super-admin.support-tickets.index') }}" class="btn btn-light">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Ticket ID</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Seller</th>
                                    <th scope="col">Created</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    <tr>
                                        <td>#{{ $ticket->id }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $ticket->subject }}</div>
                                            <div class="text-muted small text-truncate" style="max-width: 420px;">{{ \Illuminate\Support\Str::limit(strip_tags($ticket->message), 120) }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ optional($ticket->creator)->name ?? 'Unknown Seller' }}</div>
                                            <div class="text-muted small">{{ optional($ticket->creator)->email }}</div>
                                        </td>
                                        <td>{{ $ticket->created_at?->format('d M Y, h:i A') }}</td>
                                        <td>
                                            <span class="{{ $statusStyles[$ticket->status] ?? 'badge bg-secondary' }}">
                                                {{ $statusLabels[$ticket->status] ?? ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('super-admin.support-tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="ri-eye-line me-1"></i> View Details
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">No support tickets found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($tickets->hasPages())
                    <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="text-muted small">Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} of {{ $tickets->total() }} entries</div>
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
