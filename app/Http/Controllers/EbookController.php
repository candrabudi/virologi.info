<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EbookController extends Controller
{
    /* =========================
       EBOOK LIST PAGE
       /ebooks
    ========================== */
    public function index()
    {
        return view('ebooks.index');
    }

    /* =========================
       EBOOK DETAIL PAGE
       /ebooks/{slug}
    ========================== */
    public function show(string $slug)
    {
        $ebook = Ebook::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('ebooks.show', compact('ebook'));
    }

    /* =========================
       API: EBOOK LIST (PAGINATED)
       /api/ebooks
    ========================== */
    public function apiIndex(Request $request): JsonResponse
    {
        $query = Ebook::query()
            ->where('is_active', true);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('subtitle', 'like', "%{$q}%")
                    ->orWhere('summary', 'like', "%{$q}%")
                    ->orWhereJsonContains('ai_keywords', $q);
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('topic')) {
            $query->where('topic', $request->topic);
        }

        $ebooks = $query
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->paginate(6);

        return response()->json([
            'data' => $ebooks->items(),
            'meta' => [
                'current_page' => $ebooks->currentPage(),
                'last_page' => $ebooks->lastPage(),
                'per_page' => $ebooks->perPage(),
                'total' => $ebooks->total(),
            ],
        ]);
    }

    /* =========================
       API: EBOOK LEVELS
       /api/ebooks/levels
    ========================== */
    public function apiLevels(): JsonResponse
    {
        $levels = Ebook::query()
            ->where('is_active', true)
            ->select('level')
            ->distinct()
            ->orderBy('level')
            ->pluck('level');

        return response()->json([
            'data' => $levels,
        ]);
    }

    /* =========================
       API: EBOOK TOPICS
       /api/ebooks/topics
    ========================== */
    public function apiTopics(): JsonResponse
    {
        $topics = Ebook::query()
            ->where('is_active', true)
            ->select('topic')
            ->distinct()
            ->orderBy('topic')
            ->pluck('topic');

        return response()->json([
            'data' => $topics,
        ]);
    }

    /* =========================
       API: RECENT EBOOKS
       /api/ebooks/recent
    ========================== */
    public function apiRecent(): JsonResponse
    {
        $ebooks = Ebook::query()
            ->where('is_active', true)
            ->orderByDesc('published_at')
            ->limit(5)
            ->get([
                'slug',
                'title',
                'cover_image',
                'level',
                'topic',
            ]);

        return response()->json([
            'data' => $ebooks,
        ]);
    }
}
