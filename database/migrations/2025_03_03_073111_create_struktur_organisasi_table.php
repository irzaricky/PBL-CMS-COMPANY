<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('struktur_organisasi', function (Blueprint $table) {
            $table->id('id_struktur_organisasi');
            $table->string('nama');
            $table->string('role');
            $table->string('foto_profil')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('struktur_organisasi');
    }
};
