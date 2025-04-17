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
        Schema::create('testimoni', function (Blueprint $table) {
            $table->id('id_testimoni');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->text('isi_testimoni');
            $table->text('thumbnail_testimoni', 100);
            $table->tinyInteger('rating')->unsigned()->comment('Rating dari 1-5');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimoni');
    }
};