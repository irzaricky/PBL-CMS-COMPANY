<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id('id_feedback');
            $table->foreignId('id_users')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nama');
            $table->string('email');
            $table->string('tipe');
            $table->text('pesan');
            $table->boolean('status')->default(false);
            $table->text('tanggapan_perusahaan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('feedback');
    }
};
