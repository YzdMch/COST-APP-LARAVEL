<?php

namespace App\Http\Controllers;

use App\Models\EstimasiHarga;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EstimasiController extends Controller
{
    public function index()
    {
        return view('estimasi.index');
    }

    /**
     * AJAX endpoint — return estimasi harga as JSON
     */
    public function hitung(Request $request): JsonResponse
    {
        $request->validate([
            'perangkat' => 'required|in:macbook,windows,pc,imac,other',
            'kerusakan' => 'required|in:lcd,battery,ssd,thermal,other',
        ]);

        $data = EstimasiHarga::where('perangkat', $request->perangkat)
            ->where('kerusakan', $request->kerusakan)
            ->first();

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Estimasi tidak ditemukan.',
            ]);
        }

        return response()->json([
            'status'     => 'ok',
            'harga_min'  => $data->harga_min,
            'harga_max'  => $data->harga_max,
            'keterangan' => $data->keterangan,
        ]);
    }
}
