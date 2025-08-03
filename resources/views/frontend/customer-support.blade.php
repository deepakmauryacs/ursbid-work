<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>URSBID | Customer Support</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
                            <h1 class="page-title">Customer Support</h1>
                            <div class="ltn__breadcrumb-list">
                                <ul>
                                    <li><a href="index.php"><span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Home</a></li>
                                    <li>Customer Support</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="ltn__login-area pb-65">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title-area text-center">
                            <h1 class="section-title">Customer Support</h1>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                    @if(Session::has('success'))
                            <div class="alert alert-success">
                                {{ Session::get('success') }}
                            </div>
                            

                            @endif

                            @if(Session::has('error'))
                            <div class="alert alert-danger">
                                {{ Session::get('error') }}
                            </div>
                            

                            @endif
                        <div class="account-login-inner">
                            <form  class="ltn__form-box contact-form-box" action="{{ url('/support_inc') }}" method="post">
                                @csrf
                                <div class="block_in">

                                    <input type="text" name="name" required  placeholder="Name*">
                                </div>

                                <div class="block_in">

                                    <input type="email" name='email'  required placeholder="Email*">
                                </div>
                                <div class="block_in">

                                    <input type="text" name="phone" required placeholder="Phone*">
                                </div>

                                <div class="block_in">

                                    <textarea name="message" required placeholder="Write Here"></textarea>
                                </div>
                                <div class="block_in">

                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                                @error('g-recaptcha-response') <span style="color:red">{{ $message }}</span><br> @enderror

                                </div>

                                <div class="btn-wrapper">
                                    <button class="my_bnty" type="submit">Submit</button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="account-create text-center pt-50">
                            <img src="{{url('assets/front/img/inner/customer.jpg')}}">

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