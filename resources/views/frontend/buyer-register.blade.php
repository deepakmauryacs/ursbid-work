@extends('frontend.layouts.app')
@section('title', 'URSBID | Buyer Register')

@section('content')
<div class="body-wrapper">
    <div class="ltn__utilize-overlay"></div>

    <!-- Breadcrumb -->
    <div class="ltn__breadcrumb-area text-left" style="padding:50px 0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title fw-bold">Buyer Register</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li>
                                    <a href="{{ url('/') }}">
                                        <span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Home
                                    </a>
                                </li>
                                <li>Buyer Register</li>
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
            <div class="contact-card rounded shadow-sm" style="background:#fff;border:1px solid #e6eaf0;margin-bottom:75px;">
                <div class="row g-4 p-3 p-md-4 p-lg-5 align-items-center">

                    <!-- Left: Illustration / CTA -->
                    <div class="col-lg-6 order-lg-2">
                        <div class="text-center">
                            <img src="{{ url('assets/front/img/inner/login.jpg') }}" alt="Register" class="img-fluid rounded-3 shadow-sm mb-3">
                            <div class="d-flex justify-content-center gap-2">
                                {{-- If you use a login modal, keep this. Otherwise, link to buyer-login --}}
                                <a href="{{ url('buyer-login') }}" class="btn btn-outline-secondary">
                                    Already have an account? Login
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Form -->
                    <div class="col-lg-6 order-lg-1">
                        <h2 class="fw-bold mb-2" style="color:#0c1117;">Create your buyer account</h2>
                        <p class="text-muted mb-4">Fill in the details to get started.</p>

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

                        <form action="{{ url('/buyer-register') }}" method="POST" class="ltn__form-box contact-form-box" novalidate>
                            @csrf

                            <div class="form-floating has-icon mb-3">
                                <input type="text" name="name" id="by_name" class="form-control form-control-lg" placeholder=" " value="{{ old('name') }}" required>
                                <label for="by_name">Name*</label>
                                <i class="bi bi-person icon"></i>
                                @if ($errors->has('name'))
                                    <small class="text-danger d-block mt-1">{{ $errors->first('name') }}</small>
                                @endif
                            </div>

                            <div class="form-floating has-icon mb-3">
                                <input type="email" name="email" id="by_email" class="form-control form-control-lg" placeholder=" " value="{{ old('email') }}" required>
                                <label for="by_email">Email*</label>
                                <i class="bi bi-envelope icon"></i>
                                @if ($errors->has('email'))
                                    <small class="text-danger d-block mt-1">{{ $errors->first('email') }}</small>
                                @endif
                            </div>

                            <div class="form-floating has-icon mb-3">
                                <input type="tel" name="phone" id="by_phone" class="form-control form-control-lg" placeholder=" " value="{{ old('phone') }}" required>
                                <label for="by_phone">Phone*</label>
                                <i class="bi bi-telephone icon"></i>
                                @if ($errors->has('phone'))
                                    <small class="text-danger d-block mt-1">{{ $errors->first('phone') }}</small>
                                @endif
                            </div>

                            <!-- Gender -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Gender</label>
                                <div class="d-flex gap-3">
                                    @php $g = old('gender', 'Male'); @endphp
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="g_male" value="Male" {{ $g === 'Male' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="g_male">Male</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="g_female" value="Female" {{ $g === 'Female' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="g_female">Female</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-floating has-icon mb-3">
                                <input type="password" name="password" id="by_password" class="form-control form-control-lg" placeholder=" " required>
                                <label for="by_password">Password*</label>
                                <i class="bi bi-lock icon"></i>
                                @if ($errors->has('password'))
                                    <small class="text-danger d-block mt-1">{{ $errors->first('password') }}</small>
                                @endif
                            </div>

                            <button class="btn btn-primary btn-lg px-4 cs-btn my_bnty" type="submit">
                                <span>Submit</span>
                                <i class="bi bi-arrow-right-short ms-1"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

{{-- Page-local styles (match your Contact/Support look) --}}
<style>
  /* Floating + icons */
  .has-icon{ position:relative; }
  .has-icon .icon{
    position:absolute; left:14px; top:50%; transform:translateY(-50%);
    font-size:1.05rem; color:#94a3b8; pointer-events:none; z-index:3;
  }
  .form-floating.has-icon > .form-control{
    padding-left:52px; border-radius:16px; border:1px solid #e6eaf0;
  }
  .form-floating.has-icon > label{ padding-left:52px; color:#6b7280; }
  .form-control:focus{
    border-color:#bfdbfe; box-shadow:0 0 0 .2rem rgba(59,130,246,.15);
  }
  /* CTA button */
  .cs-btn{ border-radius:999px; box-shadow:0 10px 24px rgba(2,6,23,.10); }
  .cs-btn:hover{ transform:translateY(-1px); box-shadow:0 14px 30px rgba(2,6,23,.16); }
</style>
@endsection
