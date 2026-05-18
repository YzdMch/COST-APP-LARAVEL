<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Panel' }} • Geeko Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.625rem 1rem; border-radius: 0.75rem;
            font-size: 0.875rem; font-weight: 500;
            color: #94a3b8; transition: all 0.2s;
        }
        .sidebar-link:hover { background: rgba(255,255,255,0.05); color: #e2e8f0; }
        .sidebar-link.active {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #1e293b; font-weight: 700;
        }
        .sidebar-link.active i { color: #1e293b; }
        .sidebar-link i { width: 1.25rem; text-align: center; font-size: 0.875rem; }
        .stat-card {
            background: white; border-radius: 1rem; padding: 1.25rem;
            border: 1px solid #f1f5f9; box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: all 0.2s;
        }
        .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); transform: translateY(-2px); }
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
<body class="bg-gray-50 text-gray-800 antialiased">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">

        {{-- Sidebar Overlay (mobile) --}}
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-transition:enter="transition-opacity ease-out duration-300" x-transition:leave="transition-opacity ease-in duration-200"></div>

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed lg:sticky lg:translate-x-0 top-0 left-0 z-50 w-64 h-screen bg-slate-900 flex flex-col transition-transform duration-300 ease-in-out">

            {{-- Logo --}}
            <div class="px-5 py-5 border-b border-slate-800">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center">
                        <i class="fas fa-shield-alt text-slate-900 text-sm"></i>
                    </div>
                    <div>
                        <span class="text-white font-bold text-sm">Geeko Admin</span>
                        <span class="block text-slate-500 text-xs">Control Panel</span>
                    </div>
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <p class="px-3 text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Overview</p>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i> Dashboard
                </a>

                <p class="px-3 text-xs font-semibold text-slate-600 uppercase tracking-wider mt-5 mb-2">Manajemen</p>
                <a href="{{ route('admin.estimasi.index') }}" class="sidebar-link {{ request()->routeIs('admin.estimasi.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i> Estimasi Harga
                </a>
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Manajemen User
                </a>
                <a href="{{ route('admin.cabang.index') }}" class="sidebar-link {{ request()->routeIs('admin.cabang.*') ? 'active' : '' }}">
                    <i class="fas fa-store"></i> Cabang
                </a>

                <p class="px-3 text-xs font-semibold text-slate-600 uppercase tracking-wider mt-5 mb-2">Operasional</p>
                <a href="{{ route('admin.penugasan.index') }}" class="sidebar-link {{ request()->routeIs('admin.penugasan.*') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i> Monitoring Servis
                </a>
                <a href="{{ route('admin.sla.index') }}" class="sidebar-link {{ request()->routeIs('admin.sla.*') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i> SLA Tracking
                </a>

                <p class="px-3 text-xs font-semibold text-slate-600 uppercase tracking-wider mt-5 mb-2">Monitoring</p>
                <a href="{{ route('admin.audit.index') }}" class="sidebar-link {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Audit Log
                </a>
            </nav>

            {{-- User info --}}
            <div class="px-4 py-4 border-t border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center text-xs font-bold text-slate-900">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500">Admin</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-500 hover:text-red-400 transition" title="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
                <a href="{{ route('home') }}" class="mt-3 flex items-center gap-2 text-xs text-slate-500 hover:text-yellow-400 transition">
                    <i class="fas fa-external-link-alt"></i> Lihat Website
                </a>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Top Bar --}}
            <header class="sticky top-0 z-30 bg-white/90 backdrop-blur-xl border-b border-gray-100 px-5 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <h1 class="text-lg font-bold text-gray-800">{{ $title ?? 'Admin Panel' }}</h1>
                </div>
                <div class="flex items-center gap-3 text-sm text-gray-500">
                    <span><i class="fas fa-calendar-alt mr-1"></i>{{ now()->translatedFormat('l, d M Y') }}</span>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-5 lg:p-8">
                {{-- Flash messages --}}
                @if(session('pesan'))
                <div id="flashMsg" class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-4 mb-6 flex items-center justify-between">
                    <span class="text-green-700 text-sm flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> {{ session('pesan') }}
                    </span>
                    <button onclick="document.getElementById('flashMsg').remove()" class="text-green-500 hover:text-green-700"><i class="fas fa-times"></i></button>
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6">
                    <ul class="text-red-700 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle mr-1"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
