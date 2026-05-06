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
                'nama'       => 'Geeko Surabaya (Pusat)',
                'alamat'     => 'Jl. Raya Darmo No. 42, Surabaya, Jawa Timur',
                'telepon'    => '031-1234567',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Geeko Malang',
                'alamat'     => 'Jl. Ijen No. 15, Malang, Jawa Timur',
                'telepon'    => '0341-7654321',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama'       => 'Geeko Sidoarjo',
                'alamat'     => 'Jl. Pahlawan No. 88, Sidoarjo, Jawa Timur',
                'telepon'    => '031-9876543',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
