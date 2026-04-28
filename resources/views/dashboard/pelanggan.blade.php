@php
  $labelPerangkat = \App\Models\Servis::labelPerangkat();
  $labelKerusakan = \App\Models\Servis::labelKerusakan();
  $statusClass = \App\Models\Servis::statusClasses();
  $statusIcon = \App\Models\Servis::statusIcons();
@endphp

<x-app-layout>
  <x-slot:title>Dashboard Pelanggan</x-slot:title>

  <main class="max-w-7xl mx-auto px-5 py-10">

    <!-- Greeting -->
    <div class="mb-6">
      <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
        Halo, {{ Auth::user()->name }}!
      </h1>
      <p class="text-gray-500 mt-1">Pantau status servis perangkat Anda di sini.</p>
    </div>

    <!-- Popup tiket baru -->
    @if($tiketBaru)
      <div id="alertTiket" class="bg-green-50 border-l-4 border-green-500 rounded-xl p-5 mb-6 flex items-start gap-4">
        <i class="fas fa-check-circle text-green-500 text-2xl mt-1"></i>
        <div>
          <p class="font-bold text-gray-800">Booking berhasil!</p>
          <p class="text-gray-600 text-sm mt-1">
            Nomor tiket Anda:
            <span class="font-mono font-bold text-yellow-700 text-base">{{ $tiketBaru }}</span>
          </p>
          <p class="text-gray-500 text-xs mt-1">Simpan nomor tiket ini untuk melacak status servis Anda.</p>
        </div>
        <button onclick="document.getElementById('alertTiket').remove()"
          class="ml-auto text-gray-400 hover:text-gray-600">
          <i class="fas fa-times"></i>
        </button>
      </div>
    @endif

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
      <div class="bg-white rounded-xl shadow p-4 border-l-4 border-yellow-400">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Total Booking</p>
            <p class="text-2xl font-bold">{{ $total }}</p>
          </div>
          <i class="fas fa-ticket-alt text-yellow-400 text-3xl"></i>
        </div>
      </div>
      <div class="bg-white rounded-xl shadow p-4 border-l-4 border-green-400">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Selesai</p>
            <p class="text-2xl font-bold">{{ $selesai }}</p>
          </div>
          <i class="fas fa-check-circle text-green-500 text-3xl"></i>
        </div>
      </div>
      <div class="bg-white rounded-xl shadow p-4 border-l-4 border-orange-400">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Dalam Proses</p>
            <p class="text-2xl font-bold">{{ $proses }}</p>
          </div>
          <i class="fas fa-spinner fa-pulse text-orange-500 text-3xl"></i>
        </div>
      </div>
    </div>

    <!-- Daftar Servis -->
    @if($semuaServis->isEmpty())
      <div class="bg-white rounded-2xl shadow p-12 text-center">
        <i class="fas fa-inbox text-gray-300 text-6xl mb-4 block"></i>
        <p class="text-gray-500 text-lg font-semibold">Belum ada booking</p>
        <p class="text-gray-400 text-sm mt-1 mb-6">Mulai booking servis perangkat Anda sekarang.</p>
        <a href="{{ route('estimasi') }}"
          class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold px-6 py-3 rounded-xl transition">
          <i class="fas fa-plus mr-2"></i>Booking Sekarang
        </a>
      </div>
    @else
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach($semuaServis as $servis)
          <div class="bg-white rounded-2xl shadow hover:shadow-md transition overflow-hidden">
            <div class="bg-gray-50 px-5 py-4 flex items-center justify-between border-b border-gray-100">
              <span class="font-mono font-bold text-yellow-700 text-sm">{{ $servis->nomor_tiket }}</span>
              <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass[$servis->status] ?? 'bg-gray-100 text-gray-700' }}">
                <i class="fas {{ $statusIcon[$servis->status] ?? 'fa-circle' }} mr-1"></i>
                {{ $servis->status }}
              </span>
            </div>
            <div class="px-5 py-4 space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-500">Perangkat</span>
                <span class="font-semibold text-gray-800">{{ $labelPerangkat[$servis->perangkat] ?? $servis->perangkat }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Kerusakan</span>
                <span class="font-semibold text-gray-800">{{ $labelKerusakan[$servis->jenis_kerusakan] ?? $servis->jenis_kerusakan }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Estimasi Biaya</span>
                <span class="font-bold text-yellow-600">
                  {{ $servis->estimasi_harga ? 'Rp ' . number_format($servis->estimasi_harga, 0, ',', '.') : '-' }}
                </span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Tanggal Masuk</span>
                <span class="text-gray-700">{{ $servis->created_at->format('d M Y') }}</span>
              </div>
            </div>
            <div class="px-5 py-3 border-t border-gray-100 flex justify-end">
              <a href="{{ route('servis.show', $servis) }}"
                class="text-yellow-600 hover:text-yellow-700 text-sm font-semibold">
                Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
              </a>
            </div>
          </div>
        @endforeach
      </div>
    @endif

  </main>
</x-app-layout>
