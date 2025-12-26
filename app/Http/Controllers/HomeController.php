<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Ebook;
use App\Models\HomepageBlogSection;
use App\Models\HomepageHero;
use App\Models\HomepageThreatMapSection;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function index()
    {
        $hero = HomepageHero::first();
        $blog = HomepageBlogSection::first();
        $threatmap = HomepageThreatMapSection::first();

        return view('home.index', compact('hero', 'blog', 'threatmap'));
    }

    /* =========================
       FETCH LATEST ARTICLES
    ========================== */
    public function latestArticles(): JsonResponse
    {
        $articles = Article::with(['categories:id,name,slug', 'tags:id,name,slug'])
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->limit(12)
            ->get()
            ->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'excerpt' => $article->excerpt,
                    'thumbnail' => $article->thumbnail,
                    'published_at' => $article->published_at?->toDateTimeString(),
                    'categories' => $article->categories->map(fn ($c) => [
                        'id' => $c->id,
                        'name' => $c->name,
                        'slug' => $c->slug,
                    ]),
                    'tags' => $article->tags->map(fn ($t) => [
                        'id' => $t->id,
                        'name' => $t->name,
                        'slug' => $t->slug,
                    ]),
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $articles,
        ]);
    }

    /* =========================
       FETCH PRODUCTS (HOMEPAGE)
    ========================== */
    public function homepageProducts(): JsonResponse
    {
        $products = Product::query()
            ->where('is_active', true)
            ->where('is_ai_visible', true)
            ->orderByDesc('is_ai_recommended')
            ->orderByDesc('ai_priority')
            ->orderBy('sort_order')
            ->limit(12)
            ->get([
                'id',
                'slug',
                'name',
                'subtitle',
                'summary',
                'product_type',
                'ai_domain',
                'thumbnail',
                'cta_label',
                'cta_url',
                'cta_type',
            ]);

        return response()->json([
            'status' => true,
            'data' => $products,
        ]);
    }

    /* =========================
       FETCH EBOOKS (HOMEPAGE)
    ========================== */
    public function homepageEbooks(): JsonResponse
    {
        $ebooks = Ebook::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->limit(12)
            ->get([
                'id',
                'uuid',
                'slug',
                'title',
                'subtitle',
                'level',
                'topic',
                'cover_image',
                'page_count',
                'author',
                'published_at',
            ])
            ->map(function ($ebook) {
                return [
                    'id' => $ebook->id,
                    'uuid' => $ebook->uuid,
                    'slug' => $ebook->slug,
                    'title' => $ebook->title,
                    'subtitle' => $ebook->subtitle,
                    'level' => $ebook->level,
                    'topic' => $ebook->topic,
                    'cover_image' => $ebook->cover_image,
                    'page_count' => $ebook->page_count,
                    'author' => $ebook->author,
                    'published_at' => $ebook->published_at?->toDateString(),
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $ebooks,
        ]);
    }
}
