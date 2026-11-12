<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    /**
     * List all active products with category and total stock.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()->with('category:id,name');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $products = $query->paginate($request->input('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => $products,
        ]);
    }

    /**
     * Show single product details.
     */
    public function show(Product $product): JsonResponse
    {
        $product->load(['category', 'unitOfMeasure']);

        return response()->json([
            'status' => 'success',
            'data' => $product,
        ]);
    }
}
