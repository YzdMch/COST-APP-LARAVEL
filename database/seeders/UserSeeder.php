<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name'       => 'Shorekeeper',
                'email'      => 'admin@geeko.com',
                'no_telepon' => '081234567800',
                'password'   => Hash::make('123456'),
                'role'       => 'admin',
                'cabang_id'  => null, // admin sees all branches
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Nao Tomori',
                'email'      => 'pelanggan@geeko.com',
                'no_telepon' => '081234567890',
                'password'   => Hash::make('123456'),
                'role'       => 'pelanggan',
                'cabang_id'  => null,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Budi Utomo',
                'email'      => 'teknisi@geeko.com',
                'no_telepon' => '081234567891',
                'password'   => Hash::make('123456'),
                'role'       => 'teknisi',
                'cabang_id'  => 1, // Surabaya Rungkut
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
