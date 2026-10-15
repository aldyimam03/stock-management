<?php

use App\Models\Category;
use App\Models\Product;

test('the category factory persists a valid category', function () {
    $category = Category::factory()->create();

    $category->refresh();

    expect($category->name)->not->toBeEmpty()
        ->and($category->slug)->not->toBeEmpty();
});

test('the product factory persists a valid product with its category', function () {
    $product = Product::factory()->create();

    $product->refresh();

    expect($product->name)->not->toBeEmpty()
        ->and($product->slug)->not->toBeEmpty()
        ->and($product->is_active)->toBeBool()
        ->and($product->category)->toBeInstanceOf(Category::class);
});
