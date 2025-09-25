<style>
.mnav li{
    margin-top: 5px !important;
    margin-bottom: 5px !important;
}
</style>
@php
use Illuminate\Support\Facades\DB;

// Fetch active categories
$headerCategories = DB::table('categories')
    ->select('id', 'name', 'slug')
    ->where('status', '1')
    ->orderBy('name', 'ASC')
    ->get();
@endphp
<header class="star_head" style="background:var(--hdr-bg);">
  <div class="container">
    <div class="head">
      <div class="name_logo">
        <div class="logo">
          <a href="{{ url('') }}"><img src="{{ url('assets/front/img/logo.png') }}" alt="URSBID"></a>
        </div>

        <!-- Mobile search trigger -->
        <button class="mb-sh" type="button" data-bs-toggle="modal" data-bs-target="#searchModal" aria-label="Open search">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M10.5 18C14.6421 18 18 14.6421 18 10.5C18 6.35786 14.6421 3 10.5 3C6.35786 3 3 6.35786 3 10.5C3 14.6421 6.35786 18 10.5 18Z" stroke="#ffffff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M16 16L21 21" stroke="#ffffff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>

        <!-- Desktop search -->
        <div class="_3NorZ0 search-wrap" aria-label="Search">
          <form class="_2rslOn" action="{{ url('search/') }}" method="GET" role="search">
            @csrf
            <div class="search-bar">
              <div class="cat-select">
                <select name="category" aria-label="Select Category">
                  <option value="">Categories</option>
                  @foreach($headerCategories ?? [] as $cat)
                    <option value="{{ $cat->id }}" {{ (isset($data['category']) && (int)$data['category'] === (int)$cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                  @endforeach
                </select>
                <span class="arrow">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                </span>
              </div>

              <input class="search-input" type="text" name="search" title="Search for Products"
                     value="{{ $data['keyword'] ?? '' }}" autocomplete="off"
                     placeholder="Search for products, categories or brand">

              <button class="search-btn" type="submit" aria-label="Search">
                Search
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="11" cy="11" r="8"></circle>
                  <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="_2msBFL">
        <div class="all_drop">
          @php $seller = session('seller'); $email = $seller?->email; @endphp
          <div class="btn_icos">
            <div class="icon"><img src="{{ url('assets/front/img/svg/profile-52e0dc.svg') }}" alt="Profile Icon"></div>
            @if ($email)
              <span>Dashboard</span>
              <a href="{{ url('seller-dashboard') }}" class="all_fit" aria-label="Go to Dashboard"></a>
            @else
              <span>Login</span>
              <a href="#" class="all_fit" data-bs-toggle="modal" data-bs-target="#staticBackdrop" aria-label="Open Login"></a>
            @endif
          </div>
        </div>
      </div>

      <!-- Hamburger for drawer -->
      <button class="btn btnNav" type="button" aria-label="Open menu">
        <i class="bi bi-list"></i>
      </button>
    </div>
  </div>
</header>

<!-- ===== DESKTOP NAV LINKS (visible ≥992px) ===== -->
<nav class="site-links">
  <div class="container">
    <ul class="desk-nav">
      <li class="{{ request()->is('/') ? 'active' : '' }}"><a href="{{ url('/') }}">Home</a></li>
      <li class="{{ request()->is('about') ? 'active' : '' }}"><a href="{{ url('/about') }}">About us</a></li>
      <li class="{{ request()->is('advertise') ? 'active' : '' }}"><a href="{{ url('/advertise') }}">Advertise</a></li>
      <li class="{{ request()->is('customer-support') ? 'active' : '' }}"><a href="{{ url('/customer-support') }}">Customer Care</a></li>
      <li class="{{ request()->is('contact-detail') ? 'active' : '' }}"><a href="{{ url('/contact-detail') }}">Contact Detail</a></li>
    </ul>
  </div>
</nav>

<!-- Backdrop -->
<div class="nav_backdrop"></div>

<!-- Mobile Drawer -->
<nav class="navigation_sec" aria-label="Mobile navigation">
  <div class="nav_head">
    <a href="{{ url('/') }}" class="navlogo">
      <img src="{{ url('assets/front/img/logo.png') }}" alt="URSBID">
    </a>
    <button type="button" class="btn-close text-reset" aria-label="Close menu"></button>
  </div>

  <ul class="mnav">
    <li class="mnav-item {{ request()->is('/') ? 'active' : '' }}">
      <a class="mnav-link" href="{{ url('/') }}"><i class="bi bi-house"></i> Home</a>
    </li>
    <li class="mnav-item {{ request()->is('about') ? 'active' : '' }}">
      <a class="mnav-link" href="{{ url('/about') }}"><i class="bi bi-info-circle"></i> About us</a>
    </li>
    <li class="mnav-item {{ request()->is('advertise') ? 'active' : '' }}">
      <a class="mnav-link" href="{{ url('/advertise') }}"><i class="bi bi-badge-ad"></i> Advertise</a>
    </li>
    <li class="mnav-item {{ request()->is('customer-support') ? 'active' : '' }}">
      <a class="mnav-link" href="{{ url('/customer-support') }}"><i class="bi bi-headset"></i> Customer Care</a>
    </li>
    <li class="mnav-item {{ request()->is('contact-detail') ? 'active' : '' }}">
      <a class="mnav-link" href="{{ url('/contact-detail') }}"><i class="bi bi-geo-alt"></i> Contact Detail</a>
    </li>
  </ul>
</nav>

<!-- ===== Mobile Search Modal ===== -->
<div class="modal fade search-modal" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="searchModalLabel">Search</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="search-form" action="{{ url('search/') }}" method="GET" role="search">
          @csrf
          <select class="form-select" name="category" aria-label="Select category">
            <option value="">Categories</option>
            @foreach($headerCategories ?? [] as $cat)
              <option value="{{ $cat->id }}" {{ (isset($data['category']) && (int)$data['category'] === (int)$cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
          <input type="text" class="form-control" name="search" value="{{ $data['keyword'] ?? '' }}" placeholder="Search for products, categories or brand" autocomplete="off" />
          <button class="btn btn-search w-100" type="submit">Search <i class="bi bi-search"></i></button>
        </form>
        <div class="suggest">
          Try:
          <span class="badge rounded-pill">Cement</span>
          <span class="badge rounded-pill">Steel bars</span>
          <span class="badge rounded-pill">Tiles</span>
          <span class="badge rounded-pill">Excavation</span>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Single Login Modal --}}
<div class="modal fade login-ui" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="sellerLoginLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content login-card">
      <div class="modal-header border-0 pb-0">
        <h1 class="modal-title fw-bold fs-3" id="sellerLoginLabel">Login</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-3">
        <form id="loginForm" action="{{ url('/seller-login') }}" method="POST" novalidate>
          @csrf
          <div class="mb-3">
            <label class="form-label login-label" for="seller_email">Email Id</label>
            <input id="seller_email" type="email" name="email" class="form-control login-control" autocomplete="email">
            <div id="emailError" class="error-message">Email is required</div>
          </div>
          <div class="mb-2">
            <label class="form-label login-label" for="seller_password">Password</label>
            <div class="password-container">
              <input id="seller_password" type="password" name="password" class="form-control login-control pe-5" autocomplete="current-password">
              <button class="toggle-pass" type="button" aria-label="Show password" data-target="#seller_password">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/></svg>
              </button>
            </div>
            <div id="passwordError" class="error-message">Password is required</div>
          </div>
          <div class="d-flex justify-content-between align-items-center mt-1 mb-4">
            <span class="text-muted small">Forgot your password?
              <a class="link-restore" href="{{ url('/forgot-password') }}">Restore access now</a>
            </span>
          </div>
          <button class="btn btn-login w-100" type="submit">Login</button>
        </form>
      </div>
      <div class="modal-footer border-0 pt-0">
        <p class="w-100 text-center text-muted mb-0">
          Don't have an account?
          <a href="{{ url('seller-register') }}" class="link-restore">Sign Up</a>
        </p>
      </div>
    </div>
  </div>
</div>

<script>
  /* Search modal focus */
  const searchModal = document.getElementById('searchModal');
  searchModal?.addEventListener('shown.bs.modal', () => {
    searchModal.querySelector('input[name="search"]')?.focus();
  });

  /* Login modal focus */
  document.addEventListener('shown.bs.modal', function (e) {
    if (e.target && e.target.id === 'staticBackdrop') {
      e.target.querySelector('#seller_email')?.focus();
    }
  });

  /* Password eye toggle */
  document.addEventListener('click', function(e){
    const btn = e.target.closest('.toggle-pass');
    if(!btn) return;
    const input = document.querySelector(btn.getAttribute('data-target')) || document.querySelector('#seller_password');
    if(!input) return;
    input.type = input.type === 'password' ? 'text' : 'password';
  });

  /* Simple login validation */
  document.getElementById('loginForm')?.addEventListener('submit', function(e){
    let valid = true;
    const email = document.getElementById('seller_email');
    const password = document.getElementById('seller_password');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    emailError.style.display = 'none'; passwordError.style.display = 'none';
    if(email.value.trim() === ''){ emailError.style.display = 'block'; valid = false; }
    if(password.value.trim() === ''){ passwordError.style.display = 'block'; valid = false; }
    if(!valid){ e.preventDefault(); }
  });
  document.getElementById('seller_email')?.addEventListener('input', function() {
    if(this.value.trim() !== '') document.getElementById('emailError').style.display = 'none';
  });
  document.getElementById('seller_password')?.addEventListener('input', function() {
    if(this.value.trim() !== '') document.getElementById('passwordError').style.display = 'none';
  });

  /* Drawer open/close (no jQuery) */
  (function(){
    const drawer = document.querySelector('.navigation_sec');
    const backdrop = document.querySelector('.nav_backdrop');
    const openBtn = document.querySelector('.btnNav');
    const closeBtn = document.querySelector('.navigation_sec .btn-close');

    function openDrawer(){
      drawer.classList.add('show');
      backdrop.classList.add('show');
      document.body.classList.add('modal-open');
    }
    function closeDrawer(){
      drawer.classList.remove('show');
      backdrop.classList.remove('show');
      document.body.classList.remove('modal-open');
    }
    openBtn?.addEventListener('click', openDrawer);
    closeBtn?.addEventListener('click', closeDrawer);
    backdrop?.addEventListener('click', closeDrawer);
    document.querySelectorAll('.mnav-link').forEach(a=>a.addEventListener('click', closeDrawer));
  })();
</script>
