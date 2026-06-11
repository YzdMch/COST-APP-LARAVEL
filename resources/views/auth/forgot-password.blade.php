<x-guest-layout>
  <x-slot:title>Lupa Password</x-slot:title>

  <div class="max-w-md w-full">
    {{-- Mobile logo --}}
    <div class="lg:hidden text-center mb-8">
      <a href="{{ route('home') }}"><img src="{{ asset('images/logo.png') }}" class="h-9 mx-auto mb-3" alt="Geeko Komputer"></a>
    </div>

    {{-- Header --}}
    <div class="mb-8">
      <h1 class="text-2xl font-extrabold text-gray-800 mb-2">Lupa Password?</h1>
      <p class="text-gray-500 text-sm leading-relaxed">
        Tidak masalah. Masukkan alamat email Anda di bawah ini dan kami akan mengirimkan tautan reset password agar Anda bisa memilih password baru.
      </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-8">
            <label class="block text-gray-700 font-semibold mb-2 text-sm">Email</label>
            <div class="relative">
                <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input type="email" name="email" autocomplete="email" value="{{ old('email') }}" required autofocus
                  placeholder="nama@example.com"
                  class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent focus:bg-white transition text-sm">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div class="flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700 transition font-medium text-center sm:text-left">
                <i class="fas fa-arrow-left mr-1.5 text-xs"></i> Kembali ke Login
            </a>
            
            <button type="submit" class="bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-500 hover:to-amber-600 text-gray-900 font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-yellow-200 hover:shadow-xl transition-all duration-200 flex items-center justify-center sm:justify-start gap-2 text-sm">
                <span>Kirim Link Reset</span>
                <i class="fas fa-paper-plane text-xs"></i>
            </button>
        </div>
    </form>
  </div>
</x-guest-layout>
