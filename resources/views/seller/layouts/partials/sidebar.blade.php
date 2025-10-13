@php
    $seller = session('seller');
    $accTypeValues = [];
    $sellerHashId = null;

    if ($seller) {
        $uid = $seller->id ?? null;
        if ($uid) {
            $account = DB::table('seller')->where('id', $uid)->first();
            if ($account && $account->acc_type) {
                $accTypeValues = array_filter(explode(',', $account->acc_type));
            }
            $sellerHashId = $account->hash_id ?? null;
        } elseif (!empty($seller->acc_type)) {
            $accTypeValues = array_filter(explode(',', $seller->acc_type));
        }
        $sellerHashId = $sellerHashId ?? ($seller->hash_id ?? null);
    }

    $accTypeValues = array_map('intval', $accTypeValues);
    $currentShareView = request()->query('view');
@endphp

<div class="main-nav">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="{{ url('seller-dashboard') }}" class="logo-dark">
            <img src="{{ asset('public/assets/images/logo-sm.png?v=1.0.3') }}" class="logo-sm" alt="logo sm">
            <img src="{{ asset('public/assets/images/logo-dark.png?v=1.0.3') }}" class="logo-lg" alt="logo dark">
        </a>

        <a href="{{ url('seller-dashboard') }}" class="logo-light">
            <img src="{{ asset('public/assets/images/logo-sm.png?v=1.0.3') }}" class="logo-sm" alt="logo sm">
            <img src="{{ asset('public/assets/images/logo-light.png?v=1.0.3') }}" class="logo-lg" alt="logo light">
        </a>
    </div>

    <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
        <i class="ri-menu-2-line fs-24 button-sm-hover-icon"></i>
    </button>

    <div class="scrollbar" data-simplebar>
        <ul class="navbar-nav" id="navbar-nav">
            <li class="menu-title">Menu</li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('seller-dashboard') ? 'active' : '' }}" href="{{ url('seller-dashboard') }}">
                    <span class="nav-icon">
                        <i class="ri-dashboard-2-line"></i>
                    </span>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow {{ request()->is('seller/enquiry*', 'seller/active-enquiry*', 'seller/closed-enquiry*', 'seller/my-enquiry*') ? 'active' : '' }}" href="#sidebarEnquiry" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('seller/enquiry*', 'seller/active-enquiry*', 'seller/closed-enquiry*', 'seller/my-enquiry*') ? 'true' : 'false' }}" aria-controls="sidebarEnquiry">
                    <span class="nav-icon">
                        <i class="ri-question-answer-line"></i>
                    </span>
                    <span class="nav-text">Enquiries</span>
                </a>
                <div class="collapse {{ request()->is('seller/enquiry*', 'seller/active-enquiry*', 'seller/closed-enquiry*', 'seller/my-enquiry*') ? 'show' : '' }}" id="sidebarEnquiry">
                    <ul class="nav sub-navbar-nav">
                        @if(in_array(1, $accTypeValues, true) || in_array(2, $accTypeValues, true))
                            <li class="sub-nav-item">
                                <a class="sub-nav-link {{ request()->is('seller/active-enquiry/list') ? 'active' : '' }}" href="{{ url('seller/active-enquiry/list') }}">Active Enquiry List</a>
                            </li>
                            <li class="sub-nav-item">
                                <a class="sub-nav-link {{ request()->is('seller/closed-enquiry/list') ? 'active' : '' }}" href="{{ url('seller/closed-enquiry/list') }}">Closed Enquiry List</a>
                            </li>
                        @endif

                        @if(in_array(3, $accTypeValues, true) || in_array(4, $accTypeValues, true))
                            <li class="sub-nav-item">
                                <a class="sub-nav-link {{ request()->is('seller/my-enquiry/list') ? 'active' : '' }}" href="{{ url('seller/my-enquiry/list') }}">My Enquiry List</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow {{ request()->is('buyer/bidding-received*', 'buyer/my-bidding*', 'buyer-order/acc-list') ? 'active' : '' }}" href="#sidebarBidding" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('buyer/bidding-received*', 'buyer/my-bidding*', 'buyer-order/acc-list') ? 'true' : 'false' }}" aria-controls="sidebarBidding">
                    <span class="nav-icon">
                        <i class="ri-auction-line"></i>
                    </span>
                    <span class="nav-text">Bidding</span>
                </a>
                <div class="collapse {{ request()->is('buyer/bidding-received*', 'buyer/my-bidding*', 'buyer-order/acc-list') ? 'show' : '' }}" id="sidebarBidding">
                    <ul class="nav sub-navbar-nav">
                        @if(in_array(3, $accTypeValues, true) || in_array(4, $accTypeValues, true))
                            <li class="sub-nav-item">
                                <a class="sub-nav-link {{ request()->is('buyer/bidding-received/list') ? 'active' : '' }}" href="{{ url('buyer/bidding-received/list') }}">Bidding Received</a>
                            </li>
                        @endif

                        @if(in_array(1, $accTypeValues, true) || in_array(2, $accTypeValues, true))
                            <li class="sub-nav-item">
                                <a class="sub-nav-link {{ request()->is('buyer/my-bidding/list') ? 'active' : '' }}" href="{{ route('buyer-order.mylist') }}">My Bidding</a>
                            </li>
                            <li class="sub-nav-item">
                                <a class="sub-nav-link {{ request()->is('buyer-order/acc-list') ? 'active' : '' }}" href="{{ url('buyer-order/acc-list') }}">Accepted Bidding</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow {{ request()->is('seller/accounting*') && !request()->is('seller/accounting/totalshare') ? 'active' : '' }}" href="#sidebarAccounting" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->is('seller/accounting*') && !request()->is('seller/accounting/totalshare') ? 'true' : 'false' }}" aria-controls="sidebarAccounting">
                    <span class="nav-icon">
                        <i class="ri-bill-line"></i>
                    </span>
                    <span class="nav-text">Accounting</span>
                </a>
                <div class="collapse {{ request()->is('seller/accounting*') && !request()->is('seller/accounting/totalshare') ? 'show' : '' }}" id="sidebarAccounting">
                    <ul class="nav sub-navbar-nav">
                        @if(in_array(3, $accTypeValues, true) || in_array(4, $accTypeValues, true))
                            <li class="sub-nav-item">
                                <a class="sub-nav-link {{ request()->is('seller/accounting/biddrecive') ? 'active' : '' }}" href="{{ url('seller/accounting/biddrecive') }}">Bidding Received</a>
                            </li>
                        @endif

                        @if(in_array(1, $accTypeValues, true) || in_array(2, $accTypeValues, true))
                            <li class="sub-nav-item">
                                <a class="sub-nav-link {{ request()->is('seller/accounting/list') ? 'active' : '' }}" href="{{ url('seller/accounting/list') }}">Total Bidding</a>
                            </li>
                            <li class="sub-nav-item">
                                <a class="sub-nav-link {{ request()->is('seller/accounting/accbid') ? 'active' : '' }}" href="{{ url('seller/accounting/accbid') }}">Accepted Bidding</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('seller/accounting/totalshare') && $currentShareView !== 'users' ? 'active' : '' }}" href="{{ url('seller/accounting/totalshare') }}">
                    <span class="nav-icon">
                        <i class="ri-share-forward-line"></i>
                    </span>
                    <span class="nav-text">Share Profile</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('seller/accounting/totalshare') && $currentShareView === 'users' ? 'active' : '' }}" href="{{ url('seller/accounting/totalshare') }}?view=users">
                    <span class="nav-icon">
                        <i class="ri-message-3-line"></i>
                    </span>
                    <span class="nav-text">Users List</span>
                </a>
            </li>

            <li class="nav-item">
                @php
                    $settingsActive = request()->is('update-account*', 'seller/change-password', 'delete-account');
                @endphp
                <a class="nav-link menu-arrow {{ $settingsActive ? 'active' : '' }}" href="#sidebarSettings" data-bs-toggle="collapse" role="button" aria-expanded="{{ $settingsActive ? 'true' : 'false' }}" aria-controls="sidebarSettings">
                    <span class="nav-icon">
                        <i class="ri-settings-3-line"></i>
                    </span>
                    <span class="nav-text">Settings</span>
                </a>
                <div class="collapse {{ $settingsActive ? 'show' : '' }}" id="sidebarSettings">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link {{ request()->is('update-account*') ? 'active' : '' }}" href="{{ $sellerHashId ? url('update-account/' . $sellerHashId) : 'javascript:void(0);' }}">Profile</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link {{ request()->is('seller/change-password') ? 'active' : '' }}" href="{{ url('seller/change-password') }}">Change Password</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link {{ request()->is('delete-account') ? 'active' : '' }}" href="{{ url('delete-account') }}">Delete Account</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
