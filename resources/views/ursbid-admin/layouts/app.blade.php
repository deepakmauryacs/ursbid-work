<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Dashboard') || URSBID</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully responsive premium admin dashboard template, Real Estate Management Admin Template" />
    <meta name="author" content="Techzaa" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />


    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('public/assets/images/apple-touch-icon.png?v=1.0.2') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('public/assets/images/favicon-32x32.png?v=1.0.2') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('public/assets/images/favicon-16x16.png?v=1.0.2') }}">
    <link rel="shortcut icon" href="{{ asset('public/assets/images/favicon.ico?v=1.0.2') }}" type="image/x-icon">
    <link rel="manifest" href="{{ asset('public/assets/images/site.webmanifest?v=1.0.2') }}">



    <!-- Vendor css (Require in all Page) -->
    <link href="{{ asset('public/assets/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Icons css (Require in all Page) -->
    <link href="{{ asset('public/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css (Require in all Page) -->
    <link href="{{ asset('public/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Theme Config js (Require in all Page) -->
    <script src="{{ asset('public/assets/js/config.min.js') }}"></script>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Bootsrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    
    @stack('styles')
</head>

<body>
    <!-- START Wrapper -->
    <div class="wrapper">
        @include('ursbid-admin.layouts.partials.topbar')
        @include('ursbid-admin.layouts.partials.sidebar')

        <div class="page-content">
            @yield('content')
        </div>
    </div>
    <!-- END Wrapper -->

    <!-- Vendor Javascript (Require in all Page) -->
    <script src="{{ asset('public/assets/js/vendor.js') }}"></script>

    <!-- App Javascript (Require in all Page) -->
    <script src="{{ asset('public/assets/js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>