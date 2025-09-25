{{-- resources/views/frontend/support.blade.php --}}
@extends('frontend.layouts.app')
@section('title', 'URSBID | Customer Support')

@section('content')
<div class="body-wrapper">
    <div class="ltn__utilize-overlay"></div>

    <!-- Breadcrumb -->
    <div class="ltn__breadcrumb-area text-left" style="padding:50px 0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title fw-bold">Customer Support</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li>
                                    <a href="{{ url('/') }}">
                                        <span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Home
                                    </a>
                                </li>
                                <li>Customer Support</li>
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
                            <div class="alert alert-success mb-3">{{ Session::get('success') }}</div>
                        @endif
                        @if(Session::has('error'))
                            <div class="alert alert-danger mb-3">{{ Session::get('error') }}</div>
                        @endif

                        <h2 class="fw-bold mb-2" style="color:#0c1117;">We’re here to help</h2>
                        <p class="text-muted mb-4">Send us your query and our team will get back to you.</p>


                        <form class="support-form" action="{{ url('/support_inc') }}" method="post" novalidate>
                            @csrf

                            <!-- Use placeholder=" " for correct floating-label behavior -->
                            <div class="form-floating has-icon mb-3">
                                <input type="text" name="name" id="cs_name" class="form-control form-control-lg" placeholder=" " required>
                                <label for="cs_name">Name*</label>
                                <i class="bi bi-person icon"></i>
                            </div>

                            <div class="form-floating has-icon mb-3">
                                <input type="email" name="email" id="cs_email" class="form-control form-control-lg" placeholder=" " required>
                                <label for="cs_email">Email*</label>
                                <i class="bi bi-envelope icon"></i>
                            </div>

                            <div class="form-floating has-icon mb-3">
                                <input type="text" name="phone" id="cs_phone" class="form-control form-control-lg" placeholder=" " required>
                                <label for="cs_phone">Phone*</label>
                                <i class="bi bi-telephone icon"></i>
                            </div>

                            <div class="form-floating has-icon mb-3">
                                <textarea name="message" id="cs_message" class="form-control form-control-lg" placeholder=" " style="height:120px" required></textarea>
                                <label for="cs_message">Write Here</label>
                                <i class="bi bi-chat-left-text icon"></i>
                            </div>

                            <div class="mb-3">
                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                                @error('g-recaptcha-response')
                                    <span class="text-danger d-block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <button class="btn btn-primary btn-lg px-4 cs-btn my_bnty" type="submit">
                                <span>Submit</span><i class="bi bi-arrow-right-short ms-1"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Right: Illustration -->
                    <div class="col-lg-6">
                        <div class="text-center">
                            <img src="{{ url('assets/front/img/inner/customer.jpg') }}" alt="Customer Support"
                                 class="img-fluid rounded-3 shadow-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- reCAPTCHA script (if not globally loaded) --}}
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

{{-- Page-local styles --}}
<style>
  /* Quick contact chips */
  .cs-chip{
    display:inline-flex; align-items:center; gap:8px;
    background:#f8fafc; border:1px solid #eef2f7; color:#334155;
    padding:8px 12px; border-radius:999px; font-weight:600; text-decoration:none;
  }
  .cs-chip:hover{ background:#f1f5f9; color:#0f172a; }

  /* Icons inside floating inputs */
  .has-icon{ position:relative; }
  .has-icon .icon{
    position:absolute; left:14px; top:50%; transform:translateY(-50%);
    font-size:1.05rem; color:#94a3b8; pointer-events:none; z-index:3; /* keep above label */
  }

  /* Space for the icon on input & label */
  .form-floating.has-icon > .form-control,
  .form-floating.has-icon > textarea.form-control{
    padding-left:52px; /* was 44px; give extra room for icon */
    border-radius:16px; border:1px solid #e6eaf0;
  }
  .form-floating.has-icon > label{
    padding-left:52px; /* align floating label text with content */
    color:#6b7280;
  }

  /* Nicer focus */
  .form-control:focus{
    border-color:#bfdbfe;
    box-shadow:0 0 0 .2rem rgba(59,130,246,.15);
  }

  /* CTA button */
  .cs-btn{
    border-radius:999px; box-shadow:0 10px 24px rgba(2,6,23,.10);
  }
  .cs-btn:hover{ transform:translateY(-1px); box-shadow:0 14px 30px rgba(2,6,23,.16); }
</style>
@endsection
