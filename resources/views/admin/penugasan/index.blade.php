@php
  $labelPerangkat = \App\Models\Servis::labelPerangkat();
  $labelKerusakan = \App\Models\Servis::labelKerusakan();
  $statusClass = \App\Models\Servis::statusClasses();
  $allStatuses = ['Diterima', 'Sedang dicek', 'Perbaikan', 'Testing', 'Selesai'];
@endphp

<x-admin-layout>
  <x-slot:title>Monitoring Servis</x-slot:title>

  <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-3">
    <div>
      <h2 class="text-xl font-bold text-gray-800">Monitoring Proses Servis</h2>
      <p class="text-sm text-gray-400">Pantau progres servis, assign teknisi, dan deteksi overdue</p>
    </div>
    <div class="flex gap-2">
      @if($stats['unassigned'] > 0)
      <form method="POST" action="{{ route('admin.penugasan.autoAll') }}" class="inline">
        @csrf
        @if($cabangId)<input type="hidden" name="cabang_id" value="{{ $cabangId }}">@endif
        <button type="submit" class="bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold text-sm px-5 py-2.5 rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2"
          onclick="return confirm('Auto-assign {{ $stats['unassigned'] }} servis yang belum di-assign?')">
          <i class="fas fa-magic"></i> Auto-Assign ({{ $stats['unassigned'] }})
        </button>
      </form>
      @endif
    </div>
  </div>

  {{-- Pipeline Stats --}}
  <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3 mb-6">
    <a href="{{ route('admin.penugasan.index', array_merge(request()->only('cabang_id'), ['status' => ''])) }}"
      class="stat-card text-center {{ !$statusFilter ? 'ring-2 ring-yellow-400' : '' }}">
      <p class="text-2xl font-extrabold text-gray-800">{{ $stats['total'] }}</p>
      <p class="text-xs text-gray-400 font-medium">Total</p>
    </a>
    @foreach(['Diterima' => 'diterima', 'Sedang dicek' => 'sedang_dicek', 'Perbaikan' => 'perbaikan', 'Testing' => 'testing', 'Selesai' => 'selesai'] as $label => $key)
    <a href="{{ route('admin.penugasan.index', array_merge(request()->only('cabang_id'), ['status' => $label])) }}"
      class="stat-card text-center {{ $statusFilter === $label ? 'ring-2 ring-yellow-400' : '' }}">
      <p class="text-2xl font-extrabold {{ match($key) { 'selesai' => 'text-green-600', 'diterima' => 'text-blue-600', default => 'text-amber-600' } }}">{{ $stats[$key] }}</p>
      <p class="text-xs text-gray-400 font-medium">{{ $label }}</p>
    </a>
    @endforeach
    <a href="{{ route('admin.penugasan.index', array_merge(request()->only('cabang_id'), ['status' => ''])) }}"
      class="stat-card text-center {{ $stats['unassigned'] > 0 ? 'border-red-200 bg-red-50' : '' }}">
      <p class="text-2xl font-extrabold {{ $stats['unassigned'] > 0 ? 'text-red-600' : 'text-green-600' }}">{{ $stats['unassigned'] }}</p>
      <p class="text-xs text-gray-400 font-medium">Unassigned</p>
    </a>
    <div class="stat-card text-center {{ $stats['overdue'] > 0 ? 'border-red-200 bg-red-50' : '' }}">
      <p class="text-2xl font-extrabold {{ $stats['overdue'] > 0 ? 'text-red-600' : 'text-green-600' }}">{{ $stats['overdue'] }}</p>
      <p class="text-xs text-gray-400 font-medium">Overdue</p>
    </div>
  </div>

  {{-- Overdue Alert --}}
  @if($overdueServis->count() > 0)
  <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6">
    <h3 class="font-bold text-red-700 text-sm mb-2 flex items-center gap-2"><i class="fas fa-exclamation-triangle"></i> {{ $overdueServis->count() }} Servis Overdue</h3>
    <div class="flex flex-wrap gap-2">
      @foreach($overdueServis->take(5) as $s)
      <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
        {{ $s->nomor_tiket }} ({{ abs(round($s->slaRemainingHours())) }}jam overdue)
      </span>
      @endforeach
      @if($overdueServis->count() > 5)
      <span class="text-xs text-red-500">+{{ $overdueServis->count() - 5 }} lainnya</span>
      @endif
    </div>
  </div>
  @endif

  {{-- Filters Bar --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6 flex flex-wrap gap-3 items-end">
    <form method="GET" class="flex flex-wrap gap-3 items-end w-full">
      @if($statusFilter)<input type="hidden" name="status" value="{{ $statusFilter }}">@endif
      <div>
        <label class="text-xs font-semibold text-gray-500 mb-1 block">Cabang</label>
        <select name="cabang_id" onchange="this.form.submit()" class="border border-gray-200 rounded-xl py-2 px-3 text-sm focus:ring-2 focus:ring-yellow-400">
          <option value="">Semua Cabang</option>
          @foreach($cabangList as $c)
          <option value="{{ $c->id }}" {{ $cabangId == $c->id ? 'selected' : '' }}>{{ $c->nama }}</option>
          @endforeach
        </select>
      </div>
    </form>
  </div>

  {{-- Workload Summary --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
    <h3 class="font-bold text-gray-800 text-sm mb-4 flex items-center gap-2"><i class="fas fa-balance-scale text-indigo-500"></i> Beban Kerja Teknisi</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
      @foreach($workload as $w)
      <div class="bg-gray-50 rounded-xl p-3 text-center">
        <div class="w-10 h-10 mx-auto rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold mb-2">
          {{ strtoupper(substr($w->name, 0, 2)) }}
        </div>
        <p class="text-sm font-semibold text-gray-700 truncate">{{ $w->name }}</p>
        <p class="text-xs text-gray-400 truncate">{{ $w->cabang?->nama ?? '-' }}</p>
        <div class="flex justify-center gap-2 mt-2">
          <span class="px-1.5 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-700">{{ $w->active_servis_count }} aktif</span>
          <span class="px-1.5 py-0.5 rounded text-xs font-bold bg-green-100 text-green-700">{{ $w->completed_servis_count }} ✓</span>
        </div>
      </div>
      @endforeach
      @if($workload->isEmpty())
      <p class="col-span-full text-center text-gray-400 py-4">Tidak ada teknisi aktif.</p>
      @endif
    </div>
  </div>

  {{-- Servis Table --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
      <h3 class="font-bold text-gray-800 flex items-center gap-2">
        <i class="fas fa-list text-yellow-500"></i> Daftar Servis
        @if($statusFilter)
        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClass[$statusFilter] ?? 'bg-gray-100' }}">{{ $statusFilter }}</span>
        @endif
      </h3>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="bg-gray-50/50">
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Tiket</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Pelanggan</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Perangkat</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Cabang</th>
            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-400 uppercase">Status</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Teknisi</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">SLA</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Tanggal</th>
            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-400 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($servisList as $s)
          @php $isOverdue = $s->isOverSla(); @endphp
          <tr class="hover:bg-gray-50/50 {{ $isOverdue ? 'bg-red-50/50' : '' }}">
            <td class="px-5 py-3 font-mono text-yellow-600 font-bold text-xs">{{ $s->nomor_tiket }}</td>
            <td class="px-5 py-3">
              <p class="text-gray-700 font-medium">{{ $s->nama_pelanggan }}</p>
              <p class="text-xs text-gray-400">{{ $s->no_telepon }}</p>
            </td>
            <td class="px-5 py-3 text-gray-600">{{ $labelPerangkat[$s->perangkat] ?? $s->perangkat }}</td>
            <td class="px-5 py-3">
              <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-violet-100 text-violet-700">
                {{ $s->cabangRelasi?->nama ?? '-' }}
              </span>
            </td>
            <td class="px-5 py-3 text-center">
              <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClass[$s->status] ?? '' }}">{{ $s->status }}</span>
            </td>
            <td class="px-5 py-3">
              @if($s->teknisi)
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">{{ $s->teknisi->name }}</span>
              @else
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Belum di-assign</span>
              @endif
            </td>
            <td class="px-5 py-3 text-xs">
              @if($s->sla_target_jam)
                @if($s->status === 'Selesai')
                  <span class="text-green-600 font-semibold"><i class="fas fa-check-circle"></i> Selesai</span>
                @elseif($isOverdue)
                  <span class="text-red-600 font-bold"><i class="fas fa-exclamation-triangle"></i> {{ abs(round($s->slaRemainingHours())) }}j overdue</span>
                @else
                  <span class="text-gray-500">{{ round($s->slaRemainingHours()) }}j sisa</span>
                @endif
              @else
                <span class="text-gray-300">—</span>
              @endif
            </td>
            <td class="px-5 py-3 text-gray-400 text-xs">{{ $s->created_at->format('d M') }}</td>
            <td class="px-5 py-3">
              <div class="flex items-center justify-center gap-1.5">
                @if(!$s->teknisi && $s->status !== 'Selesai')
                {{-- Assign button --}}
                <button onclick="openAssignModal({{ $s->id }}, '{{ $s->nomor_tiket }}', '{{ $s->nama_pelanggan }}', {{ $s->cabang_id ?? 'null' }})"
                  class="w-7 h-7 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 flex items-center justify-center transition" title="Assign Teknisi">
                  <i class="fas fa-user-plus text-[10px]"></i>
                </button>
                @endif
                <a href="{{ route('servis.show', $s) }}"
                  class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition" title="Detail">
                  <i class="fas fa-eye text-[10px]"></i>
                </a>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="9" class="px-6 py-14 text-center text-gray-300"><i class="fas fa-inbox text-4xl mb-2 block"></i>Tidak ada data servis.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($servisList->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $servisList->withQueryString()->links() }}</div>
    @endif
  </div>

  {{-- ASSIGN MODAL --}}
  <div id="modalAssign" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden px-4" onclick="if(event.target===this)closeAssignModal()">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden">
      <div class="bg-gradient-to-r from-yellow-400 to-amber-500 px-6 py-4 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2"><i class="fas fa-user-plus"></i> Assign Teknisi</h3>
        <button onclick="closeAssignModal()" class="text-gray-700 hover:text-gray-900"><i class="fas fa-times text-lg"></i></button>
      </div>
      <div class="p-6">
        <div class="mb-4 bg-gray-50 rounded-xl p-4 text-sm space-y-1">
          <p><span class="text-gray-400">Tiket:</span> <span id="asTiket" class="font-mono text-yellow-600 font-bold"></span></p>
          <p><span class="text-gray-400">Pelanggan:</span> <span id="asPelanggan" class="font-semibold text-gray-700"></span></p>
        </div>

        {{-- Manual Assign --}}
        <form id="assignForm" method="POST" class="mb-3">
          @csrf
          <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Teknisi (cabang yang sama)</label>
          <div class="flex gap-2">
            <select name="teknisi_id" id="asTeknisi" required class="flex-1 border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-yellow-400">
              <option value="">Pilih teknisi...</option>
            </select>
            <button type="submit" class="bg-gradient-to-r from-yellow-400 to-amber-500 text-gray-900 font-bold px-5 py-2.5 rounded-xl shadow-lg shadow-yellow-200"><i class="fas fa-check"></i></button>
          </div>
        </form>

        {{-- Auto Assign --}}
        <form id="autoAssignForm" method="POST" class="text-center">
          @csrf
          <button type="submit" class="text-sm text-green-600 hover:text-green-700 font-semibold">
            <i class="fas fa-magic mr-1"></i> Atau auto-assign otomatis
          </button>
        </form>
      </div>
    </div>
  </div>

  <script>
    const allTeknisi = {!! json_encode($allTeknisi) !!};

    function openAssignModal(servisId, tiket, pelanggan, cabangId) {
      document.getElementById('assignForm').action = '/admin/penugasan/' + servisId + '/assign';
      document.getElementById('autoAssignForm').action = '/admin/penugasan/' + servisId + '/auto';
      document.getElementById('asTiket').textContent = tiket;
      document.getElementById('asPelanggan').textContent = pelanggan;

      // Populate teknisi dropdown — only from same cabang
      const sel = document.getElementById('asTeknisi');
      sel.innerHTML = '<option value="">Pilih teknisi...</option>';
      allTeknisi.forEach(t => {
        if (cabangId && t.cabang_id !== cabangId) return;
        const opt = document.createElement('option');
        opt.value = t.id;
        opt.textContent = t.name + ' (' + t.cabang_nama + ')';
        sel.appendChild(opt);
      });

      document.getElementById('modalAssign').classList.remove('hidden');
    }
    function closeAssignModal() { document.getElementById('modalAssign').classList.add('hidden'); }
  </script>
</x-admin-layout>
