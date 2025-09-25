{{-- resources/views/frontend/quotation-form.blade.php --}}
@extends('frontend.layouts.app')
@section('title', 'URSBID | Quotation Form')

@section('content')
<div class="body-wrapper">
    <div class="ltn__utilize-overlay"></div>

    <!-- Breadcrumb -->
    <div class="ltn__breadcrumb-area text-left py-4 py-md-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title fw-bold mb-2">Quotation Form</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul class="mb-0">
                                <li>
                                    <a href="{{ url('/') }}">
                                        <span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Home
                                    </a>
                                </li>
                                <li>Quotation</li>
                            </ul>
                        </div>
                    </div>
                </div> 
            </div>
        </div> 
    </div>

    <section class="privacy">
        <div class="container">
            <div class="support-card rounded shadow-sm mb-5 form-shell">
                <div class="row g-4 p-3 p-md-4 p-lg-5">
                    
                    {{-- Alerts --}}
                    <div class="col-12">
                        @if(Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                                {{ Session::get('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                                <strong>Please fix the following:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>

                    {{-- Header --}}
                    <div class="col-12">
                        <h2 class="fw-bold mb-1" style="color:#0c1117;">Request a Quotation</h2>
                        <p class="text-muted mb-4">All fields are required.</p>
                    </div>

                    {{-- Form --}}
                    <form action="{{ url('qutation_form') }}" method="POST" enctype="multipart/form-data" class="support-form">
                        @csrf

                        {{-- Hidden product fields --}}
                        <input type="hidden" name="product_brand" value="{{ isset($superproducts->title) ? $superproducts->title : 'N/A' }}">
                        <input type="hidden" name="product_name" value="{{ $products->title }}">
                        <input type="hidden" name="subcategory_check" value="{{ $sid }}">
                        <input type="hidden" name="product_id" value="{{ $products->id }}">
                        <input type="hidden" name="product_img" value="{{ $products->image }}">

                        <div class="row g-3">
                            {{-- Name --}}
                            <div class="col-md-6">
                                @php $name = session('seller')->name ?? ''; @endphp
                                <div class="form-floating has-icon">
                                    <input type="text" name="name" id="q_name" class="form-control form-control-lg" placeholder=" " value="{{ $name ?: old('name') }}" required {{ $name ? 'readonly' : '' }}>
                                    <label for="q_name">Name*</label>
                                    <i class="bi bi-person icon"></i>
                                </div>
                                @error('name') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                @php $email = session('seller')->email ?? ''; @endphp
                                <div class="form-floating has-icon">
                                    <input type="email" name="email" id="q_email" class="form-control form-control-lg" placeholder=" " value="{{ $email ?: old('email') }}" required {{ $email ? 'readonly' : '' }}>
                                    <label for="q_email">Email*</label>
                                    <i class="bi bi-envelope icon"></i>
                                </div>
                                @error('email') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Description --}}
                            <div class="col-12">
                                <div class="form-floating has-icon">
                                    <textarea name="message" id="q_message" 
                                        class="form-control form-control-lg nysc" 
                                        placeholder=" " required style="min-height:110px;">{{ old('message') }}</textarea>
                                    <label for="q_message">Description of product*</label>
                                    <i class="bi bi-chat-left-text icon"></i>
                                </div>
                                @error('message') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
                            </div>


                            {{-- Use my location + City/State/Zip/Address --}}
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-primary btn-lg cs-outline" onclick="getLocation()">
                                    <i class="bi bi-geo-alt me-1"></i> Use my location
                                </button>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating has-icon">
                                    <input type="text" id="city" name="city" class="form-control form-control-lg" placeholder=" " value="{{ old('city') }}" required>
                                    <label for="city">City*</label>
                                    <i class="bi bi-building icon"></i>
                                </div>
                                @error('city') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating has-icon">
                                    <input type="text" id="state" name="state" class="form-control form-control-lg" placeholder=" " value="{{ old('state') }}" required>
                                    <label for="state">State*</label>
                                    <i class="bi bi-map icon"></i>
                                </div>
                                @error('state') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating has-icon">
                                    <input type="text" id="postal-code" name="zipcode" class="form-control form-control-lg" placeholder=" " value="{{ old('zipcode') }}" required>
                                    <label for="postal-code">Zipcode*</label>
                                    <i class="bi bi-mailbox icon"></i>
                                </div>
                                @error('zipcode') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-floating has-icon">
                                    <textarea name="address" id="address" 
                                        class="form-control form-control-lg nysc" 
                                        placeholder=" " required style="min-height:90px;">{{ old('address') }}</textarea>
                                    <label for="address">Address*</label>
                                    <i class="bi bi-geo-alt icon"></i>
                                </div>
                                @error('address') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
                            </div>


                            {{-- Quotation Type --}}
                            <div class="col-12">
                                <h6 class="fw-semibold mt-2">Quotation Type</h6>
                                <div class="d-flex flex-wrap gap-3">
                                    @if($category->id == 9)
                                        <label class="form-check form-check-inline align-items-center">
                                            <input class="form-check-input me-2" type="radio" checked name="material" value="Only Product">
                                            <span class="form-check-label">Only Product</span>
                                        </label>
                                        <label class="form-check form-check-inline align-items-center">
                                            <input class="form-check-input me-2" type="radio" name="material" value="Including Shipping">
                                            <span class="form-check-label">Including Shipping</span>
                                        </label>
                                    @else
                                        <label class="form-check form-check-inline align-items-center">
                                            <input class="form-check-input me-2" type="radio" checked name="material" value="With Material">
                                            <span class="form-check-label">With Material</span>
                                        </label>
                                        <label class="form-check form-check-inline align-items-center">
                                            <input class="form-check-input me-2" type="radio" name="material" value="Without Material">
                                            <span class="form-check-label">Without Material</span>
                                        </label>
                                    @endif
                                </div>
                            </div>

                            {{-- Area / Time / Quantity / Unit --}}
                            <input type="hidden" id="latitude" name="latitude" value="">
                            <input type="hidden" id="longitude" name="longitude" value="">
                            <input type="hidden" name="cat_id" value="{{ $category->id }}">

                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select form-select-lg" id="bid_area" name="bid_area" required>
                                        <option value="2">Around 2 KM</option>
                                        <option value="5">Around 5 KM</option>
                                        <option value="10">Around 10 KM</option>
                                        <option value="50">Around 50 KM</option>
                                        <option value="100">Around 100 KM</option>
                                        <option value="5000">No limit</option>
                                    </select>
                                    <label for="bid_area">Area*</label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select form-select-lg" id="bid_time" name="bid_time" required>
                                        <option value="1">1 Day</option>
                                        <option value="3">3 Days</option>
                                        <option value="7">7 Days</option>
                                        <option value="28">28 Days</option>
                                    </select>
                                    <label for="bid_time">Time*</label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-floating has-icon">
                                    <input type="text" id="quantity" name="quantity" class="form-control form-control-lg" placeholder=" " value="{{ old('quantity') }}" required>
                                    <label for="quantity">Quantity*</label>
                                    <i class="bi bi-123 icon"></i>
                                </div>
                                @error('quantity') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select class="form-select form-select-lg" id="unit" name="unit" required>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->title }}">{{ $unit->title }}</option>
                                        @endforeach
                                    </select>
                                    <label for="unit">Unit*</label>
                                </div>
                            </div>

                            {{-- File Upload (refined) --}}
                            <div class="col-12">
                                <label class="fw-semibold mb-2 d-block">Attachments (quotation file / image)</label>
                                <div id="file-upload-area">
                                    <div class="file-row input-group mb-2">
                                        <input type="file" name="images[]" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                        <button type="button" class="btn btn-outline-secondary" onclick="removeFileRow(this)">
                                            <i class="bi bi-x-lg me-1"></i> Remove
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm mt-1" id="add-file-btn">
                                    <i class="bi bi-plus-lg me-1"></i> Add another file
                                </button>
                                <small class="text-muted d-block mt-1">Accepted: JPG, PNG, PDF, DOC. Max 5 files.</small>
                            </div>

                            {{-- Terms & Conditions --}}
                            <div class="col-12">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="termAndCondition1" name="term_and_condition" value="term_and_cond_acc" required>
                                    <label class="form-check-label" for="termAndCondition1">
                                        I have read and accept the terms and conditions
                                    </label>
                                </div>
                                @error('term_and_condition') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Recaptcha --}}
                            <div class="col-12">
                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                                @error('g-recaptcha-response') <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                            </div>

                            {{-- Submit --}}
                            <div class="col-12 text-center mt-1">
                                @if (session()->has('seller'))
                                    <button type="submit" class="btn btn-primary btn-lg px-4 cs-btn">Get Quotation</button>
                                @else
                                    <a data-bs-toggle="modal" data-bs-target="#staticBackdrop2" class="btn btn-primary btn-lg px-4 cs-btn">
                                        Get Quotation
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

    {{-- Login Modal --}}
    <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-text="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Login Here</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/seller-login-form') }}" method="POST">
                        @csrf
                        <div class="form-floating has-icon mb-3">
                            <input type="email" name="email" id="log_email" class="form-control" placeholder=" " value="{{ old('email') }}">
                            <label for="log_email">Email</label>
                            <i class="bi bi-envelope icon"></i>
                        </div>
                        <div class="form-floating has-icon mb-3">
                            <input type="password" name="password" id="log_pass" class="form-control" placeholder=" " value="{{ old('password') }}">
                            <label for="log_pass">Password</label>
                            <i class="bi bi-lock icon"></i>
                        </div>
                        @if($errors->any())
                            <div class='text-danger mb-2'> Please provide correct details.</div>
                        @endif
                        <div class="d-grid">
                            <button class="btn btn-primary" type="submit">Login</button>
                        </div>
                        <div class="go-to-btn mt-3">
                            <a href="{{ url('/forgot-password') }}"><small>Forgot your password?</small></a>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    Don't have an account yet?
                    <a href="{{ url('seller-register') }}">Sign Up</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Terms Modal --}}
    <div id="myModal1" class="modal1" style="display:none;">
        <div class="modal-content1">
            <p class="mb-0">
                This Service Agreement (hereinafter referred to as "Agreement") has been entered from the date of quotation accepting date & valid till the supply of material or completion of the work (quantity referred from quotation page) is not completed.
            </p>
            <p class="mb-0">URSBID, a company registered under the Companies Act, 2013, having its registered address at Parewpur, Dharshawa, Shrawasti 271835</p>
            <div class="go-to-btn mb-4">
                <a href="{{ url('/accept-terms-condition') }}"><small>Read More</small></a>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="closeModal1" name="term_and_condition" value="term_and_cond_acc">
                <label class="form-check-label" for="closeModal1">
                    I have read and accept the terms and conditions
                </label>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
  // File upload: add/remove (clean, aligned with input-group)
  (function(){
    const area  = document.getElementById('file-upload-area');
    const addBtn = document.getElementById('add-file-btn');

    function makeRow(){
      const row = document.createElement('div');
      row.className = 'file-row input-group mb-2';
      row.innerHTML = `
        <input type="file" name="images[]" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
        <button type="button" class="btn btn-outline-secondary" onclick="removeFileRow(this)">
          <i class="bi bi-x-lg me-1"></i> Remove
        </button>`;
      return row;
    }

    window.removeFileRow = function(btn){
      const rows = area.querySelectorAll('.file-row');
      const row  = btn.closest('.file-row');
      if(rows.length > 1){ row.remove(); }
      else { row.querySelector('input[type="file"]').value = ''; }
    };

    if(addBtn){
      addBtn.addEventListener('click', function(){
        const rows = area.querySelectorAll('.file-row');
        if(rows.length >= 5) return;  // soft cap to keep UI tidy
        area.appendChild(makeRow());
      });
    }
  })();

  // Geolocation -> OpenStreetMap Reverse
  function getLocation(){
    if(navigator.geolocation){ navigator.geolocation.getCurrentPosition(showPosition); }
    else alert("Geolocation is not supported by this browser.");
  }
  function showPosition(pos){
    const lat = pos.coords.latitude, lon = pos.coords.longitude;
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lon;
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
      .then(r => r.json())
      .then(data => {
        if(data && data.address){
          const city = data.address.city || data.address.town || data.address.village;
          document.getElementById('city').value = city || '';
          document.getElementById('state').value = data.address.state || '';
          document.getElementById('postal-code').value = data.address.postcode || '';
          document.getElementById('address').value = data.display_name || '';
        }
      }).catch(() => {});
  }

  // T&C custom modal show/hide
  document.getElementById('termAndCondition1').addEventListener('change', function(){
    const m = document.getElementById('myModal1');
    if(this.checked) m.style.display = 'block';
  });
  document.getElementById('closeModal1').addEventListener('click', function(){
    document.getElementById('myModal1').style.display = 'none';
  });
</script>

{{-- Page styles: refined look --}}
<style>
  .form-shell{ 
    background:#fff;
    border:1px solid #e6eaf0; 
    border-radius:14px;
    padding:1.5rem;
  }

  /* Icon inside input */
  .has-icon{ position:relative; }
  .has-icon .icon{
    position:absolute; 
    left:16px; 
    top:50%; 
    transform:translateY(-50%);
    font-size:1.15rem; 
    color:#6b7280; 
    pointer-events:none; 
    z-index:3;
  }
  .form-floating.has-icon > .form-control,
  .form-floating.has-icon > .form-select{ 
    padding-left:52px; 
  }
   /* Fix icon alignment for textarea also */
    .form-floating.has-icon > textarea.form-control {
      padding-left:52px;
      border-radius:14px;
      border:1px solid #e6eaf0;
    }

  .form-floating > .form-control,
  .form-floating > .form-select{
    border-radius:14px; 
    border:1px solid #e6eaf0;
  }
  .form-floating > label{ 
    color:#6b7280; 
    padding-left:52px; 
  }

  /* Focus state */
  .form-control:focus, 
  .form-select:focus{
    border-color:#c7d9ff; 
    box-shadow:0 6px 18px rgba(17,24,39,.06);
  }

  /* Buttons */
  .cs-btn{ 
    border-radius:999px; 
    box-shadow:0 10px 24px rgba(2,6,23,.10); 
  }
  .cs-btn:hover{ 
    transform:translateY(-1px); 
    box-shadow:0 14px 30px rgba(2,6,23,.16); 
  }
  .cs-outline{ 
    border-radius:999px; 
  }

  /* Input-group buttons look neat and same height */
  .input-group>.form-control { height: 56px; }
  .input-group>.btn{ border-color:#e6eaf0; }
  .input-group>.btn:hover{ background:#f3f6ff; }

  .nysc{ min-height:68px; }

  /* File upload rows */
  #file-upload-area .file-upload-wrapper{
    display:flex; 
    align-items:center; 
    gap:10px; 
    margin-bottom:8px;
  }
  #file-upload-area input[type="file"]{
    flex:1;
    height:35px;
    border:1px solid #e6eaf0;
    border-radius:10px;
    font-size:0.95rem;
  }
  #file-upload-area .btn{
    height: 35px;
    padding:0 .9rem;
    font-size:0.9rem;
    border-radius:8px;
    display:flex; 
    align-items:center; 
    gap:4px;
  }
  #add-file-btn{
    margin-top:6px;
    border-radius:8px;
    font-weight:500;
    padding:.5rem 1rem;
  }

  /* Custom T&C modal */
  .modal1{ 
    display:none; 
    position:fixed; 
    z-index:1055; 
    inset:0; 
    background:rgba(0,0,0,.45); 
  }
  .modal-content1{
    background:#fff; 
    margin:10% auto; 
    padding:20px; 
    border:1px solid #e6eaf0;
    width:min(600px, 90%); 
    border-radius:14px; 
    box-shadow:0 10px 24px rgba(2,6,23,.16);
  }
</style>

@endsection
