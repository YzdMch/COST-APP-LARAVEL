<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servis', function (Blueprint $table) {
            $table->foreignId('cabang_id')->nullable()->after('cabang')->constrained('cabang')->nullOnDelete();
            $table->foreignId('teknisi_id')->nullable()->after('cabang_id')->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable()->after('teknisi_id');
            $table->integer('sla_target_jam')->nullable()->after('assigned_at');
            $table->timestamp('completed_at')->nullable()->after('sla_target_jam');
        });
    }

    public function down(): void
    {
        Schema::table('servis', function (Blueprint $table) {
            $table->dropForeign(['cabang_id']);
            $table->dropForeign(['teknisi_id']);
            $table->dropColumn(['cabang_id', 'teknisi_id', 'assigned_at', 'sla_target_jam', 'completed_at']);
        });
    }
};
