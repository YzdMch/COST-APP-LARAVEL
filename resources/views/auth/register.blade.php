<x-guest-layout>
  <x-slot:title>Daftar Akun</x-slot:title>

  <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
    <!-- Header -->
    <div class="bg-yellow-400 py-5 text-center">
      <img src="{{ asset('images/logo.png') }}" alt="Geeko Komputer" class="h-10 mx-auto mb-2">
      <h1 class="text-xl font-bold text-gray-800">Buat Akun Baru</h1>
      <p class="text-gray-700 text-xs">Daftar untuk mulai booking servis</p>
    </div>

    <div class="p-6 md:p-8">
      <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <!-- Nama -->
        <div class="mb-4">
          <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap</label>
          <div class="relative">
            <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" name="name" id="name" placeholder="Contoh: John Doe"
              value="{{ old('name') }}"
              class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">
          </div>
          <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email -->
        <div class="mb-4">
          <label class="block text-gray-700 font-semibold mb-2">Email</label>
          <div class="relative">
            <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="email" name="email" id="email" placeholder="john@example.com"
              value="{{ old('email') }}"
              class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">
          </div>
          <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- No Telepon -->
        <div class="mb-4">
          <label class="block text-gray-700 font-semibold mb-2">No. Telepon</label>
          <div class="relative">
            <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="tel" name="no_telepon" id="no_telepon" placeholder="08123456789"
              value="{{ old('no_telepon') }}"
              class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">
          </div>
          <x-input-error :messages="$errors->get('no_telepon')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="mb-4">
          <label class="block text-gray-700 font-semibold mb-2">Password</label>
          <div class="relative">
            <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="password" name="password" id="password" placeholder="Minimal 8 karakter"
              class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">
          </div>
          <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Konfirmasi Password -->
        <div class="mb-6">
          <label class="block text-gray-700 font-semibold mb-2">Konfirmasi Password</label>
          <div class="relative">
            <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="password" name="password_confirmation" id="password_confirmation"
              placeholder="Ulangi password"
              class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">
          </div>
          <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Submit -->
        <button type="submit"
          class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 rounded-xl transition flex items-center justify-center gap-2">
          <span>Daftar Sekarang</span>
          <i class="fas fa-arrow-right"></i>
        </button>
      </form>

      <!-- Login -->
      <div class="mt-6 text-center">
        <p class="text-gray-600">
          Sudah punya akun?
          <a href="{{ route('login') }}" class="text-yellow-600 font-semibold hover:underline">Login di sini</a>
        </p>
      </div>
    </div>
  </div>
</x-guest-layout>
