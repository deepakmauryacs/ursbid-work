<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    {{-- ===== Title (priority: $seo.title -> @section('title') -> site_name -> default) ===== --}}
    <title>
        @if(!empty($seo['title']))
            {{ $seo['title'] }}
        @elseif(View::hasSection('title'))
            @yield('title')
        @elseif(!empty($webSettings->site_name))
            {{ $webSettings->site_name }}
        @else
            URSBID: A platform for Construction material seller and buyer
        @endif
    </title>

    {{-- ===== Canonical ===== --}}
    <link rel="canonical" href="{{ $seo['canonical'] ?? url('/') }}" />

    {{-- ===== Basic SEO (fallbacks if page didn't push its own) ===== --}}
    <meta name="description" content="{{
        $seo['description']
        ?? ($webSettings->site_description ?? 'URSBID is a virtual hub for all types of Building materials...')
    }}">
    <meta name="keywords" content="{{
        $seo['keywords']
        ?? ($webSettings->site_keywords ?? 'Building material Supplier, Construction Company, Wholesaler...')
    }}" />

    {{-- ===== Open Graph ===== --}}
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ $seo['canonical'] ?? url('/') }}">
    <meta property="og:site_name" content="{{ $webSettings->site_name ?? 'URSBID' }}" />
    <meta property="og:title" content="{{ $seo['title'] ?? ($webSettings->site_name ?? 'URSBID') }}" />
    <meta property="og:description" content="{{ $seo['description'] ?? ($webSettings->site_description ?? '') }}" />
    @php
        $ogImg = $seo['image']
                 ?? (isset($webSettings->site_logo_1)
                        ? asset('public/uploads/'.$webSettings->site_logo_1)
                        : asset('public/uploads/default-logo.png'));
    @endphp
    <meta property="og:image" content="{{ $ogImg }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />

    {{-- ===== Twitter ===== --}}
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@URSBID" />
    <meta name="twitter:title" content="{{ $seo['title'] ?? ($webSettings->site_name ?? 'URSBID') }}" />
    <meta name="twitter:description" content="{{ $seo['description'] ?? ($webSettings->site_description ?? '') }}" />
    <meta name="twitter:image" content="{{ $ogImg }}" />

    <meta name="robots" content="index, follow" />
    <meta name="google-site-verification" content="9Pleoer4kggTobWWTw9a8e2ZIppNa5I8ZG5PUIG5zYY" />

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GKTR2ETT26"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){ dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-GKTR2ETT26');
    </script>

    @include('frontend.inc.header-links')

    {{-- ===== Allow pages to push extra <head> tags (e.g., filter.blade.php SEO) ===== --}}
    @stack('head')

    {{-- Page / component styles --}}
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
