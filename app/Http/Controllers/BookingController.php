<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\EstimasiHarga;
use App\Models\Servis;
use App\Models\ServisLog;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create()
    {
        $cabangList = Cabang::where('is_active', true)->orderBy('nama')->get();
        return view('booking.create', compact('cabangList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'         => 'required|string|min:3|max:100',
            'email'        => 'required|email|max:150',
            'no_telepon'   => 'required|string|min:9|max:20',
            'perangkat'    => 'required|in:macbook,windows,pc,imac,other',
            'kerusakan'    => 'required|in:lcd,battery,ssd,thermal,other',
            'cabang_id'    => 'required|exists:cabang,id',
            'deskripsi'    => 'required|string|min:5',
            'foto_booking' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        // Get the cabang for backward-compatible 'cabang' column
        $cabang = Cabang::findOrFail($request->cabang_id);

        // Get price estimate
        $estimasi = EstimasiHarga::where('perangkat', $request->perangkat)
            ->where('kerusakan', $request->kerusakan)
            ->first();

        // Upload foto booking ke Cloudinary (jika ada)
        $fotoBookingUrl = null;
        if ($request->hasFile('foto_booking')) {
            try {
                $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
                $result = $cloudinary->uploadApi()->upload(
                    $request->file('foto_booking')->getRealPath(),
                    ['folder' => 'geeko-booking']
                );
                $fotoBookingUrl = $result['secure_url'];
            } catch (\Exception $e) {
                // Fallback: store locally if Cloudinary fails
                $path = $request->file('foto_booking')->store('uploads/booking', 'public');
                $fotoBookingUrl = $path;
            }
        }

        $servis = Servis::create([
            'nomor_tiket'    => Servis::generateNomorTiket(),
            'user_id'        => auth()->id(),
            'nama_pelanggan' => $request->nama,
            'email'          => $request->email,
            'no_telepon'     => $request->no_telepon,
            'perangkat'      => $request->perangkat,
            'jenis_kerusakan'=> $request->kerusakan,
            'cabang'         => strtolower(explode(' ', $cabang->nama)[1] ?? $cabang->nama),
            'cabang_id'      => $request->cabang_id,
            'deskripsi'      => $request->deskripsi,
            'estimasi_harga' => $estimasi?->harga_max,
            'foto_booking'   => $fotoBookingUrl,
            'status'         => 'Diterima',
        ]);

        // Create initial log
        ServisLog::create([
            'servis_id'  => $servis->id,
            'status'     => 'Diterima',
            'catatan'    => 'Booking baru masuk dari pelanggan. Cabang: ' . $cabang->nama
                          . ($fotoBookingUrl ? '. Foto perangkat dilampirkan.' : ''),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('dashboard')
            ->with('tiket_baru', $servis->nomor_tiket);
    }
}
