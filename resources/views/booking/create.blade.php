<x-app-layout>
  <x-slot:title>Booking Servis</x-slot:title>

  <section class="py-12 px-5">
    <div class="max-w-2xl mx-auto" x-data="bookingApp()">

      {{-- Header --}}
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center bg-yellow-100 p-3 rounded-full mb-4">
          <i class="fas fa-calendar-check text-yellow-600 text-2xl"></i>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-800">Booking Servis</h1>
        <p class="text-gray-400 mt-2">Lengkapi data diri Anda untuk membuat booking</p>
      </div>

      {{-- Estimasi Summary --}}
      <div x-show="estimasiData.device" class="bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-100 rounded-2xl p-5 mb-6">
        <div class="flex items-center justify-between flex-wrap gap-3">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-yellow-400 flex items-center justify-center text-white"><i class="fas fa-tag"></i></div>
            <div>
              <p class="text-xs text-gray-500">Estimasi biaya untuk servis ini</p>
              <p class="font-bold text-gray-800" x-text="priceLabel"></p>
            </div>
          </div>
          <a href="{{ route('estimasi') }}" class="text-yellow-600 hover:text-yellow-700 text-sm font-medium"><i class="fas fa-pen mr-1"></i>Ubah</a>
        </div>
      </div>

      {{-- Form --}}
      <div class="bg-white rounded-2xl shadow-xl shadow-gray-100 border border-gray-100 overflow-hidden">
        <div class="p-6 md:p-8">
          <form method="POST" action="{{ route('booking.store') }}" id="bookingForm">
            @csrf
            <input type="hidden" name="perangkat" x-model="estimasiData.device">
            <input type="hidden" name="kerusakan" x-model="estimasiData.issue">

            <div class="space-y-5">
              {{-- Nama --}}
              <div>
                <label class="block text-gray-700 font-semibold mb-2 text-sm">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', auth()->user()?->name) }}" required minlength="3" maxlength="100"
                  placeholder="Contoh: John Doe"
                  class="w-full border border-gray-200 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition">
                @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
              </div>

              {{-- Email + Telepon --}}
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-gray-700 font-semibold mb-2 text-sm">Email <span class="text-red-500">*</span></label>
                  <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required maxlength="150"
                    placeholder="john@example.com"
                    class="w-full border border-gray-200 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition">
                  @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                  <label class="block text-gray-700 font-semibold mb-2 text-sm">No. Telepon <span class="text-red-500">*</span></label>
                  <input type="tel" name="no_telepon" value="{{ old('no_telepon', auth()->user()?->no_telepon) }}" required minlength="9" maxlength="20"
                    placeholder="08123456789"
                    class="w-full border border-gray-200 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition">
                  @error('no_telepon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
              </div>

              {{-- Pilih Cabang --}}
              <div>
                <label class="block text-gray-700 font-semibold mb-2 text-sm">Lokasi Servis <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  @foreach($cabangList as $c)
                  <label class="relative cursor-pointer group">
                    <input type="radio" name="cabang_id" value="{{ $c->id }}" required
                      class="peer sr-only" {{ old('cabang_id') == $c->id ? 'checked' : '' }}>
                    <div class="border-2 border-gray-200 rounded-xl p-4 transition-all
                      peer-checked:border-yellow-400 peer-checked:bg-yellow-50 peer-checked:shadow-lg peer-checked:shadow-yellow-100
                      group-hover:border-gray-300 group-hover:bg-gray-50">
                      <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center text-white flex-shrink-0">
                          <i class="fas fa-store text-sm"></i>
                        </div>
                        <div>
                          <p class="font-semibold text-gray-800 text-sm">{{ $c->nama }}</p>
                          <p class="text-xs text-gray-400 mt-0.5">{{ $c->alamat ?? 'Alamat belum diisi' }}</p>
                          @if($c->telepon)
                          <p class="text-xs text-gray-400"><i class="fas fa-phone text-[10px] mr-1"></i>{{ $c->telepon }}</p>
                          @endif
                        </div>
                      </div>
                    </div>
                    {{-- Checkmark --}}
                    <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-gray-200 flex items-center justify-center
                      peer-checked:border-yellow-400 peer-checked:bg-yellow-400 transition-all">
                      <i class="fas fa-check text-white text-[10px] opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                    </div>
                  </label>
                  @endforeach
                </div>
                @error('cabang_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
              </div>

              {{-- Deskripsi --}}
              <div>
                <label class="block text-gray-700 font-semibold mb-2 text-sm">Deskripsi Keluhan <span class="text-red-500">*</span></label>
                <textarea name="deskripsi" rows="3" required minlength="5"
                  placeholder="Jelaskan detail kerusakan, misal: layar muncul garis vertikal sejak kemarin..."
                  class="w-full border border-gray-200 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
              </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3 mt-8">
              <a href="{{ route('estimasi') }}"
                class="flex-1 border border-gray-200 text-gray-600 font-semibold py-3 rounded-xl hover:bg-gray-50 transition text-center flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left text-sm"></i> Kembali
              </a>
              <button type="submit"
                class="flex-1 bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-500 hover:to-amber-600 text-gray-900 font-bold py-3 rounded-xl shadow-lg shadow-yellow-200 transition-all duration-200 flex items-center justify-center gap-2">
                <i class="fas fa-check"></i> Konfirmasi Booking
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </section>

  <script>
    function bookingApp() {
      const raw = JSON.parse(sessionStorage.getItem('dataBooking') || '{}');
      if (!raw.device) {
        window.location.href = '{{ route("estimasi") }}';
      }
      return {
        estimasiData: raw,
        get priceLabel() {
          if (!raw.estimasi_min) return 'Lihat estimasi dulu';
          const fmt = n => 'Rp ' + parseInt(n).toLocaleString('id-ID');
          return fmt(raw.estimasi_min) + ' — ' + fmt(raw.estimasi_max);
        }
      };
    }
  </script>
</x-app-layout>
