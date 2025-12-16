<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\HomepageBlogSection;
use App\Models\HomepageHero;
use App\Models\HomepageThreatMapSection;

class HomeController extends Controller
{
    public function index()
    {
        $hero = HomepageHero::first();
        $blog = HomepageBlogSection::first();
        $threatmap = HomepageThreatMapSection::first();

        return view('home.index', compact('hero', 'blog', 'threatmap'));
    }

    public function latestArticles()
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
}
