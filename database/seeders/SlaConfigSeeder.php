<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SlaConfigSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Generic SLA per jenis kerusakan (all devices, all branches)
            ['jenis_kerusakan' => 'lcd',     'perangkat' => null, 'target_jam' => 72,  'cabang_id' => null],
            ['jenis_kerusakan' => 'battery', 'perangkat' => null, 'target_jam' => 48,  'cabang_id' => null],
            ['jenis_kerusakan' => 'ssd',     'perangkat' => null, 'target_jam' => 24,  'cabang_id' => null],
            ['jenis_kerusakan' => 'thermal', 'perangkat' => null, 'target_jam' => 12,  'cabang_id' => null],
            ['jenis_kerusakan' => 'other',   'perangkat' => null, 'target_jam' => 72,  'cabang_id' => null],

            // MacBook LCD gets extra time (more complex)
            ['jenis_kerusakan' => 'lcd', 'perangkat' => 'macbook', 'target_jam' => 96, 'cabang_id' => null],
            ['jenis_kerusakan' => 'lcd', 'perangkat' => 'imac',    'target_jam' => 96, 'cabang_id' => null],
        ];

        foreach ($data as &$d) {
            $d['created_at'] = now();
            $d['updated_at'] = now();
        }

        DB::table('sla_configs')->insert($data);
    }
}
