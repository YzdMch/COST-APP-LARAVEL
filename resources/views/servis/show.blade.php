@php
  $labelPerangkat = \App\Models\Servis::labelPerangkat();
  $labelKerusakan = \App\Models\Servis::labelKerusakan();
  $statusClass = \App\Models\Servis::statusClasses();
  $allStatuses = ['Diterima', 'Sedang dicek', 'Perbaikan', 'Testing', 'Selesai'];
  $currentIdx = array_search($servis->status, $allStatuses);
  if ($currentIdx === false) $currentIdx = 0;
@endphp

<x-app-layout>
  <x-slot:title>Detail Servis — {{ $servis->nomor_tiket }}</x-slot:title>

  <main class="max-w-5xl mx-auto px-5 py-10">

    {{-- Error flash --}}
    @if(session('error'))
      <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-5 flex items-center gap-3 text-sm text-red-700">
        <i class="fas fa-exclamation-circle text-red-400"></i>
        {{ session('error') }}
      </div>
    @endif

    {{-- Cancelled Banner --}}
    @if($servis->status === 'Dibatalkan')
      <div class="bg-red-50 border border-red-200 rounded-2xl p-5 mb-6 flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl bg-red-500 flex items-center justify-center flex-shrink-0">
          <i class="fas fa-times text-white"></i>
        </div>
        <div class="flex-1">
          <p class="font-bold text-red-800">Booking Dibatalkan</p>
          <p class="text-red-600 text-sm mt-1">{{ $servis->alasan_batal }}</p>
          @if($servis->cancelled_at)
            <p class="text-red-400 text-xs mt-1">
              <i class="fas fa-clock mr-1"></i>{{ $servis->cancelled_at->format('d M Y, H:i') }}
            </p>
          @endif
        </div>
      </div>
    @endif

    {{-- Back + header --}}
    <div class="flex items-center gap-3 mb-6">
      <a href="{{ route('dashboard') }}" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 transition">
        <i class="fas fa-arrow-left"></i>
      </a>
      <div class="flex-1">
        <h1 class="text-xl font-extrabold text-gray-800">Detail Servis</h1>
        <p class="text-sm text-gray-400">Tiket <span class="font-mono font-bold text-yellow-600">{{ $servis->nomor_tiket }}</span></p>
      </div>
      <div class="flex items-center gap-2">
        {{-- Invoice button (Selesai only) --}}
        @if($servis->status === 'Selesai')
          <a href="{{ route('servis.invoice', $servis) }}"
             id="btn-lihat-invoice"
             class="flex items-center gap-1.5 bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-500 hover:to-amber-600 text-gray-900 font-bold px-4 py-2 rounded-xl shadow-md shadow-yellow-200 transition-all text-sm">
            <i class="fas fa-file-invoice"></i>
            <span class="hidden sm:inline">Lihat Invoice</span>
          </a>
        @endif
        {{-- Cancel button (pelanggan, hanya saat Diterima) --}}
        @if(auth()->user()->isPelanggan() && $servis->isCancellable())
          <button onclick="document.getElementById('modalBatal').classList.remove('hidden')"
            class="flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 font-bold px-4 py-2 rounded-xl border border-red-200 transition-all text-sm">
            <i class="fas fa-times-circle text-xs"></i>
            <span class="hidden sm:inline">Batalkan</span>
          </button>
        @endif
        <span class="px-4 py-1.5 rounded-full text-sm font-semibold {{ $statusClass[$servis->status] ?? 'bg-gray-100' }}">
          {{ $servis->status }}
        </span>
      </div>
    </div>

    {{-- Progress bar (only for active statuses) --}}
    @if($servis->status !== 'Dibatalkan')
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
    @endif

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

          {{-- Foto Booking --}}
          @if($servis->foto_booking)
            <div class="mt-4 pt-4 border-t border-gray-100">
              <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                <i class="fas fa-camera mr-1"></i> Foto Perangkat (dari Pelanggan)
              </p>
              <img src="{{ Str::startsWith($servis->foto_booking, 'http') ? $servis->foto_booking : asset('storage/' . $servis->foto_booking) }}"
                alt="Foto perangkat"
                class="rounded-xl w-full object-cover max-h-48 border border-gray-100 cursor-pointer hover:opacity-90 transition"
                onclick="openImage(this.src)">
            </div>
          @endif
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
                      {{ $log->status === 'Selesai' ? 'bg-green-500' : ($log->status === 'Dibatalkan' ? 'bg-red-500' : 'bg-yellow-400') }}">
                      <i class="fas {{ $log->status === 'Selesai' ? 'fa-check' : ($log->status === 'Dibatalkan' ? 'fa-times' : 'fa-circle') }} text-white text-[10px]"></i>
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

  {{-- Modal Pembatalan --}}
  @if(auth()->user()->isPelanggan() && $servis->isCancellable())
  <div id="modalBatal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden px-4"
       onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden">
      <div class="bg-gradient-to-r from-red-500 to-rose-600 px-6 py-4 flex justify-between items-center">
        <h3 class="text-lg font-bold text-white flex items-center gap-2">
          <i class="fas fa-times-circle"></i> Batalkan Booking
        </h3>
        <button onclick="document.getElementById('modalBatal').classList.add('hidden')" class="text-white/70 hover:text-white">
          <i class="fas fa-times text-lg"></i>
        </button>
      </div>
      <div class="p-6">
        <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 mb-5 flex gap-3">
          <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5"></i>
          <p class="text-sm text-amber-700">Setelah dibatalkan, servis <strong>tidak bisa dikembalikan</strong>. Anda perlu membuat booking baru jika berubah pikiran.</p>
        </div>
        <form method="POST" action="{{ route('servis.cancel', $servis) }}">
          @csrf
          <div class="mb-5">
            <label class="block text-gray-700 font-semibold mb-2 text-sm">Alasan Pembatalan <span class="text-red-500">*</span></label>
            <textarea name="alasan_batal" rows="3" required minlength="10" maxlength="500"
              placeholder="Jelaskan alasan pembatalan, misal: sudah diperbaiki di tempat lain..."
              class="w-full border border-gray-200 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent transition"></textarea>
            <p class="text-xs text-gray-400 mt-1">Minimal 10 karakter</p>
          </div>
          <div class="flex gap-3">
            <button type="button" onclick="document.getElementById('modalBatal').classList.add('hidden')"
              class="flex-1 border border-gray-200 text-gray-600 font-semibold py-3 rounded-xl hover:bg-gray-50 transition">
              Kembali
            </button>
            <button type="submit"
              class="flex-1 bg-gradient-to-r from-red-500 to-rose-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-red-200 transition-all flex items-center justify-center gap-2">
              <i class="fas fa-times-circle"></i> Konfirmasi Batalkan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  @endif

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
