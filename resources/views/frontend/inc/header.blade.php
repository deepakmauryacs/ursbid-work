<style>
    .selectchange {
        width: 202px;
    }
    @media (max-width: 576px) {
        .selectchange {
            width: 100%;
        }
    }
</style>

<header class="star_head">
    <div class="container">
        <div class="head">
            <div class="name_logo">
                <div class="logo">
                    <a href="{{ url('') }}">
                        <img src="{{ url('assets/front/img/logo.png') }}" alt="URSBID">
                    </a>
                </div>

                <!-- Mobile search toggle -->
                <button class="mb-sh" type="button" aria-label="Toggle search">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M10.5 18C14.6421 18 18 14.6421 18 10.5C18 6.35786 14.6421 3 10.5 3C6.35786 3 3 6.35786 3 10.5C3 14.6421 6.35786 18 10.5 18Z" stroke="#717478" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M16 16L21 21" stroke="#717478" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>

                <!-- Search form -->
                <div class="_3NorZ0 hidden">
                    <form class="_2rslOn" action="{{ url('search/') }}" method="GET" role="search">
                        @csrf
                        <div class="_1sFryS">
                            <div class="_2SmNnR">
                                <input class="Pke_EE" type="text" name="search"
                                    title="Search for Products"
                                    value="{{ $data['keyword'] ?? '' }}"
                                    autocomplete="off"
                                    placeholder="Search for Products">
                            </div>

                            <select name="category" class="selectchange" aria-label="Select Category">
                                <option value="">Select Category</option>
                                @foreach($headerCategories ?? [] as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ (isset($data['category']) && (int)$data['category'] === (int)$cat->id) ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>

                            <button class="_2iLD__" type="submit" aria-label="Search">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <title>Search Icon</title>
                                    <path d="M10.5 18C14.6421 18 18 14.6421 18 10.5C18 6.35786 14.6421 3 10.5 3C6.35786 3 3 6.35786 3 10.5C3 14.6421 6.35786 18 10.5 18Z" stroke="#717478" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M16 16L21 21" stroke="#717478" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="_2msBFL">
                <div class="all_drop">
                    @php
                        $seller = session('seller');
                        $email = $seller?->email;
                    @endphp

                    <div class="btn_icos">
                        <div class="icon">
                            <img src="{{ url('assets/front/img/svg/profile-52e0dc.svg') }}" alt="Profile Icon">
                        </div>

                        @if ($email)
                            <span>Dashboard</span>
                            <a href="{{ url('seller-dashboard') }}" class="all_fit" aria-label="Go to Dashboard"></a>
                        @else
                            <span>Login</span>
                            <a href="#" class="all_fit" data-bs-toggle="modal" data-bs-target="#staticBackdrop" aria-label="Open Login"></a>
                        @endif
                    </div>

                    @unless ($email)
                        <div class="login_drops">
                            <ul>
                                <li>
                                    <a href="#!" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                        <img src="{{ url('assets/front/img/svg/profile-52e0dc.svg') }}" alt="">
                                        Seller/ Contractor Login
                                    </a>
                                </li>
                                <li>
                                    <a href="#!" data-bs-toggle="modal" data-bs-target="#staticBackdrop1">
                                        <img src="{{ url('assets/front/img/svg/profile-52e0dc.svg') }}" alt="">
                                        Buyer/ Client Login
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endunless
                </div>

                <div class="dots_login">
                    <div class="icon">
                        <img src="{{ url('assets/front/img/dots.png') }}" alt="More">
                    </div>
                    <div class="contact_drop">
                        <ul>
                            <li><a href="{{ url('customer-support') }}"><img src="{{ url('assets/front/img/svg/customer.svg') }}" alt=""> Customer Care</a></li>
                            <li><a href="{{ url('advertise') }}"><img src="{{ url('assets/front/img/svg/advertising.svg') }}" alt=""> Advertise</a></li>
                            <li><a href="{{ url('contact-detail') }}"><img src="{{ url('assets/front/img/svg/contact.svg') }}" alt=""> Contact Details</a></li>
                            <li><a href="{{ url('about') }}"><img src="{{ url('assets/front/img/svg/contact.svg') }}" alt=""> About us</a></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>

{{-- Seller Login Modal --}}
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="sellerLoginLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="sellerLoginLabel">Login Here</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if (session('alert'))
                    <script>alert(@json(session('alert')));</script>
                @endif
                <form action="{{ url('/seller-login') }}" method="POST">
                    @csrf
                    <div class="block_in">
                        <label class="label" for="seller_email">Email</label>
                        <input id="seller_email" type="email" name="email" autocomplete="email" placeholder="Email*" value="{{ old('email') }}">
                    </div>
                    <div class="block_in">
                        <label class="label" for="seller_password">Password</label>
                        <input id="seller_password" type="password" name="password" autocomplete="current-password" placeholder="Password*" value="{{ old('password') }}">
                    </div>
                    @if($errors->any())
                        <div class='text-danger'>Please provide correct details.</div>
                    @endif
                    <div class="go-to-btn mt-20">
                        <a href="{{ url('/forgot-password') }}"><small>Forgot your password?</small></a>
                    </div>
                    <div class="btn-wrapper">
                        <button class="my_bnty" type="submit">Login</button>
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

{{-- Buyer Login Modal --}}
<div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="buyerLoginLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="buyerLoginLabel">Login Here</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('/buyer-login') }}" method="POST">
                    @csrf
                    <div class="block_in">
                        <label class="label" for="buyer_email">Email</label>
                        <input id="buyer_email" type="email" name="email" autocomplete="email" placeholder="Email*" value="{{ old('email') }}">
                    </div>
                    <div class="block_in">
                        <label class="label" for="buyer_password">Password</label>
                        <input id="buyer_password" type="password" name="password" autocomplete="current-password" placeholder="Password*" value="{{ old('password') }}">
                    </div>
                    @if($errors->any())
                        <div class='text-danger'>Please provide correct details.</div>
                    @endif
                    <div class="btn-wrapper">
                        <button class="my_bnty" type="submit">Login</button>
                    </div>
                    <div class="go-to-btn mt-20">
                        <a href="#"><small>Forgot your password?</small></a>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                Don't have an account yet?
                <a href="{{ url('buyer-register') }}">Sign Up</a>
            </div>
        </div>
    </div>
</div>

<script>
    let searchContainer = document.querySelector("._3NorZ0");
    let mobileBtn = document.querySelector(".mb-sh");

    mobileBtn.addEventListener("click", function() {
        searchContainer.classList.toggle("hidden");
    });
</script>
