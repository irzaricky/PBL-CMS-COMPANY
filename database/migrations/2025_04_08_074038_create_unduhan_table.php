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
        Schema::create('unduhan', function (Blueprint $table) {
            $table->id('id_unduhan');
            $table->unsignedBigInteger('id_kategori_unduhan');
            $table->unsignedBigInteger('id_user');
            $table->string('nama_unduhan', 100);
            $table->string('slug', 100)->unique();
            $table->string('lokasi_file', 200);
            $table->text('deskripsi')->nullable();
            $table->bigInteger('jumlah_unduhan')->default(0);
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_kategori_unduhan')->references('id_kategori_unduhan')->on('kategori_unduhan')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unduhan');
    }
};