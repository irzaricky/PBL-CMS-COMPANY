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
            $table->unsignedBigInteger('id_user');
            $table->string('judul_lowongan', 200);
            $table->text('deskripsi_pekerjaan');
            $table->enum('jenis_lowongan', ['Full-time', 'Part-time', 'Freelance', 'Internship']);
            $table->decimal('gaji', 10, 2)->nullable();
            $table->date('tanggal_dibuka');
            $table->date('tanggal_ditutup');
            $table->enum('status_lowongan', ['dibuka', 'ditutup']);
            $table->tinyInteger('tenaga_dibutuhkan')->unsigned();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');
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