<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    public function index()
    {
        $cabangList = Cabang::withCount('servis')
            ->withCount('teknisi')
            ->orderBy('nama')
            ->get();

        return view('admin.cabang.index', compact('cabangList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'    => 'required|string|max:100',
            'alamat'  => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
        ]);

        $cabang = Cabang::create($data);

        ActivityLogger::log(
            'create', "Cabang baru ditambahkan: {$cabang->nama}",
            'Cabang', $cabang->id, null, $data
        );

        return redirect()->route('admin.cabang.index')
            ->with('pesan', "Cabang {$cabang->nama} berhasil ditambahkan.");
    }

    public function update(Request $request, Cabang $cabang)
    {
        $data = $request->validate([
            'nama'      => 'required|string|max:100',
            'alamat'    => 'nullable|string',
            'telepon'   => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $oldData = $cabang->only(['nama', 'alamat', 'telepon', 'is_active']);
        $cabang->update($data);

        ActivityLogger::log(
            'update', "Cabang {$cabang->nama} diperbarui",
            'Cabang', $cabang->id, $oldData, $data
        );

        return redirect()->route('admin.cabang.index')
            ->with('pesan', "Cabang {$cabang->nama} berhasil diperbarui.");
    }

    public function destroy(Cabang $cabang)
    {
        if ($cabang->servis()->count() > 0) {
            return back()->withErrors(['error' => 'Cabang tidak bisa dihapus karena masih memiliki data servis.']);
        }

        $nama = $cabang->nama;
        ActivityLogger::log(
            'delete', "Cabang dihapus: {$nama}",
            'Cabang', $cabang->id, $cabang->toArray(), null
        );

        $cabang->delete();

        return redirect()->route('admin.cabang.index')
            ->with('pesan', "Cabang {$nama} berhasil dihapus.");
    }
}
