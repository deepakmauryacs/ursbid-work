@extends('frontend.layouts.app')

@section('title', 'URSBID: A platform for Construction material seller and buyer')

@section('content')
<section class="twopart">
    <div class="container">
        <div class="d_twopart">
            @foreach ($categories as $cat)
                <div class="points_cont">
                    <div class="padd">
                        <div class="heading">
                            <h3>{{ $cat->name }}</h3>
                        </div>

                        <div class="pro">
                            @forelse ($cat->subCategories as $sub)
                                <div class="item">
                                    <a href="{{ url('/filter/' . $cat->slug . '/' . $sub->slug) }}">
                                        <div class="img">
                                          @php
                                                if ($sub->image) {
                                                    if (\Illuminate\Support\Str::startsWith($sub->image, ['http://', 'https://'])) {
                                                        // External URL
                                                        $img = $sub->image;
                                                    } else {
                                                        // Local file check
                                                        $localPath = public_path($sub->image);
                                                        if (\Illuminate\Support\Facades\File::exists($localPath)) {
                                                            $img = asset('public/' . $sub->image);
                                                        } else {
                                                            $img = asset('public/uploads/placeholder/placeholder.jpg');
                                                        }
                                                    }
                                                } else {
                                                    $img = asset('public/uploads/placeholder/placeholder.jpg');
                                                }
                                            @endphp


                                            <img src="{{ $img }}" alt="{{ $sub->name }}">
                                        </div>
                                        <div class="hd_name">
                                            <h3>{{ $sub->name }}</h3>
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <p class="text-muted">No sub-categories available.</p>
                            @endforelse
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- YouTube slider (using new table: youtube_links) --}}
<div class="slider_four">
    <div class="container-fluid">
        <div class="owl-carousel owl-theme">
            @php
                $yts = DB::table('youtube_links')->where('status', 1)->get();
            @endphp
            @foreach ($yts as $yt)
                <div class="item">
                    <div class="about-us-img-wrap about-img-left">
                        <div class="ltn__video-img ltn__video-img-before-none ltn__animation-pulse2">
                            <iframe
                                src="{{ $yt->youtube_link }}"
                                title="YouTube video player"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin"
                                allowfullscreen>
                            </iframe>
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
                    {{-- optional image --}}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="ltn__testimonial-area section-bg-1--- pt-120 pb-75">
    <div class="container">
        <div class="row"><div class="col-lg-12">
            <div class="section-title-area ltn__section-title-2 text-center">
                <h1 class="section-title">Client's Feedback</h1>
            </div>
        </div></div>

        <div class="row ltn__testimonial-slider-6-active slick-arrow-1">
            @php
                $testimonials = DB::table('testimonial')->where('status', 1)->limit(6)->get();
            @endphp
            @foreach ($testimonials as $test)
                <div class="col-lg-4">
                    <div class="ltn__testimonial-item ltn__testimonial-item-7 ltn__testimonial-item-8">
                        <div class="ltn__testimoni-info">
                            <div class="ltn__testimoni-author-ratting">
                                <div class="ltn__testimoni-info-inner">
                                    <div class="ltn__testimoni-img">
                                        <img src="{{ asset('assets/front/img/testimonial/12.jfif') }}" alt="{{ $test->title }}">
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
            0:   { items: 1 },
            600: { items: 3 },
            1000:{ items: 3 },
            1300:{ items: 3 }
        }
    });
</script>
@endpush
