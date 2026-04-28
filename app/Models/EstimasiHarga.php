<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstimasiHarga extends Model
{
    protected $table = 'estimasi_harga';
    public $timestamps = false;

    protected $fillable = [
        'perangkat',
        'kerusakan',
        'harga_min',
        'harga_max',
        'keterangan',
    ];
}
