<x-app-layout>
  <x-slot:title>Transparansi Harga Reparasi</x-slot:title>

  <!-- HERO -->
  <section id="home" class="relative bg-cover bg-center py-32"
    style="background-image: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=1920&q=80')">
    <div class="max-w-7xl mx-auto px-4">
      <div class="max-w-xl text-white">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">
          Transparansi Harga <span class="text-yellow-400">Reparasi</span> Terpercaya
        </h1>
        <p class="mb-6 text-gray-200">
          Solusi perbaikan komputer modern dengan biaya jujur dan estimasi instan.
          Tanpa biaya tersembunyi, pengerjaan cepat dan bergaransi.
        </p>
        <div class="flex flex-wrap gap-4">
          <a href="{{ route('estimasi') }}"
            class="bg-yellow-400 hover:bg-yellow-300 text-black font-semibold px-6 py-3 rounded-lg transition">
            <i class="fas fa-tag mr-2"></i>Cek Estimasi Harga
          </a>
          <a href="#services"
            class="border border-white text-white hover:bg-white hover:text-black font-semibold px-6 py-3 rounded-lg transition">
            <i class="fas fa-info-circle mr-2"></i>Lihat Layanan
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- SERVICES -->
  <section id="services" class="py-20">
    <div class="max-w-7xl mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold">Layanan <span class="text-yellow-400">Populer</span></h2>
        <p class="text-gray-500 mt-2">Pilihan perbaikan dan instalasi dengan kualitas terbaik</p>
      </div>
      <div class="grid md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl shadow text-center hover:shadow-md transition">
          <i class="fas fa-mobile-alt text-3xl text-yellow-400 mb-4"></i>
          <h3 class="font-semibold mb-2">Ganti LCD</h3>
          <p class="text-sm text-gray-500">Layar bergaris, pecah, atau tidak muncul gambar</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow text-center hover:shadow-md transition">
          <i class="fas fa-battery-three-quarters text-3xl text-yellow-400 mb-4"></i>
          <h3 class="font-semibold mb-2">Ganti Baterai</h3>
          <p class="text-sm text-gray-500">Baterai kembang atau cepat habis</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow text-center hover:shadow-md transition">
          <i class="fas fa-hdd text-3xl text-yellow-400 mb-4"></i>
          <h3 class="font-semibold mb-2">Upgrade SSD</h3>
          <p class="text-sm text-gray-500">Percepat performa laptop hingga 10x</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow text-center hover:shadow-md transition">
          <i class="fas fa-thermometer-half text-3xl text-yellow-400 mb-4"></i>
          <h3 class="font-semibold mb-2">Thermal Paste</h3>
          <p class="text-sm text-gray-500">Pembersihan debu dan pasta pendingin</p>
        </div>
      </div>
    </div>
  </section>

  <!-- WHY US -->
  <section id="why" class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-4">
      <h2 class="text-center text-3xl font-bold mb-12">
        Mengapa Memilih <span class="text-yellow-400">Layanan Kami</span>
      </h2>
      <div class="grid md:grid-cols-3 gap-10 text-center">
        <div>
          <i class="fas fa-receipt text-4xl text-yellow-400 mb-4"></i>
          <h3 class="font-semibold mb-2">No Hidden Fees</h3>
          <p class="text-gray-500 text-sm">Estimasi harga transparan tanpa biaya tersembunyi</p>
        </div>
        <div>
          <i class="fas fa-map-marker-alt text-4xl text-yellow-400 mb-4"></i>
          <h3 class="font-semibold mb-2">Real-time Tracking</h3>
          <p class="text-gray-500 text-sm">Pantau status perbaikan perangkat secara langsung</p>
        </div>
        <div>
          <i class="fas fa-shield-alt text-4xl text-yellow-400 mb-4"></i>
          <h3 class="font-semibold mb-2">Garansi 90 Hari</h3>
          <p class="text-gray-500 text-sm">Perbaikan dilengkapi garansi resmi toko</p>
        </div>
      </div>
    </div>
  </section>

  <!-- DEVELOPER -->
  <section id="developer" class="py-20">
    <div class="max-w-7xl mx-auto px-4">
      <h2 class="text-center text-3xl font-bold mb-12">
        Developer <span class="text-yellow-400">Project</span>
      </h2>
      <div class="grid md:grid-cols-3 gap-10 text-center">
        <div>
          <div class="w-28 h-28 mx-auto mb-4 rounded-full bg-yellow-400 flex items-center justify-center text-3xl font-bold text-white">YZ</div>
          <h3 class="font-semibold mb-1">YZ</h3>
          <p class="text-gray-500 text-sm">NGODING / TURU</p>
        </div>
        <div>
          <div class="w-28 h-28 mx-auto mb-4 rounded-full bg-yellow-400 flex items-center justify-center text-3xl font-bold text-white">KZ</div>
          <h3 class="font-semibold mb-1">KAZUMI</h3>
          <p class="text-gray-500 text-sm">Artificial Intelligence</p>
        </div>
        <div>
          <div class="w-28 h-28 mx-auto mb-4 rounded-full bg-yellow-400 flex items-center justify-center text-3xl font-bold text-white">FR</div>
          <h3 class="font-semibold mb-1">FR</h3>
          <p class="text-gray-500 text-sm">Github & Deployment</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CONTACT -->
  <section id="contact" class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-10 items-start">
      <div>
        <h2 class="text-2xl font-bold mb-4">
          <i class="fas fa-store text-yellow-500 mr-2"></i>Geeko Komputer
        </h2>
        <p class="mb-2 text-gray-600"><i class="fas fa-map-pin text-yellow-500 mr-2"></i>Surabaya, Jawa Timur</p>
        <p class="mb-2 text-gray-600"><i class="fas fa-phone text-yellow-500 mr-2"></i>+62 812-3456-7890</p>
        <p class="mb-4 text-gray-600"><i class="fas fa-clock text-yellow-500 mr-2"></i>Sen - Sab, 09:00 - 18:00</p>
        <div class="flex gap-4 text-2xl text-gray-500">
          <a href="#" class="hover:text-green-500 transition"><i class="fab fa-whatsapp"></i></a>
          <a href="#" class="hover:text-pink-500 transition"><i class="fab fa-instagram"></i></a>
          <a href="#" class="hover:text-blue-600 transition"><i class="fab fa-facebook"></i></a>
        </div>
      </div>
      <iframe
        class="w-full h-80 rounded-xl"
        src="https://www.google.com/maps?q=surabaya&output=embed"
        allowfullscreen
        loading="lazy">
      </iframe>
    </div>
  </section>
</x-app-layout>
