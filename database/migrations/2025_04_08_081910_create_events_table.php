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
        Schema::create('event', function (Blueprint $table) {
            $table->id('id_event');
            $table->string('nama_event');
            $table->text('deskripsi_event');
            $table->json('thumbnail_event', 400)->nullable();
            $table->string('lokasi_event', 200);
            $table->dateTime('waktu_start_event');
            $table->dateTime('waktu_end_event');
            $table->string('link_daftar_event', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event');
    }
};