<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('artikel', function (Blueprint $table) {
            $table->id('id_artikel');
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_kategori_artikel')->constrained('kategori_artikel', 'id_kategori_artikel')->onDelete('cascade');
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('gambar_cover')->nullable();
            $table->text('konten');
            $table->dateTime('tanggal_upload');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('artikel');
    }
};
