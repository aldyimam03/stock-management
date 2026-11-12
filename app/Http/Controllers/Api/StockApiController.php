<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StockBalance;
use App\Models\StockMovement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockApiController extends Controller
{
    /**
     * Get stock balances grouped or filtered by warehouse/product.
     */
    public function balances(Request $request): JsonResponse
    {
        $query = StockBalance::query()->with(['product:id,name,slug,cost_price,selling_price', 'warehouse:id,name,code']);

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->input('warehouse_id'));
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }

        $balances = $query->paginate($request->input('per_page', 20));

        return response()->json([
            'status' => 'success',
            'data' => $balances,
        ]);
    }

    /**
     * Get stock movement audit trail.
     */
    public function movements(Request $request): JsonResponse
    {
        $query = StockMovement::query()
            ->with(['product:id,name,slug', 'warehouse:id,name', 'creator:id,name'])
            ->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->input('warehouse_id'));
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }

        $movements = $query->paginate($request->input('per_page', 25));

        return response()->json([
            'status' => 'success',
            'data' => $movements,
        ]);
    }
}
