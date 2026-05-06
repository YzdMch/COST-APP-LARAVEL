<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sla_configs', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_kerusakan', 50); // lcd, battery, ssd, thermal, other
            $table->string('perangkat', 50)->nullable(); // null = applies to all device types
            $table->integer('target_jam'); // target completion in hours
            $table->foreignId('cabang_id')->nullable()->constrained('cabang')->nullOnDelete();
            $table->timestamps();

            $table->unique(['jenis_kerusakan', 'perangkat', 'cabang_id'], 'uq_sla_config');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sla_configs');
    }
};
