<?php

use App\Models\Customer;
use App\Models\GoodsReceipt;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Shipment;
use App\Models\StockBalance;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\Transfer;
use App\Models\TransferItem;
use App\Models\User;
use App\Models\Warehouse;
use App\Policies\TransferPolicy;
use App\Services\NumberSeriesService;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('NumberSeriesService generates sequential document numbers properly', function () {
    $num1 = NumberSeriesService::generate('TEST', 'TST');
    $num2 = NumberSeriesService::generate('TEST', 'TST');

    expect($num1)->toBe('TST-000001')
        ->and($num2)->toBe('TST-000002');
});

test('PurchaseOrder automatically generates order number and recalculates total from items', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $supplier = Supplier::factory()->create();
    $warehouse = Warehouse::factory()->create();
    $product1 = Product::factory()->create(['cost_price' => 10000]);
    $product2 = Product::factory()->create(['cost_price' => 25000]);

    $po = PurchaseOrder::create([
        'supplier_id' => $supplier->id,
        'warehouse_id' => $warehouse->id,
        'order_date' => now(),
        'expected_date' => now()->addDays(5),
        'status' => 'draft',
        'created_by' => $user->id,
    ]);

    expect($po->order_number)->toStartWith('PO-');

    PurchaseOrderItem::create([
        'purchase_order_id' => $po->id,
        'product_id' => $product1->id,
        'quantity' => 5,
        'unit_price' => 10000,
    ]);

    PurchaseOrderItem::create([
        'purchase_order_id' => $po->id,
        'product_id' => $product2->id,
        'quantity' => 2,
        'unit_price' => 25000,
    ]);

    $po->refresh();
    // 5 * 10,000 = 50,000 + 2 * 25,000 = 50,000 => Total: 100,000
    expect((float) $po->total_amount)->toBe(100000.0);
});

test('Completing GoodsReceipt adds stock balance, syncs product total, and records stock movement', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $supplier = Supplier::factory()->create();
    $warehouse = Warehouse::factory()->create();
    $product = Product::factory()->create(['cost_price' => 15000, 'stock_quantity' => 0]);

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
        'quantity' => 20,
        'unit_price' => 15000,
    ]);

    $gr = GoodsReceipt::create([
        'purchase_order_id' => $po->id,
        'warehouse_id' => $warehouse->id,
        'received_date' => now(),
        'status' => 'pending',
        'created_by' => $user->id,
    ]);

    expect($gr->receipt_number)->toStartWith('GR-');

    // Selesaikan GoodsReceipt
    $gr->update(['status' => 'completed']);

    $balance = StockBalance::where('product_id', $product->id)
        ->where('warehouse_id', $warehouse->id)
        ->first();

    expect($balance)->not->toBeNull()
        ->and((float) $balance->quantity)->toBe(20.0)
        ->and((float) $balance->average_cost)->toBe(15000.0);

    $product->refresh();
    expect($product->stock_quantity)->toBe(20);

    $movement = StockMovement::where('product_id', $product->id)
        ->where('warehouse_id', $warehouse->id)
        ->where('type', 'in')
        ->first();

    expect($movement)->not->toBeNull()
        ->and((float) $movement->quantity)->toBe(20.0)
        ->and((float) $movement->unit_price)->toBe(15000.0);

    $po->refresh();
    expect($po->status)->toBe('received');
});

test('Shipment delivery deducts stock and records stock movement', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $customer = Customer::factory()->create();
    $warehouse = Warehouse::factory()->create();
    $product = Product::factory()->create(['cost_price' => 10000, 'selling_price' => 20000]);

    // Beri stok awal 50 unit
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
        'status' => 'confirmed',
        'created_by' => $user->id,
    ]);

    SalesOrderItem::create([
        'sales_order_id' => $so->id,
        'product_id' => $product->id,
        'quantity' => 10,
        'unit_price' => 20000,
    ]);

    $so->refresh();
    expect((float) $so->total_amount)->toBe(200000.0);

    $shipment = Shipment::create([
        'sales_order_id' => $so->id,
        'warehouse_id' => $warehouse->id,
        'shipped_date' => now(),
        'status' => 'pending',
        'created_by' => $user->id,
    ]);

    expect($shipment->shipment_number)->toStartWith('SH-');

    // Update shipment ke delivered
    $shipment->update(['status' => 'delivered']);

    $balance = StockBalance::where('product_id', $product->id)
        ->where('warehouse_id', $warehouse->id)
        ->first();

    // 50 - 10 = 40
    expect((float) $balance->quantity)->toBe(40.0);

    $product->refresh();
    expect($product->stock_quantity)->toBe(40);

    $outMovement = StockMovement::where('product_id', $product->id)
        ->where('warehouse_id', $warehouse->id)
        ->where('type', 'out')
        ->first();

    expect($outMovement)->not->toBeNull()
        ->and((float) $outMovement->quantity)->toBe(10.0);

    $so->refresh();
    expect($so->status)->toBe('delivered');
});

test('Transfer moving stock between warehouses works accurately', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $whOrigin = Warehouse::factory()->create();
    $whDestination = Warehouse::factory()->create();
    $product = Product::factory()->create(['cost_price' => 5000]);

    // Stok awal 100 di gudang asal
    $stockService = app(StockService::class);
    $stockService->addStock(
        productId: $product->id,
        warehouseId: $whOrigin->id,
        quantity: 100,
        unitPrice: 5000,
        userId: $user->id
    );

    $transfer = Transfer::create([
        'from_warehouse_id' => $whOrigin->id,
        'to_warehouse_id' => $whDestination->id,
        'status' => 'draft',
        'created_by' => $user->id,
    ]);

    TransferItem::create([
        'transfer_id' => $transfer->id,
        'product_id' => $product->id,
        'quantity' => 30,
    ]);

    // Selesaikan transfer
    $transfer->update(['status' => 'completed']);

    $originBalance = StockBalance::where('product_id', $product->id)
        ->where('warehouse_id', $whOrigin->id)
        ->first();

    $destBalance = StockBalance::where('product_id', $product->id)
        ->where('warehouse_id', $whDestination->id)
        ->first();

    expect((float) $originBalance->quantity)->toBe(70.0)
        ->and((float) $destBalance->quantity)->toBe(30.0);

    // Total stok produk tetap 100
    $product->refresh();
    expect($product->stock_quantity)->toBe(100);
});

test('TransferPolicy allows authorized admin and staff access', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $staff = User::factory()->create(['role' => 'staff']);
    $warehouse1 = Warehouse::factory()->create();
    $warehouse2 = Warehouse::factory()->create();

    $transfer = Transfer::create([
        'from_warehouse_id' => $warehouse1->id,
        'to_warehouse_id' => $warehouse2->id,
        'status' => 'draft',
        'created_by' => $staff->id,
    ]);

    $policy = new TransferPolicy;

    expect($policy->viewAny($admin))->toBeTrue()
        ->and($policy->viewAny($staff))->toBeTrue()
        ->and($policy->create($admin))->toBeTrue()
        ->and($policy->create($staff))->toBeTrue()
        ->and($policy->update($admin, $transfer))->toBeTrue()
        ->and($policy->update($staff, $transfer))->toBeTrue();

    // Selesaikan transfer
    $transfer->status = 'completed';
    // Staff tidak boleh update transfer yang sudah selesai
    expect($policy->update($staff, $transfer))->toBeFalse()
        ->and($policy->update($admin, $transfer))->toBeTrue();
});
