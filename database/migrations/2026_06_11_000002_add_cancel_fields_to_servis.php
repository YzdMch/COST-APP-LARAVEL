<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Expand enum status on servis table to include 'Dibatalkan'
        DB::statement("ALTER TABLE servis MODIFY COLUMN status ENUM(
            'Diterima', 'Sedang dicek', 'Perbaikan', 'Testing', 'Selesai', 'Dibatalkan'
        ) NOT NULL DEFAULT 'Diterima'");

        // Expand enum status on servis_log table to include 'Dibatalkan'
        DB::statement("ALTER TABLE servis_log MODIFY COLUMN status ENUM(
            'Diterima', 'Sedang dicek', 'Perbaikan', 'Testing', 'Selesai', 'Dibatalkan'
        ) NOT NULL");

        // Add cancellation columns to servis
        Schema::table('servis', function (Blueprint $table) {
            $table->text('alasan_batal')->nullable()->after('completed_at');
            $table->timestamp('cancelled_at')->nullable()->after('alasan_batal');
            $table->foreignId('cancelled_by')->nullable()->after('cancelled_at')
                  ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        // Revert enum to original
        DB::statement("ALTER TABLE servis MODIFY COLUMN status ENUM(
            'Diterima', 'Sedang dicek', 'Perbaikan', 'Testing', 'Selesai'
        ) NOT NULL DEFAULT 'Diterima'");

        DB::statement("ALTER TABLE servis_log MODIFY COLUMN status ENUM(
            'Diterima', 'Sedang dicek', 'Perbaikan', 'Testing', 'Selesai'
        ) NOT NULL");

        Schema::table('servis', function (Blueprint $table) {
            $table->dropForeign(['cancelled_by']);
            $table->dropColumn(['alasan_batal', 'cancelled_at', 'cancelled_by']);
        });
    }
};
