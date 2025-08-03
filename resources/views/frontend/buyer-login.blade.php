<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>URSBID | Login</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
                            <h1 class="page-title">Buyer Login</h1>
                            <div class="ltn__breadcrumb-list">
                                <ul>
                                    <li><a href="{{url('')}}"><span class="ltn__secondary-color"><i
                                                    class="fas fa-home"></i></span> Home</a></li>
                                    <li>Buyer Login</li>
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
                            <h1 class="section-title">Buyer Login</h1>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="account-login-inner">
                            <form action="{{ url('/buyer-login') }}" method="POST"
                                class="ltn__form-box contact-form-box">
                                @csrf
                                <div class="block_in">
                                    <label class="label">Email</label>
                                    <input type="email" name="email" placeholder="Email*" value="{{ old('email') }}">
                                </div>


                                <div class="block_in">
                                    <label class="label">Password</label>
                                    <input type="password" name="password" value="{{ old('password') }}"
                                        placeholder="Password*">
                                </div>
                                @if($errors->any())

                                <div class='text-danger'> Please Provide Correct Details.</div>
                                @endif
                                <div class="btn-wrapper">
                                    <button class="my_bnty" type="submit">Login</button>
                                </div>

                                <div class="go-to-btn mt-20">
                                    <a href="#"><small>Forgot your password?</small></a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="account-create text-center pt-50">
                            <img src="{{url('assets/front/img/inner/login.jpg')}}">
                            <div class="btn-wrapper">
                                <a href="{{url('buyer-register')}}" class="theme-btn-1 btn black-btn">CREATE
                                    ACCOUNT</a>
                            </div>
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