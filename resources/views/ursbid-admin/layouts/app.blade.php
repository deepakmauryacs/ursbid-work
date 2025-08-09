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
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('public/assets/images/apple-touch-icon.png') }}?v={{ config('app.asset_version', '1.0.0') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('public/assets/images/favicon-32x32.png') }}?v={{ config('app.asset_version', '1.0.0') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('public/assets/images/favicon-16x16.png') }}?v={{ config('app.asset_version', '1.0.0') }}">
    <link rel="shortcut icon" href="{{ asset('public/assets/images/favicon.ico') }}?v={{ config('app.asset_version', '1.0.0') }}" type="image/x-icon">

    <!-- Vendor css -->
    <link href="{{ asset('public/assets/css/vendor.min.css') }}?v={{ config('app.asset_version', '1.0.0') }}" rel="stylesheet" type="text/css" />

    <!-- Icons css -->
    <link href="{{ asset('public/assets/css/icons.min.css') }}?v={{ config('app.asset_version', '1.0.0') }}" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="{{ asset('public/assets/css/app.min.css') }}?v={{ config('app.asset_version', '1.0.0') }}" rel="stylesheet" type="text/css" />

    <!-- Theme Config js -->
    <script src="{{ asset('public/assets/js/config.min.js') }}?v={{ config('app.asset_version', '1.0.0') }}"></script>
    <script src="{{ asset('public/assets/js/jquery-3.7.1.js') }}?v={{ config('app.asset_version', '1.0.0') }}"></script>
    
    <!-- Bootstrap Icon -->
    <link href="{{ asset('public/assets/css/bootstrap-icons.min.css') }}?v={{ config('app.asset_version', '1.0.0') }}" rel="stylesheet" type="text/css" />
    
    <!-- Toastr CSS -->
    <link href="{{ asset('public/assets/css/toastr.min.css') }}?v={{ config('app.asset_version', '1.0.0') }}" rel="stylesheet" type="text/css" />
    
    <!-- Toastr JS -->
    <script src="{{ asset('public/assets/js/toastr.min.js') }}?v={{ config('app.asset_version', '1.0.0') }}"></script>
  
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

    <!-- Vendor Javascript -->
    <script src="{{ asset('public/assets/js/vendor.js') }}?v={{ config('app.asset_version', '1.0.0') }}"></script>

    <!-- App Javascript -->
    <script src="{{ asset('public/assets/js/app.js') }}?v={{ config('app.asset_version', '1.0.0') }}"></script>

    @stack('scripts')
</body>
</html>
