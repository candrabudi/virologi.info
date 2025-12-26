<?php

namespace App\Http\Controllers;

use App\Models\CyberSecurityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CyberSecurityServiceController extends Controller
{
    /* =========================
       LIST PAGE
       /services
    ========================== */
    public function index()
    {
        return view('services.index');
    }

    /* =========================
       DETAIL PAGE
       /services/{slug}
    ========================== */
    public function show(string $slug)
    {
        $service = CyberSecurityService::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('services.show', compact('service'));
    }

    /* =========================
       API: LIST SERVICES
       /api/services
    ========================== */
    public function apiIndex(Request $request): JsonResponse
    {
        $query = CyberSecurityService::query()
            ->where('is_active', true);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('summary', 'like', "%{$q}%")
                    ->orWhereJsonContains('ai_keywords', $q);
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $services = $query
            ->orderBy('sort_order')
            ->paginate(6);

        return response()->json([
            'data' => $services->items(),
            'meta' => [
                'current_page' => $services->currentPage(),
                'last_page' => $services->lastPage(),
                'total' => $services->total(),
            ],
        ]);
    }

    /* =========================
       API: CATEGORIES
       /api/services/categories
    ========================== */
    public function apiCategories(): JsonResponse
    {
        $categories = CyberSecurityService::query()
            ->where('is_active', true)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return response()->json([
            'data' => $categories,
        ]);
    }

    /* =========================
       API: RECENT SERVICES
       /api/services/recent
    ========================== */
    public function apiRecent(): JsonResponse
    {
        $services = CyberSecurityService::query()
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get([
                'slug',
                'name',
                'category',
            ]);

        return response()->json([
            'data' => $services,
        ]);
    }
}
