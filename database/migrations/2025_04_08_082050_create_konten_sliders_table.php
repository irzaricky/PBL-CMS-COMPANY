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
        Schema::create('konten_slider', function (Blueprint $table) {
            $table->id('id_konten_slider');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->unsignedBigInteger('id_galeri')->nullable();
            $table->unsignedBigInteger('id_produk')->nullable();
            $table->unsignedBigInteger('id_lowongan')->nullable();
            $table->unsignedBigInteger('id_event')->nullable();
            $table->unsignedBigInteger('id_artikel')->nullable();
            $table->string('judul_header', 100);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('set null');
            $table->foreign('id_galeri')->references('id_galeri')->on('galeri')->onDelete('set null');
            $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('set null');
            $table->foreign('id_lowongan')->references('id_lowongan')->on('lowongan')->onDelete('set null');
            $table->foreign('id_event')->references('id_event')->on('event')->onDelete('set null');
            $table->foreign('id_artikel')->references('id_artikel')->on('artikel')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konten_slider');
    }
};