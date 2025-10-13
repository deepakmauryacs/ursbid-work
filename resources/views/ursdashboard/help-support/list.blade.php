@extends('seller.layouts.app')

@section('title', 'Help & Support')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="page-title mb-0">Help &amp; Support</h4>
                        <p class="text-muted mb-0">Track your support conversations with the URSBID team.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <h5 class="card-title mb-0">Support Tickets</h5>
                        <a href="{{ route('seller.help-support.create') }}" class="btn btn-primary btn-sm">
                            <i class="ri-add-line me-1"></i> New Ticket
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">User ID</th>
                                        <th scope="col">Subject</th>
                                        <th scope="col">Message</th>
                                        <th scope="col">Attachment</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Created At</th>
                                        <th scope="col">Updated At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tickets as $ticket)
                                        <tr>
                                            <td>{{ $ticket->id }}</td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-semibold">{{ $ticket->user_id }}</span>
                                                    @if ($ticket->creator)
                                                        <small class="text-muted">{{ $ticket->creator->name }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $ticket->subject }}</td>
                                            <td>
                                                <div class="text-break" style="max-width: 320px;">
                                                    {{ $ticket->message }}
                                                </div>
                                            </td>
                                            <td>
                                                @if ($ticket->attachment)
                                                    <a href="{{ $ticket->attachment }}" target="_blank" rel="noopener">
                                                        View
                                                    </a>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $normalizedStatus = $ticket->status ?? 'pending';
                                                    $statusClass = $statusStyles[$normalizedStatus] ?? 'badge bg-secondary-subtle text-secondary';
                                                    $statusLabel = ucwords(str_replace('_', ' ', $normalizedStatus));
                                                @endphp
                                                <span class="{{ $statusClass }}">{{ $statusLabel }}</span>
                                            </td>
                                            <td>{{ optional($ticket->created_at)->format('d M, Y h:i A') }}</td>
                                            <td>{{ optional($ticket->updated_at)->format('d M, Y h:i A') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">
                                                No support tickets found yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($tickets->hasPages())
                            <div class="mt-3">
                                {{ $tickets->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
