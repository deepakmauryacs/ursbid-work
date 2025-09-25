@extends('seller.layouts.app')
@section('title', 'Delete ')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="text-center mb-4">
                <h1 class="h3 mb-2">Delete Account</h1>
                <p class="text-muted mb-0">Enter the one-time password we sent to confirm the permanent deletion of your account.</p>
            </div>

            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="alert alert-warning" role="alert">
                        <strong>Note:</strong> Once your account is deleted, it cannot be recovered.
                    </div>

                    <form method="post" action="{{ url('delete_acc') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="mb-3">
                            <label for="delete-otp" class="form-label">Enter OTP</label>
                            <input type="text" class="form-control" id="delete-otp" name="otp" placeholder="6-digit OTP" value="{{ old('otp') }}" required>
                            @if ($errors->has('otp'))
                                <div class="text-danger small mt-1">{{ $errors->first('otp') }}</div>
                            @endif
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Confirm Deletion</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
