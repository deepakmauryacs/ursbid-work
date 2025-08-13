@extends('frontend.layouts.app')

@section('content')
<style>
    :root{
      --ink:#0b1320;
      --muted:#6b7280;
      --soft:#94a3b8;
      --line:#e6e9ef;
      --card:#ffffff;
      --bg:#f5f7fb;
      --accent:#3b82f6;
    }
    html, body, h1, h2, h3, h4, h5, h6, .btn, .nav, .form-control, .badge, .card, .breadcrumb, .small, .lead {
      font-family: "DM Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
    }
    body{color:var(--ink);background:var(--bg);}
    a{text-decoration:none}
    .container-narrow{max-width:1120px}
    .crumb{font-size:.9rem;color:var(--soft)}
    .crumb a{color:#718198}
    .crumb a:hover{color:var(--ink)}
    .hero-img{width:100%;border-radius:8px;object-fit:cover;height:420px;background:#000}
    @media (max-width:767.98px){.hero-img{height:260px}}
    .meta{color:#475569;font-size:.92rem}
    .meta .dot{width:4px;height:4px;border-radius:50%;background:#cbd5e1;display:inline-block;margin:0 10px}
    .post-wrap{background:#fff;border:1px solid var(--line);border-radius:10px;padding:28px}
    .post-title{font-weight:800;font-size:2rem;line-height:1.25;margin:14px 0 16px}
    .post p{color:#334155;line-height:1.9;margin-bottom:1.1rem;font-weight:400}
    .post h2,.post h3{font-weight:800;color:#0b1320;margin:1.6rem 0 .6rem}
    .tag-share{border-top:1px solid var(--line);border-bottom:1px solid var(--line);padding:14px 0;margin:22px 0}
    .tags a{display:inline-block;background:#f1f5f9;border:1px solid #e2e8f0;color:#475569;padding:6px 10px;border-radius:999px;margin:4px 6px 0 0;font-size:.85rem;font-weight:500}
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
    .btn-accent{background:var(--accent);color:#fff;border:0;font-weight:700}
    .btn-accent:hover{filter:brightness(.95);color:#fff}
</style>

<section class="py-2">
  <div class="container container-narrow">
    <div class="crumb">
      <a href="{{ url('/') }}">Home</a> &nbsp;/&nbsp; <a href="#">Blog</a> &nbsp;/&nbsp; <span>{{ $blog->title }}</span>
    </div>
  </div>
</section>

<section class="pb-5">
  <div class="container container-narrow">
    <div class="row g-4">
      <div class="col-lg-8">
        <img class="hero-img mb-3" src="{{ $blog->image ? asset('public/'.$blog->image) : asset('public/uploads/placeholder/blog-placeholder.jpg') }}" alt="{{ $blog->title }}" />

        <div class="meta d-flex flex-wrap align-items-center mb-2">
          <span>by <strong>Admin</strong></span>
          <span class="dot"></span><span>{{ $formattedDate }}</span>
          <span class="dot"></span><i class="bi bi-clock me-1"></i> 3 Minute Read
          <span class="dot"></span><i class="bi bi-eye me-1"></i> 0 views
        </div>

        <div class="post-wrap">
          <h1 class="post-title">{{ $blog->title }}</h1>

          <article class="post">
            {!! $blog->description !!}
          </article>

          @php
            $tags = array_filter(array_map('trim', explode(',', $blog->meta_keywords ?? '')));
          @endphp
          <div class="tag-share d-flex flex-wrap align-items-center justify-content-between">
            <div class="tags">
              <strong class="me-2">Tags:</strong>
              @forelse($tags as $tag)
                <a href="#">{{ $tag }}</a>
              @empty
                <span class="text-muted">No tags</span>
              @endforelse
            </div>
            <div class="share">
              <strong class="me-2">Share</strong>
              <a href="#"><i class="bi bi-facebook"></i></a>
              <a href="#"><i class="bi bi-twitter-x"></i></a>
              <a href="#"><i class="bi bi-linkedin"></i></a>
            </div>
          </div>

          <div class="author">
            <img class="avatar" src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?q=80&w=400&auto=format&fit=crop" alt="author">
            <div>
              <div class="name">Admin</div>
              <p class="mb-2 small text-secondary">Sharing insights and updates from URSBID.</p>
              <div class="links">
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-twitter-x"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-youtube"></i></a>
              </div>
            </div>
          </div>

          @if($comments->count())
            <div class="mt-4">
              <h5 class="fw-bold mb-3">Comments</h5>
              @foreach($comments as $c)
                <div class="mb-3 border-bottom pb-2">
                  <div class="fw-bold">{{ $c->name }}</div>
                  <div class="small text-muted">{{ date('d-m-Y', strtotime($c->created_at)) }}</div>
                  <p class="mb-0">{{ $c->message }}</p>
                </div>
              @endforeach
            </div>
          @endif

          <div class="row mt-4">
            <div class="col-md-12">
              <div class="card p-3">
                <h5 class="mb-3">Leave a Comment</h5>
                <form id="commentForm">
                  @csrf
                  <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control">
                    <div class="invalid-feedback"></div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control">
                    <div class="invalid-feedback"></div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Comment</label>
                    <textarea name="message" class="form-control" rows="4"></textarea>
                    <div class="invalid-feedback"></div>
                  </div>
                  <button type="submit" class="btn btn-accent">Submit</button>
                </form>
              </div>
            </div>
          </div>

        </div>
      </div>

      <div class="col-lg-4">
        @if($featurePost)
        <div class="widget">
          <div class="w-head">Feature Post</div>
          <div class="w-body">
            <img class="w-100 rounded mb-2" src="{{ $featurePost->image ? asset('public/'.$featurePost->image) : asset('public/uploads/placeholder/blog-placeholder.jpg') }}" alt="">
            <div class="small text-secondary mb-1"><i class="bi bi-clock me-1"></i>3 Minute Read</div>
            <div class="fw-bold">{{ \Illuminate\Support\Str::limit($featurePost->title, 60) }}</div>
            <div class="small mt-1">by <strong>Admin</strong></div>
            <a class="d-inline-block mt-2 text-decoration-underline" href="{{ url('/blog/'.$featurePost->slug) }}">Read More</a>
          </div>
        </div>
        @endif

        @if($popularPosts->count())
        <div class="widget">
          <div class="w-head">Popular Post</div>
          <div class="w-body">
            @foreach($popularPosts as $p)
            <div class="mini-post">
              <img src="{{ $p->image ? asset('public/'.$p->image) : asset('public/uploads/placeholder/blog-placeholder.jpg') }}" alt="">
              <div>
                <div class="fw-semibold"><a href="{{ url('/blog/'.$p->slug) }}" class="text-decoration-none text-dark">{{ \Illuminate\Support\Str::limit($p->title, 40) }}</a></div>
                <div class="small text-secondary">3 Minute Read</div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
        @endif

        @if($categories->count())
        <div class="widget">
          <div class="w-head">Browse Category</div>
          <div class="w-body cat-list">
            @foreach($categories as $c)
            <a href="#"><span>{{ $c->name }}</span><span>({{ $c->total }})</span></a>
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
    </div>
  </div>
</section>

@if($relatedPosts->count())
<section class="py-4">
  <div class="container container-narrow">
    <h4 class="fw-bold mb-3">You Might Like This</h4>
    <div class="row g-3 related">
      @foreach($relatedPosts as $r)
      <div class="col-md-3 col-6">
        <div class="card h-100">
          <img src="{{ $r->image ? asset('public/'.$r->image) : asset('public/uploads/placeholder/blog-placeholder.jpg') }}" class="card-img-top" alt="">
          <div class="card-body">
            <div class="badge mb-2">Blog</div>
            <div class="card-title">{{ \Illuminate\Support\Str::limit($r->title, 40) }}</div>
            <div class="small text-secondary">By Admin · {{ $formattedDate }}</div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif
@endsection

@push('scripts')
<script>
$('#commentForm').on('submit', function(e){
    e.preventDefault();
    var form = $(this);
    form.find('.invalid-feedback').text('');
    form.find('.form-control').removeClass('is-invalid');

    var hasError = false;
    form.find('input[name="name"], input[name="email"], textarea[name="message"]').each(function(){
        if(!$(this).val()){
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').text('This field is required');
            hasError = true;
        }
    });
    if(hasError){
        return;
    }

    $.ajax({
        url: '{{ url('/blog/'.$blog->slug.'/comment') }}',
        method: 'POST',
        data: form.serialize(),
        success: function(res){
            if(res.status){
                form[0].reset();
                location.reload();
            }
        },
        error: function(xhr){
            if(xhr.status === 422){
                var errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(function(key){
                    var input = form.find('[name='+key+']');
                    input.addClass('is-invalid');
                    input.next('.invalid-feedback').text(errors[key][0]);
                });
            }
        }
    });
});
</script>
@endpush
