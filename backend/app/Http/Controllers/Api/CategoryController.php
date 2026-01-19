<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index(Request $request): JsonResponse
    {
        $query = Category::with(['children', 'tenant:id,name,slug'])
            ->active();

        // Filter by tenant
        if ($request->has('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        if ($request->get('root_only', false)) {
            $query->root();
        }

        $categories = $query->orderBy('sort_order')->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
            'count' => $categories->count(),
        ]);
    }

    /**
     * Display the specified category
     */
    public function show(string $slug): JsonResponse
    {
        $category = Category::with(['children', 'parent', 'tenant'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $category,
        ]);
    }

    /**
     * Get products for a specific category
     */
    public function products(Request $request, string $slug): JsonResponse
    {
        $category = Category::where('slug', $slug)
            ->active()
            ->firstOrFail();

        $query = $category->products()
            ->with(['tenant:id,name,slug'])
            ->active();


        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ],
            'data' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }
}