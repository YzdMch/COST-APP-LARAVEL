@php
  $labelPerangkat = \App\Models\Servis::labelPerangkat();
  $labelKerusakan = \App\Models\Servis::labelKerusakan();
  $statusClass = \App\Models\Servis::statusClasses();
@endphp

<x-app-layout>
  <x-slot:title>Detail Servis</x-slot:title>

  <main class="max-w-5xl mx-auto px-5 py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

      <!-- Info Servis -->
      <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">
          <i class="fas fa-info-circle text-yellow-500 mr-2"></i>Detail Servis
        </h2>
        <div class="space-y-3 text-sm">
          <div class="flex justify-between border-b border-gray-100 pb-2">
            <span class="text-gray-500">Nomor Tiket</span>
            <span class="font-mono font-bold text-yellow-700">{{ $servis->nomor_tiket }}</span>
          </div>
          <div class="flex justify-between border-b border-gray-100 pb-2">
            <span class="text-gray-500">Status</span>
            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass[$servis->status] ?? '' }}">
              {{ $servis->status }}
            </span>
          </div>
          <div class="flex justify-between border-b border-gray-100 pb-2">
            <span class="text-gray-500">Perangkat</span>
            <span class="font-semibold text-gray-800">{{ $labelPerangkat[$servis->perangkat] ?? $servis->perangkat }}</span>
          </div>
          <div class="flex justify-between border-b border-gray-100 pb-2">
            <span class="text-gray-500">Kerusakan</span>
            <span class="font-semibold text-gray-800">{{ $labelKerusakan[$servis->jenis_kerusakan] ?? $servis->jenis_kerusakan }}</span>
          </div>
          <div class="flex justify-between border-b border-gray-100 pb-2">
            <span class="text-gray-500">Cabang</span>
            <span class="font-semibold text-gray-800">Surabaya</span>
          </div>
          <div class="flex justify-between border-b border-gray-100 pb-2">
            <span class="text-gray-500">Estimasi Biaya</span>
            <span class="font-bold text-yellow-600">
              {{ $servis->estimasi_harga ? 'Rp ' . number_format($servis->estimasi_harga, 0, ',', '.') : 'Menunggu pengecekan' }}
            </span>
          </div>
          <div class="flex justify-between border-b border-gray-100 pb-2">
            <span class="text-gray-500">Tanggal Masuk</span>
            <span class="text-gray-700">{{ $servis->created_at->format('d M Y, H:i') }}</span>
          </div>
          <div>
            <span class="text-gray-500 block mb-1">Deskripsi Keluhan</span>
            <p class="text-gray-800 bg-gray-50 rounded-lg p-3">{!! nl2br(e($servis->deskripsi)) !!}</p>
          </div>
        </div>
      </div>

      <!-- Riwayat Status -->
      <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">
          <i class="fas fa-history text-yellow-500 mr-2"></i>Riwayat Update
        </h2>

        @if($logs->isEmpty())
          <p class="text-gray-400 text-sm text-center py-8">Belum ada update dari teknisi.</p>
        @else
          <div class="relative">
            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
            <div class="space-y-5">
              @foreach($logs as $log)
                <div class="flex gap-4 relative">
                  <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 z-10
                    {{ $log->status === 'Selesai' ? 'bg-green-500' : 'bg-yellow-400' }}">
                    <i class="fas fa-circle text-white text-xs"></i>
                  </div>
                  <div class="bg-gray-50 rounded-xl p-3 flex-1 text-sm">
                    <div class="flex items-center justify-between mb-1">
                      <span class="font-semibold text-gray-800">{{ $log->status }}</span>
                      <span class="text-xs text-gray-400">{{ $log->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                    @if($log->catatan)
                      <p class="text-gray-600">{!! nl2br(e($log->catatan)) !!}</p>
                    @endif
                    @if($log->updatedByUser)
                      <p class="text-xs text-gray-400 mt-1">
                        <i class="fas fa-user-cog mr-1"></i>{{ $log->updatedByUser->name }}
                      </p>
                    @endif
                    @if($log->foto)
                      <img src="{{ asset('storage/uploads/' . $log->foto) }}" alt="Foto progres"
                        class="mt-2 rounded-lg max-h-32 object-cover">
                    @endif
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif
      </div>

    </div>
  </main>
</x-app-layout>
