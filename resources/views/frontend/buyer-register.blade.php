<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>URSBID | Register</title>
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
                            <h1 class="page-title">Buyer</h1>
                            <div class="ltn__breadcrumb-list">
                                <ul>
                                    <li><a href="{{url('')}}"><span class="ltn__secondary-color"><i
                                                    class="fas fa-home"></i></span> Home</a></li>
                                    <li>Buyer Register</li>
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
                            <h1 class="section-title">Register Here</h1>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 regis">
                        @if(Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        @if($errors->any())
                        @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                        @endif
                        <div class="account-login-inner">
                            <form action="{{ url('/buyer-register') }}" method="POST"
                                class="ltn__form-box contact-form-box">
                                @csrf
                                <div class="block_in">
                                    <label class="label">Name</label>
                                    <input type="text" placeholder="Name*" name="name" value="{{ old('name') }}">
                                    @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                                <div class="block_in">
                                    <label class="label">Email</label>
                                    <input type="email" name="email" placeholder="Email*" value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="block_in">
                                    <label class="label">Phone</label>
                                    <input type="text" name="phone" placeholder="Phone*" value="{{ old('phone') }}">
                                    @if ($errors->has('phone'))
                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-7 radio mrb_t">


                                    <label>
                                        <input type="radio" name="gender" checked value="Male"><span
                                            class="outside"><span class="inside"></span></span>Male
                                    </label>

                                    <label>
                                        <input type="radio" name="gender" value="Female"><span class="outside"><span
                                                class="inside"></span></span>Female
                                    </label>


                                </div>
                                <div class="block_in">
                                    <label class="label">Password</label>
                                    <input type="text" name="password" placeholder="Password*" value="{{ old('password') }}">
                                    @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>

                                <div class="btn-wrapper">
                                    <button class="my_bnty" type="submit">Submit</button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="account-create text-center pt-50">
                            <img src="{{url('assets/front/img/inner/login.jpg')}}">
                            <div class="btn-wrapper">
                                <a href="#!" data-bs-toggle="modal" data-bs-target="#staticBackdrop1" class="theme-btn-1 btn black-btn">Login</a>
                                <!-- <a href="{{url('buyer-login')}}" class="theme-btn-1 btn black-btn">Login</a> -->
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