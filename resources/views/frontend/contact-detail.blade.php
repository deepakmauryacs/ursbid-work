<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>URSBID: A platform for Construction material seller and buyer</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description"
        content="URSBID is a virtual hub for all types of Building materials, hardware, suppliers and contractors. It deals in various materials like cement, concrete, rebar etc.">
    <meta name="keywords"
        content="Building material Supplier, Construction Company, Wholesaler, plateform for cunstruction material" />


    <link rel="canonical" href="https://ursbid.com/" />

    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://ursbid.com/”/">
    <meta property=" og:site_name" content="URSBID" />
    <meta property="og:title" content="URSBID: A platform for Construction material seller and buyer" />
    <meta property="og:description"
        content="URSBID is a virtual hub for all types of Building materials, hardware, suppliers and contractors. It deals in various materials like cement, concrete, rebar etc" />
    <meta property="og:image" content="https://ursbid.com/assets/front/img/logo.png”/">
    <meta property=" og:image:width" content="200" />
    <meta property="og:image:height" content="200" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@URSBID" />
    <meta name="twitter:title" content="URSBID: A platform for Construction material seller and buyer" />
    <meta name="twitter:description"
        content="URSBID is a virtual hub for all types of Building materials, hardware, suppliers and contractors. It deals in various materials like cement, concrete, rebar etc" />

    <meta name="robots" content="index, follow" />

    <meta name="google-site-verification" content="9Pleoer4kggTobWWTw9a8e2ZIppNa5I8ZG5PUIG5zYY" />


    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GKTR2ETT26"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-GKTR2ETT26');
    </script>
    @include('frontend.inc.header-links')

</head>

<body>


    <!-- Body main wrapper start -->
    <div class="body-wrapper">

        @include('frontend.inc.header')

        <div class="ltn__utilize-overlay"></div>

        <div class="ltn__breadcrumb-area text-left">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ltn__breadcrumb-inner">
                            <h1 class="page-title">Contact Detail</h1>
                            <div class="ltn__breadcrumb-list">
                                <ul>
                                    <li><a href="{{url('/')}}"><span class="ltn__secondary-color"><i
                                                    class="fas fa-home"></i></span> Home</a></li>
                                    <li>Contact Detail</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ltn__contact-address-area pd-60">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mb-2">
                        <div class="ltn__contact-address-item ltn__contact-address-item-3 box-shadow">
                            <div class="ltn__contact-address-icon">
                                <img src="{{url('assets/front/img/inner/10.png')}}" alt="Icon Image">
                            </div>
                            <h3 class="animated fadeIn">Email Address</h3>
                            <p>support@ursbid.com</p>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-2">
                        <div class="ltn__contact-address-item ltn__contact-address-item-3 box-shadow">
                            <div class="ltn__contact-address-icon">
                                <img src="{{url('assets/front/img/inner/11.png')}}" alt="Icon Image">
                            </div>
                            <h3 class="animated fadeIn">Phone Number</h3>
                            <p>+91 9984555400</p>
                            <p>+91 9984555300</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ltn__contact-address-item ltn__contact-address-item-3 box-shadow">
                            <div class="ltn__contact-address-icon">
                                <img src="{{url('assets/front/img/inner/12.png')}}" alt="Icon Image">
                            </div>
                            <h3 class="animated fadeIn">Office Address</h3>
                            <p>Village - Parewpur, Post - Dharshawa, District - Shrawasti, Uttar Pradesh, 271835</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="ltn__contact-message-area mb-120 mb--100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ltn__form-box contact-form-box box-shadow white-bg">
                            <h4 class="title-2">Get In Touch</h4>
                            @if(Session::has('success'))
                            <div class="alert alert-success">
                                {{ Session::get('success') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                            @endif

                            @if(Session::has('error'))
                            <div class="alert alert-danger">
                                {{ Session::get('error') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                            @endif
                            <form name="fmn" action="{{ url('/contact_inc') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-item input-item-name ltn__custom-icon">
                                            <input type="text" required name="name" placeholder="Enter your name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-item input-item-email ltn__custom-icon">
                                            <input type="email" required name="email" placeholder="Enter email address">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-item">
                                            <select required name="service" class="nice-select" style="width: 100%;">
                                                <option>Select Service Type</option>
                                                <option value="Seller">Seller </option>
                                                <option value="Buyer">Buyer </option>
                                                <option value="Contractor">Contractor </option>
                                                <option value="Client">Client </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-item input-item-phone ltn__custom-icon">
                                            <input type="text" required name="phone" placeholder="Enter phone number">
                                        </div>
                                    </div>
                                </div>
                                <div class="input-item input-item-textarea ltn__custom-icon">
                                    <textarea required name="message" placeholder="Enter message"></textarea>
                                </div>
                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                                @error('g-recaptcha-response') <span style="color:red">{{ $message }}</span><br> @enderror

                                <div class="btn-wrapper mt-0">
                                    <button class="btn theme-btn-1 btn-effect-1 text-uppercase" type="submit">Send
                                        Message</button>
                                </div>


                                <p class="form-messege mb-0 mt-20"></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>









        @include('frontend.inc.footer')


    </div>
    <!-- Body main wrapper end -->
    @include('frontend.inc.footer-links')


</body>

</html>