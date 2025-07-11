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
        Schema::create('feature_toggles', function (Blueprint $table) {
            $table->id('feature_id');
            $table->string('key')->unique(); // Misal: 'artikel_module', 'produk_module'
            $table->string('label');         // Label untuk admin: 'Modul Artikel'
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_toggles');
    }
};
