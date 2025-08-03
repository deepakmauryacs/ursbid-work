<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>URSBID | Register</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style>
    .newclass {
        display: flex;
        justify-content: center;
    }

    .newclass input{
        width: 40px;
        height: 40px;
        font-size: 20px;
        text-align: center;
        margin: 0 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        
    }


    .newclass input[type="text"]:focus
     {
        border-color: #007bff;
    }

    .newclass input[type="text"]::placeholder {
        color: #ccc;
    }

    .submit-btn {
        display: block;
        margin: 20px auto;
        padding: 10px 20px;
        font-size: 16px;
        text-transform: uppercase;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .submit-btn:hover {
        background-color: #0056b3;
    }
</style>
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
                            <h1 class="page-title"> Verify Your Account</h1>
                            <div class="ltn__breadcrumb-list">
                                <ul>
                                    <li><a href="{{url('')}}"><span class="ltn__secondary-color"><i
                                                    class="fas fa-home"></i></span> Home</a></li>
                                    <li> Verify Your Account</li>
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
                            <h1 class="section-title"> Verify Your Account</h1>

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
                        @if ($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
                            <form action="{{ url('/verify-acc') }}" method="POST"
                                class="ltn__form-box contact-form-box">
                                @csrf
                                <h3>Enter OTP to verify your account. OTP has been sent to your email.</h3>
                                <input type="hidden" name="hash_id" value="{{ $hashId }}">
                                <div class="newclass">
                                    <input type="text" id="otp1" name="otp[]" maxlength="1" required
                                        onkeyup="moveToNext(this, 'otp2')" autofocus>
                                    <input type="text" id="otp2" name="otp[]" maxlength="1" required
                                        onkeyup="moveToNext(this, 'otp3')">
                                    <input type="text" id="otp3" name="otp[]" maxlength="1" required
                                        onkeyup="moveToNext(this, 'otp4')">
                                    <input type="text" id="otp4" name="otp[]" maxlength="1" required
                                        onkeyup="moveToNext(this, 'otp5')">
                                    <input type="text" id="otp5" name="otp[]" maxlength="1" required
                                        onkeyup="moveToNext(this, 'otp6')">
                                    <input type="text" id="otp6" name="otp[]" maxlength="1" required>
                                </div>
                                <button type="submit" class="submit-btn">Verify OTP</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="account-create text-center pt-50">
                            <img src="{{url('assets/front/img/inner/login.jpg')}}">
                            <div class="btn-wrapper">
                                <a href="{{url('seller-login')}}" class="theme-btn-1 btn black-btn">Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>





        <script>
        function moveToNext(current, nextFieldID) {
            if (current.value.length >= current.maxLength) {
                document.getElementById(nextFieldID).focus();
            }
        }
        </script>



        @include('frontend.inc.footer')


    </div>
    <!-- Body main wrapper end -->
    @include('frontend.inc.footer-links')


</body>

</html>