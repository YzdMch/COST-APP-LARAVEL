<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servis_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servis_id')->constrained('servis')->cascadeOnDelete();
            $table->enum('status', ['Diterima', 'Sedang dicek', 'Perbaikan', 'Testing', 'Selesai']);
            $table->text('catatan')->nullable();
            $table->string('foto', 255)->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servis_log');
    }
};
