<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />

  @php
    use Illuminate\Support\Str;

    $siteName = config('app.name', 'URSBID');

    // Dynamic page title
    $title = !empty($search) ? "Search: {$search} • Blog" : "Blog";

    // Description & keywords (optional fallback)
    $description = !empty($search) 
      ? "Browse blog posts matching '{$search}' on {$siteName}. Stay updated with insights, tips, and news." 
      : "Discover the latest blog posts, insights, and news from {$siteName}.";
    $keywords = !empty($search) 
      ? "{$search}, blog search, {$siteName} blog, construction news" 
      : "construction blog, URSBID insights, building material news, suppliers, contractors";

    // Canonical URL
    $canonical = url()->current();
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
  <meta name="keywords" content="{{ e($keywords) }}">
  <link rel="canonical" href="{{ $canonical }}">
  <meta name="robots" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">

  <!-- Open Graph -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="{{ $siteName }}">
  <meta property="og:title" content="{{ e($title) }}">
  <meta property="og:description" content="{{ e($description) }}">
  <meta property="og:url" content="{{ $canonical }}">
  <meta property="og:image" content="{{ asset('app1/assets/favicon/android-chrome-512x512.png') }}">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="{{ e($title) }}">
  <meta name="twitter:description" content="{{ e($description) }}">
  <meta name="twitter:image" content="{{ asset('app1/assets/favicon/android-chrome-512x512.png') }}">

  <!-- JSON-LD: WebPage Schema -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": {!! json_encode($title) !!},
    "description": {!! json_encode($description) !!},
    "url": "{{ $canonical }}",
    "publisher": {
      "@type": "Organization",
      "name": "{{ $siteName }}",
      "logo": {
        "@type": "ImageObject",
        "url": "{{ asset('assets/front/img/logo.png') }}"
      }
    }
  }
  </script>

  <!-- Bootstrap 5 + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- DM Sans -->
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

    .hero{padding:22px 0 10px}
    .searchbar{background:#fff;border:1px solid var(--line);border-radius:12px;padding:10px 12px}
    .searchbar .form-control{border:none;box-shadow:none}
    .searchbar .btn{border-radius:10px}

    .card-blog{border:1px solid var(--line); border-radius:12px; overflow:hidden; background:#fff; height:100%;}
    .card-blog img{height:190px; object-fit:cover;}
    .card-blog .meta{color:#64748b; font-size:.9rem}
    .card-blog .title{font-weight:800; font-size:1.05rem; color:#0b1320; margin-top:6px;}
    .card-blog .desc{color:#334155;font-size:.95rem}
    .badge-cat{background:#f1f5f9; color:#475569; border-radius:999px; padding:4px 10px; font-weight:600}

    .widget{background:#fff;border:1px solid var(--line);border-radius:10px;margin-bottom:18px}
    .widget .w-head{padding:14px 16px;border-bottom:1px solid var(--line);font-weight:800}
    .w-body{padding:14px 16px}
    .mini-post{display:flex;gap:12px;margin-bottom:12px}
    .mini-post img{width:92px;height:72px;object-fit:cover;border-radius:6px}

    footer{background:#fff;border-top:1px solid var(--line);color:#6b7280; padding: 2rem 0;}
    .social-icons a { color: #6b7280; margin-right: 15px; font-size: 1.2rem;}
    .social-icons a:hover { color: var(--accent); }
  </style>
</head>
<body>
<!-- Header -->
<header>
  <div class="container container-narrow py-3 d-flex align-items-center justify-content-between">
    <a class="brand fs-4" href="{{ url('/') }}">URSBID</a>
    <nav class="nav d-none d-md-flex">
      <a href="{{ url('/') }}">Home</a>
      <a href="{{ url('/about') }}">About Us</a>
      <a href="{{ url('/blog') }}" class="text-primary">Blog</a>
      <a href="{{ url('/advertise') }}">Advertise</a>
      <a href="{{ url('/contact-detail') }}">Contact Us</a>
    </nav>
    <div class="d-flex align-items-center gap-3">
      <i class="bi bi-person"></i>
      <i class="bi bi-list d-md-none fs-3"></i>
    </div>
  </div>
</header>

<!-- Breadcrumb + Search -->
<section class="hero">
  <div class="container container-narrow">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
      <div class="crumb">
        <a href="{{ url('/') }}">Home</a> &nbsp;/&nbsp; <span>Blog</span>
      </div>
      <form class="ms-auto" method="get" action="{{ route('blog.index') }}" style="min-width:260px;">
        <div class="searchbar d-flex align-items-center gap-2">
          <input type="text" name="q" class="form-control" placeholder="Search posts…" value="{{ $search }}">
          <button class="btn btn-primary"><i class="bi bi-search"></i></button>
        </div>
      </form>
    </div>
  </div>
</section>

<!-- Main Grid -->
<section class="pb-5">
  <div class="container container-narrow">
    <div class="row g-4">
      <!-- Posts -->
      <div class="col-lg-8">
        @if($posts->count() === 0)
          <div class="alert alert-light border">No posts found @if($search) for “<strong>{{ $search }}</strong>” @endif.</div>
        @endif

       <div class="row g-3">
          @foreach($posts as $post)
            @php
              $img = $post->image_url;
              $date = $post->created_at ? \Carbon\Carbon::parse($post->created_at)->format('d M, Y') : '';
              $read = $post->read_minutes;
            @endphp
            <div class="col-md-6">
              <div class="card-blog">
                <a href="{{ url('/blog/'.$post->slug) }}">
                  <img src="{{ $img }}" class="w-100" alt="{{ e($post->title) }}">
                </a>
                <div class="p-3">
                  <div class="d-flex align-items-center justify-content-between">
                    <span class="badge-cat">Blog</span>
                    <span class="meta"><i class="bi bi-clock me-1"></i>{{ $read }} min</span>
                  </div>
                  <a href="{{ url('/blog/'.$post->slug) }}" class="title d-block">
                    {{ \Illuminate\Support\Str::limit($post->title, 80) }}
                  </a>
                  <div class="meta mb-1">
                    @if($date)<i class="bi bi-calendar me-1"></i>{{ $date }}@endif
                  </div>
                  <p class="desc mb-2">{{ $post->excerpt }}</p>
                  <a href="{{ url('/blog/'.$post->slug) }}" class="text-primary fw-semibold">
                    Read More →
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
          {{ $posts->links('pagination::bootstrap-5') }}
        </div>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4">
        @if(!empty($featurePost))
        <div class="widget">
          <div class="w-head">Feature Post</div>
          <div class="w-body">
            <a href="{{ url('/blog/'.$featurePost->slug) }}">
              <img class="w-100 rounded mb-2" src="{{ $featurePost->image_url }}" alt="{{ e($featurePost->title) }}">
            </a>
            <div class="fw-bold">
              <a href="{{ url('/blog/'.$featurePost->slug) }}" class="text-dark text-decoration-none">
                {{ \Illuminate\Support\Str::limit($featurePost->title, 70) }}
              </a>
            </div>
            <div class="small text-secondary mt-1"><i class="bi bi-clock me-1"></i>{{ $featurePost->read_minutes }} Minute Read</div>
            <a class="d-inline-block mt-2 text-decoration-underline" href="{{ url('/blog/'.$featurePost->slug) }}">Read More</a>
          </div>
        </div>
        @endif

        @if(!empty($popularPosts) && $popularPosts->count())
        <div class="widget">
          <div class="w-head">Popular Post</div>
          <div class="w-body">
            @foreach($popularPosts as $p)
              <div class="mini-post">
                <a href="{{ url('/blog/'.$p->slug) }}">
                  <img src="{{ $p->image_url }}" alt="{{ e($p->title) }}">
                </a>
                <div>
                  <div class="fw-semibold">
                    <a href="{{ url('/blog/'.$p->slug) }}" class="text-decoration-none text-dark">
                      {{ \Illuminate\Support\Str::limit($p->title, 60) }}
                    </a>
                  </div>
                  <div class="small text-secondary">
                    <i class="bi bi-clock me-1"></i>{{ $p->read_minutes }} Minute Read
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
            <a class="btn btn-outline-secondary btn-sm" href="https://www.facebook.com/ursbid2025"><i class="bi bi-facebook"></i></a>
            <a class="btn btn-outline-secondary btn-sm" href="https://x.com/URSBID2025"><i class="bi bi-twitter-x"></i></a>
            <a class="btn btn-outline-secondary btn-sm" href="https://www.instagram.com/ursbid2025/"><i class="bi bi-instagram"></i></a>
            <a class="btn btn-outline-secondary btn-sm" href="https://www.youtube.com/@URSBID"><i class="bi bi-youtube"></i></a>
          </div>
        </div>
      </div>
      <!-- /Sidebar -->
    </div>
  </div>
</section>

<!-- Footer -->
<footer>
  <div class="container container-narrow">
    <div class="row gy-3 align-items-center">
      <div class="col-md text-center text-md-start">
        <div class="social-icons">
            <a class="btn btn-outline-secondary btn-sm" href="https://www.facebook.com/ursbid2025"><i class="bi bi-facebook"></i></a>
            <a class="btn btn-outline-secondary btn-sm" href="https://x.com/URSBID2025"><i class="bi bi-twitter-x"></i></a>
            <a class="btn btn-outline-secondary btn-sm" href="https://www.instagram.com/ursbid2025/"><i class="bi bi-instagram"></i></a>
            <a class="btn btn-outline-secondary btn-sm" href="https://www.youtube.com/@URSBID"><i class="bi bi-youtube"></i></a>
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
