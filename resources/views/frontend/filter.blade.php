<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>URSBID | Category</title>
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

        <div>
            <!-- APPOINTMENT AREA START -->
            <div class="ltn__appointment-area pb-60">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <div class="desk-cat">
                                <h2 class="cate"> Categories</h2>
                                <div class="ltn__tab-menu ltn__tab-menu-3 ltn__tab-menu-top-right-- text-uppercase--- ">

                                    <div class="nav">
                                        @foreach ($subcategories as $subcat)
                                        <a class="@php if($sub_slug == $subcat->slug){ echo 'active';} @endphp  show"
                                            data-bs-toggle=""
                                            href="{{ url('/filter/' . $cat_slug . '/' . $subcat->slug) }}">
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
                                        <h2 class="cate"> Categories</h2>
                                    </div>

                                </header>

                                <div class="sidebar-cat" id='sidebar'>
                                    <div class="nav">
                                        @foreach ($subcategories as $subcat)
                                        <a class="@php if($sub_slug == $subcat->slug){ echo 'active';} @endphp  show"
                                            data-bs-toggle=""
                                            href="{{ url('/filter/' . $cat_slug . '/' . $subcat->slug) }}">
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

                                            <a
                                                href="{{url('product/' . $cat_slug . '/'  . $sub_slug . '/'  .$product->slug)}}">
                                                <div class="img">
                                                    <img src="{{ url('public/uploads/'.$product->image) }}">
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

                        </div>
                    </div>
                </div>
            </div>
            <!-- APPOINTMENT AREA END -->

            <div class="ltn__appointment-area pb-60 mbb">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="img ads-img">
                                @php
                                $cat_id = $sid;

                                $subc = DB::select("SELECT * FROM advertisement WHERE cat_id = '$cat_id' AND status= 1 ");

                                foreach ($subc as $product) {

                                @endphp
                                <img src="{{ url('public/uploads/'.$product->image) }}">
                                @php } @endphp

                            </div>
                        </div>
                    </div>
                </div>
            </div>



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