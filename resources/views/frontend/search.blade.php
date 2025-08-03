<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>URSBID |  Search</title>
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
                        
                        <div class="col-lg-12">
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
                                            <a href="{{url('productdetailsearch/' .$product->product_slug)}}">
                                                <div class="img">
                                                    <img src="{{ url('public/uploads/'.$product->product_image) }}">
                                                </div>
                                                <div class="hd_name">
                                                    <h3>{{ $product->product_title }}</h3>
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



        </div>

        <style>

        </style>
        @include('frontend.inc.footer')


    </div>


    <!-- Body main wrapper end -->
    @include('frontend.inc.footer-links')


</body>

</html>