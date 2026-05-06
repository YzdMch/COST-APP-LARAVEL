<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change role enum to include admin
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('pelanggan', 'teknisi', 'admin') DEFAULT 'pelanggan'");

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('cabang_id')->nullable()->after('role')->constrained('cabang')->nullOnDelete();
            $table->boolean('is_active')->default(true)->after('cabang_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['cabang_id']);
            $table->dropColumn(['cabang_id', 'is_active']);
        });

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('pelanggan', 'teknisi') DEFAULT 'pelanggan'");
    }
};
