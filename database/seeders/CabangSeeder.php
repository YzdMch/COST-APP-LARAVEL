<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CabangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cabang')->insert([
            [
                'nama'       => 'Surabaya Rungkut',
                'alamat'     => 'Jl. Rungkut Madya No. 1, Surabaya, Jawa Timur',
                'telepon'    => '031-1234567',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
