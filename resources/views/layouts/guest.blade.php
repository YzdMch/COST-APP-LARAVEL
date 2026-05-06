<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Login' }} • Geeko Komputer</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="font-sans antialiased min-h-screen flex">
        {{-- Left: Branding Panel --}}
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 relative overflow-hidden items-center justify-center p-12">
            <div class="absolute inset-0 opacity-15">
                <div class="absolute top-20 left-10 w-72 h-72 bg-yellow-400 rounded-full blur-[120px]"></div>
                <div class="absolute bottom-20 right-10 w-80 h-80 bg-amber-500 rounded-full blur-[140px]"></div>
            </div>
            <div class="relative z-10 max-w-md">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" class="h-10 mb-8 brightness-0 invert opacity-80" alt="Geeko Komputer">
                </a>
                <h2 class="text-3xl font-extrabold text-white mb-4">Servis Komputer <span class="text-yellow-400">Transparan</span></h2>
                <p class="text-gray-400 leading-relaxed mb-8">
                    Estimasi biaya instan, booking online, dan tracking real-time. Semua di satu tempat.
                </p>
                <div class="space-y-4">
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-yellow-400/20 flex items-center justify-center"><i class="fas fa-check text-yellow-400 text-xs"></i></div>
                        <span class="text-gray-300">No hidden fees — harga transparan</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-yellow-400/20 flex items-center justify-center"><i class="fas fa-check text-yellow-400 text-xs"></i></div>
                        <span class="text-gray-300">Real-time tracking status perbaikan</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-yellow-400/20 flex items-center justify-center"><i class="fas fa-check text-yellow-400 text-xs"></i></div>
                        <span class="text-gray-300">Garansi 90 hari untuk setiap servis</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Form --}}
        <div class="w-full lg:w-1/2 bg-white flex items-center justify-center px-5 py-10">
            {{ $slot }}
        </div>
    </body>
</html>
