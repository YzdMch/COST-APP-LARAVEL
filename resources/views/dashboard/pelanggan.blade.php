@php
  $labelPerangkat = \App\Models\Servis::labelPerangkat();
  $labelKerusakan = \App\Models\Servis::labelKerusakan();
  $statusClass = \App\Models\Servis::statusClasses();
  $statusIcon = \App\Models\Servis::statusIcons();
  $allStatuses = ['Diterima', 'Sedang dicek', 'Perbaikan', 'Testing', 'Selesai'];
@endphp

<x-app-layout>
  <x-slot:title>Dashboard Pelanggan</x-slot:title>

  <main class="max-w-7xl mx-auto px-5 py-8">

    {{-- Greeting --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
      <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800">
          Halo, {{ Auth::user()->name }}! 👋
        </h1>
        <p class="text-gray-400 mt-1">Pantau status servis perangkat Anda di sini</p>
      </div>
      <a href="{{ route('estimasi') }}"
        class="bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-500 hover:to-amber-600 text-gray-900 font-bold px-6 py-2.5 rounded-xl shadow-lg shadow-yellow-200 transition-all duration-200 flex items-center gap-2 text-sm w-fit">
        <i class="fas fa-plus"></i> Booking Baru
      </a>
    </div>

    {{-- Tiket baru --}}
    @if($tiketBaru)
      <div id="alertTiket" class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-5 mb-6 flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl bg-green-500 flex items-center justify-center flex-shrink-0">
          <i class="fas fa-check text-white"></i>
        </div>
        <div class="flex-1">
          <p class="font-bold text-gray-800">Booking berhasil!</p>
          <p class="text-gray-600 text-sm mt-1">
            Nomor tiket: <span class="font-mono font-bold text-yellow-600 text-base">{{ $tiketBaru }}</span>
          </p>
          <p class="text-gray-400 text-xs mt-1">Simpan nomor ini untuk tracking</p>
        </div>
        <button onclick="document.getElementById('alertTiket').remove()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
      </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Total</p>
            <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $total }}</p>
          </div>
          <div class="w-11 h-11 rounded-xl bg-yellow-50 flex items-center justify-center"><i class="fas fa-ticket-alt text-yellow-500"></i></div>
        </div>
      </div>
      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Selesai</p>
            <p class="text-3xl font-extrabold text-green-600 mt-1">{{ $selesai }}</p>
          </div>
          <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center"><i class="fas fa-check-circle text-green-500"></i></div>
        </div>
      </div>
      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Proses</p>
            <p class="text-3xl font-extrabold text-amber-600 mt-1">{{ $proses }}</p>
          </div>
          <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center"><i class="fas fa-spinner text-amber-500"></i></div>
        </div>
      </div>
    </div>

    {{-- Active service tracking --}}
    @if($activeServis)
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between mb-5">
          <h2 class="font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-satellite-dish text-yellow-500"></i> Servis Aktif
          </h2>
          <span class="font-mono text-sm font-bold text-yellow-600">{{ $activeServis->nomor_tiket }}</span>
        </div>
        {{-- Progress bar --}}
        @php
          $currentIdx = array_search($activeServis->status, $allStatuses);
          if ($currentIdx === false) $currentIdx = 0;
        @endphp
        <div class="flex items-center justify-between mb-2">
          @foreach($allStatuses as $i => $st)
            <div class="flex flex-col items-center flex-1 {{ $i > 0 ? '' : '' }}">
              <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold
                {{ $i < $currentIdx ? 'bg-green-500 text-white' : ($i === $currentIdx ? 'bg-yellow-400 text-gray-900 ring-4 ring-yellow-100' : 'bg-gray-100 text-gray-400') }}">
                @if($i < $currentIdx)
                  <i class="fas fa-check text-xs"></i>
                @else
                  {{ $i + 1 }}
                @endif
              </div>
              <p class="text-[10px] mt-1.5 font-medium text-center {{ $i === $currentIdx ? 'text-yellow-600' : 'text-gray-400' }}">{{ $st }}</p>
            </div>
            @if($i < count($allStatuses) - 1)
              <div class="flex-1 h-0.5 mt-[-18px] mx-1 {{ $i < $currentIdx ? 'bg-green-400' : 'bg-gray-200' }}"></div>
            @endif
          @endforeach
        </div>
        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100 text-sm">
          <span class="text-gray-400">{{ $labelPerangkat[$activeServis->perangkat] ?? $activeServis->perangkat }} · {{ $labelKerusakan[$activeServis->jenis_kerusakan] ?? $activeServis->jenis_kerusakan }}</span>
          <a href="{{ route('servis.show', $activeServis) }}" class="text-yellow-600 font-semibold hover:text-yellow-700 flex items-center gap-1">
            Lihat Detail <i class="fas fa-arrow-right text-xs"></i>
          </a>
        </div>
      </div>
    @endif

    {{-- Service list --}}
    @if($semuaServis->isEmpty())
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-14 text-center">
        <i class="fas fa-inbox text-gray-200 text-6xl mb-4 block"></i>
        <p class="text-gray-500 text-lg font-semibold">Belum ada booking</p>
        <p class="text-gray-400 text-sm mt-1 mb-6">Mulai booking servis perangkat Anda sekarang</p>
        <a href="{{ route('estimasi') }}"
          class="inline-flex items-center gap-2 bg-gradient-to-r from-yellow-400 to-amber-500 text-gray-900 font-bold px-6 py-3 rounded-xl shadow-lg shadow-yellow-200 transition-all">
          <i class="fas fa-plus"></i> Booking Sekarang
        </a>
      </div>
    @else
      <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
        <i class="fas fa-history text-yellow-500"></i> Semua Servis
      </h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($semuaServis as $servis)
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-gray-200 transition-all duration-200 overflow-hidden">
            <div class="px-5 py-4 flex items-center justify-between border-b border-gray-50">
              <span class="font-mono font-bold text-yellow-600 text-sm">{{ $servis->nomor_tiket }}</span>
              <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass[$servis->status] ?? 'bg-gray-100 text-gray-700' }}">
                <i class="fas {{ $statusIcon[$servis->status] ?? 'fa-circle' }} mr-1 text-[10px]"></i>{{ $servis->status }}
              </span>
            </div>
            <div class="px-5 py-4 space-y-2.5 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-400">Perangkat</span>
                <span class="font-semibold text-gray-700">{{ $labelPerangkat[$servis->perangkat] ?? $servis->perangkat }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-400">Kerusakan</span>
                <span class="font-semibold text-gray-700">{{ $labelKerusakan[$servis->jenis_kerusakan] ?? $servis->jenis_kerusakan }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-400">Estimasi</span>
                <span class="font-bold text-yellow-600">{{ $servis->estimasi_harga ? 'Rp ' . number_format($servis->estimasi_harga, 0, ',', '.') : '-' }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-400">Tanggal</span>
                <span class="text-gray-500">{{ $servis->created_at->format('d M Y') }}</span>
              </div>
            </div>
            <div class="px-5 py-3 border-t border-gray-50 flex justify-end">
              <a href="{{ route('servis.show', $servis) }}" class="text-yellow-600 hover:text-yellow-700 text-sm font-semibold flex items-center gap-1">
                Lihat Detail <i class="fas fa-arrow-right text-xs"></i>
              </a>
            </div>
          </div>
        @endforeach
      </div>
    @endif

  </main>
</x-app-layout>
