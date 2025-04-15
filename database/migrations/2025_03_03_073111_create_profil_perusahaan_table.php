<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('profil_perusahaan', function (Blueprint $table) {
            $table->id('id_profil_perusahaan');
            $table->string('nama_perusahaan');
            $table->text('deskripsi_perusahaan')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('sejarah')->nullable();
            $table->text('pencapaian_perusahaan')->nullable();
            $table->string('alamat_perusahaan')->nullable();
            $table->string('email_perusahaan')->nullable();
            $table->string('nomor_telepon_perusahaan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profil_perusahaan');
    }
};
