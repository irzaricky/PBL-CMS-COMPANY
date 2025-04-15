<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pendaftaran_lowongan', function (Blueprint $table) {
            $table->id('id_pendaftaran_lowongan');
            $table->foreignId('id_lowongan')->constrained('lowongan', 'id_lowongan')->onDelete('cascade');
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->dateTime('tanggal_registrasi');
            $table->boolean('status_registrasi')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pendaftaran_lowongan');
    }
};
