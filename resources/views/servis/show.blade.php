@php
  $labelPerangkat = \App\Models\Servis::labelPerangkat();
  $labelKerusakan = \App\Models\Servis::labelKerusakan();
  $statusClass = \App\Models\Servis::statusClasses();
  $allStatuses = ['Diterima', 'Sedang dicek', 'Perbaikan', 'Testing', 'Selesai'];
  $currentIdx = array_search($servis->status, $allStatuses) ?: 0;
@endphp

<x-app-layout>
  <x-slot:title>Detail Servis — {{ $servis->nomor_tiket }}</x-slot:title>

  <main class="max-w-5xl mx-auto px-5 py-10">

    {{-- Back + header --}}
    <div class="flex items-center gap-3 mb-6">
      <a href="{{ route('dashboard') }}" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 transition">
        <i class="fas fa-arrow-left"></i>
      </a>
      <div class="flex-1">
        <h1 class="text-xl font-extrabold text-gray-800">Detail Servis</h1>
        <p class="text-sm text-gray-400">Tiket <span class="font-mono font-bold text-yellow-600">{{ $servis->nomor_tiket }}</span></p>
      </div>
      <span class="px-4 py-1.5 rounded-full text-sm font-semibold {{ $statusClass[$servis->status] ?? 'bg-gray-100' }}">
        {{ $servis->status }}
      </span>
    </div>

    {{-- Progress bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
      <h3 class="font-bold text-sm text-gray-800 mb-4">Progress Perbaikan</h3>
      <div class="flex items-center justify-between">
        @foreach($allStatuses as $i => $st)
          <div class="flex flex-col items-center flex-1">
            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold
              {{ $i < $currentIdx ? 'bg-green-500 text-white' : ($i === $currentIdx ? 'bg-yellow-400 text-gray-900 ring-4 ring-yellow-100' : 'bg-gray-100 text-gray-400') }}">
              @if($i < $currentIdx) <i class="fas fa-check text-xs"></i> @else {{ $i + 1 }} @endif
            </div>
            <p class="text-[10px] mt-1.5 font-medium text-center {{ $i === $currentIdx ? 'text-yellow-600' : 'text-gray-400' }}">{{ $st }}</p>
          </div>
          @if($i < count($allStatuses) - 1)
            <div class="flex-1 h-0.5 mt-[-18px] mx-1 {{ $i < $currentIdx ? 'bg-green-400' : 'bg-gray-200' }}"></div>
          @endif
        @endforeach
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

      {{-- Left: Info servis --}}
      <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
          <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-laptop text-yellow-500"></i> Info Perangkat
          </h2>
          <div class="space-y-3 text-sm">
            <div class="flex justify-between"><span class="text-gray-400">Perangkat</span><span class="font-semibold text-gray-700">{{ $labelPerangkat[$servis->perangkat] ?? $servis->perangkat }}</span></div>
            <div class="flex justify-between"><span class="text-gray-400">Kerusakan</span><span class="font-semibold text-gray-700">{{ $labelKerusakan[$servis->jenis_kerusakan] ?? $servis->jenis_kerusakan }}</span></div>
            <div class="flex justify-between"><span class="text-gray-400">Cabang</span><span class="font-semibold text-gray-700">{{ $servis->cabangRelasi?->nama ?? ucfirst($servis->cabang) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-400">Tanggal Masuk</span><span class="text-gray-700">{{ $servis->created_at->format('d M Y, H:i') }}</span></div>
            <div class="flex justify-between"><span class="text-gray-400">Estimasi Biaya</span>
              <span class="font-bold text-yellow-600">{{ $servis->estimasi_harga ? 'Rp ' . number_format($servis->estimasi_harga, 0, ',', '.') : 'Menunggu pengecekan' }}</span>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
          <h2 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
            <i class="fas fa-user text-yellow-500"></i> Info Pelanggan
          </h2>
          <div class="space-y-3 text-sm">
            <div class="flex justify-between"><span class="text-gray-400">Nama</span><span class="font-semibold text-gray-700">{{ $servis->nama_pelanggan }}</span></div>
            <div class="flex justify-between"><span class="text-gray-400">Email</span><span class="text-gray-700">{{ $servis->email }}</span></div>
            <div class="flex justify-between"><span class="text-gray-400">Telepon</span><span class="text-gray-700">{{ $servis->no_telepon }}</span></div>
          </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
          <h2 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
            <i class="fas fa-comment-dots text-yellow-500"></i> Deskripsi Keluhan
          </h2>
          <p class="text-sm text-gray-600 leading-relaxed bg-gray-50 rounded-xl p-4">{!! nl2br(e($servis->deskripsi)) !!}</p>
        </div>
      </div>

      {{-- Right: Timeline --}}
      <div class="lg:col-span-3">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
          <h2 class="font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fas fa-clock-rotate-left text-yellow-500"></i> Riwayat Update
          </h2>

          @if($logs->isEmpty())
            <p class="text-gray-300 text-sm text-center py-10"><i class="fas fa-inbox text-3xl mb-2 block"></i>Belum ada update dari teknisi.</p>
          @else
            <div class="relative">
              <div class="absolute left-[15px] top-2 bottom-2 w-0.5 bg-gray-100"></div>
              <div class="space-y-6">
                @foreach($logs as $log)
                  <div class="flex gap-4 relative">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 z-10 shadow-sm
                      {{ $log->status === 'Selesai' ? 'bg-green-500' : 'bg-yellow-400' }}">
                      <i class="fas {{ $log->status === 'Selesai' ? 'fa-check' : 'fa-circle' }} text-white text-[10px]"></i>
                    </div>
                    <div class="flex-1 bg-gray-50 rounded-xl p-4">
                      <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-gray-800 text-sm">{{ $log->status }}</span>
                        <span class="text-xs text-gray-400">{{ $log->updated_at->format('d M Y, H:i') }}</span>
                      </div>
                      @if($log->catatan)
                        <p class="text-sm text-gray-600 leading-relaxed mb-2">{!! nl2br(e($log->catatan)) !!}</p>
                      @endif
                      @if($log->updatedByUser)
                        <p class="text-xs text-gray-400">
                          <i class="fas fa-user-cog mr-1"></i>{{ $log->updatedByUser->name }}
                        </p>
                      @endif
                      @if($log->foto_url)
                        <div class="mt-3">
                          <img src="{{ $log->foto_url }}" alt="Foto progres"
                            class="rounded-xl max-h-48 object-cover border border-gray-200 cursor-pointer hover:opacity-90 transition"
                            onclick="openImage(this.src)">
                          <p class="text-[10px] text-gray-400 mt-1"><i class="fas fa-camera mr-1"></i>Foto bukti progres</p>
                        </div>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>
      </div>

    </div>
  </main>

  {{-- Image preview modal --}}
  <div id="imageModal" class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 hidden cursor-pointer" onclick="this.classList.add('hidden')">
    <img id="imagePreview" class="max-w-[90vw] max-h-[90vh] rounded-xl shadow-2xl" alt="Preview">
  </div>

  <script>
    function openImage(src) {
      document.getElementById('imagePreview').src = src;
      document.getElementById('imageModal').classList.remove('hidden');
    }
  </script>
</x-app-layout>
