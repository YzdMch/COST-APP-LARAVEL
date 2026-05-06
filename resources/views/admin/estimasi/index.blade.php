@php
  $labelPerangkat = \App\Models\Servis::labelPerangkat();
  $labelKerusakan = \App\Models\Servis::labelKerusakan();
@endphp

<x-admin-layout>
  <x-slot:title>Estimasi Harga</x-slot:title>

  <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-3">
    <div>
      <h2 class="text-xl font-bold text-gray-800">Manajemen Estimasi Harga</h2>
      <p class="text-sm text-gray-400">Tambah, edit, dan hapus data estimasi harga servis</p>
    </div>
    <button onclick="openAddModal()" class="bg-gradient-to-r from-yellow-400 to-amber-500 text-gray-900 font-bold text-sm px-5 py-2.5 rounded-xl shadow-lg shadow-yellow-200 hover:shadow-xl transition-all flex items-center gap-2">
      <i class="fas fa-plus"></i> Tambah Estimasi
    </button>
  </div>

  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead>
          <tr class="bg-gray-50/50">
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">#</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Perangkat</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Kerusakan</th>
            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-400 uppercase">Harga Min</th>
            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-400 uppercase">Harga Max</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Keterangan</th>
            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-400 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($estimasi as $i => $e)
          <tr class="hover:bg-gray-50/50 transition">
            <td class="px-5 py-3 text-sm text-gray-400">{{ $i + 1 }}</td>
            <td class="px-5 py-3 text-sm font-medium text-gray-700">{{ $labelPerangkat[$e->perangkat] ?? $e->perangkat }}</td>
            <td class="px-5 py-3 text-sm text-gray-600">{{ $labelKerusakan[$e->kerusakan] ?? $e->kerusakan }}</td>
            <td class="px-5 py-3 text-sm text-right font-semibold text-gray-700">Rp {{ number_format($e->harga_min, 0, ',', '.') }}</td>
            <td class="px-5 py-3 text-sm text-right font-semibold text-yellow-600">Rp {{ number_format($e->harga_max, 0, ',', '.') }}</td>
            <td class="px-5 py-3 text-sm text-gray-500">{{ $e->keterangan ?? '-' }}</td>
            <td class="px-5 py-3 text-center">
              <div class="flex items-center justify-center gap-1.5">
                <button onclick="openEditModal({{ $e->id }}, '{{ $e->perangkat }}', '{{ $e->kerusakan }}', {{ $e->harga_min }}, {{ $e->harga_max }}, '{{ addslashes($e->keterangan ?? '') }}')"
                  class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 flex items-center justify-center transition" title="Edit">
                  <i class="fas fa-edit text-xs"></i>
                </button>
                <form method="POST" action="{{ route('admin.estimasi.destroy', $e) }}" onsubmit="return confirm('Hapus estimasi ini?')" class="inline">
                  @csrf @method('DELETE')
                  <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition" title="Hapus">
                    <i class="fas fa-trash text-xs"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" class="px-6 py-14 text-center text-gray-300"><i class="fas fa-inbox text-4xl mb-2 block"></i>Belum ada data estimasi.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- ADD MODAL --}}
  <div id="modalAdd" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden px-4" onclick="if(event.target===this)closeAddModal()">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden">
      <div class="bg-gradient-to-r from-yellow-400 to-amber-500 px-6 py-4 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2"><i class="fas fa-plus"></i> Tambah Estimasi</h3>
        <button onclick="closeAddModal()" class="text-gray-700 hover:text-gray-900"><i class="fas fa-times text-lg"></i></button>
      </div>
      <form method="POST" action="{{ route('admin.estimasi.store') }}" class="p-6 space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Perangkat <span class="text-red-500">*</span></label>
            <select name="perangkat" required class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
              @foreach($labelPerangkat as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Kerusakan <span class="text-red-500">*</span></label>
            <select name="kerusakan" required class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
              @foreach($labelKerusakan as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
            </select>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Min (Rp) <span class="text-red-500">*</span></label>
            <input type="number" name="harga_min" required min="0" placeholder="500000" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Max (Rp) <span class="text-red-500">*</span></label>
            <input type="number" name="harga_max" required min="0" placeholder="1500000" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
          </div>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan</label>
          <input type="text" name="keterangan" maxlength="255" placeholder="Opsional..." class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
        </div>
        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeAddModal()" class="flex-1 border border-gray-200 text-gray-600 font-semibold py-2.5 rounded-xl hover:bg-gray-50 transition">Batal</button>
          <button type="submit" class="flex-1 bg-gradient-to-r from-yellow-400 to-amber-500 text-gray-900 font-bold py-2.5 rounded-xl shadow-lg shadow-yellow-200 transition-all"><i class="fas fa-save mr-1"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>

  {{-- EDIT MODAL --}}
  <div id="modalEdit" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden px-4" onclick="if(event.target===this)closeEditModal()">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden">
      <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 flex justify-between items-center">
        <h3 class="text-lg font-bold text-white flex items-center gap-2"><i class="fas fa-edit"></i> Edit Estimasi</h3>
        <button onclick="closeEditModal()" class="text-white/70 hover:text-white"><i class="fas fa-times text-lg"></i></button>
      </div>
      <form id="editForm" method="POST" class="p-6 space-y-4">
        @csrf @method('PUT')
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Perangkat</label>
            <select name="perangkat" id="editPerangkat" required class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent">
              @foreach($labelPerangkat as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Kerusakan</label>
            <select name="kerusakan" id="editKerusakan" required class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent">
              @foreach($labelKerusakan as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
            </select>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Min (Rp)</label>
            <input type="number" name="harga_min" id="editMin" required min="0" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent">
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Max (Rp)</label>
            <input type="number" name="harga_max" id="editMax" required min="0" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent">
          </div>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan</label>
          <input type="text" name="keterangan" id="editKet" maxlength="255" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-blue-400 focus:border-transparent">
        </div>
        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeEditModal()" class="flex-1 border border-gray-200 text-gray-600 font-semibold py-2.5 rounded-xl hover:bg-gray-50 transition">Batal</button>
          <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold py-2.5 rounded-xl shadow-lg shadow-blue-200 transition-all"><i class="fas fa-save mr-1"></i> Update</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openAddModal() { document.getElementById('modalAdd').classList.remove('hidden'); }
    function closeAddModal() { document.getElementById('modalAdd').classList.add('hidden'); }
    function openEditModal(id, perangkat, kerusakan, min, max, ket) {
      document.getElementById('editForm').action = '/admin/estimasi/' + id;
      document.getElementById('editPerangkat').value = perangkat;
      document.getElementById('editKerusakan').value = kerusakan;
      document.getElementById('editMin').value = min;
      document.getElementById('editMax').value = max;
      document.getElementById('editKet').value = ket;
      document.getElementById('modalEdit').classList.remove('hidden');
    }
    function closeEditModal() { document.getElementById('modalEdit').classList.add('hidden'); }
  </script>
</x-admin-layout>
