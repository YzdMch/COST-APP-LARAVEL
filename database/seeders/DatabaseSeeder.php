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
     * Default: data estimasi harga + 2 akun demo (pelanggan & teknisi).
     * Untuk import data lama: php artisan db:seed --class=OldDataSeeder
     */
    public function run(): void
    {
        $this->call([
            EstimasiHargaSeeder::class,
            UserSeeder::class,
        ]);
    }
}
