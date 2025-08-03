@extends('frontend.layouts.app')

@section('title', 'URSBID: A platform for Construction material seller and buyer')

@section('content')
    <section class="twopart">
        <div class="container">
            <div class="d_twopart">
                @php
                    $category = DB::select("SELECT * FROM category WHERE status = 1 ");
                @endphp

                @foreach ($category as $cat)
                    <div class="points_cont">
                        <div class="padd">
                            <div class="heading">
                                <h3>{{ $cat->title }}</h3>
                            </div>
                            <div class="pro">
                                @php
                                    $subc = DB::select("SELECT * FROM sub WHERE cat_id = ? AND status = 1 ORDER BY order_by", [$cat->id]);
                                @endphp

                                @foreach ($subc as $sub)
                                    <div class="item">
                                        <a href="{{ url('/filter/' . $cat->slug . '/' . $sub->slug) }}">
                                            <div class="img">
                                                <img src="{{ url('public/uploads/' . $sub->image) }}" alt="{{ $sub->title }}">
                                            </div>
                                            <div class="hd_name">
                                                <h3>{{ $sub->title }}</h3>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="slider_four">
        <div class="container-fluid">
            <div class="owl-carousel owl-theme">
                @php
                    $yts = DB::select("SELECT * FROM yt WHERE status = 1 ");
                @endphp

                @foreach ($yts as $yt)
                    <div class="item">
                        <div class="about-us-img-wrap about-img-left">
                            <div class="ltn__video-img ltn__video-img-before-none ltn__animation-pulse2">
                                <iframe src="{{ $yt->link }}" title="YouTube video player" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="ltn__about-us-area section-bg-6 bg-image-right-before pt-120 pb-90">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 align-self-center">
                    <div class="about-us-info-wrap">
                        <div class="section-title-area ltn__section-title-2 mb-20">
                            <h1 class="section-title">Our Specialization & Company Features</h1>
                        </div>
                        <ul class="ltn__list-item-half ltn__list-item-half-2 list-item-margin clearfix">
                            <li><i class="icon-done"></i>Advance Bidding Process</li>
                            <li><i class="icon-done"></i>Real Time Bidding</li>
                            <li><i class="icon-done"></i>Easy To Use</li>
                            <li><i class="icon-done"></i>Advance Verification Process</li>
                            <li><i class="icon-done"></i>Personalised Customer Support</li>
                            <li><i class="icon-done"></i>Comprehensive Global Market</li>
                            <li><i class="icon-done"></i>Advance Filter for Quotation</li>
                            <li><i class="icon-done"></i>Robust Sorting Option for Bidding</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 align-self-center">
                    <div class="about-us-img-wrap about-img-left">
                        {{-- Optionally add an image here --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ltn__testimonial-area section-bg-1--- pt-120 pb-75">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h1 class="section-title">Client's Feedback</h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__testimonial-slider-6-active slick-arrow-1">
                @php
                    $testimonials = DB::select("SELECT * FROM testimonial WHERE status = 1 LIMIT 6");
                @endphp

                @foreach ($testimonials as $test)
                    <div class="col-lg-4">
                        <div class="ltn__testimonial-item ltn__testimonial-item-7 ltn__testimonial-item-8">
                            <div class="ltn__testimoni-info">
                                <div class="ltn__testimoni-author-ratting">
                                    <div class="ltn__testimoni-info-inner">
                                        <div class="ltn__testimoni-img">
                                            <img src="{{ url('assets/front/img/testimonial/12.jfif') }}" alt="{{ $test->title }}">
                                        </div>
                                        <div class="ltn__testimoni-name-designation">
                                            <h5>{{ $test->title }}</h5>
                                            <label>{{ $test->position }}</label>
                                        </div>
                                    </div>
                                    <div class="ltn__testimoni-rating">
                                        <ul class="product-ratting">
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star"></i></li>
                                            <li><i class="fas fa-star"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <p>{{ $test->description }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        responsive: {
            0: { items: 1 },
            600: { items: 3 },
            1000: { items: 3 },
            1300: { items: 3 }
        }
    });
</script>
@endpush
