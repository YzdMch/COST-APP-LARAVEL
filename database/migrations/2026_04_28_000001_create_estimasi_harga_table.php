<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estimasi_harga', function (Blueprint $table) {
            $table->id();
            $table->enum('perangkat', ['macbook', 'windows', 'pc', 'imac', 'other']);
            $table->enum('kerusakan', ['lcd', 'battery', 'ssd', 'thermal', 'other']);
            $table->decimal('harga_min', 12, 0);
            $table->decimal('harga_max', 12, 0);
            $table->string('keterangan', 255)->nullable();

            $table->unique(['perangkat', 'kerusakan'], 'uq_kombinasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estimasi_harga');
    }
};
