<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servis', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_tiket', 20)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_pelanggan', 100);
            $table->string('email', 150);
            $table->string('no_telepon', 20);
            $table->enum('perangkat', ['macbook', 'windows', 'pc', 'imac', 'other']);
            $table->enum('jenis_kerusakan', ['lcd', 'battery', 'ssd', 'thermal', 'other']);
            $table->enum('cabang', ['surabaya']);
            $table->text('deskripsi');
            $table->decimal('estimasi_harga', 12, 2)->nullable();
            $table->string('foto', 255)->nullable();
            $table->enum('status', ['Diterima', 'Sedang dicek', 'Perbaikan', 'Testing', 'Selesai'])->default('Diterima');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servis');
    }
};
