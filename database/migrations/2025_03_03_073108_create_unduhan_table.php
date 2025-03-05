<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('unduhan', function (Blueprint $table) {
            $table->id('id_unduhan');
            $table->foreignId('id_kategori_unduhan')->constrained('kategori_unduhan')->onDelete('cascade');
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('lokasi_file');
            $table->string('jenis_file');
            $table->integer('ukuran_file');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('unduhan');
    }
};
