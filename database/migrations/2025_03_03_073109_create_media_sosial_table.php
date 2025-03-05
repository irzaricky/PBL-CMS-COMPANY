<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('media_sosial', function (Blueprint $table) {
            $table->id('id_media_sosial');
            $table->string('nama');
            $table->string('ikon');
            $table->string('link');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('media_sosial');
    }
};
