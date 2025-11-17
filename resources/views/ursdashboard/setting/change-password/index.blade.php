@extends('ursdashboard.layouts.app')

@section('title', 'Change Password')

@section('content')
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="page-title mb-0">Change Password</h4>
                        <p class="text-muted mb-0">Update your account password to keep your profile secure.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $errors->first('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body p-4">
                        <form method="post" action="{{ route('seller.password.update') }}" autocomplete="off">
                            @csrf

                            <div class="mb-3">
                                <label for="current-password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current-password" name="current_password" placeholder="Enter current password" required>
                                @error('current_password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="new-password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new-password" name="password" placeholder="Enter new password" required>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Must be at least 8 characters including uppercase, lowercase, number, and special character.</div>
                            </div>

                            <div class="mb-4">
                                <label for="confirm-password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm-password" name="password_confirmation" placeholder="Confirm new password" required>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
