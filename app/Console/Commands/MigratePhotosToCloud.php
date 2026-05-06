<?php

namespace App\Console\Commands;

use App\Models\ServisLog;
use Illuminate\Console\Command;

class MigratePhotosToCloud extends Command
{
    protected $signature = 'photos:migrate-cloud';
    protected $description = 'Migrasi foto servis dari local storage ke Cloudinary';

    public function handle()
    {
        $cloudinaryUrl = env('CLOUDINARY_URL');
        $this->info("🔗 CLOUDINARY_URL: " . ($cloudinaryUrl ? 'SET ✅' : 'NOT SET ❌'));

        if (!$cloudinaryUrl) {
            $this->error('Set CLOUDINARY_URL di .env dulu.');
            return 1;
        }

        $logs = ServisLog::whereNotNull('foto')
            ->where('foto', 'NOT LIKE', 'http%')
            ->get();

        if ($logs->isEmpty()) {
            $this->info('✅ Tidak ada foto lokal yang perlu dimigrasi.');
            return 0;
        }

        $this->info("📦 Ditemukan {$logs->count()} foto untuk dimigrasi...\n");

        $cloudinary = new \Cloudinary\Cloudinary($cloudinaryUrl);
        $success = 0;
        $failed = 0;

        foreach ($logs as $log) {
            $filename = str_contains($log->foto, '/') ? $log->foto : 'uploads/' . $log->foto;
            $localPath = storage_path('app/public/' . $filename);

            if (!file_exists($localPath)) {
                $this->warn("⚠️  Skip ID {$log->id}: file tidak ditemukan");
                $failed++;
                continue;
            }

            try {
                $this->info("📤 Uploading ID {$log->id}: {$filename}...");

                $result = $cloudinary->uploadApi()->upload($localPath, [
                    'folder' => 'geeko-servis',
                ]);

                $cloudUrl = $result['secure_url'];
                $log->update(['foto' => $cloudUrl]);

                $this->info("   ✅ → {$cloudUrl}");
                $success++;
            } catch (\Exception $e) {
                $this->error("   ❌ " . $e->getMessage());
                $failed++;
            }
        }

        $this->newLine();
        $this->info("📊 Hasil: {$success} berhasil, {$failed} gagal.");
        return 0;
    }
}
