<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EstimasiHarga;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class EstimasiController extends Controller
{
    public function index()
    {
        $estimasi = EstimasiHarga::orderBy('perangkat')->orderBy('kerusakan')->get();
        return view('admin.estimasi.index', compact('estimasi'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'perangkat'  => 'required|in:macbook,windows,pc,imac,other',
            'kerusakan'  => 'required|in:lcd,battery,ssd,thermal,other',
            'harga_min'  => 'required|numeric|min:0',
            'harga_max'  => 'required|numeric|min:0|gte:harga_min',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $estimasi = EstimasiHarga::create($data);

        ActivityLogger::log(
            'create', "Estimasi harga baru: {$data['perangkat']} - {$data['kerusakan']}",
            'EstimasiHarga', $estimasi->id, null, $data
        );

        return redirect()->route('admin.estimasi.index')
            ->with('pesan', 'Estimasi harga berhasil ditambahkan.');
    }

    public function update(Request $request, EstimasiHarga $estimasi)
    {
        $data = $request->validate([
            'perangkat'  => 'required|in:macbook,windows,pc,imac,other',
            'kerusakan'  => 'required|in:lcd,battery,ssd,thermal,other',
            'harga_min'  => 'required|numeric|min:0',
            'harga_max'  => 'required|numeric|min:0|gte:harga_min',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $oldData = $estimasi->only(['perangkat', 'kerusakan', 'harga_min', 'harga_max', 'keterangan']);
        $estimasi->update($data);

        ActivityLogger::log(
            'update', "Estimasi harga diupdate: {$data['perangkat']} - {$data['kerusakan']}",
            'EstimasiHarga', $estimasi->id, $oldData, $data
        );

        return redirect()->route('admin.estimasi.index')
            ->with('pesan', 'Estimasi harga berhasil diperbarui.');
    }

    public function destroy(EstimasiHarga $estimasi)
    {
        $info = "{$estimasi->perangkat} - {$estimasi->kerusakan}";
        $oldData = $estimasi->toArray();

        ActivityLogger::log(
            'delete', "Estimasi harga dihapus: {$info}",
            'EstimasiHarga', $estimasi->id, $oldData, null
        );

        $estimasi->delete();

        return redirect()->route('admin.estimasi.index')
            ->with('pesan', 'Estimasi harga berhasil dihapus.');
    }
}
