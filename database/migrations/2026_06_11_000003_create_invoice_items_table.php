<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servis_id')->constrained('servis')->cascadeOnDelete();
            $table->string('nama_item', 255);
            $table->unsignedInteger('qty')->default(1);
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 12, 2);  // qty * harga_satuan, stored for reporting
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
