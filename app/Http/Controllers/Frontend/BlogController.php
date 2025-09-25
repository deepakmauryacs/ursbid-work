<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Show blog list page.
     */
    public function index(Request $request)
    {
        $search = trim($request->get('q', ''));
    
        // ============ POSTS (list + search + paginate) ============
        $posts = DB::table('blogs')
            ->select(['id','title','slug','image','description','created_at','meta_keywords','status'])
            ->where('status', '1')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('title', 'like', "%{$search}%")
                      ->orWhere('meta_keywords', 'like', "%{$search}%")
                      ->orWhere('meta_description', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();
    
        // Enrich paginated items so your Blade can use image_url / read_minutes / excerpt
        $posts->through(function ($p) {
            // image_url
            $p->image_url = empty($p->image)
                ? asset('public/uploads/placeholder/blog-placeholder.jpg')
                : (Str::startsWith($p->image, ['http://','https://']) ? $p->image : asset('public/'.$p->image));
    
            // ~200 wpm read time
            $p->read_minutes = max(1, (int) ceil(str_word_count(strip_tags($p->description ?? '')) / 200));
    
            // short description
            $p->excerpt = Str::limit(strip_tags($p->description ?? ''), 160);
    
            return $p;
        });
    
        // ============ FEATURE POST (latest one) ============
        $featurePost = DB::table('blogs')
            ->select(['id','title','slug','image','created_at','description'])
            ->where('status', '1')
            ->orderByDesc('created_at')
            ->first();
    
        if ($featurePost) {
            $featurePost->image_url = empty($featurePost->image)
                ? asset('public/uploads/placeholder/blog-placeholder.jpg')
                : (Str::startsWith($featurePost->image, ['http://','https://']) ? $featurePost->image : asset('public/'.$featurePost->image));
            $featurePost->read_minutes = max(1, (int) ceil(str_word_count(strip_tags($featurePost->description ?? '')) / 200));
        }
    
        // ============ POPULAR POSTS (top 5 recent; swap to views when available) ============
        $popularPosts = DB::table('blogs')
            ->select(['id','title','slug','image','created_at','description'])
            ->where('status', '1')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($p) {
                $p->image_url = empty($p->image)
                    ? asset('public/uploads/placeholder/blog-placeholder.jpg')
                    : (Str::startsWith($p->image, ['http://','https://']) ? $p->image : asset('public/'.$p->image));
                $p->read_minutes = max(1, (int) ceil(str_word_count(strip_tags($p->description ?? '')) / 200));
                return $p;
            });
    
        return view('frontend.blog', compact('posts','featurePost','popularPosts','search'));
    }

    
}
