<x-admin-layout>
  <x-slot:title>Manajemen User</x-slot:title>

  <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-3">
    <div>
      <h2 class="text-xl font-bold text-gray-800">Manajemen User</h2>
      <p class="text-sm text-gray-400">Kelola semua akun pengguna sistem</p>
    </div>
    <button onclick="openAddUser()" class="bg-gradient-to-r from-yellow-400 to-amber-500 text-gray-900 font-bold text-sm px-5 py-2.5 rounded-xl shadow-lg shadow-yellow-200 hover:shadow-xl transition-all flex items-center gap-2">
      <i class="fas fa-user-plus"></i> Tambah User
    </button>
  </div>

  {{-- Filters --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
      <div>
        <label class="text-xs font-semibold text-gray-500 mb-1 block">Role</label>
        <select name="role" class="border border-gray-200 rounded-xl py-2 px-3 text-sm focus:ring-2 focus:ring-yellow-400">
          <option value="">Semua</option>
          <option value="pelanggan" {{ request('role') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
          <option value="teknisi" {{ request('role') == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
          <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
      </div>
      <div class="flex-1 min-w-[200px]">
        <label class="text-xs font-semibold text-gray-500 mb-1 block">Cari</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau email..."
          class="w-full border border-gray-200 rounded-xl py-2 px-3 text-sm focus:ring-2 focus:ring-yellow-400">
      </div>
      <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm px-4 py-2 rounded-xl transition">
        <i class="fas fa-search mr-1"></i> Filter
      </button>
    </form>
  </div>

  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead>
          <tr class="bg-gray-50/50">
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">User</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Email</th>
            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-400 uppercase">Role</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Cabang</th>
            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-400 uppercase">Status</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Bergabung</th>
            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-400 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($users as $u)
          <tr class="hover:bg-gray-50/50 transition {{ !$u->is_active ? 'opacity-50' : '' }}">
            <td class="px-5 py-3">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br {{ match($u->role) { 'admin' => 'from-red-400 to-red-600', 'teknisi' => 'from-blue-400 to-blue-600', default => 'from-gray-300 to-gray-400' } }} flex items-center justify-center text-white text-xs font-bold">
                  {{ strtoupper(substr($u->name, 0, 2)) }}
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-700">{{ $u->name }}</p>
                  <p class="text-xs text-gray-400">{{ $u->no_telepon ?? '-' }}</p>
                </div>
              </div>
            </td>
            <td class="px-5 py-3 text-sm text-gray-600">{{ $u->email }}</td>
            <td class="px-5 py-3 text-center">
              <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ match($u->role) { 'admin' => 'bg-red-100 text-red-700', 'teknisi' => 'bg-blue-100 text-blue-700', default => 'bg-gray-100 text-gray-700' } }}">
                {{ ucfirst($u->role) }}
              </span>
            </td>
            <td class="px-5 py-3 text-sm text-gray-600">{{ $u->cabang?->nama ?? '-' }}</td>
            <td class="px-5 py-3 text-center">
              <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $u->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
              </span>
            </td>
            <td class="px-5 py-3 text-sm text-gray-400">{{ $u->created_at?->format('d M Y') }}</td>
            <td class="px-5 py-3">
              <div class="flex items-center justify-center gap-1.5">
                <button onclick="openEditUser({{ json_encode($u->only('id','name','email','no_telepon','role','cabang_id')) }})"
                  class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 flex items-center justify-center transition" title="Edit">
                  <i class="fas fa-edit text-xs"></i>
                </button>
                @if($u->id !== auth()->id())
                <form method="POST" action="{{ route('admin.users.toggle', $u) }}" class="inline">
                  @csrf
                  <button type="submit" class="w-8 h-8 rounded-lg {{ $u->is_active ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }} flex items-center justify-center transition"
                    title="{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                    <i class="fas {{ $u->is_active ? 'fa-ban' : 'fa-check' }} text-xs"></i>
                  </button>
                </form>
                <form method="POST" action="{{ route('admin.users.resetPassword', $u) }}" onsubmit="return confirm('Reset password {{ $u->name }} ke default (123456)?')" class="inline">
                  @csrf
                  <button type="submit" class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 hover:bg-orange-100 flex items-center justify-center transition" title="Reset Password">
                    <i class="fas fa-key text-xs"></i>
                  </button>
                </form>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" class="px-6 py-14 text-center text-gray-300"><i class="fas fa-users text-4xl mb-2 block"></i>Tidak ada user ditemukan.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($users->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $users->withQueryString()->links() }}</div>
    @endif
  </div>

  {{-- ADD USER MODAL --}}
  <div id="modalAddUser" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden px-4" onclick="if(event.target===this)closeAddUser()">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden">
      <div class="bg-gradient-to-r from-yellow-400 to-amber-500 px-6 py-4 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2"><i class="fas fa-user-plus"></i> Tambah User Baru</h3>
        <button onclick="closeAddUser()" class="text-gray-700 hover:text-gray-900"><i class="fas fa-times text-lg"></i></button>
      </div>
      <form method="POST" action="{{ route('admin.users.store') }}" class="p-6 space-y-4">
        @csrf
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
          <input type="text" name="name" required maxlength="255" placeholder="Nama lengkap" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" required placeholder="user@geeko.com" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
            <input type="password" name="password" required minlength="6" placeholder="Min 6 karakter" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
          </div>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">No Telepon</label>
          <input type="text" name="no_telepon" maxlength="20" placeholder="08123456789" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
            <select name="role" id="addRole" required onchange="toggleAddCabang()" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
              <option value="pelanggan">Pelanggan</option>
              <option value="teknisi">Teknisi</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div id="addCabangWrap" style="display:none">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Cabang <span class="text-red-500">*</span></label>
            <select name="cabang_id" id="addCabang" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
              <option value="">— Pilih Cabang —</option>
              @foreach($cabangList as $c)<option value="{{ $c->id }}">{{ $c->nama }}</option>@endforeach
            </select>
          </div>
        </div>
        <p id="addCabangHint" class="text-xs text-gray-400 hidden"><i class="fas fa-info-circle mr-1"></i>Teknisi wajib ditempatkan di salah satu cabang</p>
        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeAddUser()" class="flex-1 border border-gray-200 text-gray-600 font-semibold py-2.5 rounded-xl hover:bg-gray-50 transition">Batal</button>
          <button type="submit" class="flex-1 bg-gradient-to-r from-yellow-400 to-amber-500 text-gray-900 font-bold py-2.5 rounded-xl shadow-lg shadow-yellow-200 transition-all"><i class="fas fa-save mr-1"></i> Buat User</button>
        </div>
      </form>
    </div>
  </div>

  {{-- EDIT USER MODAL --}}
  <div id="modalEditUser" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden px-4" onclick="if(event.target===this)closeEditUser()">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden">
      <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 flex justify-between items-center">
        <h3 class="text-lg font-bold text-white flex items-center gap-2"><i class="fas fa-user-edit"></i> Edit User</h3>
        <button onclick="closeEditUser()" class="text-white/70 hover:text-white"><i class="fas fa-times text-lg"></i></button>
      </div>
      <form id="editUserForm" method="POST" class="p-6 space-y-4">
        @csrf @method('PUT')
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Nama</label>
          <input type="text" name="name" id="euName" required class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
          <input type="email" name="email" id="euEmail" required class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">No Telepon</label>
          <input type="text" name="no_telepon" id="euTelp" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-blue-400">
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
            <select name="role" id="euRole" required onchange="toggleEditCabang()" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-blue-400">
              <option value="pelanggan">Pelanggan</option>
              <option value="teknisi">Teknisi</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div id="editCabangWrap">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Cabang</label>
            <select name="cabang_id" id="euCabang" class="w-full border border-gray-200 rounded-xl py-2.5 px-3 text-sm focus:ring-2 focus:ring-blue-400">
              <option value="">— Tanpa Cabang —</option>
              @foreach($cabangList as $c)<option value="{{ $c->id }}">{{ $c->nama }}</option>@endforeach
            </select>
          </div>
        </div>
        <p id="editCabangHint" class="text-xs text-gray-400 hidden"><i class="fas fa-info-circle mr-1"></i>Teknisi wajib ditempatkan di salah satu cabang</p>
        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeEditUser()" class="flex-1 border border-gray-200 text-gray-600 font-semibold py-2.5 rounded-xl hover:bg-gray-50 transition">Batal</button>
          <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold py-2.5 rounded-xl shadow-lg shadow-blue-200 transition-all"><i class="fas fa-save mr-1"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Toggle cabang field visibility based on role
    function toggleAddCabang() {
      const role = document.getElementById('addRole').value;
      const wrap = document.getElementById('addCabangWrap');
      const hint = document.getElementById('addCabangHint');
      const sel = document.getElementById('addCabang');
      if (role === 'teknisi') {
        wrap.style.display = '';
        hint.classList.remove('hidden');
        sel.required = true;
      } else {
        wrap.style.display = 'none';
        hint.classList.add('hidden');
        sel.required = false;
        sel.value = '';
      }
    }

    function toggleEditCabang() {
      const role = document.getElementById('euRole').value;
      const wrap = document.getElementById('editCabangWrap');
      const hint = document.getElementById('editCabangHint');
      const sel = document.getElementById('euCabang');
      if (role === 'teknisi') {
        wrap.style.display = '';
        hint.classList.remove('hidden');
        sel.required = true;
      } else {
        wrap.style.display = role === 'admin' ? 'none' : 'none';
        hint.classList.add('hidden');
        sel.required = false;
        if (role !== 'teknisi') sel.value = '';
      }
    }

    // Add user modal
    function openAddUser() { document.getElementById('modalAddUser').classList.remove('hidden'); toggleAddCabang(); }
    function closeAddUser() { document.getElementById('modalAddUser').classList.add('hidden'); }

    // Edit user modal
    function openEditUser(u) {
      document.getElementById('editUserForm').action = '/admin/users/' + u.id;
      document.getElementById('euName').value = u.name;
      document.getElementById('euEmail').value = u.email;
      document.getElementById('euTelp').value = u.no_telepon || '';
      document.getElementById('euRole').value = u.role;
      document.getElementById('euCabang').value = u.cabang_id || '';
      document.getElementById('modalEditUser').classList.remove('hidden');
      toggleEditCabang();
    }
    function closeEditUser() { document.getElementById('modalEditUser').classList.add('hidden'); }
  </script>
</x-admin-layout>
