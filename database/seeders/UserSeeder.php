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
                'name'       => 'Pelanggan Demo',
                'email'      => 'pelanggan@geeko.com',
                'no_telepon' => '081234567890',
                'password'   => Hash::make('123456'),
                'role'       => 'pelanggan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Teknisi Demo',
                'email'      => 'teknisi@geeko.com',
                'no_telepon' => '081234567891',
                'password'   => Hash::make('123456'),
                'role'       => 'teknisi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
