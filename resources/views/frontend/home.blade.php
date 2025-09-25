@extends('frontend.layouts.app')
@section('title', 'URSBID: A platform for Construction material seller and buyer')

@section('content')
<style>
/* ====== MOBILE ONLY (<= 768px) ====== */
@media (max-width: 767.98px){
  /* row ko 2 columns bana do */
  .cat-two .row.g-4{
    display: grid !important;
    grid-template-columns: 1fr 1fr;   /* left & right */
    gap: 16px;
  }
  /* bootstrap col widths reset */
  .cat-two .col-lg-6{
    width: auto !important;
    max-width: none !important;
    padding: 0 !important;
  }

  .cat-two .cat-col{
    height: 100%;
    background: #fff;
    border-radius: 14px;
  }

  /* heading sticky rakhna (optional) */
  .cat-two .cat-title{
    position: sticky;
    top: 0;
    background: #fff;
    padding: 10px 12px;
    margin: 0 0 8px 0;
    z-index: 2;
    font-size: 16px;
    line-height: 1.2;
    border-bottom: 1px solid #eef2f6;
  }

  /* cards column-wise stack */
  .cat-two .cat-grid{
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 0 10px 12px;
  }

  .cat-two .cat-card{
    display: block;
    border: 1px solid #e7edf3;
    border-radius: 14px;
    padding: 8px;
    text-decoration: none;
    background: #fff;
  }

  .cat-two .cat-img{
    display: block;
    border-radius: 10px;
    overflow: hidden;
    aspect-ratio: 16/10;              /* uniform thumbnails */
    background: #f4f6f8;
  }
  .cat-two .cat-img img{
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .cat-two .cat-name{
    display: block;
    margin-top: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #111827;
  }
}
</style> 
<section class="cat-two">
  <div class="container">
    <div class="row g-4">
      @foreach ($categories as $loopIndex => $cat)
        <div class="col-lg-6">
          <div class="cat-col {{ $loopIndex === 1 ? 'cat-col--right' : '' }}">
            <h3 class="cat-title">{{ $cat->name }}</h3>

            <div class="cat-grid">
              @forelse ($cat->subCategories as $sub)
                @php
                  if ($sub->image) {
                    if (\Illuminate\Support\Str::startsWith($sub->image, ['http://','https://'])) {
                      $img = $sub->image;
                    } else {
                      $localPath = public_path($sub->image);
                      $img = \Illuminate\Support\Facades\File::exists($localPath)
                              ? asset('public/'.$sub->image)
                              : asset('public/uploads/placeholder/placeholder.jpg');
                    }
                  } else {
                    $img = asset('public/uploads/placeholder/placeholder.jpg');
                  }
                @endphp
                <a class="cat-card" href="{{ route('filter', ['category' => $cat->slug, 'subcategory' => $sub->slug]) }}">
                  <span class="cat-img"><img src="{{ $img }}" alt="{{ $sub->name }}"></span>
                  <span class="cat-name">{{ $sub->name }}</span>
                </a>
              @empty
                <p class="text-muted m-0">No items yet.</p>
              @endforelse
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
{{-- === New User Register CTA (place after Products & Services) === --}}
@php
  $sellerUrl =  url('seller-register');
  $buyerUrl  =  url('seller-register');
@endphp

<section class="nu-cta">
  <div class="container">
    <div class="nu-wrap">
      <div class="nu-left">
        <span class="nu-kicker">New to URSBID?</span>
        <h2 class="nu-title">New User Register Here</h2>
        <p class="nu-sub">
          Join India’s fast, transparent construction marketplace. Register as a Seller/Contractor to
          list products & services, or as a Buyer/Client to post RFQs and start getting bids in minutes.
        </p>

        <ul class="nu-points">
          <li><i class="bi bi-check2-circle"></i> Free signup, quick KYC</li>
          <li><i class="bi bi-check2-circle"></i> Live bidding & real-time ranks</li>
          <li><i class="bi bi-check2-circle"></i> Verified vendors & secure communication</li>
        </ul>
      </div>

      <div class="nu-right">
        <div class="nu-card">
          <h3 class="nu-card-title"><i class="bi bi-person-gear"></i> I’m a Seller / Contractor</h3>
          <p class="nu-card-sub">Showcase your products & services and start receiving RFQs.</p>
          <a href="{{ $sellerUrl }}" class="nu-btn nu-btn-primary">Register as Seller / Contractor</a>
        </div>

        <div class="nu-card">
          <h3 class="nu-card-title"><i class="bi bi-briefcase"></i> I’m a Buyer / Client</h3>
          <p class="nu-card-sub">Create RFQs, compare quotes, and award the best bid.</p>
          <a href="{{ $buyerUrl }}" class="nu-btn nu-btn-outline">Register as Buyer / Client</a>
        </div>
      </div>
    </div>
  </div>
</section>

@push('styles')
<style>
  .nu-cta{ padding:60px 0; background:#0f172a; position:relative; overflow:hidden; }
  .nu-cta:before{
    content:""; position:absolute; inset:auto -10% -35% -10%;
    height:300px; background:radial-gradient(50% 50% at 50% 50%, rgba(59,130,246,.25), rgba(59,130,246,0) 70%);
    filter: blur(40px);
  }
  .nu-wrap{
    display:grid; gap:28px; align-items:center;
    grid-template-columns: 1.2fr .8fr;
  }
  @media (max-width:991.98px){ .nu-wrap{ grid-template-columns:1fr; } }

  .nu-kicker{
    display:inline-block; padding:6px 12px; border:1px solid #27334a; border-radius:999px;
    color:#9fb2d1; font-size:12px; font-weight:700; letter-spacing:.4px; text-transform:uppercase;
    background:rgba(255,255,255,.02);
  }
  .nu-title{ margin:12px 0 8px; color:#fff; font-weight:800; font-size:32px; }
  .nu-sub{ color:#c8d5e6; max-width:720px; margin-bottom:14px; }
  .nu-points{ margin:0; padding:0; list-style:none; display:grid; gap:10px; color:#cfe0f7; }
  .nu-points li i{ margin-right:8px; }

  .nu-right{ display:grid; gap:16px; }
  .nu-card{
    background:#0b1220; border:1px solid #1b2a44; border-radius:18px; padding:20px;
    box-shadow:0 18px 60px rgba(15,23,42,.25);
  }
  .nu-card-title{ color:#e6efff; font-size:18px; font-weight:700; margin:0 0 6px; }
  .nu-card-title i{ margin-right:8px; }
  .nu-card-sub{ color:#9fb2d1; margin:0 0 14px; }

  .nu-btn{
    display:inline-block; width:100%; text-align:center; padding:12px 16px; border-radius:12px;
    font-weight:700; text-decoration:none; transition:transform .15s ease, box-shadow .15s ease, background .2s ease;
    border:1px solid transparent;
  }
  .nu-btn:hover{ transform:translateY(-1px); box-shadow:0 10px 30px rgba(59,130,246,.25); }
  .nu-btn-primary{ background:#3b82f6; color:#fff; }
  .nu-btn-primary:hover{ background:#2f74ea; }
  .nu-btn-outline{ background:transparent; color:#e6efff; border-color:#2a3b5d; }
  .nu-btn-outline:hover{ background:#142036; }
</style>
@endpush

{{-- YOUTUBE SECTION — privacy-enhanced, lazy, no background requests until click --}}
<div class="yt-wrap">
  <div class="container">
    <h2 class="yt-title">Latest from URSBID</h2>
    <p class="yt-sub">Quick tips, announcements, and product walk-throughs. Watch and get bidding faster.</p>
    <div class="yt-owl owl-carousel owl-theme">
      @php $yts = DB::table('youtube_links')->where('status', 1)->get(); @endphp
      @foreach ($yts as $yt)
        @php
          $url = $yt->youtube_link ?? '';
          $vid = null;
          if (preg_match('~(?:youtu\.be/|v=|embed/|shorts/)([A-Za-z0-9_-]{11})~', $url, $m)) {
              $vid = $m[1];
          }
        @endphp
        @if($vid)
          <div class="item">
            <div class="yt-card">
              <button
                type="button"
                class="yt-lazy"
                data-ytid="{{ $vid }}"
                aria-label="Play video"
              >
                <img
                  class="yt-thumb"
                  src="https://i.ytimg.com/vi/{{ $vid }}/hqdefault.jpg"
                  alt="Video thumbnail"
                  loading="lazy"
                >
                <span class="yt-play">
                  <i class="bi bi-play-fill" aria-hidden="true"></i>
                </span>
              </button>
            </div>
          </div>
        @endif
      @endforeach
    </div>
  </div>
</div>


<div class="sf-wrap">
  <div class="container">
    <h2 class="sf-title">Our Specialization & Company Features</h2>
    <p class="sf-sub">Built for speed, transparency, and scale—tailored to construction procurement.</p>

    @php
      $features = [
        ['txt'=>'Advance Bidding Process','note'=>'Configurable rules & smart validation','icon'=>'bi-rocket-takeoff'],
        ['txt'=>'Real Time Bidding','note'=>'Live ranks & instant updates','icon'=>'bi-lightning-charge'],
        ['txt'=>'Easy To Use','note'=>'Minimal clicks, clean UI','icon'=>'bi-mouse3'],
        ['txt'=>'Advance Verification Process','note'=>'KYC & multi-step approvals','icon'=>'bi-shield-check'],
        ['txt'=>'Personalised Customer Support','note'=>'Human-first assistance 7 days a week','icon'=>'bi-headset'],
        ['txt'=>'Comprehensive Global Market','note'=>'Vendors across regions & categories','icon'=>'bi-globe2'],
        ['txt'=>'Advance Filter for Quotation','note'=>'Drill down by spec, brand & price','icon'=>'bi-funnel'],
        ['txt'=>'Robust Sorting Option for Bidding','note'=>'Sort by rank, time, price & more','icon'=>'bi-arrow-down-up'],
      ];
    @endphp

    <div class="sf-grid">
      @foreach($features as $f)
        <div class="sf-card">
          <div class="sf-ico"><i class="bi {{ $f['icon'] }}"></i></div>
          <div>
            <div class="sf-head">{{ $f['txt'] }}</div>
            <p class="sf-note">{{ $f['note'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

<div class="t-section">
  <div class="container">
    <div class="text-center t-headline">
      <span class="t-kicker">What clients say</span>
      <h2 class="t-title">Client's Feedback</h2>
      <p class="t-sub">Real experiences from people who’ve used our platform to save time, cut costs, and ship faster.</p>
    </div>

    @php
      $testimonials = DB::table('testimonial')->where('status', 1)->limit(6)->get();
    @endphp

    @if($testimonials->count())
      <div class="t-grid">
        @foreach ($testimonials as $test)
          <article class="t-card">
            <div class="t-card-inner">
              <header class="t-top">
                <div class="t-avatar-wrap">
                  <img class="t-avatar" src="{{ asset('assets/front/img/testimonial/12.jfif') }}" alt="{{ $test->title }}">
                </div>
                <div class="t-bio">
                  <h3 class="t-name">{{ $test->title }}</h3>
                  <div class="t-role">{{ $test->position }}</div>
                </div>

                <div class="t-top-right">
                  <div class="t-stars" aria-label="rating 5 out of 5">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                  </div>
                  <div class="t-verify">
                    <i class="fas fa-check-circle"></i> Verified
                  </div>
                </div>
              </header>

              <p class="t-text clamp">{{ $test->description }}</p>
              <i class="t-quote fas fa-quote-right"></i>
            </div>
          </article>
        @endforeach
      </div>
    @else
      <p class="text-center text-muted">No testimonials yet.</p>
    @endif
  </div>
</div>

<section class="blog-section">
  <div class="container">
    <h2 class="blog-title">Latest Blogs</h2>

    @php
      $blogs = DB::table('blogs')->where('status', 1)->latest()->limit(3)->get();
    @endphp

    @if ($blogs->count())
      <div class="row">
        @foreach ($blogs as $blog)
          <div class="col-md-4 mb-4">
            <div class="blog-card">
              <div class="blog-image">
                <img src="{{ $blog->image ? asset('public/'.$blog->image) : asset('public/uploads/placeholder/blog-placeholder.jpg') }}" alt="{{ $blog->title }}">
              </div>
              <div class="blog-body">
                <div>
                  <h3 class="blog-heading">{{ \Illuminate\Support\Str::limit($blog->title, 60) }}</h3>
                  <p class="blog-snippet">{{ \Illuminate\Support\Str::limit(strip_tags($blog->description), 100) }}</p>
                </div>
                <a href="{{ url('/blog/'.$blog->slug) }}" class="blog-link">Read More &rarr;</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <p class="text-muted text-center">No blog posts available yet.</p>
    @endif
  </div>
</section>
@endsection



@push('scripts')
<script>
  // Owl (unchanged)
  $('.yt-owl').owlCarousel({
    loop:true, margin:10, nav:true, dots:true, stagePadding:40, smartSpeed:550, autoplay:false,
    navText:['<span>&larr;</span>','<span>&rarr;</span>'],
    responsive:{ 0:{items:1,stagePadding:10}, 600:{items:2,stagePadding:20}, 1000:{items:3,stagePadding:30}, 1300:{items:3,stagePadding:60} }
  });

  // Lazy load YouTube on click using youtube-nocookie
  document.addEventListener('click', function(e){
    const btn = e.target.closest('.yt-lazy');
    if(!btn) return;
    const id = btn.dataset.ytid;
    if(!id) return;

    const iframe = document.createElement('iframe');
    iframe.src = 'https://www.youtube-nocookie.com/embed/' + id + '?rel=0&modestbranding=1&autoplay=1';
    iframe.title = 'URSBID video';
    iframe.loading = 'lazy';
    iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
    iframe.referrerPolicy = 'strict-origin-when-cross-origin';
    iframe.allowFullscreen = true;
    iframe.style.width = '100%';
    iframe.style.height = (btn.querySelector('.yt-thumb')?.height || 315) + 'px';
    iframe.style.border = '0';
    btn.replaceWith(iframe);
  });
</script>
@endpush
