<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Default: cabang, estimasi harga, SLA configs, akun demo (admin + pelanggan + teknisi).
     * Untuk import data lama: php artisan db:seed --class=OldDataSeeder
     */
    public function run(): void
    {
        $this->call([
            CabangSeeder::class,
            EstimasiHargaSeeder::class,
            SlaConfigSeeder::class,
            UserSeeder::class,
        ]);
    }
}
