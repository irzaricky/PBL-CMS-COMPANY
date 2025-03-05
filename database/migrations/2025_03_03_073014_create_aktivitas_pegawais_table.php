<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('aktivitas_pegawai', function (Blueprint $table) {
            $table->id('id_aktivitas');
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->string('id_fitur');
            $table->string('tipe_fitur');
            $table->string('aksi');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aktivitas_pegawai');
    }
};
