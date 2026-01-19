<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['category:id,name,slug', 'tenant:id,name,slug'])
            ->active();

        if ($request->has('tenant_id')) {
            $query->forTenant($request->tenant_id);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('featured')) {
            $query->where('is_featured', true);
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    /**
     * Display the specified product
     */
    public function show(string $slug): JsonResponse
    {
        $product = Product::with(['category', 'tenant'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    /**
     * Search products using Meilisearch
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Search query is required',
            ], 400);
        }

        $products = Product::search($query)
            ->where('is_active', true)
            ->take(50)
            ->get();

        $products->load(['category:id,name,slug', 'tenant:id,name,slug']);

        return response()->json([
            'success' => true,
            'data' => $products,
            'query' => $query,
            'count' => $products->count(),
        ]);
    }
}