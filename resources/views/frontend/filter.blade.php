@extends('frontend.layouts.app')

{{-- ===== SEO computed in blade and pushed to <head> ===== --}}
@php
    // Get current subcategory from the list you already have
    $currentSub = collect($subcategories)->firstWhere('slug', $sub_slug);

    $siteName = config('app.name', 'URSBID');

    $seo = [
        'title' => !empty($currentSub->meta_title)
                    ? $currentSub->meta_title
                    : (($currentSub->name ?? 'Category') . ' | ' . $siteName),

        'description' => !empty($currentSub->meta_description)
                    ? $currentSub->meta_description
                    : \Illuminate\Support\Str::limit(
                        trim(strip_tags($currentSub->description ?? '')),
                        160,
                        ''
                      ),

        'keywords' => !empty($currentSub->meta_keywords)
                    ? $currentSub->meta_keywords
                    : implode(',', array_filter([
                          $currentSub->name ?? null,
                          'construction materials',
                          isset($currentSub->name) ? "buy {$currentSub->name}" : null,
                          isset($currentSub->name) ? "best {$currentSub->name} suppliers" : null,
                      ])),

        'image' => !empty($currentSub->image) ? asset('public/'.ltrim($currentSub->image, '/')) : null,
        'canonical' => route('filter', ['category' => $cat_slug, 'subcategory' => $sub_slug]),
    ];
@endphp

{{-- Set page <title> --}}
@section('title', $seo['title'])

@push('head')
<link rel="canonical" href="{{ $seo['canonical'] }}" />
<meta name="description" content="{{ $seo['description'] }}">
<meta name="keywords" content="{{ $seo['keywords'] }}">

{{-- Open Graph --}}
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ $seo['canonical'] }}">
<meta property="og:site_name" content="{{ $webSettings->site_name ?? 'URSBID' }}" />
<meta property="og:title" content="{{ $seo['title'] }}" />
<meta property="og:description" content="{{ $seo['description'] }}" />
@php
    $ogImg = $seo['image']
             ?? (isset($webSettings->site_logo_1)
                    ? asset('public/uploads/'.$webSettings->site_logo_1)
                    : asset('public/uploads/default-logo.png'));
@endphp
<meta property="og:image" content="{{ $ogImg }}" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />

{{-- Twitter --}}
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $seo['title'] }}" />
<meta name="twitter:description" content="{{ $seo['description'] }}" />
<meta name="twitter:image" content="{{ $ogImg }}" />
@endpush
{{-- ===== /SEO ===== --}}

@section('content')
<div class="ltn__utilize-overlay"></div>
<div>
    <!-- APPOINTMENT AREA START -->
    <div class="ltn__appointment-area pb-60">
        <div class="container">
            <div class="row">
                <!-- Left: Subcategory list -->
                <div class="col-lg-4 col-md-4">
                    <div class="desk-cat">
                        <h2 class="cate">Categories</h2>
                        <div class="ltn__tab-menu ltn__tab-menu-3 ltn__tab-menu-top-right-- text-uppercase--- ">
                            <div class="nav">
                                @foreach ($subcategories as $subcat)
                                    <a class="{{ $sub_slug == $subcat->slug ? 'active' : '' }} show"
                                       href="{{ route('filter', ['category' => $cat_slug, 'subcategory' => $subcat->slug]) }}">
                                        {{ $subcat->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="mob-cat">
                        <header class="header-cat">
                            <div class="header_in">
                                <button type="button" class="toggle" id="toggle">
                                    <span></span>
                                </button>
                                <h2 class="cate">Categories</h2>
                            </div>
                        </header>
                        <div class="sidebar-cat" id="sidebar">
                            <div class="nav">
                                @foreach ($subcategories as $subcat)
                                    <a class="{{ $sub_slug == $subcat->slug ? 'active' : '' }} show"
                                       href="{{ route('filter', ['category' => $cat_slug, 'subcategory' => $subcat->slug]) }}">
                                        {{ $subcat->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Right: Products -->
                <div class="col-lg-8 col-md-8">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="liton_tab_3_1">
                            <div class="sub_dp">
                                @if (isset($message))
                                    <div class="alert alert-danger w-100" role="alert">
                                        {{ $message }}
                                    </div>
                                @else
                                    @foreach ($products as $product)
                                        @php
                                            // Safe product image
                                            $imgSrc = 'assets/front/img/placeholder-300x200.png';
                                            if (!empty($product->image)) {
                                                $imgSrc = 'public/' . ltrim($product->image, '/');
                                            }
                                            // Product detail URL via named route
                                            $pUrl = route('product.show', [
                                                'category'   => $cat_slug,
                                                'subcategory'=> $sub_slug,
                                                'product'    => $product->slug,
                                            ]);
                                        @endphp
                                        <div class="item">
                                            <a href="{{ $pUrl }}">
                                                <div class="img">
                                                    <img src="{{ asset($imgSrc) }}" alt="{{ $product->title }}">
                                                </div>
                                                <div class="hd_name">
                                                    <h3>{{ $product->title }}</h3>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                         <div class="col-md-12">
                             <div style="margin-top: 20px;">{!! $sub_category_description !!}</div>
                         </div>
                    </div>

                </div>
                <!-- /Right -->
            </div>
        </div>
    </div>
    <!-- APPOINTMENT AREA END -->

    <!-- Ads -->
    <div class="ltn__appointment-area pb-60 mbb">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="img ads-img">
                        @php
                            $cat_id = $sid;
                            $ads = DB::select("SELECT * FROM advertisement WHERE cat_id = ? AND status = 1", [$cat_id]);
                        @endphp
                        @foreach ($ads as $ad)
                            @php
                                $adImg = 'assets/front/img/placeholder-300x200.png';
                                if (!empty($ad->image)) {
                                    $candidate = 'uploads/' . ltrim($ad->image, '/');
                                    if (\Illuminate\Support\Facades\File::exists(public_path($candidate))) {
                                        $adImg = $candidate;
                                    }
                                }
                            @endphp
                            <img src="{{ asset($adImg) }}" alt="Advertisement">
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 

@push('scripts')
<script>
    // Mobile category toggle
    (function () {
        var btn = document.getElementById('toggle');
        if (!btn) return;
        var open = false;
        btn.addEventListener('click', function () {
            open = !open;
            var side = document.getElementById('sidebar');
            if (!side) return;
            side.classList.toggle('sidebarshow', open);
        });
    })();
</script>
@endpush
