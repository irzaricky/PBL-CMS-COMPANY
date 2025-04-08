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
            $table->unsignedBigInteger('id_user');
            $table->text('isi_testimoni');
            $table->tinyInteger('rating')->unsigned()->comment('Rating dari 1-5');
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
        Schema::dropIfExists('testimoni');
    }
};