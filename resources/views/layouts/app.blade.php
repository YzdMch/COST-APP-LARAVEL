<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Geeko Komputer — Servis komputer transparan dengan estimasi biaya instan, booking online, dan tracking real-time.">

        <title>{{ $title ?? 'Geeko Komputer' }} • Geeko Komputer</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }
            .animate-fade-up {
                animation: fadeUp 0.7s ease-out both;
            }
            .animate-fade-up-delay-1 { animation-delay: 0.15s; }
            .animate-fade-up-delay-2 { animation-delay: 0.3s; }
            .animate-fade-up-delay-3 { animation-delay: 0.45s; }
            .animate-fade-up-delay-4 { animation-delay: 0.6s; }
            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(24px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-12px); }
            }
            .gradient-text {
                background: linear-gradient(135deg, #f59e0b, #d97706);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            .glass-card {
                background: rgba(255,255,255,0.7);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255,255,255,0.3);
            }
        </style>
        <script>
        (function (m, a, z, e) {
          var s, t, u, v;
          try {
            t = m.sessionStorage.getItem('maze-us');
          } catch (err) {}

          if (!t) {
            t = new Date().getTime();
            try {
              m.sessionStorage.setItem('maze-us', t);
            } catch (err) {}
          }

          u = document.currentScript || (function () {
            var w = document.getElementsByTagName('script');
            return w[w.length - 1];
          })();
          v = u && u.nonce;

          s = a.createElement('script');
          s.src = z + '?apiKey=' + e;
          s.async = true;
          if (v) s.setAttribute('nonce', v);
          a.getElementsByTagName('head')[0].appendChild(s);
          m.mazeUniversalSnippetApiKey = e;
        })(window, document, 'https://snippet.maze.co/maze-universal-loader.js', 'e9f5d1e9-c9c1-4f88-b125-f4e527295f33');
        </script>
    </head>
    <body class="bg-gray-50 text-gray-800 font-sans antialiased">
        {{-- Navigation --}}
        @include('layouts.navigation')

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-400">
            <div class="max-w-7xl mx-auto px-5 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
                    <!-- Brand -->
                    <div class="md:col-span-2">
                        <img src="{{ asset('images/logo.png') }}" class="h-9 mb-4 brightness-0 invert opacity-80" alt="Geeko Komputer">
                        <p class="text-sm leading-relaxed max-w-sm">
                            Solusi perbaikan komputer modern dengan biaya transparan, pengerjaan cepat, dan bergaransi. Trusted since 2024.
                        </p>
                        <div class="flex gap-3 mt-5">
                            <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-green-600 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-200"><i class="fab fa-whatsapp"></i></a>
                            <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-pink-600 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-200"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-blue-600 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-200"><i class="fab fa-facebook-f"></i></a>
                        </div>
                    </div>
                    <!-- Quick Links -->
                    <div>
                        <h4 class="text-white font-semibold text-sm mb-4">Quick Links</h4>
                        <ul class="space-y-2.5 text-sm">
                            <li><a href="{{ route('home') }}#services" class="hover:text-yellow-400 transition">Layanan</a></li>
                            <li><a href="{{ route('estimasi') }}" class="hover:text-yellow-400 transition">Estimasi Harga</a></li>
                            <li><a href="{{ route('home') }}#why" class="hover:text-yellow-400 transition">Keunggulan</a></li>
                            <li><a href="{{ route('home') }}#contact" class="hover:text-yellow-400 transition">Kontak</a></li>
                        </ul>
                    </div>
                    <!-- Contact Info -->
                    <div>
                        <h4 class="text-white font-semibold text-sm mb-4">Kontak</h4>
                        <ul class="space-y-2.5 text-sm">
                            <li class="flex items-center gap-2"><i class="fas fa-map-pin text-yellow-500 w-4 text-center"></i>Surabaya, Jawa Timur</li>
                            <li class="flex items-center gap-2"><i class="fas fa-phone text-yellow-500 w-4 text-center"></i>+62 812-3456-7890</li>
                            <li class="flex items-center gap-2"><i class="fas fa-clock text-yellow-500 w-4 text-center"></i>Sen–Sab, 09:00–18:00</li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 pt-6 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-gray-500">
                    <p>&copy; {{ date('Y') }} Geeko Komputer. All rights reserved.</p>
                    <p>Built with <i class="fas fa-heart text-red-500 mx-0.5"></i> by YZ, KZ & FR</p>
                </div>
            </div>
        </footer>
    </body>
</html>
