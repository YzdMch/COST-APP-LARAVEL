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
          @if($pesan === 'status_berhasil') Status berhasil diupdate{{ $tiketHighlight ? ' — ' . $tiketHighlight : '' }}.
          @elseif($pesan === 'batal_berhasil') Booking {{ $tiketHighlight }} telah dibatalkan.
          @else Berhasil!
          @endif
        </span>
        <button onclick="document.getElementById('alertPesan').remove()" class="text-green-500 hover:text-green-700"><i class="fas fa-times"></i></button>
      </div>
    @endif

    {{-- Cancellation Alert --}}
    @if($pembatalanBaru > 0)
      <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-red-500 flex items-center justify-center flex-shrink-0">
          <i class="fas fa-bell text-white text-sm"></i>
        </div>
        <div class="flex-1">
          <p class="font-bold text-red-800">{{ $pembatalanBaru }} Pembatalan Baru</p>
          <p class="text-red-600 text-sm">Ada pelanggan yang membatalkan booking di cabang Anda.</p>
        </div>
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
            <div class="w-2 h-2 rounded-full {{ $log->status === 'Selesai' ? 'bg-green-400' : ($log->status === 'Dibatalkan' ? 'bg-red-400' : 'bg-yellow-400') }} flex-shrink-0"></div>
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
                <td class="px-5 py-4 text-sm text-gray-600">
                  <p>{{ $labelPerangkat[$s->perangkat] ?? $s->perangkat }}</p>
                  <p class="text-xs text-gray-400">{{ $labelKerusakan[$s->jenis_kerusakan] ?? $s->jenis_kerusakan }}</p>
                </td>
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
                    {{-- Update Progres (disabled if Selesai or Dibatalkan) --}}
                    @if(!in_array($s->status, ['Selesai', 'Dibatalkan']))
                      @php
                        $nextStatus = $allStatuses[($statusIdx !== false ? $statusIdx : -1) + 1] ?? null;
                      @endphp
                      @if($nextStatus)
                        <button onclick="openUpdateModal(this, {{ $s->id }}, '{{ $s->nomor_tiket }}', '{{ addslashes($s->nama_pelanggan) }}', '{{ addslashes($labelPerangkat[$s->perangkat] ?? $s->perangkat) }}', '{{ $s->status }}', '{{ $nextStatus }}', {{ $s->biaya_jasa ?? 50000 }})"
                          data-items="{{ json_encode($s->invoiceItems->map(fn($i) => ['id'=>$i->id, 'nama_item'=>$i->nama_item, 'qty'=>$i->qty, 'harga_satuan'=>$i->harga_satuan])->values()) }}"
                          class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-yellow-50 text-yellow-700 hover:bg-yellow-100 transition text-xs font-semibold" title="Update Progres">
                          <i class="fas fa-arrow-right text-xs"></i> Update
                        </button>
                      @endif
                    @elseif($s->status === 'Selesai')
                      <a href="{{ route('servis.invoice', $s) }}"
                        class="flex items-center gap-1 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition text-xs font-semibold" title="Invoice">
                        <i class="fas fa-file-invoice text-xs"></i> Invoice
                      </a>
                    @else
                      <span class="px-3 py-1.5 rounded-lg bg-red-50 text-red-400 text-xs font-semibold">Dibatalkan</span>
                    @endif
                    {{-- Detail --}}
                    <a href="{{ route('servis.show', $s) }}"
                      class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition" title="Detail">
                      <i class="fas fa-eye text-xs"></i>
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="7" class="px-6 py-14 text-center text-gray-300"><i class="fas fa-inbox text-4xl mb-2 block"></i>Belum ada data servis masuk.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </main>

  {{-- ═══════════════════════════════════════════════════════════════════ --}}
  {{-- MODAL UPDATE PROGRES TERPADU --}}
  {{-- ═══════════════════════════════════════════════════════════════════ --}}
  <div id="modalUpdate" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-start justify-center z-50 hidden px-4 py-6 overflow-y-auto"
       onclick="if(event.target===this)closeUpdateModal()">
    <div class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden my-auto">
      {{-- Header --}}
      <div class="bg-gradient-to-r from-yellow-400 to-amber-500 px-6 py-4 flex justify-between items-center">
        <div>
          <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-arrow-right"></i> Update Progres
          </h3>
          <p class="text-xs text-gray-700 mt-0.5">
            <span id="uTiket" class="font-mono font-bold"></span> —
            <span id="uPelanggan"></span>
          </p>
        </div>
        <button onclick="closeUpdateModal()" class="text-gray-700 hover:text-gray-900 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-black/10">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <div class="p-6">
        {{-- Info servis --}}
        <div class="bg-gray-50 rounded-xl p-4 mb-5 grid grid-cols-2 gap-3 text-sm">
          <div><span class="text-gray-400">Perangkat:</span> <span id="uPerangkat" class="font-semibold text-gray-700 ml-1"></span></div>
          <div>
            <span class="text-gray-400">Status:</span>
            <span id="uCurrentStatus" class="font-semibold text-amber-600 ml-1"></span>
            <i class="fas fa-arrow-right text-gray-300 mx-1"></i>
            <span id="uNextStatus" class="font-semibold text-green-600"></span>
          </div>
        </div>

        <form id="updateForm" method="POST" enctype="multipart/form-data">
          @csrf

          {{-- 1. Catatan (wajib) --}}
          <div class="mb-5">
            <label class="block text-gray-700 font-bold mb-2 text-sm">
              <i class="fas fa-pen text-yellow-500 mr-1"></i> Catatan Tindakan
              <span class="text-red-500">*</span>
            </label>
            <textarea name="catatan" rows="3" required maxlength="2000"
              placeholder="Jelaskan apa yang sudah dikerjakan. Misal: LCD sudah diganti dengan panel baru merk LG, kondisi bagus..."
              class="w-full border border-gray-200 rounded-xl py-3 px-4 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent focus:bg-white transition text-sm"></textarea>
          </div>

          {{-- 2. Parts/Komponen (opsional) --}}
          <div class="mb-5">
            <div class="flex items-center justify-between mb-2">
              <label class="text-gray-700 font-bold text-sm">
                <i class="fas fa-shopping-cart text-yellow-500 mr-1"></i> Komponen / Parts yang Digunakan
                <span class="text-gray-400 font-normal">(opsional)</span>
              </label>
              <button type="button" onclick="addPartRow()"
                class="text-xs font-semibold text-yellow-600 hover:text-yellow-700 bg-yellow-50 hover:bg-yellow-100 px-3 py-1.5 rounded-lg transition flex items-center gap-1">
                <i class="fas fa-plus text-[10px]"></i> Tambah Item
              </button>
            </div>

            <div class="border border-gray-200 rounded-xl overflow-hidden">
              <table class="w-full text-sm" id="partsTable">
                <thead>
                  <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-400 uppercase">Nama Item / Komponen</th>
                    <th class="px-3 py-2.5 text-center text-xs font-semibold text-gray-400 uppercase w-16">Qty</th>
                    <th class="px-3 py-2.5 text-right text-xs font-semibold text-gray-400 uppercase w-36">Harga/unit (Rp)</th>
                    <th class="px-3 py-2.5 text-right text-xs font-semibold text-gray-400 uppercase w-32">Subtotal</th>
                    <th class="w-10"></th>
                  </tr>
                </thead>
                <tbody id="partRows">
                  {{-- Rows added dynamically by JS --}}
                </tbody>
              </table>
              <div id="emptyParts" class="px-4 py-6 text-center text-gray-300 text-sm">
                <i class="fas fa-box-open text-2xl mb-1 block"></i>
                Belum ada komponen ditambahkan
              </div>
            </div>

            {{-- Total --}}
            <div id="totalPartsRow" class="hidden flex items-center justify-end gap-3 mt-2 px-1">
              <span class="text-sm text-gray-500 font-medium">Total Parts:</span>
              <span id="totalParts" class="text-base font-bold text-yellow-600">Rp 0</span>
            </div>
          </div>

          {{-- 3. Update Biaya Jasa Servis (opsional) --}}
          <div class="mb-5">
            <label class="block text-gray-700 font-bold mb-2 text-sm">
              <i class="fas fa-tools text-yellow-500 mr-1"></i> Update Biaya Jasa Servis
              <span class="text-gray-400 font-normal">(opsional)</span>
            </label>
            <div class="flex items-center gap-3">
              <div class="flex-1 relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-semibold text-sm">Rp</span>
                <input type="number" name="biaya_jasa" id="uBiayaJasa" min="0" step="1000"
                  placeholder="Kosongkan jika tetap (Rp 50.000)"
                  class="w-full border border-gray-200 rounded-xl py-3 pl-10 pr-4 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent focus:bg-white transition text-sm">
              </div>
            </div>
            <p class="text-xs text-gray-400 mt-1.5">
              <i class="fas fa-info-circle mr-1"></i>
              Ubah nilai ini jika tingkat kesulitan servis membutuhkan biaya jasa lebih/kurang dari default.
            </p>
          </div>

          {{-- 4. Foto Bukti (opsional) --}}
          <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2 text-sm">
              <i class="fas fa-camera text-yellow-500 mr-1"></i> Foto Bukti
              <span class="text-gray-400 font-normal">(opsional)</span>
            </label>
            <input type="file" name="foto" id="uFoto" accept="image/*"
              onchange="previewUpdateFoto(this)"
              class="w-full text-gray-500 border border-gray-200 rounded-xl p-2.5 bg-gray-50 text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-yellow-100 file:text-yellow-700 file:font-semibold file:text-xs">
            <div id="updateFotoPreview" class="hidden mt-2">
              <img id="updateFotoImg" src="" alt="Preview" class="max-h-32 rounded-xl object-cover border border-gray-100">
            </div>
          </div>

          {{-- Actions --}}
          <div class="flex gap-3 pt-2 border-t border-gray-100">
            <button type="button" onclick="closeUpdateModal()"
              class="flex-1 border border-gray-200 text-gray-600 font-semibold py-3 rounded-xl hover:bg-gray-50 transition">
              Batal
            </button>
            <button type="submit" id="updateSubmitBtn"
              class="flex-1 bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-500 hover:to-amber-600 text-gray-900 font-bold py-3 rounded-xl shadow-lg shadow-yellow-200 transition-all flex items-center justify-center gap-2">
              <i class="fas fa-save"></i>
              <span>Simpan & Update ke <span id="uNextStatusBtn" class="underline"></span></span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    // ── Filter & Search ──────────────────────────────────────────────
    function filterStatus(val) {
      document.querySelectorAll('.servis-row').forEach(r => r.style.display = (!val || r.dataset.status === val) ? '' : 'none');
    }
    function searchTable(q) {
      q = q.toLowerCase();
      document.querySelectorAll('.servis-row').forEach(r => {
        r.style.display = (r.dataset.tiket.toLowerCase().includes(q) || r.dataset.nama.includes(q)) ? '' : 'none';
      });
    }

    // ── Update Modal ─────────────────────────────────────────────────
    function openUpdateModal(btn, id, tiket, pelanggan, perangkat, currentStatus, nextStatus, biayaJasa) {
      document.getElementById('updateForm').action = '/servis/' + id + '/status';
      document.getElementById('uTiket').textContent = tiket;
      document.getElementById('uPelanggan').textContent = pelanggan;
      document.getElementById('uPerangkat').textContent = perangkat;
      document.getElementById('uCurrentStatus').textContent = currentStatus;
      document.getElementById('uNextStatus').textContent = nextStatus;
      document.getElementById('uNextStatusBtn').textContent = nextStatus;
      document.getElementById('uBiayaJasa').placeholder = biayaJasa > 0
        ? 'Saat ini: Rp ' + parseInt(biayaJasa).toLocaleString('id-ID')
        : 'Kosongkan jika tetap';

      // Reset
      document.getElementById('partRows').innerHTML = '';
      partCount = 0;
      
      const itemsRaw = btn.getAttribute('data-items');
      if (itemsRaw) {
        try {
          const parsed = JSON.parse(itemsRaw);
          const items = Array.isArray(parsed) ? parsed : Object.values(parsed);
          items.forEach(item => {
            addPartRow(item);
          });
        } catch(e) {}
      }
      
      if (document.querySelectorAll('.part-row').length === 0) {
        document.getElementById('emptyParts').classList.remove('hidden');
        document.getElementById('totalPartsRow').classList.add('hidden');
      } else {
        document.getElementById('emptyParts').classList.add('hidden');
        document.getElementById('totalPartsRow').classList.remove('hidden');
      }
      
      document.getElementById('uBiayaJasa').value = '';
      document.getElementById('uFoto').value = '';
      document.getElementById('updateFotoPreview').classList.add('hidden');
      document.querySelector('#updateForm textarea[name=catatan]').value = '';

      document.getElementById('modalUpdate').classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }
    function closeUpdateModal() {
      document.getElementById('modalUpdate').classList.add('hidden');
      document.body.style.overflow = '';
    }

    // ── Parts rows ───────────────────────────────────────────────────
    let partCount = 0;

    function addPartRow(item = null) {
      partCount++;
      const idx = partCount;
      const tbody = document.getElementById('partRows');
      const tr = document.createElement('tr');
      tr.className = 'border-t border-gray-100 part-row';
      tr.id = 'partRow_' + idx;
      
      const idVal = item ? item.id : '';
      const namaVal = item ? item.nama_item.replace(/"/g, '&quot;') : '';
      const qtyVal = item ? item.qty : 1;
      const hargaVal = item ? item.harga_satuan : '';
      
      tr.innerHTML = `
        <input type="hidden" name="items[${idx}][id]" value="${idVal}">
        <td class="px-2 py-2">
          <input type="text" name="items[${idx}][nama_item]" required value="${namaVal}"
            placeholder="Misal: LCD Panel LG 13.3 inch"
            class="w-full border border-gray-200 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-1 focus:ring-yellow-400 focus:border-transparent">
        </td>
        <td class="px-2 py-2">
          <input type="number" name="items[${idx}][qty]" required min="1" max="999" value="${qtyVal}"
            onchange="calcSubtotal(${idx})" oninput="calcSubtotal(${idx})"
            class="w-full border border-gray-200 rounded-lg py-2 px-2 text-sm text-center focus:outline-none focus:ring-1 focus:ring-yellow-400">
        </td>
        <td class="px-2 py-2">
          <input type="number" name="items[${idx}][harga_satuan]" required min="0" step="1000" value="${hargaVal}"
            placeholder="0" onchange="calcSubtotal(${idx})" oninput="calcSubtotal(${idx})"
            class="w-full border border-gray-200 rounded-lg py-2 px-3 text-sm text-right focus:outline-none focus:ring-1 focus:ring-yellow-400">
        </td>
        <td class="px-2 py-2 text-right">
          <span id="sub_${idx}" class="font-semibold text-gray-700 text-sm">Rp 0</span>
        </td>
        <td class="px-2 py-2">
          <button type="button" onclick="removePartRow(${idx})" class="w-7 h-7 rounded-lg bg-red-50 text-red-400 hover:bg-red-100 flex items-center justify-center">
            <i class="fas fa-trash text-[10px]"></i>
          </button>
        </td>
      `;
      tbody.appendChild(tr);
      if (item) {
        calcSubtotal(idx);
      }
      document.getElementById('emptyParts').classList.add('hidden');
      calcTotal();
    }

    function removePartRow(idx) {
      document.getElementById('partRow_' + idx)?.remove();
      calcTotal();
      if (!document.querySelector('.part-row')) {
        document.getElementById('emptyParts').classList.remove('hidden');
        document.getElementById('totalPartsRow').classList.add('hidden');
      }
    }

    function calcSubtotal(idx) {
      const qty   = parseFloat(document.querySelector(`[name="items[${idx}][qty]"]`)?.value) || 0;
      const harga = parseFloat(document.querySelector(`[name="items[${idx}][harga_satuan]"]`)?.value) || 0;
      const sub   = qty * harga;
      const el = document.getElementById('sub_' + idx);
      if (el) el.textContent = 'Rp ' + sub.toLocaleString('id-ID');
      calcTotal();
    }

    function calcTotal() {
      let total = 0;
      document.querySelectorAll('.part-row').forEach(row => {
        const idx = row.id.replace('partRow_', '');
        const qty   = parseFloat(document.querySelector(`[name="items[${idx}][qty]"]`)?.value) || 0;
        const harga = parseFloat(document.querySelector(`[name="items[${idx}][harga_satuan]"]`)?.value) || 0;
        total += qty * harga;
      });
      document.getElementById('totalParts').textContent = 'Rp ' + total.toLocaleString('id-ID');
      document.getElementById('totalPartsRow').classList.toggle('hidden', total === 0);
    }

    // ── Foto preview ─────────────────────────────────────────────────
    function previewUpdateFoto(input) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
          document.getElementById('updateFotoImg').src = e.target.result;
          document.getElementById('updateFotoPreview').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
      }
    }

    // ── Highlight row ─────────────────────────────────────────────────
    const hl = '{{ $tiketHighlight ?? '' }}';
    if (hl) {
      const row = document.querySelector(`.servis-row[data-tiket="${hl}"]`);
      if (row) { row.classList.add('bg-yellow-50'); row.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
    }
  </script>
</x-app-layout>
