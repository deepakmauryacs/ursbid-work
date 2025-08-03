<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>URSBID | Update-password</title>
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
                            <h1 class="page-title">Update password</h1>
                            <div class="ltn__breadcrumb-list">
                                <ul>
                                    <li><a href="{{url('')}}"><span class="ltn__secondary-color"><i
                                                    class="fas fa-home"></i></span> Home</a></li>
                                    <li>Update password</li>
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
                            <h1 class="section-title">Update password</h1>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="account-login-inner">
                            @if($errors->any())
                            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                            @endif
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
                            <form action="{{ route('reset.submit') }}" method="POST"
                                class="ltn__form-box contact-form-box">
                                @csrf

                                <input type="hidden" name="token" value="{{ $token }}">
                                <input type="hidden" name="email" value="{{ $email }}">
                                <div class="block_in">
                                    <label class="label">New Password</label>
                                    <input type="password" name="password" placeholder="New Password*"
                                        value="{{ old('password') }}">
                                </div>

                                @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                                <div class="block_in">
                                    <label class="label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" placeholder="Confirm Password*"
                                        value="{{ old('password_confirmation') }}">
                                </div>

                                @if ($errors->has('password_confirmation'))
                                <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                @endif

                                <div class="btn-wrapper">
                                    <button class="my_bnty" type="submit">Reset Password</button>
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










        @include('frontend.inc.footer')


    </div>
    <!-- Body main wrapper end -->
    @include('frontend.inc.footer-links')


</body>

</html>