@extends('frontend.layouts.app')

@section('title', 'URSBID: A platform for Construction material seller and buyer')

@section('content')
{{-- =========================
     PRODUCTS & SERVICES (2 columns, uniform image size + same grid both sides)
========================= --}}
<style>
  .cat-two{padding:36px 0 10px;background:#ffffff}
  .cat-title{font-size:28px;font-weight:800;color:#0f172a;margin:0 0 12px}
  .cat-col{position:relative}
  @media (min-width:992px){
    .cat-col--right{padding-left:28px}
    .cat-col--right:before{content:"";position:absolute;left:0;top:0;bottom:0;width:1px;background:#e5e7eb}
  }

  /* UNIFIED GRID: Products == Services */
  .cat-grid{
    display:grid;
    gap:14px;
    grid-template-columns:repeat(2, 1fr);   /* default (tablet / small desktop) */
  }
  @media (min-width:1200px){
    .cat-grid{ grid-template-columns:repeat(3, 1fr); } /* desktop: 3 per row */
  }
  @media (max-width:575.98px){
    .cat-grid{ grid-template-columns:repeat(2, 1fr); } /* mobile: 2 per row (set to 1 if you prefer) */
  }

  /* Card */
  .cat-card{
    display:flex;flex-direction:column;gap:10px;background:#fff;border:1px solid #e5e7eb;
    border-radius:14px;overflow:hidden;box-shadow:0 6px 16px rgba(2,6,23,.06);
    transition:transform .15s ease, box-shadow .15s ease, border-color .15s ease;
    text-decoration:none;
  }
  .cat-card:hover{transform:translateY(-2px);box-shadow:0 12px 28px rgba(2,6,23,.12);border-color:#dfe3ea}

  /* >>> UNIFORM IMAGE SIZE + BORDER (3:2 ratio) <<< */
  .cat-img{
    position:relative;width:100%;padding-top:66.666%; /* 3:2 aspect ratio */
    border:1px solid #e5e7eb; border-radius: 12px 12px 0px 0px;overflow:hidden;
    background:linear-gradient(180deg,#f8fafc,#f1f5f9);
  }
  .cat-card:hover .cat-img{border-color:#cbd5e1}
  .cat-img img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;display:block}

  .cat-name{font-weight:700;font-size:13px;line-height:1.35;color:#0f172a;padding:0 12px 12px}
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
                <a class="cat-card" href="{{ url('/filter/'.$cat->slug.'/'.$sub->slug) }}">
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

{{-- =========================
     YouTube Slider (LIGHT THEME – distinct from Features)
========================= --}}
<style>
  .yt-wrap{padding:70px 0;background:#f6f9fc;border-top:1px solid #eef2f7;border-bottom:1px solid #eef2f7}
  .yt-title{color:#0f172a;font-weight:800;font-size:32px;margin:0 0 10px;text-align:center}
  .yt-sub{color:#475569;text-align:center;margin:0 auto 28px;max-width:820px}

  .yt-owl .owl-stage{display:flex}
  .yt-owl .item{height:100%}
  .yt-owl .owl-item{margin-right:10px !important} /* 10px gap */

  .yt-card{
      height:100%; background:#ffffff; border:1px solid #e5e7eb;
      border-radius:16px; overflow:hidden; box-shadow:0 10px 24px rgba(2,6,23,.06);
      transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
  }
  .yt-card:hover{ transform:translateY(-4px); box-shadow:0 18px 40px rgba(2,6,23,.16); border-color:#dfe3ea }

  .yt-frame{position:relative;width:100%;padding-top:56.25%;background:#0a0f22}
  .yt-frame iframe{position:absolute;inset:0;width:100%;height:100%;border:0;border-radius:12px}

  .yt-overlay{
      position:absolute; inset:0; border-radius:12px;
      background:linear-gradient(to bottom, rgba(0,0,0,.08), rgba(0,0,0,.18));
      display:flex; align-items:center; justify-content:center; pointer-events:none;
  }
  .yt-play{
      width:60px;height:60px;border-radius:9999px;
      background:linear-gradient(135deg,#fb7185,#f59e0b);
      display:grid;place-items:center;box-shadow:0 8px 22px rgba(0,0,0,.20);
      transform:scale(1); transition:transform .18s ease;
  }
  .yt-card:hover .yt-play{ transform:scale(1.08) }
  .yt-play:before{
      content:""; display:block; width:0;height:0;
      border-left:16px solid #ffffff; border-top:10px solid transparent; border-bottom:10px solid transparent;
      margin-left:4px;
  }

  .yt-owl .owl-nav{position:relative;margin-top:18px;text-align:center}
  .yt-owl .owl-nav button{
      width:40px;height:40px;border-radius:50%;border:1px solid #cbd5e1!important;
      background:#ffffff!important;color:#0f172a!important;margin:0 6px;display:inline-flex;
      align-items:center;justify-content:center;transition:all .15s ease; box-shadow:0 4px 12px rgba(2,6,23,.06);
  }
  .yt-owl .owl-nav button:hover{background:#f8fafc!important;border-color:#94a3b8!important}
  .yt-owl .owl-nav button span{font-size:20px;line-height:1}

  .yt-owl .owl-dots{margin-top:8px;text-align:center}
  .yt-owl .owl-dot span{width:8px;height:8px;background:#94a3b8;opacity:.6;display:inline-block;border-radius:9999px;margin:0 4px;transition:all .15s}
  .yt-owl .owl-dot.active span{opacity:1;width:18px;background:#0f172a}

  @media(min-width:1200px){
    .yt-owl .owl-stage-outer{padding-top:4px;padding-bottom:12px}
  }
</style>

<div class="yt-wrap">
  <div class="container">
    <h2 class="yt-title">Latest from URSBID</h2>
    <p class="yt-sub">Quick tips, announcements, and product walk-throughs. Watch and get bidding faster.</p>

    <div class="yt-owl owl-carousel owl-theme">
      @php $yts = DB::table('youtube_links')->where('status', 1)->get(); @endphp
      @foreach ($yts as $yt)
        <div class="item">
          <div class="yt-card">
            <div class="yt-frame">
              <iframe
                src="{{ $yt->youtube_link }}"
                title="URSBID video"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin"
                allowfullscreen>
              </iframe>
              <div class="yt-overlay"><div class="yt-play"></div></div>
            </div>
            {{-- <div class="yt-cap">{{ $yt->title ?? 'URSBID' }}</div> --}}
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

{{-- =========================
     Our Specialization & Company Features (Dark, 3 per row)
========================= --}}
<style>
  .sf-wrap{padding:80px 0;background:#0b1020;text-align:center}
  .sf-title{font-size:36px;font-weight:800;color:#e8eefc;margin-bottom:10px}
  .sf-sub{color:#b6c2e2;line-height:1.6;max-width:720px;margin:0 auto 40px}

  .sf-grid{display:grid;gap:20px;grid-template-columns:repeat(3,minmax(0,1fr))}
  @media (max-width:991.98px){ .sf-grid{grid-template-columns:repeat(2,1fr)} }
  @media (max-width:575.98px){ .sf-grid{grid-template-columns:1fr} }

  .sf-card{
    background:#101734;border:1px solid rgba(255,255,255,.10);border-radius:16px;padding:18px;
    display:flex;gap:14px;align-items:flex-start;
    box-shadow:0 10px 28px rgba(0,0,0,.35);
    transition:transform .15s ease, box-shadow .15s ease, border-color .15s ease;
    text-align:left;
  }
  .sf-card:hover{ transform:translateY(-3px); box-shadow:0 16px 44px rgba(0,0,0,.45); border-color:rgba(255,255,255,.18) }
  .sf-ico{
    flex:0 0 46px;width:46px;height:46px;border-radius:12px;display:grid;place-items:center;
    background:linear-gradient(135deg,#3b82f6,#06b6d4); color:#061120; box-shadow:inset 0 0 0 2px rgba(255,255,255,.35)
  }
  .sf-ico i{font-size:20px}
  .sf-head{font-weight:800;color:#f2f5ff;margin:2px 0 4px;font-size:17px;line-height:1.25}
  .sf-note{color:#a8b3cf;margin:0;font-size:13.5px;line-height:1.6}
</style>

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

{{-- =========================
     Client's Feedback (Modern Grid, No Owl)
========================= --}}
<style>
    :root{
        --t-bg:#ffffff; --t-text:#0f172a; --t-muted:#475569; --t-border:#e5e7eb;
        --t-star:#f59e0b; --t-accent:#2dd4bf; --t-shadow:0 12px 30px rgba(2,6,23,.10);
        --t-shadow-hover:0 18px 50px rgba(2,6,23,.18);
    }
    .t-section{padding:80px 0 60px}
    .t-title{font-size:38px;font-weight:800;letter-spacing:.2px;color:var(--t-text);margin-bottom:28px}
    .t-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
    @media (max-width:1199.98px){.t-grid{grid-template-columns:repeat(2,1fr)}}
    @media (max-width:767.98px){.t-grid{grid-template-columns:1fr}}
    .t-card{position:relative;background:var(--t-bg);border:1px solid var(--t-border);border-radius:20px;padding:22px;box-shadow:var(--t-shadow);transition:.18s;overflow:hidden}
    .t-card::before{content:"";position:absolute;inset:0 0 auto 0;height:4px;background:linear-gradient(90deg,var(--t-accent),#60a5fa);opacity:.9}
    .t-card:hover{transform:translateY(-4px);box-shadow:var(--t-shadow-hover)}
    .t-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
    .t-author{display:flex;align-items:center;gap:12px}
    .t-avatar{width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid var(--t-border)}
    .t-name{margin:0;font-weight:800;font-size:18px;color:var(--t-text)}
    .t-role{margin-top:2px;color:#64748b;font-size:13px}
    .t-stars{display:flex;gap:6px}
    .t-stars i{color:var(--t-star);font-size:16px}
    .t-text{margin:8px 0 0;color:var(--t-text);font-size:15px;line-height:1.65}
    .t-quote{position:absolute;right:18px;bottom:-8px;font-size:72px;color:#e2e8f0;opacity:.55;pointer-events:none}
</style>

<div class="t-section">
    <div class="container">
        <div class="text-center">
            <h2 class="t-title">Client's Feedback</h2>
        </div>

        @php
            $testimonials = DB::table('testimonial')->where('status', 1)->limit(6)->get();
        @endphp

        @if($testimonials->count())
            <div class="t-grid">
                @foreach ($testimonials as $test)
                    <div class="t-card">
                        <div class="t-head">
                            <div class="t-author">
                                <img class="t-avatar" src="{{ asset('assets/front/img/testimonial/12.jfif') }}" alt="{{ $test->title }}">
                                <div>
                                    <p class="t-name">{{ $test->title }}</p>
                                    <div class="t-role">{{ $test->position }}</div>
                                </div>
                            </div>
                            <div class="t-stars" aria-label="rating 5 out of 5">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <p class="t-text">{{ $test->description }}</p>
                        <i class="t-quote fas fa-quote-right"></i>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-muted">No testimonials yet.</p>
        @endif
    </div>
</div>

{{-- =========================
     Blog Section (3 Column Grid)
========================= --}}
<style>
  .blog-section {padding: 70px 0; background: #f9fafb}
  .blog-title {font-size: 38px; font-weight: 800; color: #0f172a; margin-bottom: 28px; text-align: center}
  .blog-card {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 14px;
      box-shadow: 0 10px 24px rgba(2,6,23,.06);
      transition: 0.3s;
      overflow: hidden;
      height: 100%;
      display: flex;
      flex-direction: column;
  }
  .blog-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 18px 40px rgba(2,6,23,.16);
  }
  .blog-image {
      position: relative;
      width: 100%;
      padding-top: 56.25%;
      overflow: hidden;
  }
  .blog-image img {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
  }
  .blog-body {
      padding: 18px;
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
  }
  .blog-heading {
      font-size: 18px;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 8px;
  }
  .blog-snippet {
      font-size: 14.5px;
      color: #475569;
      line-height: 1.6;
      margin-bottom: 16px;
  }
  .blog-link {
      font-size: 14px;
      color: #3b82f6;
      font-weight: 600;
      text-decoration: none;
      display: inline-block;
  }
  .blog-link:hover {
      text-decoration: underline;
  }
</style>

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
  // YouTube slider (10px gap)
  $('.yt-owl').owlCarousel({
      loop:true, margin:10, nav:true, dots:true, stagePadding:40, smartSpeed:550, autoplay:false,
      navText:['<span>&larr;</span>','<span>&rarr;</span>'],
      responsive:{ 0:{items:1,stagePadding:10}, 600:{items:2,stagePadding:20}, 1000:{items:3,stagePadding:30}, 1300:{items:3,stagePadding:60} }
  });
</script>
@endpush
