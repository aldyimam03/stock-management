<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Identitas Produk
            $table->string('slug')->unique();                    // Slug
            $table->string('name');                              // Nama produk
            $table->text('description')->nullable();             // Deskripsi (opsional)

            // Kategori & Satuan
            $table->foreignId('category_id')                     // Relasi ke tabel categories
              ->nullable()
              ->constrained()
              ->nullOnDelete();
            $table->string('unit')->default('pcs');              // Satuan: pcs, kg, box, liter

            // Harga
            $table->decimal('cost_price', 15, 2)->default(0);    // Harga beli/modal
            $table->decimal('selling_price', 15, 2)->default(0); // Harga jual

            // Stok
            $table->integer('stock_quantity')->default(0);       // Jumlah stok saat ini
            $table->integer('minimum_stock')->default(0);        // Batas minimum (untuk notifikasi)

            // Status
            $table->boolean('is_active')->default(true);         // Aktif/nonaktif

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
