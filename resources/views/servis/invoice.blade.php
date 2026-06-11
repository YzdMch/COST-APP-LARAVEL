@php
  $labelPerangkat = \App\Models\Servis::labelPerangkat();
  $labelKerusakan = \App\Models\Servis::labelKerusakan();
  $nomorInvoice   = 'INV-' . $servis->nomor_tiket;
  $tanggalCetak   = now()->format('d M Y');

  $biayaServis    = $servis->biaya_jasa;
  $subtotal       = $biayaServis + $items->sum('subtotal');
  $tax            = 0;
  $total          = $subtotal;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invoice {{ $nomorInvoice }} — Geeko Komputer</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('images/logo1x1.png') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    * { font-family: 'Inter', sans-serif; }
    @media print {
      .no-print { display: none !important; }
      body { background: white !important; padding: 0 !important; margin: 0 !important; }
      .invoice-paper { box-shadow: none !important; border-radius: 0 !important; border: none !important; }
      @page { size: A4; margin: 8mm; }
      .max-w-3xl { max-width: 100% !important; width: 100% !important; padding: 0 !important; margin: 0 !important; }
      .py-8 { padding-top: 0 !important; padding-bottom: 0 !important; }
      .px-8 { padding-left: 1.5rem !important; padding-right: 1.5rem !important; }
      .py-6 { padding-top: 1rem !important; padding-bottom: 1rem !important; }
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">

  {{-- Action Bar --}}
  <div class="no-print bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-3xl mx-auto px-5 py-3 flex items-center justify-between gap-3">
      <div class="flex items-center gap-3">
        <a href="{{ route('servis.show', $servis) }}"
           class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 transition">
          <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
          <p class="font-bold text-gray-800 text-sm">Invoice Servis</p>
          <p class="text-xs text-gray-400 font-mono">{{ $nomorInvoice }}</p>
        </div>
      </div>
      <div class="flex items-center gap-2">
        @if(!auth()->user()->isPelanggan())
          <a href="{{ route('invoice.edit', $servis) }}"
             class="flex items-center gap-1.5 text-sm font-semibold text-gray-600 hover:text-gray-800 px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
            <i class="fas fa-edit text-xs"></i> Edit Item
          </a>
        @endif
        <button onclick="window.print()"
          class="bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-500 hover:to-amber-600 text-gray-900 font-bold px-5 py-2 rounded-xl shadow-lg shadow-yellow-200 transition-all flex items-center gap-2 text-sm">
          <i class="fas fa-print"></i> Cetak
        </button>
      </div>
    </div>
  </div>

  {{-- Invoice Paper --}}
  <div class="max-w-3xl mx-auto py-8 px-4">
    <div class="invoice-paper bg-white shadow-2xl overflow-hidden">

      {{-- Header Orange --}}
      <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-8 py-6 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
        <div>
          <h1 class="text-4xl font-black text-white tracking-tight">INVOICE</h1>
        </div>
        <div class="text-right text-white">
          <p class="font-bold text-base">Geeko Komputer</p>
          <p class="text-amber-100 text-sm mt-0.5">{{ $servis->cabangRelasi?->alamat ?? 'Surabaya, Jawa Timur' }}</p>
          <p class="text-amber-100 text-sm">+62 812-3456-7890</p>
          <p class="text-amber-100 text-sm">servis@geeko.com</p>
        </div>
      </div>

      {{-- Meta + Bill To --}}
      <div class="px-8 py-6 grid grid-cols-1 sm:grid-cols-2 gap-6 border-b border-gray-200">
        {{-- Left: Invoice info --}}
        <div class="space-y-2 text-sm">
          <div class="flex gap-3">
            <span class="font-bold text-gray-700 w-28">Invoice No.</span>
            <span class="text-gray-600 font-mono">{{ $nomorInvoice }}</span>
          </div>
          <div class="flex gap-3">
            <span class="font-bold text-gray-700 w-28">Date of Issue</span>
            <span class="text-gray-600">{{ $servis->completed_at?->format('d M Y') ?? $tanggalCetak }}</span>
          </div>
          <div class="flex gap-3">
            <span class="font-bold text-gray-700 w-28">Tanggal Masuk</span>
            <span class="text-gray-600">{{ $servis->created_at->format('d M Y') }}</span>
          </div>
          <div class="flex gap-3">
            <span class="font-bold text-gray-700 w-28">Teknisi</span>
            <span class="text-gray-600">{{ $servis->teknisi?->name ?? '-' }}</span>
          </div>
        </div>
        {{-- Right: Bill To --}}
        <div class="sm:text-right">
          <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Bill To</p>
          <p class="font-bold text-gray-900 text-base">{{ $servis->nama_pelanggan }}</p>
          <p class="text-gray-500 text-sm">{{ $servis->email }}</p>
          <p class="text-gray-500 text-sm">{{ $servis->no_telepon }}</p>
          <p class="text-gray-400 text-xs mt-1 font-mono">{{ $servis->nomor_tiket }}</p>
        </div>
      </div>

      {{-- Items Table --}}
      <div class="px-8 py-6">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b-2 border-gray-800">
              <th class="pb-3 text-left font-bold text-gray-800 w-8">Item</th>
              <th class="pb-3 text-left font-bold text-gray-800">Deskripsi</th>
              <th class="pb-3 text-center font-bold text-gray-800 w-12">Qty</th>
              <th class="pb-3 text-right font-bold text-gray-800 w-32">Rate</th>
              <th class="pb-3 text-right font-bold text-gray-800 w-32">Amount</th>
            </tr>
          </thead>
          <tbody>
            {{-- Row 1: Selalu Jasa Servis --}}
            <tr class="border-b border-gray-100">
              <td class="py-2.5 text-gray-400 text-xs">1</td>
              <td class="py-2.5 text-gray-700">
                Jasa Servis — {{ $labelKerusakan[$servis->jenis_kerusakan] ?? $servis->jenis_kerusakan }}
                <br><span class="text-xs text-gray-400">{{ $labelPerangkat[$servis->perangkat] ?? $servis->perangkat }}</span>
              </td>
              <td class="py-2.5 text-center text-gray-600">1</td>
              <td class="py-2.5 text-right text-gray-600">Rp {{ number_format($biayaServis, 0, ',', '.') }}</td>
              <td class="py-2.5 text-right font-semibold text-gray-800">Rp {{ number_format($biayaServis, 0, ',', '.') }}</td>
            </tr>

            {{-- Baris tambahan untuk spareparts/items --}}
            @if($items->isNotEmpty())
              @foreach($items as $i => $item)
              <tr class="border-b border-gray-100">
                <td class="py-2.5 text-gray-400 text-xs">{{ $i + 2 }}</td>
                <td class="py-2.5 text-gray-700">
                  {{ $item->nama_item }}
                  @if($item->catatan)
                    <br><span class="text-xs text-gray-400">{{ $item->catatan }}</span>
                  @endif
                </td>
                <td class="py-2.5 text-center text-gray-600">{{ $item->qty }}</td>
                <td class="py-2.5 text-right text-gray-600">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                <td class="py-2.5 text-right font-semibold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
              </tr>
              @endforeach
            @endif
          </tbody>
        </table>

        {{-- Totals --}}
        <div class="flex justify-end mt-4">
          <div class="w-full sm:w-64">
            <div class="flex justify-between py-2 border-b border-gray-100 text-sm">
              <span class="font-semibold text-gray-700">Subtotal</span>
              <span class="text-gray-700">Rp {{ number_format($subtotal > 0 ? $subtotal : ($servis->estimasi_harga ?? 0), 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100 text-sm">
              <span class="font-semibold text-gray-700">Discount</span>
              <span class="text-gray-500">Rp 0</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100 text-sm">
              <span class="font-semibold text-gray-700">Tax Rate</span>
              <span class="text-gray-500">0%</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-200 text-sm">
              <span class="font-semibold text-gray-700">Tax</span>
              <span class="text-gray-500">Rp 0</span>
            </div>
            <div class="flex justify-between py-3 text-base font-black">
              <span class="text-gray-800">Total</span>
              <span class="bg-orange-600 text-white px-4 py-1 rounded text-sm font-bold">
                Rp {{ number_format($subtotal > 0 ? $subtotal : ($servis->estimasi_harga ?? 0), 0, ',', '.') }}
              </span>
            </div>
          </div>
        </div>

        {{-- Terms --}}
        <div class="mt-4 pt-4 border-t border-gray-100">
          <p class="text-xs font-bold text-gray-700 mb-1">Terms</p>
          <p class="text-xs text-gray-500 leading-relaxed">
            Perbaikan bergaransi <strong>30 hari</strong> untuk kerusakan yang sama sejak tanggal pengambilan.
            Invoice ini merupakan bukti sah transaksi di Geeko Komputer.
          </p>
        </div>
      </div>

      {{-- Footer bar --}}
      <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-8 py-4 text-center">
        <p class="text-white font-semibold text-sm">Thank you for your business!</p>
      </div>

    </div>
  </div>
</body>
</html>
