<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>
        @if(!empty($webSettings->site_name))
            {{ $webSettings->site_name }}
        @elseif(View::hasSection('title'))
            @yield('title')
        @else
            URSBID: A platform for Construction material seller and buyer
        @endif
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ $webSettings->site_description ?? 'URSBID is a virtual hub for all types of Building materials...' }}">
    <meta name="keywords" content="{{ $webSettings->site_keywords ?? 'Building material Supplier, Construction Company, Wholesaler...' }}" />
    <link rel="canonical" href="{{ url('/') }}" />

    <!-- Open Graph -->
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:site_name" content="{{ $webSettings->site_name ?? 'URSBID' }}" />
    <meta property="og:title" content="{{ $webSettings->site_name ?? 'URSBID' }}" />
    <meta property="og:description" content="{{ $webSettings->site_description ?? '' }}" />
    <meta property="og:image" content="{{ asset('public/uploads/' . ($webSettings->site_logo_1 ?? 'default-logo.png')) }}" />
    <meta property="og:image:width" content="200" />
    <meta property="og:image:height" content="200" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@URSBID" />
    <meta name="twitter:title" content="{{ $webSettings->site_name ?? 'URSBID' }}" />
    <meta name="twitter:description" content="{{ $webSettings->site_description ?? '' }}" />

    <meta name="robots" content="index, follow" />
    <meta name="google-site-verification" content="9Pleoer4kggTobWWTw9a8e2ZIppNa5I8ZG5PUIG5zYY" />

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GKTR2ETT26"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-GKTR2ETT26');
    </script>

    @include('frontend.inc.header-links')
    @stack('styles')
</head>

<body>
    <div class="body-wrapper">
        @include('frontend.inc.header')

        <div class="ltn__utilize-overlay"></div>

        {{-- Main content --}}
        @yield('content')

        @include('frontend.inc.footer')
    </div>

    @include('frontend.inc.footer-links')
    @stack('scripts')
</body>
</html>
