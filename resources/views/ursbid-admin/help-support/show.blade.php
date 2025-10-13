@extends('ursbid-admin.layouts.app')
@section('title', 'Ticket #' . $ticket->id)
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0 fw-semibold">Ticket Details</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.support-tickets.index') }}">Help &amp; Support</a></li>
                    <li class="breadcrumb-item active">Ticket #{{ $ticket->id }}</li>
                </ol>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="text-muted small">Ticket ID: #{{ $ticket->id }}</span>
                            <h3 class="fw-semibold mt-1 mb-0">{{ $ticket->subject }}</h3>
                        </div>
                        <span class="{{ $statusStyles[$ticket->status] ?? 'badge bg-secondary' }}">
                            {{ $statusLabels[$ticket->status] ?? ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted text-uppercase">Message</h6>
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $ticket->message }}</p>
                    </div>

                    @if($ticket->attachment)
                        <div class="border rounded p-3 bg-light">
                            <h6 class="text-muted text-uppercase">Attachment</h6>
                            <a href="{{ asset($ticket->attachment) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                <i class="ri-download-line me-1"></i> Download attachment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Update Status</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('super-admin.support-tickets.update-status', $ticket) }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="status" class="form-label">Ticket Status</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                @foreach($statusLabels as $value => $label)
                                    <option value="{{ $value }}" @selected($ticket->status === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Seller Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <span class="text-muted d-block small">Name</span>
                            <span class="fw-semibold">{{ optional($ticket->creator)->name ?? 'Unknown Seller' }}</span>
                        </li>
                        <li class="mb-2">
                            <span class="text-muted d-block small">Email</span>
                            <span class="fw-semibold">{{ optional($ticket->creator)->email ?? 'N/A' }}</span>
                        </li>
                        <li class="mb-0">
                            <span class="text-muted d-block small">Phone</span>
                            <span class="fw-semibold">{{ optional($ticket->creator)->phone ?? 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
