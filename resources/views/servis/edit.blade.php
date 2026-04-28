<x-app-layout>
  <x-slot:title>Edit Servis</x-slot:title>

  <section class="py-12 px-5">
    <div class="container mx-auto max-w-3xl">
      <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-6 md:p-10">

          <!-- Heading -->
          <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-600 transition">
              <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
              <h1 class="text-2xl font-extrabold text-gray-800">Edit Data Servis</h1>
              <p class="text-gray-500 text-sm">
                Tiket: <span class="font-mono text-yellow-700">{{ $servis->nomor_tiket }}</span>
              </p>
            </div>
          </div>

          @if($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-700 text-sm px-4 py-3 rounded-xl mb-6">
              <i class="fas fa-exclamation-circle mr-2"></i>
              @foreach($errors->all() as $error)
                {{ $error }}<br>
              @endforeach
            </div>
          @endif

          <form method="POST" action="{{ route('servis.update', $servis) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

              <!-- Nama Pelanggan -->
              <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Nama Pelanggan <span class="text-red-500">*</span></label>
                <input type="text" name="nama_pelanggan"
                  value="{{ old('nama_pelanggan', $servis->nama_pelanggan) }}"
                  class="w-full border border-gray-300 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">
              </div>

              <!-- Email -->
              <div>
                <label class="block text-gray-700 font-semibold mb-2">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email"
                  value="{{ old('email', $servis->email) }}"
                  class="w-full border border-gray-300 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">
              </div>

              <!-- No Telepon -->
              <div>
                <label class="block text-gray-700 font-semibold mb-2">No. Telepon <span class="text-red-500">*</span></label>
                <input type="tel" name="no_telepon"
                  value="{{ old('no_telepon', $servis->no_telepon) }}"
                  class="w-full border border-gray-300 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">
              </div>

              <!-- Perangkat -->
              <div>
                <label class="block text-gray-700 font-semibold mb-2">Perangkat <span class="text-red-500">*</span></label>
                <select name="perangkat"
                  class="w-full border border-gray-300 rounded-xl py-3 px-4 bg-white focus:outline-none focus:ring-2 focus:ring-yellow-400">
                  @foreach(\App\Models\Servis::labelPerangkat() as $key => $label)
                    <option value="{{ $key }}" {{ old('perangkat', $servis->perangkat) === $key ? 'selected' : '' }}>{{ $label }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Kerusakan -->
              <div>
                <label class="block text-gray-700 font-semibold mb-2">Jenis Kerusakan <span class="text-red-500">*</span></label>
                <select name="jenis_kerusakan"
                  class="w-full border border-gray-300 rounded-xl py-3 px-4 bg-white focus:outline-none focus:ring-2 focus:ring-yellow-400">
                  @foreach(\App\Models\Servis::labelKerusakan() as $key => $label)
                    <option value="{{ $key }}" {{ old('jenis_kerusakan', $servis->jenis_kerusakan) === $key ? 'selected' : '' }}>{{ $label }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Status -->
              <div>
                <label class="block text-gray-700 font-semibold mb-2">Status</label>
                <select name="status"
                  class="w-full border border-gray-300 rounded-xl py-3 px-4 bg-white focus:outline-none focus:ring-2 focus:ring-yellow-400">
                  @foreach(['Diterima', 'Sedang dicek', 'Perbaikan', 'Testing', 'Selesai'] as $s)
                    <option value="{{ $s }}" {{ old('status', $servis->status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Estimasi Harga -->
              <div>
                <label class="block text-gray-700 font-semibold mb-2">Estimasi Harga (Rp)</label>
                <input type="number" name="estimasi_harga"
                  value="{{ old('estimasi_harga', $servis->estimasi_harga) }}"
                  placeholder="Contoh: 1500000"
                  class="w-full border border-gray-300 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">
              </div>

              <!-- Deskripsi -->
              <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Deskripsi Keluhan <span class="text-red-500">*</span></label>
                <textarea name="deskripsi" rows="3"
                  class="w-full border border-gray-300 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                  >{{ old('deskripsi', $servis->deskripsi) }}</textarea>
              </div>

            </div>

            <!-- Tombol -->
            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-end">
              <a href="{{ route('dashboard') }}"
                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-100 transition text-center font-semibold">
                Batal
              </a>
              <button type="submit"
                class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-bold rounded-xl transition">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </section>
</x-app-layout>
