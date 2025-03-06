<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('kategori_testimoni', function (Blueprint $table) {
            $table->id('id_kategori_testimoni');
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_testimoni');
    }
};