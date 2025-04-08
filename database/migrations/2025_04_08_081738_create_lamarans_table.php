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
        Schema::create('lamaran', function (Blueprint $table) {
            $table->id('id_lamaran');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_lowongan');
            $table->string('cv', 200)->nullable();
            $table->string('portfolio', 200)->nullable();
            $table->enum('status_lamaran', ['Diterima', 'Diproses', 'Ditolak'])->default('Diproses');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('id_lowongan')
                ->references('id_lowongan')
                ->on('lowongan')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lamaran');
    }
};