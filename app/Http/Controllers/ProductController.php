<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /* =========================
       PRODUCT LIST PAGE
       /products
    ========================== */
    public function index()
    {
        return view('products.index');
    }

    /* =========================
       PRODUCT DETAIL PAGE
       /products/{slug}
    ========================== */
    public function show(string $slug)
    {
        $product = Product::with(['images' => function ($q) {
            $q->orderByDesc('is_primary')
              ->orderBy('sort_order');
        }])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $product->increment('ai_view_count');

        return view('products.show', compact('product'));
    }

    /* =========================
       API: PRODUCT LIST (PAGINATED)
       /api/products
    ========================== */
    public function apiIndex(Request $request): JsonResponse
    {
        $query = Product::query()
            ->where('is_active', true);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('summary', 'like', "%{$q}%")
                    ->orWhereJsonContains('ai_keywords', $q);
            });
        }

        if ($request->filled('type')) {
            $query->where('product_type', $request->type);
        }

        if ($request->filled('domain')) {
            $query->where('ai_domain', $request->domain);
        }

        if ($request->filled('level')) {
            $query->where('ai_level', $request->level);
        }

        $products = $query
            ->orderByDesc('is_ai_recommended')
            ->orderByDesc('ai_priority')
            ->orderBy('sort_order')
            ->paginate(6);

        return response()->json([
            'data' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    /* =========================
       API: PRODUCT TYPES
       /api/products/types
    ========================== */
    public function apiTypes(): JsonResponse
    {
        $types = Product::query()
            ->where('is_active', true)
            ->select('product_type')
            ->distinct()
            ->orderBy('product_type')
            ->pluck('product_type');

        return response()->json([
            'data' => $types,
        ]);
    }

    /* =========================
       API: AI DOMAINS
       /api/products/domains
    ========================== */
    public function apiDomains(): JsonResponse
    {
        $domains = Product::query()
            ->where('is_active', true)
            ->select('ai_domain')
            ->distinct()
            ->orderBy('ai_domain')
            ->pluck('ai_domain');

        return response()->json([
            'data' => $domains,
        ]);
    }

    /* =========================
       API: RECENT PRODUCTS
       /api/products/recent
    ========================== */
    public function apiRecent(): JsonResponse
    {
        $products = Product::query()
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get([
                'slug',
                'name',
                'product_type',
                'thumbnail',
            ]);

        return response()->json([
            'data' => $products,
        ]);
    }

    /* =========================
       API: TRACK CTA CLICK
       /api/products/{id}/click
    ========================== */
    public function apiTrackClick(int $id): JsonResponse
    {
        Product::where('id', $id)->increment('ai_click_count');

        return response()->json([
            'status' => true,
        ]);
    }
}
