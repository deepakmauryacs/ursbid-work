{{-- resources/views/frontend/forgot-password.blade.php --}}
@extends('frontend.layouts.app')
@section('title', 'URSBID | Forgot Password')

@section('content')
<div class="body-wrapper">
    <div class="ltn__utilize-overlay"></div>

    <!-- Breadcrumb -->
    <div class="ltn__breadcrumb-area text-left" style="padding:50px 0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title fw-bold">Forgot Password</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li>
                                    <a href="{{ url('/') }}">
                                        <span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Home
                                    </a>
                                </li>
                                <li>Forgot Password</li>
                            </ul>
                        </div>
                    </div>
                </div> 
            </div>
        </div> 
    </div>

    <!-- Content -->
    <section class="privacy">
        <div class="container">
            <div class="support-card rounded shadow-sm" style="background:#fff;border:1px solid #e6eaf0;margin-bottom:75px;">
                <div class="row g-4 p-3 p-md-4 p-lg-5 align-items-center">

                    <!-- Left: Form -->
                    <div class="col-lg-6">
                        @if(Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                                {{ Session::get('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(Session::has('error'))
                            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                                {{ Session::get('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <h2 class="fw-bold mb-2" style="color:#0c1117;">Reset your password</h2>
                        <p class="text-muted mb-4">
                            Enter the email associated with your account and we’ll send you a reset link.
                        </p>

                        <form action="{{ url('/forgot-password') }}" method="POST" class="support-form" novalidate>
                            @csrf

                            <div class="form-floating has-icon mb-3">
                                <input type="email" name="email" id="fp_email" class="form-control form-control-lg" placeholder=" " value="{{ old('email') }}" required>
                                <label for="fp_email">Email*</label>
                                <i class="bi bi-envelope icon"></i>
                                @error('email') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                                @error('g-recaptcha-response')
                                    <span class="text-danger d-block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <button class="btn btn-primary btn-lg px-4 cs-btn my_bnty" type="submit">
                                <span>Send Reset Link</span><i class="bi bi-arrow-right-short ms-1"></i>
                            </button>

                            <div class="mt-3">
                                <a href="{{ url('/login') }}" class="text-decoration-none">
                                    <i class="bi bi-arrow-left me-1"></i> Back to Login
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Right: Visual / Helpful text -->
                    <div class="col-lg-6 d-flex align-items-center justify-content-center">
                        <div class="text-center p-3 p-md-4">
                            <blockquote class="mt-4" style="font-size:1.05rem; color:#1f2937; font-weight:600; line-height:1.6;">
                                “Security first. Reset your password in a few clicks and get back to building with URSBID.”
                            </blockquote>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

{{-- Recaptcha --}}
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

{{-- Page-specific styles to match Register page look --}}
<style>
  .has-icon{ position:relative; }
  .has-icon .icon{
    position:absolute; left:14px; top:50%; transform:translateY(-50%);
    font-size:1.05rem; color:#94a3b8; pointer-events:none; z-index:3;
  }
  .form-floating.has-icon > .form-control{
    padding-left:52px; border-radius:16px; border:1px solid #e6eaf0;
  }
  .form-floating.has-icon > label{ padding-left:52px; color:#6b7280; }
  .form-control:focus{ border-color:#bfdbfe; box-shadow:0 0 0 .2rem rgba(59,130,246,.15); }
  .cs-btn{ border-radius:999px; box-shadow:0 10px 24px rgba(2,6,23,.10); }
  .cs-btn:hover{ transform:translateY(-1px); box-shadow:0 14px 30px rgba(2,6,23,.16); }
</style>
@endsection
