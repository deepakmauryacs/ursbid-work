<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>URSBID | forgot-password</title>
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
                            <h1 class="page-title">Forgot password</h1>
                            <div class="ltn__breadcrumb-list">
                                <ul>
                                    <li><a href="{{url('')}}"><span class="ltn__secondary-color"><i
                                                    class="fas fa-home"></i></span> Home</a></li>
                                    <li>Forgot password</li>
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
                            <h1 class="section-title">Forgot password</h1>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <div class="account-login-inner">
                        @if(Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ Session::get('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            @endif
                            @if(Session::has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ Session::get('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            @endif
                            <form action="{{ url('/forgot-password/') }}" method="POST"
                                class="ltn__form-box contact-form-box">
                                @csrf
                                <div class="block_in">
                                    <label class="label">Email</label>
                                    <input type="email" name="email" placeholder="Email*" value="{{ old('email') }}">
                                </div>

                                @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                        
                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
        @error('g-recaptcha-response') <span style="color:red">{{ $message }}</span><br> @enderror




                        <div class="btn-wrapper">
                            <button class="my_bnty" type="submit">Send Reset Link</button>
                        </div>


                        </form>
                    </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="account-create text-center pt-50">
                        <img src="{{url('assets/front/img/inner/login.jpg')}}">
                        
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