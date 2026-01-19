<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;

class TenantController extends Controller
{
    /**
     * Display a listing of active tenants
     */
    public function index(): JsonResponse
    {
        $tenants = Tenant::active()
            ->select('id', 'name', 'slug', 'domain', 'logo', 'primary_color')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $tenants,
            'count' => $tenants->count(),
        ]);
    }

    /**
     * Display the specified tenant
     */
    public function show(string $slug): JsonResponse
    {
        $tenant = Tenant::where('slug', $slug)
            ->orWhere('domain', $slug)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $tenant,
        ]);
    }
}