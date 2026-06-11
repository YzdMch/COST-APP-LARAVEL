<x-guest-layout>
  <x-slot:title>Reset Password</x-slot:title>

  <div class="max-w-md w-full">
    {{-- Mobile logo --}}
    <div class="lg:hidden text-center mb-8">
      <a href="{{ route('home') }}"><img src="{{ asset('images/logo.png') }}" class="h-9 mx-auto mb-3" alt="Geeko Komputer"></a>
    </div>

    {{-- Header --}}
    <div class="mb-8">
      <h1 class="text-2xl font-extrabold text-gray-800 mb-2">Buat Password Baru</h1>
      <p class="text-gray-500 text-sm leading-relaxed">
        Silakan masukkan password baru untuk akun Anda.
      </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-5">
            <label class="block text-gray-700 font-semibold mb-2 text-sm">Email</label>
            <div class="relative">
                <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" readonly
                  class="w-full pl-11 pr-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition text-sm">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div class="mb-5" x-data="{ show: false }">
            <label class="block text-gray-700 font-semibold mb-2 text-sm">Password Baru</label>
            <div class="relative">
                <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password"
                  placeholder="••••••••"
                  class="w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent focus:bg-white transition text-sm">
                <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                  <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-8" x-data="{ show: false }">
            <label class="block text-gray-700 font-semibold mb-2 text-sm">Konfirmasi Password Baru</label>
            <div class="relative">
                <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input :type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                  placeholder="••••••••"
                  class="w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent focus:bg-white transition text-sm">
                <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                  <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-500 hover:to-amber-600 text-gray-900 font-bold py-3.5 rounded-xl shadow-lg shadow-yellow-200 hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-2">
            <span>Simpan Password Baru</span>
            <i class="fas fa-check text-sm"></i>
        </button>
    </form>
  </div>
</x-guest-layout>
