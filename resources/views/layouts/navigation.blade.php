<header class="bg-white shadow sticky top-0 z-30" x-data="{ open: false }">
  <div class="max-w-7xl mx-auto flex items-center justify-between px-4 py-4">

    <!-- Logo -->
    <a href="{{ route('home') }}">
      <img src="{{ asset('images/logo.png') }}" class="h-10" alt="Geeko Komputer">
    </a>

    <!-- Nav desktop -->
    <nav class="hidden md:block">
      <ul class="flex gap-6 font-medium text-gray-700">
        <li><a href="{{ route('home') }}" class="hover:text-yellow-500 transition">Home</a></li>
        <li><a href="{{ route('home') }}#services" class="hover:text-yellow-500 transition">Layanan</a></li>
        <li><a href="{{ route('home') }}#why" class="hover:text-yellow-500 transition">Mengapa Kami</a></li>
        <li><a href="{{ route('home') }}#developer" class="hover:text-yellow-500 transition">Developer</a></li>
        <li><a href="{{ route('home') }}#contact" class="hover:text-yellow-500 transition">Kontak</a></li>
      </ul>
    </nav>

    <!-- Auth buttons -->
    <div class="flex items-center gap-3 flex-wrap">
      @auth
        <span class="text-sm text-gray-600">
          <i class="fas fa-user text-yellow-500 mr-1"></i>
          {{ Auth::user()->name }}
        </span>
        <a href="{{ route('dashboard') }}"
          class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
          <i class="fas fa-th-large mr-1"></i> Dashboard
        </a>
        <form method="POST" action="{{ route('logout') }}" class="inline">
          @csrf
          <button type="submit"
            class="border border-gray-300 text-gray-600 hover:bg-gray-100 text-sm font-semibold px-4 py-2 rounded-lg transition">
            Logout
          </button>
        </form>
      @else
        <a href="{{ route('login') }}"
          class="border border-gray-300 text-gray-700 hover:bg-gray-100 text-sm font-semibold px-4 py-2 rounded-lg transition">
          Login
        </a>
        <a href="{{ route('register') }}"
          class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
          Daftar
        </a>
      @endauth
    </div>

    <!-- Burger -->
    <button @click="open = !open" class="md:hidden text-2xl text-gray-700">
      <i class="fas fa-bars"></i>
    </button>

  </div>

  <!-- Mobile menu -->
  <div x-show="open" x-cloak class="md:hidden border-t border-gray-100 px-4 py-4">
    <ul class="flex flex-col gap-3 text-gray-700 font-medium mb-4">
      <li><a href="{{ route('home') }}" class="block hover:text-yellow-500 transition">Home</a></li>
      <li><a href="{{ route('home') }}#services" class="block hover:text-yellow-500 transition">Layanan</a></li>
      <li><a href="{{ route('home') }}#why" class="block hover:text-yellow-500 transition">Mengapa Kami</a></li>
      <li><a href="{{ route('home') }}#developer" class="block hover:text-yellow-500 transition">Developer</a></li>
      <li><a href="{{ route('home') }}#contact" class="block hover:text-yellow-500 transition">Kontak</a></li>
    </ul>
    <div class="flex flex-col gap-2 border-t border-gray-100 pt-3">
      @auth
        <a href="{{ route('dashboard') }}"
          class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold px-4 py-2 rounded-lg text-center transition">
          <i class="fas fa-th-large mr-1"></i> Dashboard
        </a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit"
            class="w-full border border-gray-300 text-gray-600 text-sm font-semibold px-4 py-2 rounded-lg text-center transition">
            Logout
          </button>
        </form>
      @else
        <a href="{{ route('login') }}"
          class="border border-gray-300 text-gray-700 text-sm font-semibold px-4 py-2 rounded-lg text-center transition">
          Login
        </a>
        <a href="{{ route('register') }}"
          class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold px-4 py-2 rounded-lg text-center transition">
          Daftar
        </a>
      @endauth
    </div>
  </div>
</header>
