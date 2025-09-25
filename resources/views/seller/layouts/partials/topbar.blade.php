@php
    $seller = session('seller');
    $sellerName = $seller->name ?? $seller->company_name ?? $seller->email ?? 'Seller';
@endphp

<header class="">
    <div class="topbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <div class="d-flex align-items-center gap-2">
                    <!-- Menu Toggle Button -->
                    <div class="topbar-item">
                        <button type="button" class="button-toggle-menu topbar-button">
                            <i class="ri-menu-2-line fs-24"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-1">
                    <!-- Theme Color (Light/Dark) -->
                    <div class="topbar-item">
                        <button type="button" class="topbar-button" id="light-dark-mode">
                            <i class="ri-moon-line fs-24 light-mode"></i>
                            <i class="ri-sun-line fs-24 dark-mode"></i>
                        </button>
                    </div>

                    <!-- User -->
                    <div class="dropdown topbar-item">
                        <a type="button" class="topbar-button" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="d-flex align-items-center">
                                <img class="rounded-circle" width="32" src="{{ asset('public/assets/images/users/avatar-1.jpg') }}" alt="seller-avatar">
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <h6 class="dropdown-header">Welcome {{ $sellerName }}!</h6>
                            <div class="dropdown-divider my-1"></div>
                            <a class="dropdown-item text-danger" href="{{ url('seller/logout') }}">
                                <iconify-icon icon="solar:logout-3-broken" class="align-middle me-2 fs-18"></iconify-icon>
                                <span class="align-middle">Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
