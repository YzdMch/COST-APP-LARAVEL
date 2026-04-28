<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstimasiHargaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['perangkat' => 'macbook', 'kerusakan' => 'lcd',     'harga_min' => 1500000, 'harga_max' => 3500000, 'keterangan' => 'Tergantung ukuran layar dan tipe panel'],
            ['perangkat' => 'macbook', 'kerusakan' => 'battery', 'harga_min' => 800000,  'harga_max' => 1500000, 'keterangan' => 'Baterai original Apple'],
            ['perangkat' => 'macbook', 'kerusakan' => 'ssd',     'harga_min' => 600000,  'harga_max' => 2000000, 'keterangan' => 'Upgrade 256GB - 1TB NVMe'],
            ['perangkat' => 'macbook', 'kerusakan' => 'thermal', 'harga_min' => 250000,  'harga_max' => 450000,  'keterangan' => 'Thermal paste + deep cleaning'],
            ['perangkat' => 'macbook', 'kerusakan' => 'other',   'harga_min' => 300000,  'harga_max' => 1500000, 'keterangan' => 'Estimasi menyesuaikan kerusakan'],

            ['perangkat' => 'windows', 'kerusakan' => 'lcd',     'harga_min' => 400000,  'harga_max' => 1200000, 'keterangan' => 'Tergantung ukuran dan resolusi layar'],
            ['perangkat' => 'windows', 'kerusakan' => 'battery', 'harga_min' => 250000,  'harga_max' => 600000,  'keterangan' => 'Baterai kompatibel sesuai tipe laptop'],
            ['perangkat' => 'windows', 'kerusakan' => 'ssd',     'harga_min' => 300000,  'harga_max' => 1200000, 'keterangan' => 'Upgrade 256GB - 1TB SSD SATA/NVMe'],
            ['perangkat' => 'windows', 'kerusakan' => 'thermal', 'harga_min' => 150000,  'harga_max' => 300000,  'keterangan' => 'Thermal paste + cleaning fan'],
            ['perangkat' => 'windows', 'kerusakan' => 'other',   'harga_min' => 200000,  'harga_max' => 800000,  'keterangan' => 'Estimasi menyesuaikan kerusakan'],

            ['perangkat' => 'pc', 'kerusakan' => 'lcd',     'harga_min' => 350000,  'harga_max' => 1000000, 'keterangan' => 'Monitor PC berbagai ukuran'],
            ['perangkat' => 'pc', 'kerusakan' => 'battery', 'harga_min' => 50000,   'harga_max' => 150000,  'keterangan' => 'Baterai CMOS / UPS'],
            ['perangkat' => 'pc', 'kerusakan' => 'ssd',     'harga_min' => 300000,  'harga_max' => 1500000, 'keterangan' => 'Upgrade SSD + migrasi data'],
            ['perangkat' => 'pc', 'kerusakan' => 'thermal', 'harga_min' => 100000,  'harga_max' => 250000,  'keterangan' => 'Thermal paste CPU + cleaning'],
            ['perangkat' => 'pc', 'kerusakan' => 'other',   'harga_min' => 150000,  'harga_max' => 750000,  'keterangan' => 'Estimasi menyesuaikan kerusakan'],

            ['perangkat' => 'imac', 'kerusakan' => 'lcd',     'harga_min' => 2000000, 'harga_max' => 5000000, 'keterangan' => 'Panel layar all-in-one iMac'],
            ['perangkat' => 'imac', 'kerusakan' => 'battery', 'harga_min' => 500000,  'harga_max' => 1000000, 'keterangan' => 'Baterai Magic Mouse / keyboard'],
            ['perangkat' => 'imac', 'kerusakan' => 'ssd',     'harga_min' => 800000,  'harga_max' => 2500000, 'keterangan' => 'Upgrade SSD internal iMac'],
            ['perangkat' => 'imac', 'kerusakan' => 'thermal', 'harga_min' => 300000,  'harga_max' => 600000,  'keterangan' => 'Thermal paste + cleaning iMac'],
            ['perangkat' => 'imac', 'kerusakan' => 'other',   'harga_min' => 400000,  'harga_max' => 2000000, 'keterangan' => 'Estimasi menyesuaikan kerusakan'],

            ['perangkat' => 'other', 'kerusakan' => 'lcd',     'harga_min' => 300000,  'harga_max' => 1500000, 'keterangan' => 'Estimasi menyesuaikan perangkat'],
            ['perangkat' => 'other', 'kerusakan' => 'battery', 'harga_min' => 200000,  'harga_max' => 700000,  'keterangan' => 'Estimasi menyesuaikan perangkat'],
            ['perangkat' => 'other', 'kerusakan' => 'ssd',     'harga_min' => 250000,  'harga_max' => 1000000, 'keterangan' => 'Estimasi menyesuaikan perangkat'],
            ['perangkat' => 'other', 'kerusakan' => 'thermal', 'harga_min' => 100000,  'harga_max' => 300000,  'keterangan' => 'Estimasi menyesuaikan perangkat'],
            ['perangkat' => 'other', 'kerusakan' => 'other',   'harga_min' => 150000,  'harga_max' => 750000,  'keterangan' => 'Hubungi teknisi untuk estimasi lebih akurat'],
        ];

        DB::table('estimasi_harga')->insert($data);
    }
}
