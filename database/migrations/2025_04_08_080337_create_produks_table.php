<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');
            $table->unsignedBigInteger('id_kategori_produk');
            $table->string('nama_produk', 100);
            $table->string('gambar_produk', 200)->nullable();
            $table->string('harga_produk', 50);
            $table->string('slug', 100)->unique();
            $table->text('deskripsi_produk')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_kategori_produk')
                ->references('id_kategori_produk')
                ->on('kategori_produk')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};