{{-- resources/views/frontend/register.blade.php --}}
@extends('frontend.layouts.app')
@section('title', 'URSBID | Register')

@section('content')
<div class="body-wrapper">
    <div class="ltn__utilize-overlay"></div>

    <!-- Breadcrumb -->
    <div class="ltn__breadcrumb-area text-left" style="padding:50px 0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title fw-bold">Register Here</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li>
                                    <a href="{{ url('/') }}">
                                        <span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Home
                                    </a>
                                </li>
                                <li>Register</li>
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

                        <h2 class="fw-bold mb-2" style="color:#0c1117;">Create your account</h2>
                        <p class="text-muted mb-4">Fill in the details to register with us.</p>

                        <form class="support-form" action="{{ url('/seller-register') }}" method="POST" novalidate>
                            @csrf

                            <div class="form-floating has-icon mb-3">
                                <input type="text" name="name" id="reg_name" class="form-control form-control-lg" placeholder=" " value="{{ old('name') }}" required>
                                <label for="reg_name">Name*</label>
                                <i class="bi bi-person icon"></i>
                                @error('name') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-floating has-icon mb-3">
                                <input type="email" name="email" id="reg_email" class="form-control form-control-lg" placeholder=" " value="{{ old('email') }}" required>
                                <label for="reg_email">Email*</label>
                                <i class="bi bi-envelope icon"></i>
                                @error('email') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-floating has-icon mb-3">
                                <input type="text" name="phone" id="reg_phone" class="form-control form-control-lg" placeholder=" " value="{{ old('phone') }}" required>
                                <label for="reg_phone">Phone*</label>
                                <i class="bi bi-telephone icon"></i>
                                @error('phone') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Account Type -->
                            <div class="mb-3">
                                <label class="fw-semibold mb-2 d-block">Register As</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <label class="me-3">
                                        <input type="checkbox" id="acc_type_seller" name="acc_type[]" value="1"> Seller
                                    </label>
                                    <label class="me-3">
                                        <input type="checkbox" id="acc_type_contractor" name="acc_type[]" value="2"> Contractor
                                    </label>
                                    <label class="me-3">
                                        <input type="checkbox" id="acc_type_client" name="acc_type[]" value="3" checked> Client
                                    </label>
                                    <label class="me-3">
                                        <input type="checkbox" id="acc_type_buyer" name="acc_type[]" value="4"> Buyer
                                    </label>
                                </div>
                            </div>

                            <!-- GST + Product/Services (hidden until Seller/Contractor) -->
                            <div id="gstField" style="display:none;">
                                <div class="mb-3">
                                    <label for="pro_ser" class="fw-semibold d-block mb-2">Product/Services</label>
                                    <div id="checkboxContainer"></div>
                                </div>

                                <div class="form-floating has-icon mb-3">
                                    <input type="text" name="gst" id="gstNo" class="form-control form-control-lg" placeholder=" ">
                                    <label for="gstNo">GST No.</label>
                                    <i class="bi bi-file-earmark-text icon"></i>
                                </div>
                            </div>

                            <div class="form-floating has-icon mb-3">
                                <input type="password" name="password" id="reg_password" class="form-control form-control-lg" placeholder=" " required>
                                <label for="reg_password">Password*</label>
                                <i class="bi bi-lock icon"></i>
                                @error('password') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
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

                    <!-- Right: Motivational + About URSBID -->
                    <div class="col-lg-6 d-flex align-items-center justify-content-center">
                        <div class="text-center p-3 p-md-4">
                            <blockquote class="mt-4" style="font-size:1.15rem; color:#1d4ed8; font-weight:600; line-height:1.6;">
                                “Dream it. Build it. Achieve it.<br>
                                URSBID empowers you to connect with the right people,<br>
                                find trusted materials, and grow your business.<br>
                                Together, we build smarter and stronger.”
                            </blockquote>
                    
                            <!-- Login Button -->
                            <div class="btn-wrapper mt-5">
                                <a href="#!" data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                   class="btn btn-outline-dark btn-lg rounded-pill">
                                    Already have an account? Login
                                </a>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

{{-- KEEPING YOUR AJAX --}}
<script>
    $(document).ready(function() {
        function updateDropdown() {
            var selectedCategories = [];
            if ($('#acc_type_seller').is(':checked')) selectedCategories.push(1);
            if ($('#acc_type_contractor').is(':checked')) selectedCategories.push(2);

            if (selectedCategories.length > 0) {
                $('#gstField').show();
                $.ajax({
                    url: '{{ route("fetch.options") }}',
                    type: 'POST',
                    data: {
                        categories: selectedCategories,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#checkboxContainer').html(response);
                    }
                });
            } else {
                $('#gstField').hide();
                $('#checkboxContainer').html('');
            }
        }

        $('input[name="acc_type[]"]').change(updateDropdown);
        updateDropdown();
    });
</script>

<style>
  .has-icon{ position:relative; }
  .has-icon .icon{
    position:absolute; left:14px; top:50%; transform:translateY(-50%);
    font-size:1.05rem; color:#94a3b8; pointer-events:none; z-index:3;
  }
  .form-floating.has-icon > .form-control{ padding-left:52px; border-radius:16px; border:1px solid #e6eaf0; }
  .form-floating.has-icon > label{ padding-left:52px; color:#6b7280; }
  .form-control:focus{ border-color:#bfdbfe; box-shadow:0 0 0 .2rem rgba(59,130,246,.15); }
  .cs-btn{ border-radius:999px; box-shadow:0 10px 24px rgba(2,6,23,.10); }
  .cs-btn:hover{ transform:translateY(-1px); box-shadow:0 14px 30px rgba(2,6,23,.16); }
</style>
@endsection
