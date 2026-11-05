<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Resources\Categories\Tables\CategoriesTable;
use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Filament\Resources\Products\Tables\ProductsTable;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('category can be created via Filament form schema', function () {
    $form = CategoryForm::configure(
        Schema::make()
    );

    $components = $form->getComponents();
    $names = collect($components)->map(fn ($c) => $c->getName())->toArray();

    expect($names)->toContain('name')
        ->and($names)->toContain('slug')
        ->and($names)->toContain('description');
});

test('product can be created with category relation', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);

    expect($product->category->id)->toBe($category->id)
        ->and($product->category->name)->toBe($category->name);
});

test('product slug must be unique', function () {
    Product::factory()->create(['slug' => 'test-product']);
    Product::factory()->create(['slug' => 'test-product']);
})->throws(UniqueConstraintViolationException::class);

test('product soft delete hides from default query', function () {
    $product = Product::factory()->create();
    $product->delete();

    expect(Product::find($product->id))->toBeNull()
        ->and(Product::withTrashed()->find($product->id))->not->toBeNull();
});

test('category has many products', function () {
    $category = Category::factory()->create();
    Product::factory()->count(3)->create(['category_id' => $category->id]);

    expect($category->products)->toHaveCount(3);
});

test('product factory sets selling price above cost price', function () {
    $product = Product::factory()->create();

    expect($product->selling_price)->toBeGreaterThan($product->cost_price);
});

test('product is_active cast to boolean', function () {
    $product = Product::factory()->create(['is_active' => 1]);
    expect($product->is_active)->toBeTrue();

    $product2 = Product::factory()->create(['is_active' => 0]);
    expect($product2->is_active)->toBeFalse();
});

test('product prices cast to decimal', function () {
    $product = Product::factory()->create([
        'cost_price' => 1000.50,
        'selling_price' => 1300.75,
    ]);

    expect($product->cost_price)->toBe('1000.50')
        ->and($product->selling_price)->toBe('1300.75');
});

test('user can login and access dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('guest redirected to login', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});