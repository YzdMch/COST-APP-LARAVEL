<header
  x-data="{ open: false, scrolled: false }"
  x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 30)"
  :class="scrolled ? 'bg-white/90 backdrop-blur-xl shadow-lg' : 'bg-white/70 backdrop-blur-md'"
  class="sticky top-0 z-50 transition-all duration-300">
  <div class="max-w-7xl mx-auto flex items-center justify-between px-5 py-3.5">

    <!-- Logo -->
    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
      <img src="{{ asset('images/logo.png') }}" class="h-9 transition-transform duration-300 group-hover:scale-105" alt="Geeko Komputer">
    </a>

    <!-- Nav desktop -->
    <nav class="hidden lg:block">
      <ul class="flex gap-1 font-medium text-sm text-gray-600">
        <li><a href="{{ route('home') }}" class="px-4 py-2 rounded-lg hover:bg-yellow-50 hover:text-yellow-600 transition-all duration-200">Home</a></li>
        <li><a href="{{ route('home') }}#services" class="px-4 py-2 rounded-lg hover:bg-yellow-50 hover:text-yellow-600 transition-all duration-200">Layanan</a></li>
        <li><a href="{{ route('estimasi') }}" class="px-4 py-2 rounded-lg hover:bg-yellow-50 hover:text-yellow-600 transition-all duration-200">Estimasi</a></li>
        <li><a href="{{ route('home') }}#why" class="px-4 py-2 rounded-lg hover:bg-yellow-50 hover:text-yellow-600 transition-all duration-200">Keunggulan</a></li>
        <li><a href="{{ route('home') }}#contact" class="px-4 py-2 rounded-lg hover:bg-yellow-50 hover:text-yellow-600 transition-all duration-200">Kontak</a></li>
      </ul>
    </nav>

    <!-- Auth buttons -->
    <div class="hidden lg:flex items-center gap-2.5">
      @auth
        <span class="text-sm text-gray-500 mr-1">
          <i class="fas fa-circle text-green-400 text-[8px] mr-1.5"></i>{{ Auth::user()->name }}
        </span>
        <a href="{{ route('dashboard') }}"
          class="bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-500 hover:to-amber-600 text-white text-sm font-semibold px-5 py-2 rounded-xl shadow-sm shadow-yellow-200 hover:shadow-md hover:shadow-yellow-200 transition-all duration-200">
          <i class="fas fa-th-large mr-1.5"></i>Dashboard
        </a>
        <form method="POST" action="{{ route('logout') }}" class="inline">
          @csrf
          <button type="submit"
            class="border border-gray-200 text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50 text-sm font-medium px-4 py-2 rounded-xl transition-all duration-200">
            Logout
          </button>
        </form>
      @else
        <a href="{{ route('login') }}"
          class="text-gray-600 hover:text-gray-800 text-sm font-medium px-4 py-2 rounded-xl hover:bg-gray-50 transition-all duration-200">
          Login
        </a>
        <a href="{{ route('register') }}"
          class="bg-gradient-to-r from-yellow-400 to-amber-500 hover:from-yellow-500 hover:to-amber-600 text-white text-sm font-semibold px-5 py-2 rounded-xl shadow-sm shadow-yellow-200 hover:shadow-md hover:shadow-yellow-200 transition-all duration-200">
          Daftar Gratis
        </a>
      @endauth
    </div>

    <!-- Burger -->
    <button @click="open = !open" class="lg:hidden text-xl text-gray-600 hover:text-gray-800 p-2 rounded-lg hover:bg-gray-100 transition">
      <i :class="open ? 'fas fa-times' : 'fas fa-bars'"></i>
    </button>

  </div>

  <!-- Mobile menu -->
  <div x-show="open" x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="lg:hidden border-t border-gray-100 px-5 py-4 bg-white/95 backdrop-blur-xl">
    <ul class="flex flex-col gap-1 text-gray-600 font-medium mb-4">
      <li><a href="{{ route('home') }}" class="block px-4 py-2.5 rounded-lg hover:bg-yellow-50 hover:text-yellow-600 transition">Home</a></li>
      <li><a href="{{ route('home') }}#services" class="block px-4 py-2.5 rounded-lg hover:bg-yellow-50 hover:text-yellow-600 transition">Layanan</a></li>
      <li><a href="{{ route('estimasi') }}" class="block px-4 py-2.5 rounded-lg hover:bg-yellow-50 hover:text-yellow-600 transition">Estimasi</a></li>
      <li><a href="{{ route('home') }}#why" class="block px-4 py-2.5 rounded-lg hover:bg-yellow-50 hover:text-yellow-600 transition">Keunggulan</a></li>
      <li><a href="{{ route('home') }}#contact" class="block px-4 py-2.5 rounded-lg hover:bg-yellow-50 hover:text-yellow-600 transition">Kontak</a></li>
    </ul>
    <div class="flex flex-col gap-2 border-t border-gray-100 pt-3">
      @auth
        <a href="{{ route('dashboard') }}"
          class="bg-gradient-to-r from-yellow-400 to-amber-500 text-white text-sm font-semibold px-4 py-2.5 rounded-xl text-center transition">
          <i class="fas fa-th-large mr-1.5"></i>Dashboard
        </a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit"
            class="w-full border border-gray-200 text-gray-600 text-sm font-medium px-4 py-2.5 rounded-xl text-center transition hover:bg-gray-50">
            Logout
          </button>
        </form>
      @else
        <a href="{{ route('login') }}"
          class="border border-gray-200 text-gray-600 text-sm font-medium px-4 py-2.5 rounded-xl text-center hover:bg-gray-50 transition">
          Login
        </a>
        <a href="{{ route('register') }}"
          class="bg-gradient-to-r from-yellow-400 to-amber-500 text-white text-sm font-semibold px-4 py-2.5 rounded-xl text-center transition">
          Daftar Gratis
        </a>
      @endauth
    </div>
  </div>
</header>
