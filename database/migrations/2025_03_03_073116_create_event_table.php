<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('event', function (Blueprint $table) {
            $table->id('id_event');
            $table->string('nama');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('gambar_cover')->nullable();
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_akhir');
            $table->string('lokasi')->nullable();
            $table->string('link_pendaftaran')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('event');
    }
};
