<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet">

    <!-- inject:css-->

    <link rel="stylesheet" href="{{url('assets/css/plugin.min.css')}}">

    <link rel="stylesheet" href="{{url('assets/style.css')}}">

    <!-- endinject -->

    <link rel="icon" type="image/png" sizes="16x16" href="{{url('assets/front/img/logo.png')}}">

</head>

<body>
    <main class="main-content">

        <div class="signUP-admin">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-4 col-lg-5 col-md-5 p-0">
                        <div class="signUP-admin-left signIn-admin-left position-relative">
                            <div class="signUP-overlay">
                               
                               
                            </div><!-- End: .signUP-overlay  -->
                            <div class="signUP-admin-left__content">
                                <div class="text-capitalize mb-md-30 mb-15 d-flex align-items-center justify-content-md-start justify-content-center">
                                    <a class="wh-36 bg-primary text-white radius-md mr-10 content-center" href="#!">a</a>
                                    <span class="text-dark">admin</span>
                                </div>
                                <h1>Admin Login</h1>
                            </div><!-- End: .signUP-admin-left__content  -->
                            <div class="signUP-admin-left__img">
                                <img class="img-fluid svg" src="{{url('assets/img/svg/signupIllustration.svg')}}" alt="img" />
                            </div><!-- End: .signUP-admin-left__img  -->
                        </div><!-- End: .signUP-admin-left  -->
                    </div><!-- End: .col-xl-4  -->
                    <div class="col-xl-8 col-lg-7 col-md-7 col-sm-8">
                        <div class="signUp-admin-right signIn-admin-right  p-md-40 p-10">
                            <div class="signUp-topbar d-flex align-items-center justify-content-md-end justify-content-center mt-md-0 mb-md-0 mt-20 mb-1">
                                <!-- <p class="mb-0">
                                    Don't have an account?
                                    <a href="sign-up.html" class="color-primary">
                                        Sign up
                                    </a>
                                </p> -->
                            </div><!-- End: .signUp-topbar  -->
                            <div class="row justify-content-center">
                                <div class="col-xl-7 col-lg-8 col-md-12">
                                    <div class="edit-profile mt-md-25 mt-0">
                                        <div class="card border-0">
                                            <div class="card-header border-0  pb-md-15 pb-10 pt-md-20 pt-10 ">
                                                <div class="edit-profile__title">
                                                    <h6>Sign up to <span class="color-primary">Admin</span></h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                            <form method="POST" action="{{ route('login') }}">
                                                     @csrf
                                                <div class="edit-profile__body">
                                                    <div class="form-group mb-20">
                                                        <label for="username"> Email Address</label>
                                                        <input type="eamil"  id="username" placeholder="Email" name='email'  class="form-control @error('email') is-invalid @enderror"  value="{{ old('email') }}" required autocomplete="email" autofocus>
                                                    </div>
                                                    @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
                                                    <div class="form-group mb-15">
                                                        <label for="password-field">password</label>
                                                        <div class="position-relative">
                                                            <input id="password-field" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                                                            <div class="fa fa-fw fa-eye-slash text-light fs-16 field-icon toggle-password2"></div>
                                                        </div>
                                                    </div>
                                                    @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
                                                   
                                                    <div class="button-group d-flex pt-1 justify-content-md-start justify-content-center">
                                                        <button class="btn btn-primary btn-default btn-squared mr-15 text-capitalize lh-normal px-50 py-15 signIn-createBtn ">
                                                            sign in
                                                        </button>
                                                    </div>
                                                    <p class="social-connector text-center mb-sm-25 mb-15  mt-sm-30 mt-20"><span></span></p>
                                                    <div class="button-group d-flex align-items-center justify-content-md-start justify-content-center">
                                                        
                                                    </div>
                                                </div>
                                            </form>
                                            </div><!-- End: .card-body -->
                                        </div><!-- End: .card -->
                                    </div><!-- End: .edit-profile -->
                                </div><!-- End: .col-xl-5 -->
                            </div>
                        </div><!-- End: .signUp-admin-right  -->
                    </div><!-- End: .col-xl-8  -->
                </div>
            </div>
        </div><!-- End: .signUP-admin  -->

    </main>
    <div id="overlayer">
        <span class="loader-overlay">
            <div class="atbd-spin-dots spin-lg">
                <span class="spin-dot badge-dot dot-primary"></span>
                <span class="spin-dot badge-dot dot-primary"></span>
                <span class="spin-dot badge-dot dot-primary"></span>
                <span class="spin-dot badge-dot dot-primary"></span>
            </div>
        </span>
    </div>

    <!-- inject:js-->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.querySelector('.toggle-password2');
            const passwordField = document.getElementById('password-field');

            togglePassword.addEventListener('click', function () {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });
    </script>
    <script src="{{url('assets/js/plugins.min.js')}}"></script>

    <script src="{{url('assets/js/script.min.js')}}"></script>

    <!-- endinject-->
</body>

</html>