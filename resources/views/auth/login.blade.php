<x-guest-layout>
  <x-slot:title>Login</x-slot:title>

  <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
    <!-- Header -->
    <div class="bg-yellow-400 py-5 text-center">
      <img src="{{ asset('images/logo.png') }}" alt="Geeko Komputer" class="h-10 mx-auto mb-2">
      <h1 class="text-xl font-bold text-gray-800">Selamat Datang Kembali</h1>
      <p class="text-gray-700 text-xs">Login ke akun Geeko Anda</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mx-6 mt-4" :status="session('status')" />

    <div class="p-6 md:p-8">
      <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <!-- Email -->
        <div class="mb-4">
          <label class="block text-gray-700 font-semibold mb-2">Email</label>
          <div class="relative">
            <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="email" name="email" id="email" placeholder="nama@example.com"
              autocomplete="email" value="{{ old('email') }}"
              class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">
          </div>
          <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="mb-4">
          <label class="block text-gray-700 font-semibold mb-2">Password</label>
          <div class="relative">
            <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="password" name="password" id="password" placeholder="••••••••"
              autocomplete="current-password"
              class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">
          </div>
          <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember -->
        <div class="flex items-center justify-between mb-6">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="remember" class="w-4 h-4 accent-yellow-500 border-gray-300 rounded">
            <span class="text-sm text-gray-600">Ingat saya</span>
          </label>
        </div>

        <!-- Submit -->
        <button type="submit"
          class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 rounded-xl transition flex items-center justify-center gap-2">
          <span>Login</span>
          <i class="fas fa-arrow-right"></i>
        </button>
      </form>

      <!-- Register -->
      <div class="mt-6 text-center">
        <p class="text-gray-600">
          Belum punya akun?
          <a href="{{ route('register') }}" class="text-yellow-600 font-semibold hover:underline">Daftar sekarang</a>
        </p>
      </div>

      <!-- Demo akun -->
      <div class="mt-6 pt-4 border-t border-gray-200 text-center space-y-1">
        <p class="text-xs text-gray-400">Demo akun:</p>
        <p class="text-xs text-gray-400">Pelanggan: pelanggan@geeko.com / 123456</p>
        <p class="text-xs text-gray-400">Teknisi: teknisi@geeko.com / 123456</p>
      </div>
    </div>
  </div>
</x-guest-layout>
