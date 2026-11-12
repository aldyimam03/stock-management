<?php

use App\Exports\StockReportExport;
use App\Models\Notification;
use App\Models\Product;
use App\Models\StockBalance;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);

test('StockReportExport formats columns and headings properly for Excel', function () {
    $warehouse = Warehouse::factory()->create(['name' => 'Gudang Pusat']);
    $product = Product::factory()->create([
        'name' => 'Buku Catatan',
        'slug' => 'PRD-BUKU',
    ]);

    StockBalance::create([
        'product_id' => $product->id,
        'warehouse_id' => $warehouse->id,
        'quantity' => 150,
        'reserved_quantity' => 20,
        'average_cost' => 12500,
    ]);

    $export = new StockReportExport($warehouse->id);
    $headings = $export->headings();
    $data = $export->collection();
    $mapped = $export->map($data->first());

    expect($headings)->toContain('Kode Produk')
        ->and($headings)->toContain('Nama Produk')
        ->and($headings)->toContain('Nilai Total Stok (Rp)')
        ->and($mapped[0])->toBe('PRD-BUKU')
        ->and($mapped[1])->toBe('Buku Catatan')
        ->and($mapped[3])->toBe(150.0)
        ->and($mapped[5])->toBe(130.0); // 150 - 20
});

test('check low stock command detects minimum stock breach and creates notifications for admins', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $warehouse = Warehouse::factory()->create();

    // Produk dengan minimum stock 20, tapi stok saldo hanya 5
    $product = Product::factory()->create([
        'name' => 'Kertas A4',
        'minimum_stock' => 20,
        'is_active' => true,
    ]);

    $stockService = app(StockService::class);
    $stockService->addStock(
        productId: $product->id,
        warehouseId: $warehouse->id,
        quantity: 5,
        unitPrice: 50000
    );

    Artisan::call('stock:check-low');

    $notif = Notification::where('user_id', $admin->id)
        ->where('type', 'low_stock')
        ->first();

    expect($notif)->not->toBeNull()
        ->and($notif->title)->toContain('Kertas A4')
        ->and($notif->message)->toContain('5');
});

test('API endpoints for products return valid JSON response', function () {
    $product = Product::factory()->create([
        'name' => 'Laptop Asus',
        'slug' => 'PRD-ASUS',
    ]);

    $response = $this->getJson('/api/v1/products');

    $response->assertOk()
        ->assertJsonStructure([
            'status',
            'data' => [
                'data' => [
                    '*' => ['id', 'name', 'slug'],
                ],
            ],
        ]);

    $detailResponse = $this->getJson("/api/v1/products/{$product->id}");
    $detailResponse->assertOk()
        ->assertJsonPath('data.name', 'Laptop Asus')
        ->assertJsonPath('data.slug', 'PRD-ASUS');
});

test('API endpoints for stock balances and movements return audit trail data', function () {
    $warehouse = Warehouse::factory()->create(['name' => 'Gudang Utama']);
    $product = Product::factory()->create(['name' => 'Mouse Wireless']);

    $stockService = app(StockService::class);
    $stockService->addStock(
        productId: $product->id,
        warehouseId: $warehouse->id,
        quantity: 50,
        unitPrice: 150000,
        note: 'Stok Masuk Batch 1'
    );

    $balanceRes = $this->getJson('/api/v1/stock/balances');
    $balanceRes->assertOk()
        ->assertJsonStructure(['status', 'data' => ['data']]);

    $movementRes = $this->getJson('/api/v1/stock/movements');
    $movementRes->assertOk()
        ->assertJsonPath('data.data.0.note', 'Stok Masuk Batch 1')
        ->assertJsonPath('data.data.0.type', 'in');
});
