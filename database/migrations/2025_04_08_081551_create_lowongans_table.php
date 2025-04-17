<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lowongan', function (Blueprint $table) {
            $table->id('id_lowongan');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->string('judul_lowongan', 200);
            $table->json("thumbnail_lowongan", 400)->nullable();
            $table->text('deskripsi_pekerjaan');
            $table->enum('jenis_lowongan', ['Full-time', 'Part-time', 'Freelance', 'Internship']);
            $table->decimal('gaji', 10, 2)->nullable();
            $table->date('tanggal_dibuka');
            $table->date('tanggal_ditutup');
            $table->enum('status_lowongan', ['dibuka', 'ditutup']);
            $table->tinyInteger('tenaga_dibutuhkan')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lowongan');
    }
};