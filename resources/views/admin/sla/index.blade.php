@php
  $labelKerusakan = \App\Models\Servis::labelKerusakan();
  $labelPerangkat = \App\Models\Servis::labelPerangkat();
@endphp

<x-admin-layout>
  <x-slot:title>SLA Tracking</x-slot:title>

  <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-3">
    <div>
      <h2 class="text-xl font-bold text-gray-800">SLA Configuration & Monitoring</h2>
      <p class="text-sm text-gray-400">Set target waktu penyelesaian dan monitor ketepatan waktu</p>
    </div>
    <button onclick="openAddSla()" class="bg-gradient-to-r from-yellow-400 to-amber-500 text-gray-900 font-bold text-sm px-5 py-2.5 rounded-xl shadow-lg shadow-yellow-200 hover:shadow-xl transition-all flex items-center gap-2">
      <i class="fas fa-plus"></i> Tambah SLA
    </button>
  </div>

  {{-- Overdue Alert --}}
  @if($overdueServis->count() > 0)
  <div class="bg-red-50 border border-red-200 rounded-2xl p-5 mb-6">
    <h3 class="font-bold text-red-700 mb-3 flex items-center gap-2"><i class="fas fa-exclamation-triangle"></i> Servis Overdue ({{ $overdueServis->count() }})</h3>
    <div class="space-y-2">
      @foreach($overdueServis as $s)
      <div class="flex items-center justify-between bg-white rounded-xl p-3 border border-red-100">
        <div class="flex items-center gap-3">
          <span class="font-mono text-sm text-red-600 font-bold">{{ $s->nomor_tiket }}</span>
          <span class="text-sm text-gray-600">{{ $s->nama_pelanggan }}</span>
          <span class="text-xs text-gray-400">→ {{ $s->teknisi?->name ?? 'Belum di-assign' }}</span>
        </div>
        <div class="flex items-center gap-2">
          <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">
            {{ abs(round($s->slaRemainingHours())) }} jam overdue
          </span>
          <span class="text-xs text-gray-400">Target: {{ $s->sla_target_jam }} jam</span>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif

  {{-- SLA Config Table --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100">
      <h3 class="font-bold text-gray-800 flex items-center gap-2"><i class="fas fa-cog text-gray-400"></i> Konfigurasi SLA</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="bg-gray-50/50">
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Kerusakan</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Perangkat</th>
            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-400 uppercase">Target (Jam)</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Cabang</th>
            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-400 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($slaConfigs as $sla)
          <tr class="hover:bg-gray-50/50">
            <td class="px-5 py-3 font-medium text-gray-700">{{ $labelKerusakan[$sla->jenis_kerusakan] ?? $sla->jenis_kerusakan }}</td>
            <td class="px-5 py-3 text-gray-600">{{ $sla->perangkat ? ($labelPerangkat[$sla->perangkat] ?? $sla->perangkat) : 'Semua' }}</td>
            <td class="px-5 py-3 text-center"><span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">{{ $sla->target_jam }} jam</span></td>
            <td class="px-5 py-3 text-gray-600">{{ $sla->cabang?->nama ?? 'Global' }}</td>
            <td class="px-5 py-3">
              <div class="flex items-center justify-center gap-1.5">
                <button onclick="openEditSla({{ json_encode($sla->only('id','jenis_kerusakan','perangkat','target_jam','cabang_id')) }})"
                  class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 flex items-center justify-center transition"><i class="fas fa-edit text-xs"></i></button>
                <form method="POST" action="{{ route('admin.sla.destroy', $sla) }}" onsubmit="return confirm('Hapus SLA config ini?')" class="inline">
                  @csrf @method('DELETE')
                  <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition"><i class="fas fa-trash text-xs"></i></button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" class="px-6 py-14 text-center text-gray-300"><i class="fas fa-clock text-4xl mb-2 block"></i>Belum ada konfigurasi SLA.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- ADD MODAL --}}
  <div id="modalAddSla" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden px-4" onclick="if(event.target===this)closeAddSla()">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden">
      <div class="bg-gradient-to-r from-yellow-400 to-amber-500 px-6 py-4 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-plus mr-2"></i>Tambah SLA</h3>
        <button onclick="closeAddSla()" class="text-gray-700 hover:text-gray-900"><i class="fas fa-times text-lg"></i></button>
      </div>
      <form method="POST" action="{{ route('admin.sla.store') }}" class="p-6 space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Kerusakan <span class="text-red-500">*</span></label>
            <select name="jenis_kerusakan" required class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm">
              @foreach($labelKerusakan as $k => $l)<option value="{{ $k }}">{{ $l }}</option>@endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Perangkat</label>
            <select name="perangkat" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm">
              <option value="">Semua</option>
              @foreach($labelPerangkat as $k => $l)<option value="{{ $k }}">{{ $l }}</option>@endforeach
            </select>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Target (Jam) <span class="text-red-500">*</span></label>
            <input type="number" name="target_jam" required min="1" placeholder="48" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm">
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Cabang</label>
            <select name="cabang_id" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm">
              <option value="">Global</option>
              @foreach($cabangList as $c)<option value="{{ $c->id }}">{{ $c->nama }}</option>@endforeach
            </select>
          </div>
        </div>
        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeAddSla()" class="flex-1 border border-gray-200 text-gray-600 font-semibold py-2.5 rounded-xl hover:bg-gray-50 transition">Batal</button>
          <button type="submit" class="flex-1 bg-gradient-to-r from-yellow-400 to-amber-500 text-gray-900 font-bold py-2.5 rounded-xl shadow-lg shadow-yellow-200"><i class="fas fa-save mr-1"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>

  {{-- EDIT MODAL --}}
  <div id="modalEditSla" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden px-4" onclick="if(event.target===this)closeEditSla()">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden">
      <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 flex justify-between items-center">
        <h3 class="text-lg font-bold text-white"><i class="fas fa-edit mr-2"></i>Edit SLA</h3>
        <button onclick="closeEditSla()" class="text-white/70 hover:text-white"><i class="fas fa-times text-lg"></i></button>
      </div>
      <form id="editSlaForm" method="POST" class="p-6 space-y-4">
        @csrf @method('PUT')
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Kerusakan</label>
            <select name="jenis_kerusakan" id="esKerusakan" required class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm">
              @foreach($labelKerusakan as $k => $l)<option value="{{ $k }}">{{ $l }}</option>@endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Perangkat</label>
            <select name="perangkat" id="esPerangkat" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm">
              <option value="">Semua</option>
              @foreach($labelPerangkat as $k => $l)<option value="{{ $k }}">{{ $l }}</option>@endforeach
            </select>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Target (Jam)</label>
            <input type="number" name="target_jam" id="esTarget" required min="1" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm">
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Cabang</label>
            <select name="cabang_id" id="esCabang" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm">
              <option value="">Global</option>
              @foreach($cabangList as $c)<option value="{{ $c->id }}">{{ $c->nama }}</option>@endforeach
            </select>
          </div>
        </div>
        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeEditSla()" class="flex-1 border border-gray-200 text-gray-600 font-semibold py-2.5 rounded-xl hover:bg-gray-50 transition">Batal</button>
          <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold py-2.5 rounded-xl shadow-lg shadow-blue-200"><i class="fas fa-save mr-1"></i> Update</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openAddSla() { document.getElementById('modalAddSla').classList.remove('hidden'); }
    function closeAddSla() { document.getElementById('modalAddSla').classList.add('hidden'); }
    function openEditSla(s) {
      document.getElementById('editSlaForm').action = '/admin/sla/' + s.id;
      document.getElementById('esKerusakan').value = s.jenis_kerusakan;
      document.getElementById('esPerangkat').value = s.perangkat || '';
      document.getElementById('esTarget').value = s.target_jam;
      document.getElementById('esCabang').value = s.cabang_id || '';
      document.getElementById('modalEditSla').classList.remove('hidden');
    }
    function closeEditSla() { document.getElementById('modalEditSla').classList.add('hidden'); }
  </script>
</x-admin-layout>
