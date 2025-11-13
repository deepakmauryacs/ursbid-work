<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>URSBID | Product</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    @include('frontend.inc.header-links')

    <style>
        div#sidebar .nav {
            height: auto !important;
        }
    </style>

</head>

<body>
    <!-- Body main wrapper start -->
    <div class="body-wrapper">

        @include('frontend.inc.header')

        <div class="ltn__utilize-overlay"></div>

        <div>
            <!-- APPOINTMENT AREA START -->
            <div class="ltn__appointment-area pb-60">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <div class="desk-cat">
                                <h2 class="cate"> Products</h2>
                                <div class="ltn__tab-menu ltn__tab-menu-3 ltn__tab-menu-top-right-- text-uppercase--- ">
                                    <div class="nav">
                                        @foreach ($supcategories as $subcat)
                                            <a class="@php if($sup_slug == $subcat->slug){ echo 'active';} @endphp  show" data-bs-toggle="" href="{{ url('/product/' . $cat_slug . '/'. $sub_slug . '/' . $subcat->slug) }}">
                                                {{ $subcat->title }}
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
                                        <h2 class="cate"> Products</h2>
                                    </div>

                                </header>

                                <div class="sidebar-cat" id='sidebar'>
                                    <div class="nav">
                                        @foreach ($supcategories as $subcat)
                                            <a class="@php if($sup_slug == $subcat->slug){ echo 'active';} @endphp  show" data-bs-toggle="" href="{{ url('/product/' . $cat_slug . '/'. $sub_slug . '/' . $subcat->slug) }}">
                                                {{ $subcat->title }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                                <div class="item">
                                                    @if (request()->session()->get('seller')) 
                                                        <a href="{{ url('product-detail/' .$product->slug) }}">
                                                            <div class="img">
                                                                <img src="{{ url('public/'.$product->image) }}">
                                                            </div>
                                                            <div class="hd_name">
                                                                <h3>{{ $product->brand_name }}</h3>
                                                            </div>
                                                        </a>
                                                    @else
                                                        <a href="javascript:void(0);" class="quotationLogin" data-redirect_url="{{ url('product-detail/' .$product->slug) }}">
                                                            <div class="img">
                                                                <img src="{{ url('public/'.$product->image) }}">
                                                            </div>
                                                            <div class="hd_name">
                                                                <h3>{{ $product->brand_name }}</h3>
                                                            </div>
                                                        </a>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- APPOINTMENT AREA END -->
        </div>

        <style>

        </style>
        @include('frontend.inc.footer')

    </div>

    <!-- Body main wrapper end -->
    @include('frontend.inc.footer-links')
    

    <script>
        var btn = document.querySelector('.toggle');
        var btnst = true;
        btn.onclick = function() {
            if (btnst == true) {
                document.querySelector('.toggle span').classList.add('toggle');
                document.getElementById('sidebar').classList.add('sidebarshow');
                btnst = false;
            } else if (btnst == false) {
                document.querySelector('.toggle span').classList.remove('toggle');
                document.getElementById('sidebar').classList.remove('sidebarshow');
                btnst = true;
            }
        }
    </script>

    
</body>

</html>