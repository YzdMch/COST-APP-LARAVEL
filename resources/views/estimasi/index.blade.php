<x-app-layout>
  <x-slot:title>Estimasi Biaya</x-slot:title>

  {{-- HEADER --}}
  <section class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-14 overflow-hidden">
    <div class="absolute inset-0 opacity-10">
      <div class="absolute top-10 right-20 w-72 h-72 bg-yellow-400 rounded-full blur-[120px]"></div>
    </div>
    <div class="max-w-4xl mx-auto px-5 text-center relative z-10">
      <div class="inline-flex items-center gap-2 bg-yellow-400/10 border border-yellow-400/20 text-yellow-400 text-sm font-medium px-4 py-1.5 rounded-full mb-4">
        <i class="fas fa-bolt text-xs"></i> Hasil Instan — Tanpa Perlu Login
      </div>
      <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-3">Cek <span class="gradient-text">Estimasi Biaya</span></h1>
      <p class="text-gray-400 max-w-md mx-auto">Pilih perangkat dan jenis kerusakan, langsung lihat kisaran biayanya</p>
    </div>
  </section>

  {{-- MAIN --}}
  <section class="py-12 md:py-16 px-5" x-data="estimasiApp()">
    <div class="max-w-5xl mx-auto">

      {{-- STEP 1: PERANGKAT --}}
      <div class="mb-10">
        <div class="flex items-center gap-3 mb-5">
          <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center text-white text-sm font-bold shadow-lg shadow-yellow-200">1</div>
          <h2 class="text-lg font-bold text-gray-800">Pilih Perangkat</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
          <template x-for="d in devices" :key="d.key">
            <button
              @click="selectDevice(d.key)"
              :class="device === d.key
                ? 'border-yellow-400 bg-yellow-50 ring-2 ring-yellow-200 shadow-lg shadow-yellow-100'
                : 'border-gray-200 bg-white hover:border-gray-300 hover:shadow-md'"
              class="relative p-5 rounded-2xl border text-center transition-all duration-200 group cursor-pointer">
              <div :class="device === d.key ? 'text-yellow-500 scale-110' : 'text-gray-400 group-hover:text-gray-600'"
                class="text-2xl mb-2 transition-all duration-200">
                <i :class="'fas ' + d.icon"></i>
              </div>
              <p :class="device === d.key ? 'text-yellow-700 font-bold' : 'text-gray-600 font-medium'"
                class="text-sm transition-colors" x-text="d.label"></p>
              <div x-show="device === d.key" class="absolute top-2 right-2 w-5 h-5 rounded-full bg-yellow-400 flex items-center justify-center">
                <i class="fas fa-check text-white text-[10px]"></i>
              </div>
            </button>
          </template>
        </div>
      </div>

      {{-- STEP 2: KERUSAKAN --}}
      <div class="mb-10" x-show="device" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="flex items-center gap-3 mb-5">
          <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center text-white text-sm font-bold shadow-lg shadow-yellow-200">2</div>
          <h2 class="text-lg font-bold text-gray-800">Pilih Jenis Kerusakan</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
          <template x-for="i in issues" :key="i.key">
            <button
              @click="selectIssue(i.key)"
              :class="issue === i.key
                ? 'border-yellow-400 bg-yellow-50 ring-2 ring-yellow-200 shadow-lg shadow-yellow-100'
                : 'border-gray-200 bg-white hover:border-gray-300 hover:shadow-md'"
              class="relative p-5 rounded-2xl border text-center transition-all duration-200 group cursor-pointer">
              <div :class="issue === i.key ? 'text-yellow-500 scale-110' : 'text-gray-400 group-hover:text-gray-600'"
                class="text-2xl mb-2 transition-all duration-200">
                <i :class="'fas ' + i.icon"></i>
              </div>
              <p :class="issue === i.key ? 'text-yellow-700 font-bold' : 'text-gray-600 font-medium'"
                class="text-sm transition-colors" x-text="i.label"></p>
              <div x-show="issue === i.key" class="absolute top-2 right-2 w-5 h-5 rounded-full bg-yellow-400 flex items-center justify-center">
                <i class="fas fa-check text-white text-[10px]"></i>
              </div>
            </button>
          </template>
        </div>
      </div>

      {{-- RESULT --}}
      <div x-show="result" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-6 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-100 overflow-hidden">
          {{-- Header --}}
          <div class="bg-gradient-to-r from-yellow-400 to-amber-500 px-6 py-4 flex items-center gap-3">
            <i class="fas fa-file-invoice-dollar text-gray-800 text-xl"></i>
            <h3 class="text-lg font-bold text-gray-800">Hasil Estimasi Biaya</h3>
          </div>
          <div class="p-6 md:p-8">
            {{-- Info --}}
            <div class="grid sm:grid-cols-3 gap-4 mb-6">
              <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Perangkat</p>
                <p class="font-bold text-gray-800 text-sm" x-text="deviceLabel"></p>
              </div>
              <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Kerusakan</p>
                <p class="font-bold text-gray-800 text-sm" x-text="issueLabel"></p>
              </div>
              <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs text-gray-400 mb-1">Keterangan</p>
                <p class="font-bold text-gray-800 text-sm" x-text="result?.keterangan || '-'"></p>
              </div>
            </div>
            {{-- Price --}}
            <div class="text-center py-6 bg-gradient-to-br from-yellow-50 to-amber-50 rounded-2xl border border-yellow-100 mb-6">
              <p class="text-sm text-gray-500 mb-2">Estimasi Biaya</p>
              <p class="text-3xl md:text-4xl font-extrabold text-gray-800" x-text="priceRange"></p>
              <p class="text-xs text-gray-400 mt-2"><i class="fas fa-info-circle mr-1"></i>Harga final ditentukan setelah pengecekan teknisi</p>
            </div>
            {{-- CTA --}}
            <div class="flex flex-col sm:flex-row gap-3">
              <button @click="resetForm()"
                class="flex-1 border border-gray-200 text-gray-600 font-semibold py-3.5 rounded-xl hover:bg-gray-50 transition-all duration-200 flex items-center justify-center gap-2">
                <i class="fas fa-redo text-sm"></i> Cek Lagi
              </button>
              <button @click="goToBooking()"
                class="flex-1 bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-500 hover:to-amber-600 text-gray-900 font-bold py-3.5 rounded-xl shadow-lg shadow-yellow-200 hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2">
                <i class="fas fa-calendar-check"></i> Booking Sekarang
              </button>
            </div>
          </div>
        </div>
      </div>

      {{-- LOADING --}}
      <div x-show="loading" class="text-center py-12">
        <i class="fas fa-spinner fa-spin text-yellow-500 text-3xl mb-3"></i>
        <p class="text-gray-400 text-sm">Menghitung estimasi...</p>
      </div>

      {{-- ERROR --}}
      <div x-show="error" x-transition class="bg-red-50 border border-red-200 rounded-2xl p-6 text-center">
        <i class="fas fa-exclamation-triangle text-red-400 text-2xl mb-2"></i>
        <p class="text-red-600 font-semibold" x-text="error"></p>
        <button @click="error = null" class="mt-3 text-sm text-red-500 hover:underline">Tutup</button>
      </div>

    </div>
  </section>

  {{-- PRICE TABLE --}}
  <section class="pb-16 px-5" x-data="{ showTable: false }">
    <div class="max-w-5xl mx-auto">
      <button @click="showTable = !showTable"
        class="w-full flex items-center justify-center gap-2 text-gray-400 hover:text-gray-600 font-medium text-sm py-3 transition">
        <i class="fas fa-table"></i>
        <span x-text="showTable ? 'Sembunyikan Tabel Harga' : 'Lihat Semua Daftar Harga'"></span>
        <i :class="showTable ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas text-xs"></i>
      </button>
      <div x-show="showTable" x-collapse>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mt-3">
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead>
                <tr class="bg-gray-50 text-left">
                  <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Perangkat</th>
                  <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Kerusakan</th>
                  <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Harga Min</th>
                  <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Harga Max</th>
                  <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Keterangan</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                @php
                  $allEstimasi = \App\Models\EstimasiHarga::all();
                  $lPerangkat = \App\Models\Servis::labelPerangkat();
                  $lKerusakan = \App\Models\Servis::labelKerusakan();
                @endphp
                @foreach($allEstimasi as $e)
                <tr class="hover:bg-gray-50 transition">
                  <td class="px-5 py-3 font-medium text-gray-700">{{ $lPerangkat[$e->perangkat] ?? $e->perangkat }}</td>
                  <td class="px-5 py-3 text-gray-600">{{ $lKerusakan[$e->kerusakan] ?? $e->kerusakan }}</td>
                  <td class="px-5 py-3 text-gray-700 font-semibold">Rp {{ number_format($e->harga_min, 0, ',', '.') }}</td>
                  <td class="px-5 py-3 text-yellow-600 font-bold">Rp {{ number_format($e->harga_max, 0, ',', '.') }}</td>
                  <td class="px-5 py-3 text-gray-400 text-xs">{{ $e->keterangan }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script>
    function estimasiApp() {
      return {
        device: null,
        issue: null,
        result: null,
        loading: false,
        error: null,

        devices: [
          { key: 'macbook', label: 'MacBook', icon: 'fa-laptop-code' },
          { key: 'windows', label: 'Windows Laptop', icon: 'fa-laptop' },
          { key: 'pc', label: 'Desktop PC', icon: 'fa-desktop' },
          { key: 'imac', label: 'iMac / Mac', icon: 'fa-display' },
          { key: 'other', label: 'Lainnya', icon: 'fa-microchip' },
        ],
        issues: [
          { key: 'lcd', label: 'LCD / Layar', icon: 'fa-mobile-screen-button' },
          { key: 'battery', label: 'Baterai', icon: 'fa-battery-three-quarters' },
          { key: 'ssd', label: 'Upgrade SSD', icon: 'fa-hard-drive' },
          { key: 'thermal', label: 'Thermal Paste', icon: 'fa-temperature-low' },
          { key: 'other', label: 'Lainnya', icon: 'fa-screwdriver-wrench' },
        ],

        get deviceLabel() {
          return this.devices.find(d => d.key === this.device)?.label || '';
        },
        get issueLabel() {
          return this.issues.find(i => i.key === this.issue)?.label || '';
        },
        get priceRange() {
          if (!this.result) return '';
          return this.fmt(this.result.harga_min) + ' — ' + this.fmt(this.result.harga_max);
        },

        fmt(n) {
          return 'Rp ' + parseInt(n).toLocaleString('id-ID');
        },

        selectDevice(key) {
          this.device = key;
          this.result = null;
          this.error = null;
          if (this.issue) this.fetchEstimasi();
        },
        selectIssue(key) {
          this.issue = key;
          this.result = null;
          this.error = null;
          if (this.device) this.fetchEstimasi();
        },

        async fetchEstimasi() {
          this.loading = true;
          this.error = null;
          try {
            const res = await fetch('{{ route("estimasi.hitung") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
              },
              body: JSON.stringify({ perangkat: this.device, kerusakan: this.issue }),
            });
            const data = await res.json();
            if (data.status === 'ok') {
              this.result = data;
            } else {
              this.error = data.message || 'Estimasi tidak ditemukan.';
            }
          } catch (e) {
            this.error = 'Terjadi kesalahan. Coba lagi.';
          }
          this.loading = false;
        },

        resetForm() {
          this.device = null;
          this.issue = null;
          this.result = null;
          this.error = null;
        },

        goToBooking() {
          sessionStorage.setItem('dataBooking', JSON.stringify({
            device: this.device,
            issue: this.issue,
            estimasi_min: this.result?.harga_min,
            estimasi_max: this.result?.harga_max,
          }));
          @auth
            window.location.href = '{{ route("booking.create") }}';
          @else
            window.location.href = '{{ route("login") }}?redirect=booking';
          @endauth
        },
      };
    }
  </script>
</x-app-layout>
