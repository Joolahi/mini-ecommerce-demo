<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    private function getSessionId(Request $request): string
    {
        // Hae session ID headerista tai luo uusi
        $sessionId = $request->header('X-Session-ID') 
                  ?? Str::uuid()->toString();

        return $sessionId;
    }

    public function index(Request $request): JsonResponse
    {
        $sessionId = $this->getSessionId($request);

        $cartItems = CartItem::with(['product.category', 'tenant'])
            ->forSession($sessionId)
            ->get();

        $cartItems->each(function ($item) {
            $item->subtotal = $item->price * $item->quantity;
        });

        $total = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $itemCount = $cartItems->sum('quantity');

        return response()->json([
            'success' => true,
            'data' => $cartItems,
            'meta' => [
                'session_id' => $sessionId,
                'item_count' => $itemCount,
                'total' => number_format($total, 2, '.', ''),
            ],
        ])->header('X-Session-ID', $sessionId);
    }

    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        $sessionId = $this->getSessionId($request);
        $product = Product::findOrFail($validated['product_id']);

        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available',
            ], 400);
        }

        if ($product->track_inventory && $product->stock < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock',
                'available_stock' => $product->stock,
            ], 400);
        }

        $cartItem = CartItem::forSession($sessionId)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $validated['quantity'];
            $cartItem->save();
        } else {
            $cartItem = CartItem::create([
                'session_id' => $sessionId,
                'tenant_id' => $validated['tenant_id'],
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'price' => $product->price,
            ]);
        }

        $cartItem->load(['product', 'tenant']);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'data' => $cartItem,
        ])->header('X-Session-ID', $sessionId);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $sessionId = $this->getSessionId($request);

        $cartItem = CartItem::forSession($sessionId)
            ->findOrFail($id);

        if ($cartItem->product->track_inventory && 
            $cartItem->product->stock < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock',
                'available_stock' => $cartItem->product->stock,
            ], 400);
        }

        $cartItem->update(['quantity' => $validated['quantity']]);
        $cartItem->load(['product', 'tenant']);

        return response()->json([
            'success' => true,
            'message' => 'Cart item updated',
            'data' => $cartItem,
        ]);
    }

    public function remove(Request $request, int $id): JsonResponse
    {
        $sessionId = $this->getSessionId($request);

        $cartItem = CartItem::forSession($sessionId)
            ->findOrFail($id);

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
        ]);
    }

    public function clear(Request $request): JsonResponse
    {
        $sessionId = $this->getSessionId($request);

        CartItem::forSession($sessionId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared',
        ]);
    }
}