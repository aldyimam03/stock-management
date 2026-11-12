<?php

use App\Models\Customer;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\Shipment;
use App\Models\StockBalance;
use App\Models\StockMovement;
use App\Models\StockOpname;
use App\Models\StockOpnameItem;
use App\Models\Supplier;
use App\Models\UnitOfMeasure;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('GoodsReceiptItem handles partial fulfillment accurately', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $supplier = Supplier::factory()->create();
    $warehouse = Warehouse::factory()->create();
    $product = Product::factory()->create(['cost_price' => 12000]);

    $po = PurchaseOrder::create([
        'supplier_id' => $supplier->id,
        'warehouse_id' => $warehouse->id,
        'order_date' => now(),
        'expected_date' => now()->addDays(5),
        'status' => 'confirmed',
        'created_by' => $user->id,
    ]);

    PurchaseOrderItem::create([
        'purchase_order_id' => $po->id,
        'product_id' => $product->id,
        'quantity' => 100,
        'unit_price' => 12000,
    ]);

    // Penerimaan Parsial Tahap 1: hanya 40 barang yang sampai
    $gr = GoodsReceipt::create([
        'purchase_order_id' => $po->id,
        'warehouse_id' => $warehouse->id,
        'received_date' => now(),
        'status' => 'pending',
        'created_by' => $user->id,
    ]);

    GoodsReceiptItem::create([
        'goods_receipt_id' => $gr->id,
        'product_id' => $product->id,
        'quantity' => 40,
        'unit_price' => 12000,
    ]);

    $gr->update(['status' => 'completed']);

    $balance = StockBalance::where('product_id', $product->id)
        ->where('warehouse_id', $warehouse->id)
        ->first();

    expect((float) $balance->quantity)->toBe(40.0);

    $poItem = PurchaseOrderItem::where('purchase_order_id', $po->id)->first();
    expect((float) $poItem->received_quantity)->toBe(40.0);
});

test('SalesOrder auto-reserves stock on confirmed and releases stock on cancelled', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $customer = Customer::factory()->create();
    $warehouse = Warehouse::factory()->create();
    $product = Product::factory()->create(['cost_price' => 10000, 'selling_price' => 20000]);

    // Beri stok awal 50
    $stockService = app(StockService::class);
    $stockService->addStock(
        productId: $product->id,
        warehouseId: $warehouse->id,
        quantity: 50,
        unitPrice: 10000,
        userId: $user->id
    );

    $so = SalesOrder::create([
        'customer_id' => $customer->id,
        'warehouse_id' => $warehouse->id,
        'order_date' => now(),
        'expected_delivery_date' => now()->addDays(2),
        'status' => 'draft',
        'created_by' => $user->id,
    ]);

    SalesOrderItem::create([
        'sales_order_id' => $so->id,
        'product_id' => $product->id,
        'quantity' => 15,
        'unit_price' => 20000,
    ]);

    // Saat status dikonfirmasi -> reserved stock harus bertambah 15
    $so->update(['status' => 'confirmed']);

    $balance = StockBalance::where('product_id', $product->id)
        ->where('warehouse_id', $warehouse->id)
        ->first();

    expect((float) $balance->reserved_quantity)->toBe(15.0)
        ->and((float) $balance->available_quantity)->toBe(35.0);

    // Saat status dibatalkan -> reserved stock harus kembali ke 0
    $so->update(['status' => 'cancelled']);
    $balance->refresh();

    expect((float) $balance->reserved_quantity)->toBe(0.0)
        ->and((float) $balance->available_quantity)->toBe(50.0);
});

test('PurchaseReturn completed status deducts stock and records return movement', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $supplier = Supplier::factory()->create();
    $warehouse = Warehouse::factory()->create();
    $product = Product::factory()->create(['cost_price' => 20000]);

    // Stok awal 30
    $stockService = app(StockService::class);
    $stockService->addStock(
        productId: $product->id,
        warehouseId: $warehouse->id,
        quantity: 30,
        unitPrice: 20000,
        userId: $user->id
    );

    $po = PurchaseOrder::create([
        'supplier_id' => $supplier->id,
        'warehouse_id' => $warehouse->id,
        'order_date' => now(),
        'status' => 'received',
        'created_by' => $user->id,
    ]);

    $gr = GoodsReceipt::create([
        'purchase_order_id' => $po->id,
        'warehouse_id' => $warehouse->id,
        'received_date' => now(),
        'status' => 'completed',
        'created_by' => $user->id,
    ]);

    $pr = PurchaseReturn::create([
        'goods_receipt_id' => $gr->id,
        'warehouse_id' => $warehouse->id,
        'return_date' => now(),
        'status' => 'pending',
        'created_by' => $user->id,
    ]);

    PurchaseReturnItem::create([
        'purchase_return_id' => $pr->id,
        'product_id' => $product->id,
        'quantity' => 5,
        'unit_price' => 20000,
    ]);

    // Selesaikan retur ke supplier
    $pr->update(['status' => 'completed']);

    $balance = StockBalance::where('product_id', $product->id)
        ->where('warehouse_id', $warehouse->id)
        ->first();

    // 30 - 5 = 25
    expect((float) $balance->quantity)->toBe(25.0);

    $movement = StockMovement::where('product_id', $product->id)
        ->where('reference_type', PurchaseReturn::class)
        ->where('type', 'return')
        ->first();

    expect($movement)->not->toBeNull()
        ->and((float) $movement->quantity)->toBe(5.0);
});

test('SalesReturn completed status adds stock and records return movement', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $customer = Customer::factory()->create();
    $warehouse = Warehouse::factory()->create();
    $product = Product::factory()->create(['cost_price' => 10000, 'selling_price' => 18000]);

    $stockService = app(StockService::class);
    $stockService->addStock(
        productId: $product->id,
        warehouseId: $warehouse->id,
        quantity: 10,
        unitPrice: 10000,
        userId: $user->id
    );

    $so = SalesOrder::create([
        'customer_id' => $customer->id,
        'warehouse_id' => $warehouse->id,
        'order_date' => now(),
        'status' => 'delivered',
        'created_by' => $user->id,
    ]);

    $shipment = Shipment::create([
        'sales_order_id' => $so->id,
        'warehouse_id' => $warehouse->id,
        'shipped_date' => now(),
        'status' => 'delivered',
        'created_by' => $user->id,
    ]);

    $sr = SalesReturn::create([
        'shipment_id' => $shipment->id,
        'warehouse_id' => $warehouse->id,
        'return_date' => now(),
        'status' => 'pending',
        'created_by' => $user->id,
    ]);

    SalesReturnItem::create([
        'sales_return_id' => $sr->id,
        'product_id' => $product->id,
        'quantity' => 4,
        'unit_price' => 18000,
    ]);

    // Selesaikan retur dari customer
    $sr->update(['status' => 'completed']);

    $balance = StockBalance::where('product_id', $product->id)
        ->where('warehouse_id', $warehouse->id)
        ->first();

    // 10 + 4 = 14
    expect((float) $balance->quantity)->toBe(14.0);

    $movement = StockMovement::where('product_id', $product->id)
        ->where('reference_type', SalesReturn::class)
        ->where('type', 'return')
        ->first();

    expect($movement)->not->toBeNull()
        ->and((float) $movement->quantity)->toBe(4.0);
});

test('StockOpname completing adjusts physical quantity and logs adjustment movement', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $warehouse = Warehouse::factory()->create();
    $product = Product::factory()->create(['cost_price' => 50000]);

    // Stok di sistem tercatat 100
    $stockService = app(StockService::class);
    $stockService->addStock(
        productId: $product->id,
        warehouseId: $warehouse->id,
        quantity: 100,
        unitPrice: 50000,
        userId: $user->id
    );

    $opname = StockOpname::create([
        'warehouse_id' => $warehouse->id,
        'opname_date' => now(),
        'status' => 'in_progress',
        'created_by' => $user->id,
    ]);

    // Ternyata fisik di lapangan ditemukan 92 (selisih -8)
    StockOpnameItem::create([
        'stock_opname_id' => $opname->id,
        'product_id' => $product->id,
        'system_quantity' => 100,
        'physical_quantity' => 92,
        'difference' => -8,
    ]);

    // Selesaikan opname
    $opname->update(['status' => 'completed']);

    $balance = StockBalance::where('product_id', $product->id)
        ->where('warehouse_id', $warehouse->id)
        ->first();

    expect((float) $balance->quantity)->toBe(92.0);

    $movement = StockMovement::where('product_id', $product->id)
        ->where('reference_type', StockOpname::class)
        ->where('type', 'adjustment')
        ->first();

    expect($movement)->not->toBeNull()
        ->and((float) $movement->quantity)->toBe(-8.0);
});

test('Product belongs to UnitOfMeasure properly', function () {
    $uom = UnitOfMeasure::create([
        'code' => 'BOX',
        'name' => 'Box',
        'is_active' => true,
    ]);

    $product = Product::factory()->create([
        'unit_of_measure_id' => $uom->id,
        'unit' => 'box',
    ]);

    expect($product->unitOfMeasure->id)->toBe($uom->id)
        ->and($product->unitOfMeasure->code)->toBe('BOX');
});
