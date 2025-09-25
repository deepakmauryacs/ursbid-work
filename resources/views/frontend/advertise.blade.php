@extends('frontend.layouts.app')
@section('title', 'URSBID | Advertise')

@section('content')
<div class="body-wrapper">
    <div class="ltn__utilize-overlay"></div>

    <!-- Breadcrumb -->
    <div class="ltn__breadcrumb-area text-left" style="padding:50px 0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title fw-bold">Advertise</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li>
                                    <a href="{{ url('/') }}">
                                        <span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Home
                                    </a>
                                </li>
                                <li>Advertise</li>
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
                <div class="row g-4 p-3 p-md-4 p-lg-5 align-items-start">
                    
                    <!-- Left: Contact info -->
                    <div class="col-lg-5">
                        <div class="d-grid gap-3">
                            <!-- Email -->
                            <div class="info-card">
                                <div class="info-icon">
                                    <img src="{{ url('assets/front/img/inner/10.png') }}" alt="Email">
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold">Email Address</h5>
                                    <p class="mb-0">
                                        <a href="mailto:advertise@ursbid.com">advertise@ursbid.com</a>
                                    </p>
                                </div>
                            </div>
                            <!-- Phone -->
                            <div class="info-card">
                                <div class="info-icon">
                                    <img src="{{ url('assets/front/img/inner/11.png') }}" alt="Phone">
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold">Phone Number</h5>
                                    <p class="mb-1"><a href="tel:+919984555400">+91 9984555400</a></p>
                                    <p class="mb-0"><a href="tel:+919984555300">+91 9984555300</a></p>
                                </div>
                            </div>
                            <!-- Address -->
                            <div class="info-card">
                                <div class="info-icon">
                                    <img src="{{ url('assets/front/img/inner/12.png') }}" alt="Address">
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold">Office Address</h5>
                                    <p class="mb-0">
                                        Village - Parewpur, Post - Dharshawa, District - Shrawasti,<br>
                                        Uttar Pradesh, 271835
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Form -->
                    <div class="col-lg-7">
                        <h2 class="fw-bold mb-2" style="color:#0c1117;">Get In Touch</h2>
                        <p class="text-muted mb-4">Tell us about your advertising needs and we’ll get back to you.</p>

                        @if(Session::has('success'))
                            <div class="alert alert-success d-flex justify-content-between align-items-center">
                                <span>{{ Session::get('success') }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(Session::has('error'))
                            <div class="alert alert-danger d-flex justify-content-between align-items-center">
                                <span>{{ Session::get('error') }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ url('/advertise_inc') }}" method="post" novalidate>
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating has-icon">
                                        <input type="text" name="name" id="ad_name" class="form-control form-control-lg" placeholder=" " required>
                                        <label for="ad_name">Enter your name</label>
                                        <i class="bi bi-person icon"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating has-icon">
                                        <input type="email" name="email" id="ad_email" class="form-control form-control-lg" placeholder=" " required>
                                        <label for="ad_email">Enter email address</label>
                                        <i class="bi bi-envelope icon"></i>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select name="service" id="ad_service" class="form-select form-select-lg" required>
                                            <option value="" selected>Select Option</option>
                                            <option value="Company">Company</option>
                                            <option value="Individual">Individual</option>
                                        </select>
                                        <label for="ad_service">Advertiser Type</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating has-icon">
                                        <input type="text" name="phone" id="ad_phone" class="form-control form-control-lg" placeholder=" " required>
                                        <label for="ad_phone">Enter phone number</label>
                                        <i class="bi bi-telephone icon"></i>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating has-icon">
                                        <textarea name="message" id="ad_message" class="form-control form-control-lg" placeholder=" " style="height:130px" required></textarea>
                                        <label for="ad_message">Enter message</label>
                                        <i class="bi bi-chat-left-text icon"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 mb-3">
                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                                @error('g-recaptcha-response')
                                    <span class="text-danger d-block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <button class="btn btn-primary btn-lg px-4 cs-btn my_bnty" type="submit">
                                <span>Send Message</span>
                                <i class="bi bi-arrow-right-short ms-1"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- reCAPTCHA (if not globally loaded) --}}
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

{{-- Page-local styles (shared with Support/Contact look) --}}
<style>
  /* Info cards */
  .info-card{
    display:flex; gap:14px; align-items:flex-start;
    background:#fff; border:1px solid #e6eaf0; border-radius:16px;
    padding:16px; box-shadow:0 6px 20px rgba(2,6,23,.06);
  }
  .info-icon{
    width:52px; height:52px; border-radius:14px; background:#f8fafc;
    display:flex; align-items:center; justify-content:center; border:1px solid #eef2f7;
  }
  .info-icon img{ width:26px; height:26px; object-fit:contain; }
  .info-card a{ color:#0f172a; text-decoration:none; }
  .info-card a:hover{ text-decoration:underline; }

  /* Icon+floating inputs */
  .has-icon{ position:relative; }
  .has-icon .icon{
    position:absolute; left:14px; top:50%; transform:translateY(-50%);
    font-size:1.05rem; color:#94a3b8; pointer-events:none; z-index:3;
  }
  .form-floating.has-icon > .form-control,
  .form-floating.has-icon > textarea.form-control{
    padding-left:52px; border-radius:16px; border:1px solid #e6eaf0;
  }
  .form-floating.has-icon > label{ padding-left:52px; color:#6b7280; }
  .form-control:focus, .form-select:focus{
    border-color:#bfdbfe; box-shadow:0 0 0 .2rem rgba(59,130,246,.15);
  }

  /* Submit button */
  .cs-btn{ border-radius:999px; box-shadow:0 10px 24px rgba(2,6,23,.10); }
  .cs-btn:hover{ transform:translateY(-1px); box-shadow:0 14px 30px rgba(2,6,23,.16); }
</style>
@endsection
