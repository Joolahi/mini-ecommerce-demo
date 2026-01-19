<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\TenantController;

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'services' => [
            'database' => 'connected',
            'redis' => 'connected',
            'meilisearch' => 'connected',
        ],
        'version' => '1.0.0',
    ]);
});

// Public API routes (no authentication required for demo)
Route::prefix('v1')->group(function () {
    
    // Tenant routes
    Route::get('/tenants', [TenantController::class, 'index']);
    Route::get('/tenants/{slug}', [TenantController::class, 'show']);
    
    // Product routes
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{slug}', [ProductController::class, 'show']);    
    
    // Category routes
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);
    Route::get('/categories/{slug}/products', [CategoryController::class, 'products']);
    
    // Cart routes
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/add', [CartController::class, 'add']);
        Route::put('/update/{id}', [CartController::class, 'update']);
        Route::delete('/remove/{id}', [CartController::class, 'remove']);
        Route::delete('/clear', [CartController::class, 'clear']);
    });
});

Route::prefix('test')->group(function () {
    Route::get('/db', function () {
        try {
            \DB::connection()->getPdo();
            return response()->json(['database' => 'connected']);
        } catch (\Exception $e) {
            return response()->json(['database' => 'failed', 'error' => $e->getMessage()], 500);
        }
    });
    
    Route::get('/redis', function () {
        try {
            \Cache::put('test', 'value', 60);
            $value = \Cache::get('test');
            return response()->json(['redis' => 'connected', 'test_value' => $value]);
        } catch (\Exception $e) {
            return response()->json(['redis' => 'failed', 'error' => $e->getMessage()], 500);
        }
    });
    
    Route::get('/meilisearch', function () {
        try {
            $results = \App\Models\Product::search('*')->take(1)->get();
            return response()->json(['meilisearch' => 'connected', 'indexed_products' => $results->count()]);
        } catch (\Exception $e) {
            return response()->json(['meilisearch' => 'failed', 'error' => $e->getMessage()], 500);
        }
    });
});