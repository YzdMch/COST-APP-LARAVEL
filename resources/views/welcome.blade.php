<x-app-layout>
  <x-slot:title>Transparansi Harga Reparasi</x-slot:title>

  {{-- HERO --}}
  <section id="home" class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-20 md:py-28 lg:py-32">
    <div class="absolute inset-0 opacity-10">
      <div class="absolute top-20 left-10 w-72 h-72 bg-yellow-400 rounded-full blur-[120px]"></div>
      <div class="absolute bottom-10 right-20 w-96 h-96 bg-amber-500 rounded-full blur-[150px]"></div>
    </div>
    <div class="max-w-7xl mx-auto px-5 relative z-10">
      <div class="grid md:grid-cols-2 gap-10 items-center">
        <div>
          <div class="animate-fade-up inline-flex items-center gap-2 bg-yellow-400/10 border border-yellow-400/20 text-yellow-400 text-sm font-medium px-4 py-1.5 rounded-full mb-6">
            <i class="fas fa-shield-alt text-xs"></i> Garansi 90 Hari Setiap Servis
          </div>
          <h1 class="animate-fade-up animate-fade-up-delay-1 text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight mb-5">
            Transparansi Harga <span class="gradient-text">Reparasi</span> Terpercaya
          </h1>
          <p class="animate-fade-up animate-fade-up-delay-2 text-gray-400 text-lg leading-relaxed mb-8 max-w-lg">
            Solusi perbaikan komputer modern dengan biaya jujur dan estimasi instan.
            Tanpa biaya tersembunyi, pengerjaan cepat dan bergaransi.
          </p>
          <div class="animate-fade-up animate-fade-up-delay-3 flex flex-wrap gap-3">
            <a href="{{ route('estimasi') }}"
              class="bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-500 hover:to-amber-600 text-gray-900 font-bold px-7 py-3.5 rounded-xl shadow-lg shadow-yellow-500/20 hover:shadow-xl hover:shadow-yellow-500/30 transition-all duration-300 flex items-center gap-2">
              <i class="fas fa-calculator"></i> Cek Estimasi Harga
            </a>
            <a href="#services"
              class="border border-gray-600 text-gray-300 hover:bg-white/10 hover:border-gray-400 font-semibold px-7 py-3.5 rounded-xl transition-all duration-300 flex items-center gap-2">
              <i class="fas fa-arrow-down text-sm"></i> Lihat Layanan
            </a>
          </div>
          <div class="animate-fade-up animate-fade-up-delay-4 flex items-center gap-6 mt-10 text-sm text-gray-500">
            <div class="flex items-center gap-2"><i class="fas fa-check-circle text-green-400"></i> No Hidden Fees</div>
            <div class="flex items-center gap-2"><i class="fas fa-check-circle text-green-400"></i> Real-time Tracking</div>
          </div>
        </div>
        <div class="hidden md:flex justify-center">
          <div class="relative animate-float">
            <div class="w-72 h-72 lg:w-80 lg:h-80 rounded-3xl bg-gradient-to-br from-yellow-400/20 to-amber-600/10 border border-yellow-400/10 backdrop-blur-sm flex items-center justify-center">
              <i class="fas fa-laptop-code text-8xl lg:text-9xl text-yellow-400/60"></i>
            </div>
            <div class="absolute -top-4 -right-4 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg animate-pulse">
              <i class="fas fa-bolt mr-1"></i> Fast Service
            </div>
            <div class="absolute -bottom-3 -left-3 glass-card px-4 py-2.5 rounded-xl shadow-lg">
              <p class="text-xs text-gray-400">Mulai dari</p>
              <p class="text-lg font-bold text-yellow-400">Rp 100.000</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- STATS BAR --}}
  <section class="relative z-20 -mt-8">
    <div class="max-w-5xl mx-auto px-5">
      <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 p-6 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        <div>
          <p class="text-2xl md:text-3xl font-extrabold text-gray-800">500<span class="text-yellow-500">+</span></p>
          <p class="text-xs text-gray-400 mt-1">Perangkat Diperbaiki</p>
        </div>
        <div>
          <p class="text-2xl md:text-3xl font-extrabold text-gray-800">98<span class="text-yellow-500">%</span></p>
          <p class="text-xs text-gray-400 mt-1">Tingkat Kepuasan</p>
        </div>
        <div>
          <p class="text-2xl md:text-3xl font-extrabold text-gray-800">90<span class="text-yellow-500"> Hari</span></p>
          <p class="text-xs text-gray-400 mt-1">Garansi Servis</p>
        </div>
        <div>
          <p class="text-2xl md:text-3xl font-extrabold text-gray-800">24<span class="text-yellow-500"> Jam</span></p>
          <p class="text-xs text-gray-400 mt-1">Rata-rata Selesai</p>
        </div>
      </div>
    </div>
  </section>

  {{-- SERVICES --}}
  <section id="services" class="py-20 md:py-24">
    <div class="max-w-7xl mx-auto px-5">
      <div class="text-center mb-14">
        <p class="text-yellow-500 font-semibold text-sm tracking-wider uppercase mb-2">Layanan Kami</p>
        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800">Layanan <span class="gradient-text">Populer</span></h2>
        <p class="text-gray-400 mt-3 max-w-md mx-auto">Pilihan perbaikan dan upgrade dengan kualitas terbaik dan harga transparan</p>
      </div>
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @php
        $services = [
          ['icon' => 'fa-mobile-screen-button', 'title' => 'Ganti LCD', 'desc' => 'Layar bergaris, pecah, atau tidak muncul gambar', 'color' => 'blue'],
          ['icon' => 'fa-battery-three-quarters', 'title' => 'Ganti Baterai', 'desc' => 'Baterai kembang, drop, atau cepat habis', 'color' => 'green'],
          ['icon' => 'fa-hard-drive', 'title' => 'Upgrade SSD', 'desc' => 'Percepat performa laptop hingga 10x lipat', 'color' => 'purple'],
          ['icon' => 'fa-temperature-low', 'title' => 'Thermal Paste', 'desc' => 'Pembersihan debu dan penggantian pasta pendingin', 'color' => 'orange'],
        ];
        $colors = [
          'blue' => 'from-blue-500 to-blue-600 shadow-blue-200',
          'green' => 'from-emerald-500 to-emerald-600 shadow-emerald-200',
          'purple' => 'from-violet-500 to-violet-600 shadow-violet-200',
          'orange' => 'from-orange-500 to-orange-600 shadow-orange-200',
        ];
        @endphp
        @foreach($services as $s)
        <div class="group bg-white rounded-2xl p-6 border border-gray-100 hover:border-gray-200 hover:shadow-xl hover:shadow-gray-100 hover:-translate-y-1 transition-all duration-300">
          <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $colors[$s['color']] }} flex items-center justify-center text-white text-lg mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
            <i class="fas {{ $s['icon'] }}"></i>
          </div>
          <h3 class="font-bold text-gray-800 mb-2">{{ $s['title'] }}</h3>
          <p class="text-sm text-gray-400 leading-relaxed">{{ $s['desc'] }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- HOW IT WORKS --}}
  <section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-5">
      <div class="text-center mb-14">
        <p class="text-yellow-500 font-semibold text-sm tracking-wider uppercase mb-2">Cara Kerja</p>
        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800">Semudah <span class="gradient-text">3 Langkah</span></h2>
      </div>
      <div class="grid md:grid-cols-3 gap-8">
        @php
        $steps = [
          ['num' => '01', 'icon' => 'fa-calculator', 'title' => 'Cek Estimasi', 'desc' => 'Pilih perangkat dan jenis kerusakan, dapatkan estimasi biaya instan tanpa perlu login'],
          ['num' => '02', 'icon' => 'fa-calendar-check', 'title' => 'Booking Online', 'desc' => 'Daftar akun dan buat booking servis. Dapatkan nomor tiket unik untuk tracking'],
          ['num' => '03', 'icon' => 'fa-eye', 'title' => 'Pantau Status', 'desc' => 'Lihat progres perbaikan real-time di dashboard, lengkap dengan foto dan catatan teknisi'],
        ];
        @endphp
        @foreach($steps as $i => $step)
        <div class="relative text-center group">
          <div class="text-6xl font-black text-gray-100 group-hover:text-yellow-100 transition-colors duration-300 mb-4">{{ $step['num'] }}</div>
          <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center text-white text-xl mx-auto mb-4 shadow-lg shadow-yellow-200 group-hover:scale-110 transition-transform duration-300">
            <i class="fas {{ $step['icon'] }}"></i>
          </div>
          <h3 class="font-bold text-gray-800 text-lg mb-2">{{ $step['title'] }}</h3>
          <p class="text-sm text-gray-400 leading-relaxed max-w-xs mx-auto">{{ $step['desc'] }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- WHY US --}}
  <section id="why" class="py-20 md:py-24">
    <div class="max-w-7xl mx-auto px-5">
      <div class="grid md:grid-cols-2 gap-12 items-center">
        <div>
          <p class="text-yellow-500 font-semibold text-sm tracking-wider uppercase mb-2">Keunggulan</p>
          <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-6">Mengapa Memilih <span class="gradient-text">Geeko?</span></h2>
          <div class="space-y-5">
            @php
            $reasons = [
              ['icon' => 'fa-receipt', 'color' => 'text-emerald-500 bg-emerald-50', 'title' => 'No Hidden Fees', 'desc' => 'Estimasi harga transparan sebelum servis dimulai. Harga yang Anda lihat adalah harga yang Anda bayar.'],
              ['icon' => 'fa-location-dot', 'color' => 'text-blue-500 bg-blue-50', 'title' => 'Real-time Tracking', 'desc' => 'Pantau status perbaikan perangkat Anda secara langsung dari mana saja melalui dashboard.'],
              ['icon' => 'fa-shield-halved', 'color' => 'text-violet-500 bg-violet-50', 'title' => 'Garansi 90 Hari', 'desc' => 'Setiap perbaikan dilengkapi garansi resmi 90 hari untuk ketenangan pikiran Anda.'],
              ['icon' => 'fa-bolt', 'color' => 'text-amber-500 bg-amber-50', 'title' => 'Pengerjaan Cepat', 'desc' => 'Rata-rata penyelesaian 24 jam untuk kerusakan umum. Perangkat Anda kembali dengan cepat.'],
            ];
            @endphp
            @foreach($reasons as $r)
            <div class="flex gap-4 group">
              <div class="w-11 h-11 rounded-xl {{ $r['color'] }} flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                <i class="fas {{ $r['icon'] }}"></i>
              </div>
              <div>
                <h3 class="font-bold text-gray-800 mb-0.5">{{ $r['title'] }}</h3>
                <p class="text-sm text-gray-400 leading-relaxed">{{ $r['desc'] }}</p>
              </div>
            </div>
            @endforeach
          </div>
        </div>
        <div class="hidden md:flex justify-center">
          <div class="relative">
            <div class="w-80 h-80 rounded-3xl bg-gradient-to-br from-yellow-50 to-amber-50 border border-yellow-100 flex items-center justify-center">
              <i class="fas fa-gears text-[7rem] text-yellow-300"></i>
            </div>
            <div class="absolute -top-5 -right-5 glass-card rounded-2xl p-4 shadow-xl">
              <div class="flex items-center gap-2 text-sm"><i class="fas fa-star text-yellow-400"></i><span class="font-bold text-gray-700">4.9/5</span><span class="text-gray-400">Rating</span></div>
            </div>
            <div class="absolute -bottom-5 -left-5 glass-card rounded-2xl p-4 shadow-xl">
              <div class="flex items-center gap-2 text-sm"><i class="fas fa-check-circle text-green-500"></i><span class="font-bold text-gray-700">500+</span><span class="text-gray-400">Servis</span></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- CTA --}}
  <section class="py-20">
    <div class="max-w-4xl mx-auto px-5">
      <div class="relative bg-gradient-to-r from-gray-900 to-gray-800 rounded-3xl p-10 md:p-14 text-center overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-yellow-400/10 rounded-full blur-[80px]"></div>
        <div class="relative z-10">
          <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">Perangkat Anda Bermasalah?</h2>
          <p class="text-gray-400 mb-8 max-w-lg mx-auto">Cek estimasi biaya perbaikan sekarang — gratis, tanpa registrasi, dan langsung tahu kisaran harganya.</p>
          <div class="flex flex-wrap justify-center gap-3">
            <a href="{{ route('estimasi') }}"
              class="bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-500 hover:to-amber-600 text-gray-900 font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-yellow-500/20 transition-all duration-300 flex items-center gap-2">
              <i class="fas fa-calculator"></i> Cek Estimasi Gratis
            </a>
            <a href="{{ route('register') }}"
              class="border border-gray-600 text-gray-300 hover:bg-white/10 font-semibold px-8 py-3.5 rounded-xl transition-all duration-300 flex items-center gap-2">
              <i class="fas fa-user-plus"></i> Daftar Akun
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- DEVELOPER --}}
  <section id="developer" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-5">
      <div class="text-center mb-14">
        <p class="text-yellow-500 font-semibold text-sm tracking-wider uppercase mb-2">Tim Kami</p>
        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800">Developer <span class="gradient-text">Project</span></h2>
      </div>
      <div class="grid md:grid-cols-3 gap-6 max-w-3xl mx-auto">
        @php
        $team = [
          ['photo' => 'storage/images/nao.jpg', 'init' => 'YZ', 'name' => 'YZ', 'role' => 'Ternak Bebek enak ya...', 'color' => 'from-yellow-400 to-amber-500'],
          ['photo' => '', 'init' => 'KZ', 'name' => 'KAZUMI', 'role' => 'AI & Architecture', 'color' => 'from-violet-500 to-purple-600'],
          ['photo' => '', 'init' => 'FR', 'name' => 'FR', 'role' => 'DevOps & Deployment', 'color' => 'from-blue-500 to-cyan-500'],
        ];
        @endphp
        @foreach($team as $t)
        <div class="group text-center p-6 rounded-2xl hover:bg-gray-50 transition-all duration-300">
          <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-gradient-to-br {{ $t['color'] }} flex items-center justify-center text-2xl font-black text-white shadow-lg group-hover:scale-110 group-hover:rounded-xl transition-all duration-300 overflow-hidden relative">
            @if($t['photo'])
              <img src="{{ asset($t['photo']) }}" alt="{{ $t['name'] }}" class="w-full h-full object-cover absolute inset-0">
            @else
              {{ $t['init'] }}
            @endif
          </div>
          <h3 class="font-bold text-gray-800">{{ $t['name'] }}</h3>
          <p class="text-sm text-gray-400 mt-0.5">{{ $t['role'] }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- CONTACT --}}
  <section id="contact" class="py-20">
    <div class="max-w-7xl mx-auto px-5">
      <div class="text-center mb-14">
        <p class="text-yellow-500 font-semibold text-sm tracking-wider uppercase mb-2">Hubungi Kami</p>
        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800">Lokasi <span class="gradient-text">Toko</span></h2>
      </div>
      <div class="grid md:grid-cols-2 gap-8 items-start">
        <div class="bg-white rounded-2xl p-7 border border-gray-100 shadow-sm">
          <h3 class="font-bold text-lg text-gray-800 mb-5 flex items-center gap-2">
            <i class="fas fa-store text-yellow-500"></i> Geeko Komputer
          </h3>
          <div class="space-y-4 text-sm text-gray-500">
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg bg-yellow-50 flex items-center justify-center flex-shrink-0"><i class="fas fa-map-pin text-yellow-500"></i></div>
              <div><p class="font-semibold text-gray-700">Alamat</p><p>Surabaya, Jawa Timur</p></div>
            </div>
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg bg-yellow-50 flex items-center justify-center flex-shrink-0"><i class="fas fa-phone text-yellow-500"></i></div>
              <div><p class="font-semibold text-gray-700">Telepon</p><p>+62 812-3456-7890</p></div>
            </div>
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg bg-yellow-50 flex items-center justify-center flex-shrink-0"><i class="fas fa-clock text-yellow-500"></i></div>
              <div><p class="font-semibold text-gray-700">Jam Operasional</p><p>Senin – Sabtu, 09:00 – 18:00</p></div>
            </div>
          </div>
        </div>
        <iframe
          class="w-full h-80 rounded-2xl border border-gray-100 shadow-sm"
          src="https://www.google.com/maps?q=surabaya&output=embed"
          allowfullscreen loading="lazy">
        </iframe>
      </div>
    </div>
  </section>

</x-app-layout>
