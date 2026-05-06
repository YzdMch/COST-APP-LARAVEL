<x-admin-layout>
  <x-slot:title>Cabang</x-slot:title>

  <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-3">
    <div>
      <h2 class="text-xl font-bold text-gray-800">Manajemen Cabang</h2>
      <p class="text-sm text-gray-400">Kelola cabang / lokasi servis</p>
    </div>
    <button onclick="openAddCabang()" class="bg-gradient-to-r from-yellow-400 to-amber-500 text-gray-900 font-bold text-sm px-5 py-2.5 rounded-xl shadow-lg shadow-yellow-200 hover:shadow-xl transition-all flex items-center gap-2">
      <i class="fas fa-plus"></i> Tambah Cabang
    </button>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($cabangList as $c)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition {{ !$c->is_active ? 'opacity-60' : '' }}">
      <div class="flex items-start justify-between mb-3">
        <div>
          <h3 class="font-bold text-gray-800">{{ $c->nama }}</h3>
          <p class="text-xs text-gray-400 mt-0.5">{{ $c->alamat ?? 'Alamat belum diisi' }}</p>
        </div>
        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $c->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
          {{ $c->is_active ? 'Aktif' : 'Nonaktif' }}
        </span>
      </div>
      @if($c->telepon)
      <p class="text-sm text-gray-500 mb-3"><i class="fas fa-phone text-gray-300 mr-1"></i>{{ $c->telepon }}</p>
      @endif
      <div class="grid grid-cols-2 gap-3 mb-4">
        <div class="bg-gray-50 rounded-xl p-3 text-center">
          <p class="text-lg font-bold text-gray-800">{{ $c->servis_count }}</p>
          <p class="text-xs text-gray-400">Servis</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-3 text-center">
          <p class="text-lg font-bold text-blue-600">{{ $c->teknisi_count }}</p>
          <p class="text-xs text-gray-400">Teknisi</p>
        </div>
      </div>
      <div class="flex gap-2">
        <button onclick="openEditCabang({{ json_encode($c->only('id','nama','alamat','telepon','is_active')) }})"
          class="flex-1 bg-yellow-50 text-yellow-700 text-sm font-semibold py-2 rounded-xl hover:bg-yellow-100 transition">
          <i class="fas fa-edit mr-1"></i> Edit
        </button>
        @if($c->servis_count == 0)
        <form method="POST" action="{{ route('admin.cabang.destroy', $c) }}" onsubmit="return confirm('Hapus cabang {{ $c->nama }}?')" class="inline">
          @csrf @method('DELETE')
          <button type="submit" class="bg-red-50 text-red-600 text-sm font-semibold py-2 px-4 rounded-xl hover:bg-red-100 transition">
            <i class="fas fa-trash"></i>
          </button>
        </form>
        @endif
      </div>
    </div>
    @empty
    <div class="col-span-full text-center py-14 text-gray-300">
      <i class="fas fa-store text-5xl mb-3 block"></i>
      <p>Belum ada cabang. Tambahkan cabang pertama.</p>
    </div>
    @endforelse
  </div>

  {{-- ADD MODAL --}}
  <div id="modalAddCabang" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden px-4" onclick="if(event.target===this)closeAddCabang()">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden">
      <div class="bg-gradient-to-r from-yellow-400 to-amber-500 px-6 py-4 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800"><i class="fas fa-plus mr-2"></i>Tambah Cabang</h3>
        <button onclick="closeAddCabang()" class="text-gray-700 hover:text-gray-900"><i class="fas fa-times text-lg"></i></button>
      </div>
      <form method="POST" action="{{ route('admin.cabang.store') }}" class="p-6 space-y-4">
        @csrf
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Cabang <span class="text-red-500">*</span></label>
          <input type="text" name="nama" required maxlength="100" placeholder="Geeko Jakarta" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-yellow-400">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat</label>
          <textarea name="alamat" rows="2" placeholder="Jl. ..." class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-yellow-400"></textarea>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Telepon</label>
          <input type="text" name="telepon" maxlength="20" placeholder="021-1234567" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-yellow-400">
        </div>
        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeAddCabang()" class="flex-1 border border-gray-200 text-gray-600 font-semibold py-2.5 rounded-xl hover:bg-gray-50 transition">Batal</button>
          <button type="submit" class="flex-1 bg-gradient-to-r from-yellow-400 to-amber-500 text-gray-900 font-bold py-2.5 rounded-xl shadow-lg shadow-yellow-200"><i class="fas fa-save mr-1"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>

  {{-- EDIT MODAL --}}
  <div id="modalEditCabang" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden px-4" onclick="if(event.target===this)closeEditCabang()">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden">
      <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 flex justify-between items-center">
        <h3 class="text-lg font-bold text-white"><i class="fas fa-edit mr-2"></i>Edit Cabang</h3>
        <button onclick="closeEditCabang()" class="text-white/70 hover:text-white"><i class="fas fa-times text-lg"></i></button>
      </div>
      <form id="editCabangForm" method="POST" class="p-6 space-y-4">
        @csrf @method('PUT')
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Cabang</label>
          <input type="text" name="nama" id="ecNama" required maxlength="100" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat</label>
          <textarea name="alamat" id="ecAlamat" rows="2" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-blue-400"></textarea>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Telepon</label>
          <input type="text" name="telepon" id="ecTelepon" maxlength="20" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
          <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" id="ecActive" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-400">
            Cabang Aktif
          </label>
        </div>
        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeEditCabang()" class="flex-1 border border-gray-200 text-gray-600 font-semibold py-2.5 rounded-xl hover:bg-gray-50 transition">Batal</button>
          <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold py-2.5 rounded-xl shadow-lg shadow-blue-200"><i class="fas fa-save mr-1"></i> Update</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openAddCabang() { document.getElementById('modalAddCabang').classList.remove('hidden'); }
    function closeAddCabang() { document.getElementById('modalAddCabang').classList.add('hidden'); }
    function openEditCabang(c) {
      document.getElementById('editCabangForm').action = '/admin/cabang/' + c.id;
      document.getElementById('ecNama').value = c.nama;
      document.getElementById('ecAlamat').value = c.alamat || '';
      document.getElementById('ecTelepon').value = c.telepon || '';
      document.getElementById('ecActive').checked = c.is_active;
      document.getElementById('modalEditCabang').classList.remove('hidden');
    }
    function closeEditCabang() { document.getElementById('modalEditCabang').classList.add('hidden'); }
  </script>
</x-admin-layout>
