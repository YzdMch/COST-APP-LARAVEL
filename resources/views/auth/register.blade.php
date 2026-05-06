<x-guest-layout>
  <x-slot:title>Daftar Akun</x-slot:title>

  <div class="max-w-md w-full">
    {{-- Mobile logo --}}
    <div class="lg:hidden text-center mb-8">
      <a href="{{ route('home') }}"><img src="{{ asset('images/logo.png') }}" class="h-9 mx-auto mb-3" alt="Geeko Komputer"></a>
    </div>

    {{-- Header --}}
    <div class="mb-8">
      <h1 class="text-2xl font-extrabold text-gray-800 mb-1">Buat Akun Baru</h1>
      <p class="text-gray-400 text-sm">Daftar gratis untuk mulai booking servis</p>
    </div>

    <form method="POST" action="{{ route('register') }}" x-data="{ show: false }">
      @csrf

      {{-- Nama --}}
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2 text-sm">Nama Lengkap</label>
        <div class="relative">
          <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
          <input type="text" name="name" value="{{ old('name') }}" required minlength="3"
            placeholder="Contoh: John Doe"
            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent focus:bg-white transition">
        </div>
        <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
      </div>

      {{-- Email --}}
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2 text-sm">Email</label>
        <div class="relative">
          <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
          <input type="email" name="email" value="{{ old('email') }}" required
            placeholder="john@example.com"
            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent focus:bg-white transition">
        </div>
        <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
      </div>

      {{-- No Telepon --}}
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2 text-sm">No. Telepon</label>
        <div class="relative">
          <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
          <input type="tel" name="no_telepon" value="{{ old('no_telepon') }}" required minlength="9" maxlength="20"
            placeholder="08123456789"
            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent focus:bg-white transition">
        </div>
        <x-input-error :messages="$errors->get('no_telepon')" class="mt-1.5" />
      </div>

      {{-- Password --}}
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2 text-sm">Password</label>
        <div class="relative">
          <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
          <input :type="show ? 'text' : 'password'" name="password" required
            placeholder="Minimal 8 karakter"
            class="w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent focus:bg-white transition">
          <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
          </button>
        </div>
        <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
      </div>

      {{-- Konfirmasi Password --}}
      <div class="mb-6">
        <label class="block text-gray-700 font-semibold mb-2 text-sm">Konfirmasi Password</label>
        <div class="relative">
          <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
          <input :type="show ? 'text' : 'password'" name="password_confirmation" required
            placeholder="Ulangi password"
            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent focus:bg-white transition">
        </div>
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
      </div>

      {{-- Submit --}}
      <button type="submit"
        class="w-full bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-500 hover:to-amber-600 text-gray-900 font-bold py-3.5 rounded-xl shadow-lg shadow-yellow-200 hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2">
        <span>Daftar Sekarang</span>
        <i class="fas fa-arrow-right text-sm"></i>
      </button>
    </form>

    {{-- Login --}}
    <p class="mt-6 text-center text-gray-500 text-sm">
      Sudah punya akun?
      <a href="{{ route('login') }}" class="text-yellow-600 font-semibold hover:text-yellow-700">Login di sini</a>
    </p>
  </div>
</x-guest-layout>
