@php
  $labelPerangkat = \App\Models\Servis::labelPerangkat();
  $labelKerusakan = \App\Models\Servis::labelKerusakan();
  $statusClass = \App\Models\Servis::statusClasses();
  $pesan = session('pesan');
  $tiketHighlight = session('tiket_highlight');
  $allStatuses = ['Diterima', 'Sedang dicek', 'Perbaikan', 'Testing', 'Selesai'];
@endphp

<x-app-layout>
  <x-slot:title>Dashboard Teknisi</x-slot:title>

  <main class="max-w-7xl mx-auto px-4 py-8">

    <div class="mb-8">
      <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800">Dashboard Teknisi</h1>
      <p class="text-gray-400 mt-1">Kelola dan update progres perbaikan pelanggan — <span class="text-yellow-600 font-semibold">{{ $cabangNama }}</span></p>
    </div>

    {{-- Alerts --}}
    @if($pesan)
      <div id="alertPesan" class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-4 mb-6 flex items-center justify-between">
        <span class="text-green-700 text-sm flex items-center gap-2">
          <i class="fas fa-check-circle"></i>
          @if($pesan === 'hapus_berhasil') Data servis berhasil dihapus.
          @elseif($pesan === 'edit_berhasil') Data servis berhasil diperbarui{{ $tiketHighlight ? ' — ' . $tiketHighlight : '' }}.
          @elseif($pesan === 'status_berhasil') Status berhasil diupdate{{ $tiketHighlight ? ' — ' . $tiketHighlight : '' }}.
          @elseif($pesan === 'harga_berhasil') Harga berhasil diperbarui{{ $tiketHighlight ? ' — ' . $tiketHighlight : '' }}.
          @endif
        </span>
        <button onclick="document.getElementById('alertPesan').remove()" class="text-green-500 hover:text-green-700"><i class="fas fa-times"></i></button>
      </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between"><div><p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Total</p><p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $totalServis }}</p></div><div class="w-11 h-11 rounded-xl bg-yellow-50 flex items-center justify-center"><i class="fas fa-tools text-yellow-500"></i></div></div>
      </div>
      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between"><div><p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Selesai</p><p class="text-3xl font-extrabold text-green-600 mt-1">{{ $totalSelesai }}</p></div><div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center"><i class="fas fa-check-circle text-green-500"></i></div></div>
      </div>
      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between"><div><p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Proses</p><p class="text-3xl font-extrabold text-amber-600 mt-1">{{ $totalProses }}</p></div><div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center"><i class="fas fa-spinner text-amber-500"></i></div></div>
      </div>
      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between"><div><p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Revenue</p><p class="text-xl font-extrabold text-gray-800 mt-1">{{ 'Rp ' . number_format($totalRevenue, 0, ',', '.') }}</p></div><div class="w-11 h-11 rounded-xl bg-violet-50 flex items-center justify-center"><i class="fas fa-coins text-violet-500"></i></div></div>
      </div>
    </div>

    {{-- Status pills --}}
    <div class="flex flex-wrap gap-2 mb-8">
      @foreach($statusBreakdown as $status => $count)
        <button onclick="filterStatus('{{ $status }}')" class="status-pill px-4 py-2 rounded-xl text-xs font-semibold border transition-all {{ $statusClass[$status] ?? '' }} border-transparent hover:shadow-md cursor-pointer">
          {{ $status }} <span class="ml-1 font-bold">{{ $count }}</span>
        </button>
      @endforeach
      <button onclick="filterStatus('')" class="px-4 py-2 rounded-xl text-xs font-semibold border border-gray-200 text-gray-500 hover:bg-gray-50 transition cursor-pointer">
        Semua <span class="ml-1 font-bold">{{ $totalServis }}</span>
      </button>
    </div>

    {{-- Recent Activity --}}
    @if($recentLogs->isNotEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-8">
      <h3 class="font-bold text-gray-800 text-sm mb-4 flex items-center gap-2"><i class="fas fa-bell text-yellow-500"></i> Aktivitas Terakhir</h3>
      <div class="space-y-3">
        @foreach($recentLogs as $log)
          <div class="flex items-center gap-3 text-sm">
            <div class="w-2 h-2 rounded-full {{ $log->status === 'Selesai' ? 'bg-green-400' : 'bg-yellow-400' }} flex-shrink-0"></div>
            <span class="text-gray-500 flex-1">
              <span class="font-semibold text-gray-700">{{ $log->servis?->nomor_tiket }}</span> → {{ $log->status }}
              @if($log->catatan) — <span class="text-gray-400 italic">{{ Str::limit($log->catatan, 40) }}</span> @endif
            </span>
            <span class="text-xs text-gray-400 flex-shrink-0">{{ $log->updated_at->diffForHumans() }}</span>
          </div>
        @endforeach
      </div>
    </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <h2 class="font-bold text-gray-800 flex items-center gap-2"><i class="fas fa-list text-yellow-500"></i> Daftar Servis</h2>
        <div class="relative">
          <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
          <input type="text" placeholder="Cari tiket / nama..." oninput="searchTable(this.value)"
            class="pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent w-48">
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full">
          <thead>
            <tr class="bg-gray-50/50">
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Tiket</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Pelanggan</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Perangkat</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Kerusakan</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Harga</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Teknisi</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Status</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            @forelse($semuaServis as $s)
              @php $statusIdx = array_search($s->status, $allStatuses); @endphp
              <tr class="hover:bg-gray-50/50 transition servis-row" data-status="{{ $s->status }}" data-tiket="{{ $s->nomor_tiket }}" data-nama="{{ strtolower($s->nama_pelanggan) }}">
                <td class="px-5 py-4 font-mono text-sm text-yellow-600 font-bold">{{ $s->nomor_tiket }}</td>
                <td class="px-5 py-4 text-sm">
                  <p class="font-medium text-gray-700">{{ $s->nama_pelanggan }}</p>
                  <p class="text-xs text-gray-400">{{ $s->no_telepon }}</p>
                </td>
                <td class="px-5 py-4 text-sm text-gray-600">{{ $labelPerangkat[$s->perangkat] ?? $s->perangkat }}</td>
                <td class="px-5 py-4 text-sm text-gray-600">{{ $labelKerusakan[$s->jenis_kerusakan] ?? $s->jenis_kerusakan }}</td>
                <td class="px-5 py-4 text-sm font-bold text-yellow-600">{{ $s->estimasi_harga ? 'Rp ' . number_format($s->estimasi_harga, 0, ',', '.') : '-' }}</td>
                <td class="px-5 py-4 text-sm">
                  @if($s->teknisi)
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">{{ $s->teknisi->name }}</span>
                  @else
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Unassigned</span>
                  @endif
                </td>
                <td class="px-5 py-4">
                  <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass[$s->status] ?? 'bg-gray-100 text-gray-700' }}">{{ $s->status }}</span>
                </td>
                <td class="px-5 py-4">
                  <div class="flex items-center gap-1.5">
                    {{-- Update status (disabled if Selesai) --}}
                    @if($s->status !== 'Selesai')
                      <button onclick="openStatusModal({{ $s->id }}, '{{ $s->nomor_tiket }}', '{{ $s->nama_pelanggan }}', '{{ $labelPerangkat[$s->perangkat] ?? $s->perangkat }}', '{{ $s->status }}', {{ $statusIdx }})"
                        class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 flex items-center justify-center transition" title="Update Status">
                        <i class="fas fa-arrow-right text-xs"></i>
                      </button>
                    @else
                      <div class="w-8 h-8 rounded-lg bg-green-50 text-green-400 flex items-center justify-center" title="Selesai">
                        <i class="fas fa-check text-xs"></i>
                      </div>
                    @endif
                    {{-- Update harga --}}
                    <button onclick="openHargaModal({{ $s->id }}, '{{ $s->nomor_tiket }}', '{{ $s->nama_pelanggan }}', {{ $s->estimasi_harga ?? 0 }})"
                      class="w-8 h-8 rounded-lg bg-violet-50 text-violet-600 hover:bg-violet-100 flex items-center justify-center transition" title="Update Harga">
                      <i class="fas fa-tag text-xs"></i>
                    </button>
                    {{-- Detail --}}
                    <a href="{{ route('servis.show', $s) }}"
                      class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition" title="Detail">
                      <i class="fas fa-eye text-xs"></i>
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="8" class="px-6 py-14 text-center text-gray-300"><i class="fas fa-inbox text-4xl mb-2 block"></i>Belum ada data servis masuk.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </main>

  {{-- MODAL UPDATE STATUS --}}
  <div id="modalStatus" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden px-4" onclick="if(event.target===this)closeStatusModal()">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden">
      <div class="bg-gradient-to-r from-yellow-400 to-amber-500 px-6 py-4 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2"><i class="fas fa-arrow-right"></i> Update Status</h3>
        <button onclick="closeStatusModal()" class="text-gray-700 hover:text-gray-900"><i class="fas fa-times text-lg"></i></button>
      </div>
      <div class="p-6">
        <div class="mb-5 bg-gray-50 rounded-xl p-4 space-y-1.5 text-sm">
          <p><span class="text-gray-400">Tiket:</span> <span id="sTiket" class="font-mono text-yellow-600 font-bold"></span></p>
          <p><span class="text-gray-400">Pelanggan:</span> <span id="sPelanggan" class="font-semibold text-gray-700"></span></p>
          <p><span class="text-gray-400">Perangkat:</span> <span id="sPerangkat" class="text-gray-700"></span></p>
          <p><span class="text-gray-400">Status saat ini:</span> <span id="sCurrentStatus" class="font-semibold text-amber-600"></span></p>
        </div>
        <form id="statusForm" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2 text-sm">Status Berikutnya <span class="text-red-500">*</span></label>
            <select name="status" id="sStatus" class="w-full border border-gray-200 rounded-xl py-3 px-4 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent focus:bg-white">
              {{-- Options will be populated by JS (only forward statuses) --}}
            </select>
            <p class="text-xs text-gray-400 mt-1.5"><i class="fas fa-info-circle mr-1"></i>Status hanya bisa dimajukan, tidak bisa dikembalikan</p>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2 text-sm">Catatan Tindakan <span class="text-red-500">*</span></label>
            <textarea name="catatan" rows="3" required placeholder="Jelaskan tindakan yang dilakukan, contoh: LCD sudah diganti panel baru merk LG, tinggal testing..."
              class="w-full border border-gray-200 rounded-xl py-3 px-4 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent focus:bg-white"></textarea>
          </div>
          <div class="mb-5">
            <label class="block text-gray-700 font-semibold mb-2 text-sm">Foto Bukti <span class="text-gray-400 font-normal">(opsional)</span></label>
            <input type="file" name="foto" accept="image/*"
              class="w-full text-gray-500 border border-gray-200 rounded-xl p-2.5 bg-gray-50 text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-yellow-100 file:text-yellow-700 file:font-semibold file:text-xs">
          </div>
          <div class="flex gap-3">
            <button type="button" onclick="closeStatusModal()" class="flex-1 border border-gray-200 text-gray-600 font-semibold py-3 rounded-xl hover:bg-gray-50 transition">Batal</button>
            <button type="submit" class="flex-1 bg-gradient-to-r from-yellow-400 to-amber-500 text-gray-900 font-bold py-3 rounded-xl shadow-lg shadow-yellow-200 transition-all flex items-center justify-center gap-2">
              <i class="fas fa-save"></i> Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- MODAL UPDATE HARGA --}}
  <div id="modalHarga" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden px-4" onclick="if(event.target===this)closeHargaModal()">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden">
      <div class="bg-gradient-to-r from-violet-500 to-purple-600 px-6 py-4 flex justify-between items-center">
        <h3 class="text-lg font-bold text-white flex items-center gap-2"><i class="fas fa-tag"></i> Update Harga</h3>
        <button onclick="closeHargaModal()" class="text-white/70 hover:text-white"><i class="fas fa-times text-lg"></i></button>
      </div>
      <div class="p-6">
        <div class="mb-5 bg-gray-50 rounded-xl p-4 space-y-1.5 text-sm">
          <p><span class="text-gray-400">Tiket:</span> <span id="hTiket" class="font-mono text-yellow-600 font-bold"></span></p>
          <p><span class="text-gray-400">Pelanggan:</span> <span id="hPelanggan" class="font-semibold text-gray-700"></span></p>
          <p><span class="text-gray-400">Harga saat ini:</span> <span id="hCurrent" class="font-bold text-yellow-600"></span></p>
        </div>
        <form id="hargaForm" method="POST">
          @csrf
          <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2 text-sm">Harga Final (Rp) <span class="text-red-500">*</span></label>
            <input type="number" name="estimasi_harga" id="hHarga" required min="0" placeholder="Contoh: 1500000"
              class="w-full border border-gray-200 rounded-xl py-3 px-4 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-violet-400 focus:border-transparent focus:bg-white">
          </div>
          <div class="mb-5">
            <label class="block text-gray-700 font-semibold mb-2 text-sm">Alasan Perubahan <span class="text-red-500">*</span></label>
            <textarea name="catatan_harga" rows="2" required placeholder="Contoh: Setelah pengecekan, perlu ganti komponen tambahan..."
              class="w-full border border-gray-200 rounded-xl py-3 px-4 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-violet-400 focus:border-transparent focus:bg-white"></textarea>
            <p class="text-xs text-gray-400 mt-1.5"><i class="fas fa-info-circle mr-1"></i>Alasan ini akan terlihat oleh pelanggan di timeline servis</p>
          </div>
          <div class="flex gap-3">
            <button type="button" onclick="closeHargaModal()" class="flex-1 border border-gray-200 text-gray-600 font-semibold py-3 rounded-xl hover:bg-gray-50 transition">Batal</button>
            <button type="submit" class="flex-1 bg-gradient-to-r from-violet-500 to-purple-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-violet-200 transition-all flex items-center justify-center gap-2">
              <i class="fas fa-save"></i> Konfirmasi Harga
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    const allStatuses = ['Diterima', 'Sedang dicek', 'Perbaikan', 'Testing', 'Selesai'];

    function filterStatus(val) {
      document.querySelectorAll('.servis-row').forEach(r => r.style.display = (!val || r.dataset.status === val) ? '' : 'none');
    }
    function searchTable(q) {
      q = q.toLowerCase();
      document.querySelectorAll('.servis-row').forEach(r => {
        r.style.display = (r.dataset.tiket.toLowerCase().includes(q) || r.dataset.nama.includes(q)) ? '' : 'none';
      });
    }

    // Status modal — only show FORWARD statuses
    function openStatusModal(id, tiket, pelanggan, perangkat, currentStatus, currentIdx) {
      document.getElementById('statusForm').action = '/servis/' + id + '/status';
      document.getElementById('sTiket').textContent = tiket;
      document.getElementById('sPelanggan').textContent = pelanggan;
      document.getElementById('sPerangkat').textContent = perangkat;
      document.getElementById('sCurrentStatus').textContent = currentStatus;

      const sel = document.getElementById('sStatus');
      sel.innerHTML = '';
      allStatuses.forEach((s, i) => {
        if (i > currentIdx) {
          const opt = document.createElement('option');
          opt.value = s; opt.textContent = s;
          sel.appendChild(opt);
        }
      });
      document.getElementById('modalStatus').classList.remove('hidden');
    }
    function closeStatusModal() { document.getElementById('modalStatus').classList.add('hidden'); }

    // Harga modal
    function openHargaModal(id, tiket, pelanggan, harga) {
      document.getElementById('hargaForm').action = '/servis/' + id + '/harga';
      document.getElementById('hTiket').textContent = tiket;
      document.getElementById('hPelanggan').textContent = pelanggan;
      document.getElementById('hCurrent').textContent = harga ? 'Rp ' + parseInt(harga).toLocaleString('id-ID') : 'Belum ditentukan';
      document.getElementById('hHarga').value = harga || '';
      document.getElementById('modalHarga').classList.remove('hidden');
    }
    function closeHargaModal() { document.getElementById('modalHarga').classList.add('hidden'); }

    // Highlight
    const hl = '{{ $tiketHighlight ?? '' }}';
    if (hl) { const row = document.querySelector(`.servis-row[data-tiket="${hl}"]`); if (row) { row.classList.add('bg-yellow-50'); row.scrollIntoView({ behavior: 'smooth', block: 'center' }); } }
  </script>
</x-app-layout>
