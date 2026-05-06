<x-guest-layout>
  <x-slot:title>Login</x-slot:title>

  <div class="max-w-md w-full">
    {{-- Mobile logo --}}
    <div class="lg:hidden text-center mb-8">
      <a href="{{ route('home') }}"><img src="{{ asset('images/logo.png') }}" class="h-9 mx-auto mb-3" alt="Geeko Komputer"></a>
    </div>

    {{-- Header --}}
    <div class="mb-8">
      <h1 class="text-2xl font-extrabold text-gray-800 mb-1">Selamat Datang Kembali</h1>
      <p class="text-gray-400 text-sm">Login ke akun Geeko Anda untuk melanjutkan</p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
      @csrf

      {{-- Email --}}
      <div class="mb-5">
        <label class="block text-gray-700 font-semibold mb-2 text-sm">Email</label>
        <div class="relative">
          <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
          <input type="email" name="email" autocomplete="email" value="{{ old('email') }}" required
            placeholder="nama@example.com"
            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent focus:bg-white transition">
        </div>
        <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
      </div>

      {{-- Password --}}
      <div class="mb-5" x-data="{ show: false }">
        <label class="block text-gray-700 font-semibold mb-2 text-sm">Password</label>
        <div class="relative">
          <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
          <input :type="show ? 'text' : 'password'" name="password" autocomplete="current-password" required
            placeholder="••••••••"
            class="w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent focus:bg-white transition">
          <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
          </button>
        </div>
        <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
      </div>

      {{-- Remember --}}
      <div class="flex items-center justify-between mb-6">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" name="remember" class="w-4 h-4 accent-yellow-500 border-gray-300 rounded">
          <span class="text-sm text-gray-500">Ingat saya</span>
        </label>
        @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="text-sm text-yellow-600 hover:text-yellow-700 font-medium">Lupa password?</a>
        @endif
      </div>

      {{-- Submit --}}
      <button type="submit"
        class="w-full bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-500 hover:to-amber-600 text-gray-900 font-bold py-3.5 rounded-xl shadow-lg shadow-yellow-200 hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2">
        <span>Login</span>
        <i class="fas fa-arrow-right text-sm"></i>
      </button>
    </form>

    {{-- Register --}}
    <p class="mt-6 text-center text-gray-500 text-sm">
      Belum punya akun?
      <a href="{{ route('register') }}" class="text-yellow-600 font-semibold hover:text-yellow-700">Daftar sekarang</a>
    </p>

    {{-- Demo --}}
    <div class="mt-8 pt-6 border-t border-gray-100">
      <p class="text-xs text-gray-400 text-center mb-3">Quick login demo:</p>
      <div class="grid grid-cols-2 gap-2">
        <button type="button" onclick="fillDemo('pelanggan@geeko.com','123456')"
          class="border border-gray-200 rounded-xl px-3 py-2.5 text-xs text-gray-500 hover:bg-gray-50 hover:border-gray-300 transition text-center">
          <i class="fas fa-user text-yellow-500 mr-1"></i> Pelanggan
        </button>
        <button type="button" onclick="fillDemo('teknisi@geeko.com','123456')"
          class="border border-gray-200 rounded-xl px-3 py-2.5 text-xs text-gray-500 hover:bg-gray-50 hover:border-gray-300 transition text-center">
          <i class="fas fa-wrench text-yellow-500 mr-1"></i> Teknisi
        </button>
      </div>
    </div>
  </div>

  <script>
    function fillDemo(email, pass) {
      document.querySelector('input[name="email"]').value = email;
      document.querySelector('input[name="password"]').value = pass;
    }
  </script>
</x-guest-layout>
