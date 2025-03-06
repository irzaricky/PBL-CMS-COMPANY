<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('lowongan', function (Blueprint $table) {
            $table->id('id_lowongan');
            $table->string('judul');
            $table->text('deskripsi');
            $table->text('manfaat');
            $table->text('persyaratan');
            $table->integer('durasi_lowongan');
            $table->dateTime('waktu_dibuka');
            $table->dateTime('waktu_ditutup');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lowongan');
    }
};
