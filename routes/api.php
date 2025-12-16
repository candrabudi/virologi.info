<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/article-categories', fn () => response()->json([
    'data' => App\Models\ArticleCategory::orderBy('name')->get(),
])
);

Route::get('/article-tags', fn () => response()->json([
    'data' => App\Models\ArticleTag::orderBy('name')->get(),
])
);

Route::get('/articles/recent', fn () => response()->json([
    'data' => App\Models\Article::where('is_published', true)
        ->orderByDesc('published_at')
        ->limit(5)
        ->get(['title', 'slug', 'thumbnail', 'published_at'])
        ->map(fn ($a) => [
            'title' => $a->title,
            'slug' => $a->slug,
            'thumbnail' => $a->thumbnail,
            'published_at' => $a->published_at->format('d M Y'),
        ]),
])
);

Route::get('/articles', function (Request $request) {
    $q = $request->q;

    $articles = App\Models\Article::with('categories')
        ->where('is_published', true)
        ->when($q, fn ($query) => $query->where('title', 'like', "%$q%")
                  ->orWhere('excerpt', 'like', "%$q%")
        )
        ->orderByDesc('published_at')
        ->paginate(5);

    return response()->json([
        'data' => $articles->map(fn ($a) => [
            'title' => $a->title,
            'slug' => $a->slug,
            'excerpt' => $a->excerpt,
            'thumbnail' => $a->thumbnail,
            'published_at' => $a->published_at->format('d M Y'),
            'categories' => $a->categories->pluck('name')->toArray(),
        ]),
        'meta' => [
            'current_page' => $articles->currentPage(),
            'last_page' => $articles->lastPage(),
        ],
    ]);
});
