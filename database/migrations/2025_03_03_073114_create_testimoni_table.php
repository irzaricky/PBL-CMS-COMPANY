<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('testimoni', function (Blueprint $table) {
            $table->id('id_testimoni');
            $table->foreignId('id_kategori_testimoni')->constrained('kategori_testimoni')->onDelete('cascade');
            $table->string('nama');
            $table->string('email');
            $table->string('foto_profil')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('rating')->default(5);
            $table->text('konten');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('testimoni');
    }
};
