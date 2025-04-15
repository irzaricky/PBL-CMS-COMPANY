<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('konten_slider', function (Blueprint $table) {
            $table->id('id_konten_slider');
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('lokasi_file');
            $table->string('tipe_media');
            $table->string('link')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('konten_slider');
    }
};