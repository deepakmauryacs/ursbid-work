<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  @php
    use Illuminate\Support\Str;
    use Carbon\Carbon;

    // Core values
    $siteName   = config('app.name', 'URSBID');
    $title      = $blog->meta_title ? trim($blog->meta_title) : trim($blog->title);
    $rawDesc    = $blog->meta_description ?: Str::limit(trim(strip_tags($blog->description ?? '')), 160, '');
    $description= preg_replace('/\s+/', ' ', $rawDesc); // collapse whitespace
    $canonical  = url('/blog/'.$blog->slug);

    // Image (absolute) 
    $image = $blog->image
      ? (Str::startsWith($blog->image, ['http://','https://']) ? $blog->image : asset('public/'.$blog->image))
      : asset('public/uploads/placeholder/blog-placeholder.jpg');

    // Tags
    $tags = array_filter(array_map('trim', explode(',', $blog->meta_keywords ?? '')));

    // Dates (ISO 8601 for OG/Schema)
    $publishedIso = $blog->created_at ? Carbon::parse($blog->created_at)->toIso8601String() : null;
    $updatedIso   = $blog->updated_at ? Carbon::parse($blog->updated_at)->toIso8601String() : $publishedIso;

    // Author
    $authorName = $blog->author_name ?? 'Admin';

    // Robots (index live posts only)
    $robots = (string)$blog->status === '1' ? 'index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1' : 'noindex,nofollow';

    // Twitter handle (optional – set in config if you have one)
    $twitterSite = config('seo.twitter_site', null); // e.g. @ursbid
  @endphp

  <title>{{ $title }} • {{ $siteName }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Favicons -->
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon/favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('assets/favicon/site.webmanifest') }}">
  <link rel="shortcut icon" href="{{ asset('assets/favicon/favicon.ico') }}" type="image/x-icon">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">

  <!-- Primary Meta -->
  <meta name="title" content="{{ e($title) }}">
  <meta name="description" content="{{ e($description) }}">
  @if(!empty($tags))
    <meta name="keywords" content="{{ e(implode(', ', $tags)) }}">
  @endif
  <link rel="canonical" href="{{ $canonical }}">
  <meta name="robots" content="{{ $robots }}">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="article">
  <meta property="og:site_name" content="{{ e($siteName) }}">
  <meta property="og:title" content="{{ e($title) }}">
  <meta property="og:description" content="{{ e($description) }}">
  <meta property="og:url" content="{{ $canonical }}">
  <meta property="og:image" content="{{ $image }}">
  @if($publishedIso)<meta property="article:published_time" content="{{ $publishedIso }}">@endif
  @if($updatedIso)<meta property="article:modified_time" content="{{ $updatedIso }}">@endif
  @foreach($tags as $t)
    <meta property="article:tag" content="{{ e($t) }}">
  @endforeach
  <meta property="article:author" content="{{ e($authorName) }}">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  @if($twitterSite)<meta name="twitter:site" content="{{ $twitterSite }}">@endif
  <meta name="twitter:title" content="{{ e($title) }}">
  <meta name="twitter:description" content="{{ e($description) }}">
  <meta name="twitter:image" content="{{ $image }}">

  <!-- JSON-LD: Article Schema -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Article",
    "mainEntityOfPage": { "@type": "WebPage", "@id": "{{ $canonical }}" },
    "headline": {!! json_encode($title, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!},
    "description": {!! json_encode($description, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!},
    "image": [ "{{ $image }}" ],
    "author": { "@type": "Person", "name": {!! json_encode($authorName, JSON_UNESCAPED_UNICODE) !!} },
    "publisher": {
      "@type": "Organization",
      "name": {!! json_encode($siteName, JSON_UNESCAPED_UNICODE) !!},
      "logo": { "@type": "ImageObject", "url": "{{ asset('assets/front/img/logo.png') }}" }
    },
    "datePublished": "{{ $publishedIso }}",
    "dateModified": "{{ $updatedIso }}"
  }
  </script>

  <!-- Bootstrap 5 + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- DM Sans (400–800) -->
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --ink:#0b1320; --muted:#6b7280; --soft:#94a3b8; --line:#e6e9ef;
      --card:#ffffff; --bg:#f5f7fb; --accent:#3b82f6;
    }
    html, body, h1, h2, h3, h4, h5, h6, .btn, .nav, .form-control, .badge, .card, .breadcrumb, .small, .lead {
      font-family: "DM Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
    }
    body{ color:var(--ink); background:var(--bg); -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; }
    a{text-decoration:none}
    .container-narrow{max-width:1120px}
    header{background:#fff;border-bottom:1px solid var(--line)}
    .brand{font-weight:800;color:var(--ink);letter-spacing:.2px}
    .nav a{color:#334155;margin-left:20px;font-weight:500}
    .nav a:hover{color:var(--accent)}

    .crumb{font-size:.9rem;color:var(--soft)}
    .crumb a{color:#718198}
    .crumb a:hover{color:var(--ink)}

    .hero-img{width:100%;border-radius:8px;object-fit:cover;height:420px;background:#000}
    @media (max-width: 767.98px){ .hero-img{height:260px} }

    .meta{color:#475569;font-size:.92rem}
    .meta .dot{width:4px;height:4px;border-radius:50%;background:#cbd5e1;display:inline-block;margin:0 10px}

    .post-wrap{background:#fff;border:1px solid var(--line);border-radius:10px;padding:28px}
    .post-title{font-weight:800;font-size:2rem;line-height:1.25;margin:14px 0 16px}
    .post p{color:#334155;line-height:1.9;margin-bottom:1.1rem;font-weight:400}
    .post h2,.post h3{font-weight:800;color:#0b1320;margin:1.6rem 0 .6rem}
    .post img{border-radius:8px}

    .tag-share{border-top:1px solid var(--line);border-bottom:1px solid var(--line);padding:14px 0;margin:22px 0}
    .tags a{display:inline-block;background:#f1f5f9;border:1px solid #e2e8f0;color:#475569;
            padding:6px 10px;border-radius:999px;margin:4px 6px 0 0;font-size:.85rem;font-weight:500}
    .tags a:hover{background:#eef2f7}
    .share a{color:#475569;margin-left:10px}
    .share a:hover{color:var(--accent)}

    .author{display:flex;gap:16px;padding:18px;background:#fff;border:1px solid var(--line);border-radius:10px}
    .author .avatar{width:76px;height:76px;border-radius:50%;object-fit:cover;border:2px solid #f1f5f9}
    .author .name{font-weight:800}
    .author .links a{color:#475569;margin-right:10px}
    .author .links a:hover{color:var(--accent)}

    .widget{background:#fff;border:1px solid var(--line);border-radius:10px;margin-bottom:18px}
    .widget .w-head{padding:14px 16px;border-bottom:1px solid var(--line);font-weight:800}
    .w-body{padding:14px 16px}
    .mini-post{display:flex;gap:12px;margin-bottom:12px}
    .mini-post img{width:92px;height:72px;object-fit:cover;border-radius:6px}
    .cat-list a{display:flex;justify-content:space-between;color:#334155;padding:8px 0;border-bottom:1px dashed #edf2f7}
    .cat-list a:last-child{border:0}

    .related .card{border:1px solid var(--line);border-radius:10px;overflow:hidden}
    .related .card img{height:160px;object-fit:cover}
    .related .card-title{font-weight:700;font-size:1rem}
    .related .badge{background:#f1f5f9;color:#475569}

    footer{background:#fff;border-top:1px solid var(--line);color:#6b7280; padding: 2rem 0;}
    .social-icons a { color: #6b7280; margin-right: 15px; font-size: 1.2rem;}
    .social-icons a:hover { color: var(--accent); }

    /* Tables inside post */
    .post table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; }
    .post table th, .post table td { border: 1px solid var(--line); padding: 10px 14px; text-align: left; vertical-align: top; }
    .post table th { background: #f9fafb; font-weight: 700; color: var(--ink); }
    .post table tr:nth-child(even) td { background: #fcfdff; }
  </style>

  {{-- Custom header injections (per-post) --}}
  @if(!empty($blog->custom_header_code))
    {!! $blog->custom_header_code !!}
  @endif
</head>
<body>
<!-- Header -->
<header>
  <div class="container container-narrow py-3 d-flex align-items-center justify-content-between">
    <a class="brand fs-4" href="{{ url('/') }}">{{ $siteName }}</a>
    <nav class="nav d-none d-md-flex">
      <a href="{{ url('/') }}">Home</a>
      <a href="{{ url('/about') }}">About Us</a>
      <a href="{{ url('/blog') }}">Blog</a>
      <a href="{{ url('/advertise') }}">Advertise</a>
      <a href="{{ url('/contact-detail') }}">Contact Us</a>
    </nav>
    <div class="d-flex align-items-center gap-3">
      <i class="bi bi-person"></i>
      <i class="bi bi-list d-md-none fs-3"></i>
    </div>
  </div>
</header>

<!-- Breadcrumb -->
<section class="py-2">
  <div class="container container-narrow">
    <div class="crumb">
      <a href="{{ url('/') }}">Home</a> &nbsp;/&nbsp;
      <a href="{{ url('/blog') }}">Blog</a> &nbsp;/&nbsp;
      <span>{{ e($blog->title) }}</span>
    </div>
  </div>
</section>

<!-- Main Grid -->
<section class="pb-5">
  <div class="container container-narrow">
    <div class="row g-4">
      <!-- Left Content (Dynamic) -->
      <div class="col-lg-8">
        @php
          $img = $image; // from head block
          $date = $blog->created_at ? Carbon::parse($blog->created_at)->format('d M, Y') : '';
          $shareUrl = url()->current();
          $shareText = urlencode($blog->title);
        @endphp

        <img class="hero-img mb-3" src="{{ $img }}" alt="{{ e($blog->title) }}" />

        <div class="meta d-flex flex-wrap align-items-center mb-2">
          <span>by <strong>{{ e($authorName) }}</strong></span>
          @if($date)<span class="dot"></span><span>{{ $date }}</span>@endif
          @if(!empty($blog->read_minutes))
            <span class="dot"></span><i class="bi bi-clock me-1"></i> {{ (int)$blog->read_minutes }} Minute Read
          @endif
          @if(isset($blog->views))
            <span class="dot"></span><i class="bi bi-eye me-1"></i> {{ number_format((int)$blog->views) }} views
          @endif

          <span class="ms-auto d-none d-md-inline">Share: </span>
          <div class="share ms-2 d-none d-md-inline">
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ $shareText }}" target="_blank" rel="noopener"><i class="bi bi-twitter-x"></i></a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($shareUrl) }}&title={{ $shareText }}" target="_blank" rel="noopener"><i class="bi bi-linkedin"></i></a>
            <a href="{{ $shareUrl }}"><i class="bi bi-share"></i></a>
          </div>
        </div>

        <div class="post-wrap">
          <h1 class="post-title">{{ e($blog->title) }}</h1>

          <article class="post">
            {!! $blog->description !!}
          </article>

          <div class="tag-share d-flex flex-wrap align-items-center justify-content-between">
            <div class="tags">
              <strong class="me-2">Tags:</strong>
              @forelse($tags as $tag)
                <a href="{{ url('/blog/tag/'.Str::slug($tag)) }}">{{ e($tag) }}</a>
              @empty
                <span class="text-muted">No tags</span>
              @endforelse
            </div>
          </div>
        </div>
      </div>

      <!-- Right Sidebar (Dynamic) -->
      <div class="col-lg-4">
        @php
          $featureImg = null;
          if(!empty($featurePost)){
            $featureImg = $featurePost->image
              ? (Str::startsWith($featurePost->image, ['http://','https://']) ? $featurePost->image : asset('public/'.$featurePost->image))
              : asset('public/uploads/placeholder/blog-placeholder.jpg');
          }
        @endphp

        @if(!empty($featurePost))
        <div class="widget">
          <div class="w-head">Feature Post</div>
          <div class="w-body">
            <img class="w-100 rounded mb-2" src="{{ $featureImg }}" alt="{{ e($featurePost->title) }}">
            <div class="small text-secondary mb-1">
              <i class="bi bi-clock me-1"></i>
              @if(!empty($featurePost->read_minutes)) {{ (int)$featurePost->read_minutes }} Minute Read @else 3 Minute Read @endif
            </div>
            <div class="fw-bold">
              <a href="{{ url('/blog/'.$featurePost->slug) }}" class="text-dark text-decoration-none">
                {{ \Illuminate\Support\Str::limit($featurePost->title, 70) }}
              </a>
            </div>
            <div class="small mt-1">by <strong>{{ e($featurePost->author_name ?? 'Admin') }}</strong></div>
            <a class="d-inline-block mt-2 text-decoration-underline" href="{{ url('/blog/'.$featurePost->slug) }}">Read More</a>
          </div>
        </div>
        @endif

        @if(!empty($popularPosts) && $popularPosts->count())
        <div class="widget">
          <div class="w-head">Popular Post</div>
          <div class="w-body">
            @foreach($popularPosts as $p)
              @php
                $pImg = $p->image
                  ? (Str::startsWith($p->image, ['http://','https://']) ? $p->image : asset('public/'.$p->image))
                  : asset('public/uploads/placeholder/blog-placeholder.jpg');
              @endphp
              <div class="mini-post">
                <a href="{{ url('/blog/'.$p->slug) }}">
                  <img src="{{ $pImg }}" alt="{{ e($p->title) }}">
                </a>
                <div>
                  <div class="fw-semibold">
                    <a href="{{ url('/blog/'.$p->slug) }}" class="text-decoration-none text-dark">
                      {{ \Illuminate\Support\Str::limit($p->title, 60) }}
                    </a>
                  </div>
                  <div class="small text-secondary">
                    @if(!empty($p->read_minutes)) {{ (int)$p->read_minutes }} Minute Read @else 3 Minute Read @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
        @endif

        <div class="widget">
          <div class="w-head">Follow Me</div>
          <div class="w-body d-flex gap-2">
            <a class="btn btn-outline-secondary btn-sm" href="#"><i class="bi bi-facebook"></i></a>
            <a class="btn btn-outline-secondary btn-sm" href="#"><i class="bi bi-twitter-x"></i></a>
            <a class="btn btn-outline-secondary btn-sm" href="#"><i class="bi bi-instagram"></i></a>
            <a class="btn btn-outline-secondary btn-sm" href="#"><i class="bi bi-youtube"></i></a>
          </div>
        </div>
      </div>
      <!-- /Sidebar -->
    </div>
  </div>
</section>

<!-- Related Posts (Dynamic) -->
@if(!empty($relatedPosts) && $relatedPosts->count())
<section class="py-4">
  <div class="container container-narrow">
    <h4 class="fw-bold mb-3">You Might Like This</h4>
    <div class="row g-3 related">
      @foreach($relatedPosts as $r)
        @php
          $rImg = $r->image
            ? (Str::startsWith($r->image, ['http://','https://']) ? $r->image : asset('public/'.$r->image))
            : asset('public/uploads/placeholder/blog-placeholder.jpg');
          $rDate = $r->created_at ? \Carbon\Carbon::parse($r->created_at)->format('d M, Y') : '';
        @endphp
        <div class="col-md-3 col-6">
          <div class="card h-100">
            <a href="{{ url('/blog/'.$r->slug) }}">
              <img src="{{ $rImg }}" class="card-img-top" alt="{{ e($r->title) }}">
            </a>
            <div class="card-body">
              <div class="badge mb-2">Blog</div>
              <div class="card-title">
                <a href="{{ url('/blog/'.$r->slug) }}" class="text-dark text-decoration-none">
                  {{ \Illuminate\Support\Str::limit($r->title, 60) }}
                </a>
              </div>
              <div class="small text-secondary">By {{ e($r->author_name ?? 'Admin') }} @if($rDate) · {{ $rDate }} @endif</div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- Footer -->
<footer>
  <div class="container container-narrow">
    <div class="row gy-3 align-items-center">
      <div class="col-md text-center text-md-start">
        <div class="social-icons">
          <a href="#"><i class="bi bi-facebook"></i></a>
          <a href="#"><i class="bi bi-twitter-x"></i></a>
          <a href="#"><i class="bi bi-instagram"></i></a>
          <a href="#"><i class="bi bi-youtube"></i></a>
        </div>
      </div>
      <div class="col-md-auto text-center text-md-end">
        <div class="small">{{ date('Y') }} © All rights reserved.</div>
      </div>
    </div>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
