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
        Schema::create('artikel', function (Blueprint $table) {
            $table->id('id_artikel');
            $table->unsignedBigInteger('id_kategori_artikel');
            $table->unsignedBigInteger('id_user');
            $table->string('thumbnail_artikel', 200)->nullable();
            $table->string('judul_artikel', 100);
            $table->text('konten_artikel');
            $table->string('slug', 100)->unique();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_kategori_artikel')
                ->references('id_kategori_artikel')
                ->on('kategori_artikel')
                ->onDelete('cascade');

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
        Schema::dropIfExists('artikel');
    }
};