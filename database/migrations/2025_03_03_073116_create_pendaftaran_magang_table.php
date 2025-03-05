<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pendaftaran_magang', function (Blueprint $table) {
            $table->id('id_pendaftaran_magang');
            $table->foreignId('id_magang')->constrained('magang')->onDelete('cascade');
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->dateTime('tanggal_registrasi');
            $table->boolean('status_registrasi')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pendaftaran_magang');
    }
};
