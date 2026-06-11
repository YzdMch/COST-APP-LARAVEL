@php
  $labelPerangkat = \App\Models\Servis::labelPerangkat();
  $labelKerusakan = \App\Models\Servis::labelKerusakan();
  $subtotal = $items->sum('subtotal');
@endphp

<x-app-layout>
  <x-slot:title>Edit Invoice — {{ $servis->nomor_tiket }}</x-slot:title>

  <main class="max-w-4xl mx-auto px-5 py-10">

    {{-- Flash messages --}}
    @if(session('pesan') === 'item_added')
      <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 mb-5 flex items-center gap-3 text-sm text-green-700">
        <i class="fas fa-check-circle text-green-400"></i> Item berhasil ditambahkan.
      </div>
    @elseif(session('pesan') === 'item_deleted')
      <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 mb-5 flex items-center gap-3 text-sm text-green-700">
        <i class="fas fa-check-circle text-green-400"></i> Item berhasil dihapus.
      </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
      <a href="{{ route('servis.invoice', $servis) }}" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 transition">
        <i class="fas fa-arrow-left"></i>
      </a>
      <div class="flex-1">
        <h1 class="text-xl font-extrabold text-gray-800">Edit Invoice</h1>
        <p class="text-sm text-gray-400">Tiket <span class="font-mono font-bold text-yellow-600">{{ $servis->nomor_tiket }}</span> — {{ $servis->nama_pelanggan }}</p>
      </div>
      <a href="{{ route('servis.invoice', $servis) }}"
         class="flex items-center gap-2 bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-500 hover:to-amber-600 text-gray-900 font-bold px-5 py-2 rounded-xl shadow-md shadow-yellow-200 transition-all text-sm">
        <i class="fas fa-file-invoice"></i> Lihat Invoice
      </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      {{-- Left: Info servis --}}
      <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-4">
          <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Info Servis</p>
          <div class="space-y-2.5 text-sm">
            <div class="flex justify-between"><span class="text-gray-400">Perangkat</span><span class="font-semibold text-gray-700 text-right">{{ $labelPerangkat[$servis->perangkat] ?? $servis->perangkat }}</span></div>
            <div class="flex justify-between"><span class="text-gray-400">Kerusakan</span><span class="font-semibold text-gray-700 text-right">{{ $labelKerusakan[$servis->jenis_kerusakan] ?? $servis->jenis_kerusakan }}</span></div>
            <div class="flex justify-between"><span class="text-gray-400">Teknisi</span><span class="font-semibold text-gray-700">{{ $servis->teknisi?->name ?? '-' }}</span></div>
          </div>
        </div>

        {{-- Summary --}}
        <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-5 text-white">
          <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-3">Ringkasan Invoice</p>
          <div class="space-y-2 text-sm mb-4">
            <div class="flex justify-between">
              <span class="text-gray-400">Jumlah item</span>
              <span class="font-semibold">{{ $items->count() }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-400">Subtotal</span>
              <span class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
          </div>
          <div class="border-t border-gray-700 pt-3 flex justify-between items-center">
            <span class="text-white font-bold">Total</span>
            <span class="text-yellow-400 font-black text-lg">
              Rp {{ number_format($subtotal > 0 ? $subtotal : ($servis->estimasi_harga ?? 0), 0, ',', '.') }}
            </span>
          </div>
        </div>
      </div>

      {{-- Right: Items table + add form --}}
      <div class="lg:col-span-2 space-y-5">

        {{-- Current items --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
          <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
              <i class="fas fa-list text-yellow-500"></i> Daftar Item Invoice
            </h2>
            <span class="text-xs text-gray-400">{{ $items->count() }} item</span>
          </div>

          @if($items->isEmpty())
            <div class="py-12 text-center text-gray-300">
              <i class="fas fa-box-open text-4xl mb-2 block"></i>
              <p class="text-sm">Belum ada item. Tambah menggunakan form di bawah.</p>
            </div>
          @else
            <table class="w-full text-sm">
              <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Nama Item</th>
                  <th class="px-4 py-3 text-center text-xs font-semibold text-gray-400 uppercase w-14">Qty</th>
                  <th class="px-4 py-3 text-right text-xs font-semibold text-gray-400 uppercase w-32">Harga/unit</th>
                  <th class="px-4 py-3 text-right text-xs font-semibold text-gray-400 uppercase w-32">Subtotal</th>
                  <th class="w-10"></th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-50">
                @foreach($items as $item)
                <tr class="hover:bg-gray-50/50">
                  <td class="px-4 py-3">
                    <p class="font-medium text-gray-700">{{ $item->nama_item }}</p>
                    @if($item->catatan)
                      <p class="text-xs text-gray-400">{{ $item->catatan }}</p>
                    @endif
                    <p class="text-xs text-gray-300">{{ $item->createdBy?->name }} · {{ $item->created_at->format('d M Y, H:i') }}</p>
                  </td>
                  <td class="px-4 py-3 text-center text-gray-600 font-medium">{{ $item->qty }}</td>
                  <td class="px-4 py-3 text-right text-gray-600">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                  <td class="px-4 py-3 text-right font-bold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                  <td class="px-4 py-3">
                    <form method="POST" action="{{ route('invoice.deleteItem', $item) }}"
                          onsubmit="return confirm('Hapus item ini dari invoice?')">
                      @csrf @method('DELETE')
                      <button type="submit"
                        class="w-7 h-7 rounded-lg bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 flex items-center justify-center transition">
                        <i class="fas fa-trash text-[10px]"></i>
                      </button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          @endif
        </div>

        {{-- Add new item form --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
          <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-plus-circle text-yellow-500"></i> Tambah Item Baru
          </h2>
          <form method="POST" action="{{ route('invoice.addItem', $servis) }}" id="addItemForm">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
              <div class="sm:col-span-2">
                <label class="block text-gray-700 font-semibold mb-1.5 text-sm">Nama Item / Komponen <span class="text-red-500">*</span></label>
                <input type="text" name="nama_item" required maxlength="255"
                  placeholder="Misal: LCD Panel LG 13.3 inch FHD"
                  class="w-full border border-gray-200 rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition">
              </div>
              <div>
                <label class="block text-gray-700 font-semibold mb-1.5 text-sm">Qty <span class="text-red-500">*</span></label>
                <input type="number" name="qty" required min="1" max="999" value="1" id="addQty"
                  oninput="calcPreview()"
                  class="w-full border border-gray-200 rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition">
              </div>
              <div>
                <label class="block text-gray-700 font-semibold mb-1.5 text-sm">Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="harga_satuan" required min="0" step="1000" id="addHarga"
                  placeholder="850000" oninput="calcPreview()"
                  class="w-full border border-gray-200 rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition">
              </div>
              <div class="sm:col-span-2">
                <label class="block text-gray-700 font-semibold mb-1.5 text-sm">Catatan <span class="text-gray-400 font-normal">(opsional)</span></label>
                <input type="text" name="catatan" maxlength="255"
                  placeholder="Misal: merk original, garansi 3 bulan"
                  class="w-full border border-gray-200 rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition">
              </div>
            </div>

            {{-- Preview subtotal --}}
            <div id="addPreview" class="hidden bg-yellow-50 border border-yellow-100 rounded-xl px-4 py-3 mb-4 flex items-center justify-between text-sm">
              <span class="text-gray-600">Subtotal item ini:</span>
              <span id="addSubtotal" class="font-bold text-yellow-700 text-base">Rp 0</span>
            </div>

            <button type="submit"
              class="w-full bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-500 hover:to-amber-600 text-gray-900 font-bold py-3 rounded-xl shadow-md shadow-yellow-200 transition-all flex items-center justify-center gap-2">
              <i class="fas fa-plus"></i> Tambah ke Invoice
            </button>
          </form>
        </div>

      </div>
    </div>
  </main>

  <script>
    function calcPreview() {
      const qty   = parseFloat(document.getElementById('addQty').value) || 0;
      const harga = parseFloat(document.getElementById('addHarga').value) || 0;
      const sub   = qty * harga;
      const preview = document.getElementById('addPreview');
      const span = document.getElementById('addSubtotal');
      if (sub > 0) {
        span.textContent = 'Rp ' + sub.toLocaleString('id-ID');
        preview.classList.remove('hidden');
      } else {
        preview.classList.add('hidden');
      }
    }
  </script>
</x-app-layout>
