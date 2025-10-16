@extends('seller.layouts.app')

@section('title', 'Create Support Ticket')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="page-title mb-0">Create Support Ticket</h4>
                        <p class="text-muted mb-0">Share your query with the URSBID support team.</p>
                    </div>
                    <div>
                        <a href="{{ route('seller.help-support.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-go-back-line me-1"></i> Back to Tickets
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('seller.help-support.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" id="subject" class="form-control"
                                    value="{{ old('subject') }}" required maxlength="255" placeholder="Subject of your query">
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea name="message" id="message" rows="5" class="form-control" required placeholder="Describe your issue or question">{{ old('message') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="attachment" class="form-label">Attachment</label>
                                <input type="file" name="attachment" id="attachment" class="form-control"
                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                <div class="form-text">Optional supporting file (max 5MB).</div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('seller.help-support.index') }}" class="btn btn-light">Cancel</a>
                                <button type="submit" class="btn btn-primary">Submit Ticket</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
