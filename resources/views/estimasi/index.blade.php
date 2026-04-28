<x-app-layout>
  <x-slot:title>Form Estimasi Harga</x-slot:title>

  <!-- FORM SECTION -->
  <section class="py-12 md:py-16 px-5">
    <div class="container mx-auto max-w-5xl">
      <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-6 md:p-8 lg:p-10">

          <!-- Heading -->
          <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center bg-yellow-100 p-3 rounded-full mb-4">
              <i class="fas fa-calculator text-yellow-600 text-3xl"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800">Form Estimasi Biaya</h1>
            <p class="text-gray-500 mt-2 max-w-xl mx-auto">
              Isi data lengkap Anda, sistem akan menghitung estimasi biaya secara otomatis.
            </p>
          </div>

          <!-- Form -->
          <form id="estimasiForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

              <!-- Nama -->
              <div class="md:col-span-2" id="group-nama">
                <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="nama" placeholder="Contoh: John Doe"
                  @auth value="{{ Auth::user()->name }}" @endauth
                  class="w-full border border-gray-300 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">
                <div class="error-msg text-red-500 text-sm mt-1 hidden">Nama harus diisi</div>
              </div>

              <!-- Email -->
              <div id="group-email">
                <label class="block text-gray-700 font-semibold mb-2">Email <span class="text-red-500">*</span></label>
                <input type="email" id="email" placeholder="john@example.com"
                  @auth value="{{ Auth::user()->email }}" @endauth
                  class="w-full border border-gray-300 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">
                <div class="error-msg text-red-500 text-sm mt-1 hidden">Email tidak valid</div>
              </div>

              <!-- Telepon -->
              <div id="group-phone">
                <label class="block text-gray-700 font-semibold mb-2">No. Telepon <span class="text-red-500">*</span></label>
                <input type="tel" id="phone" placeholder="08123456789"
                  @auth value="{{ Auth::user()->no_telepon }}" @endauth
                  class="w-full border border-gray-300 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">
                <div class="error-msg text-red-500 text-sm mt-1 hidden">Nomor telepon harus diisi</div>
              </div>

              <!-- Perangkat -->
              <div id="group-device">
                <label class="block text-gray-700 font-semibold mb-2">Perangkat <span class="text-red-500">*</span></label>
                <select id="device"
                  class="w-full border border-gray-300 rounded-xl py-3 px-4 bg-white focus:outline-none focus:ring-2 focus:ring-yellow-400">
                  <option value="">Pilih Perangkat</option>
                  <option value="macbook">MacBook Pro / Air</option>
                  <option value="windows">Windows Laptop</option>
                  <option value="pc">Desktop PC</option>
                  <option value="imac">iMac / Mac Desktop</option>
                  <option value="other">Lainnya</option>
                </select>
                <div class="error-msg text-red-500 text-sm mt-1 hidden">Pilih perangkat</div>
              </div>

              <!-- Kerusakan -->
              <div id="group-issue">
                <label class="block text-gray-700 font-semibold mb-2">Jenis Kerusakan <span class="text-red-500">*</span></label>
                <select id="issue"
                  class="w-full border border-gray-300 rounded-xl py-3 px-4 bg-white focus:outline-none focus:ring-2 focus:ring-yellow-400">
                  <option value="">Pilih Kerusakan</option>
                  <option value="lcd">Layar Pecah / LCD Rusak</option>
                  <option value="battery">Baterai Kembang / Drop</option>
                  <option value="ssd">Upgrade SSD</option>
                  <option value="thermal">Thermal Paste / Cleaning</option>
                  <option value="other">Lainnya</option>
                </select>
                <div class="error-msg text-red-500 text-sm mt-1 hidden">Pilih jenis kerusakan</div>
              </div>

              <!-- Cabang -->
              <div id="group-branch" class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Cabang Terdekat <span class="text-red-500">*</span></label>
                <select id="branch"
                  class="w-full border border-gray-300 rounded-xl py-3 px-4 bg-white focus:outline-none focus:ring-2 focus:ring-yellow-400">
                  <option value="">Pilih Cabang</option>
                  <option value="surabaya">Surabaya</option>
                </select>
                <div class="error-msg text-red-500 text-sm mt-1 hidden">Pilih cabang</div>
              </div>

              <!-- Deskripsi -->
              <div class="md:col-span-2" id="group-description">
                <label class="block text-gray-700 font-semibold mb-2">Deskripsi Keluhan <span class="text-red-500">*</span></label>
                <textarea id="description" rows="3"
                  placeholder="Jelaskan detail kerusakan... (contoh: layar muncul garis, baterai cepat habis)"
                  class="w-full border border-gray-300 rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-yellow-400"></textarea>
                <div class="error-msg text-red-500 text-sm mt-1 hidden">Deskripsi harus diisi</div>
              </div>

            </div>

            <!-- Submit -->
            <div class="mt-8 text-center">
              <button type="submit" id="submitBtn"
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-8 rounded-xl shadow-md transition duration-200 flex items-center justify-center gap-3 w-full md:w-auto md:mx-auto text-lg">
                <span>Hitung Estimasi</span>
                <i class="fas fa-calculator"></i>
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- POPUP HASIL ESTIMASI -->
  <div id="popupEstimasi" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden px-4">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden">
      <div class="bg-yellow-400 px-6 py-4 flex items-center gap-3">
        <i class="fas fa-file-invoice-dollar text-gray-800 text-2xl"></i>
        <h3 class="text-xl font-bold text-gray-800">Hasil Estimasi Biaya</h3>
      </div>
      <div class="p-6">
        <div class="bg-gray-50 rounded-xl p-4 mb-5 space-y-2 text-sm">
          <div class="flex justify-between">
            <span class="text-gray-500">Perangkat</span>
            <span class="font-semibold text-gray-800" id="popupPerangkat"></span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-500">Kerusakan</span>
            <span class="font-semibold text-gray-800" id="popupKerusakan"></span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-500">Cabang</span>
            <span class="font-semibold text-gray-800" id="popupCabang"></span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-500">Keterangan</span>
            <span class="font-semibold text-gray-800 text-right max-w-xs" id="popupKeterangan"></span>
          </div>
        </div>
        <div class="text-center mb-5">
          <p class="text-gray-500 text-sm mb-1">Estimasi Biaya Sementara</p>
          <p class="text-3xl font-extrabold text-yellow-600" id="popupHarga"></p>
          <p class="text-xs text-gray-400 mt-1">*Harga final ditentukan setelah pengecekan teknisi</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
          <button onclick="tutupPopup()"
            class="flex-1 border border-gray-300 text-gray-700 font-semibold py-3 rounded-xl hover:bg-gray-100 transition">
            <i class="fas fa-times mr-2"></i>Keluar
          </button>
          <button onclick="lanjutBooking()"
            class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 rounded-xl transition">
            <i class="fas fa-calendar-check mr-2"></i>Booking Sekarang
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const labelPerangkat = {
      macbook: 'MacBook Pro / Air', windows: 'Windows Laptop',
      pc: 'Desktop PC', imac: 'iMac / Mac Desktop', other: 'Lainnya',
    };
    const labelKerusakan = {
      lcd: 'Layar Pecah / LCD Rusak', battery: 'Baterai Kembang / Drop',
      ssd: 'Upgrade SSD', thermal: 'Thermal Paste / Cleaning', other: 'Lainnya',
    };
    const labelCabang = { surabaya: 'Surabaya' };
    let dataForm = {};

    function formatRupiah(angka) {
      return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
    }

    document.getElementById('estimasiForm').addEventListener('submit', async function (e) {
      e.preventDefault();
      let valid = true;
      const fields = [
        { id: 'nama', msg: 'Nama harus diisi' },
        { id: 'email', msg: 'Email tidak valid' },
        { id: 'phone', msg: 'Nomor telepon harus diisi' },
        { id: 'device', msg: 'Pilih perangkat' },
        { id: 'issue', msg: 'Pilih jenis kerusakan' },
        { id: 'branch', msg: 'Pilih cabang' },
        { id: 'description', msg: 'Deskripsi harus diisi' },
      ];
      fields.forEach(f => {
        const el = document.getElementById(f.id);
        const err = el.closest('[id^="group"]')?.querySelector('.error-msg');
        if (!el.value.trim()) {
          if (err) { err.textContent = f.msg; err.classList.remove('hidden'); }
          valid = false;
        } else {
          if (err) err.classList.add('hidden');
        }
      });
      if (!valid) return;

      dataForm = {
        nama: document.getElementById('nama').value.trim(),
        email: document.getElementById('email').value.trim(),
        phone: document.getElementById('phone').value.trim(),
        device: document.getElementById('device').value,
        issue: document.getElementById('issue').value,
        branch: document.getElementById('branch').value,
        description: document.getElementById('description').value.trim(),
      };

      const btn = document.getElementById('submitBtn');
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghitung...';

      try {
        const res = await fetch('{{ route("estimasi.hitung") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          },
          body: JSON.stringify({ perangkat: dataForm.device, kerusakan: dataForm.issue }),
        });
        const data = await res.json();
        if (data.status === 'ok') {
          document.getElementById('popupPerangkat').textContent = labelPerangkat[dataForm.device] || dataForm.device;
          document.getElementById('popupKerusakan').textContent = labelKerusakan[dataForm.issue] || dataForm.issue;
          document.getElementById('popupCabang').textContent = labelCabang[dataForm.branch] || dataForm.branch;
          document.getElementById('popupKeterangan').textContent = data.keterangan || '-';
          document.getElementById('popupHarga').textContent = formatRupiah(data.harga_min) + ' – ' + formatRupiah(data.harga_max);
          document.getElementById('popupEstimasi').classList.remove('hidden');
        } else {
          alert('Gagal mengambil estimasi: ' + (data.message || 'Coba lagi.'));
        }
      } catch (err) {
        alert('Terjadi kesalahan. Pastikan koneksi internet Anda aktif.');
      }
      btn.disabled = false;
      btn.innerHTML = '<span>Hitung Estimasi</span><i class="fas fa-calculator"></i>';
    });

    function tutupPopup() {
      document.getElementById('popupEstimasi').classList.add('hidden');
    }

    function lanjutBooking() {
      sessionStorage.setItem('dataBooking', JSON.stringify(dataForm));
      @auth
        window.location.href = '{{ route("booking.create") }}';
      @else
        window.location.href = '{{ route("login") }}?redirect=booking';
      @endauth
    }

    document.getElementById('popupEstimasi').addEventListener('click', function (e) {
      if (e.target === this) tutupPopup();
    });
  </script>
</x-app-layout>
