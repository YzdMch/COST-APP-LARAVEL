@php
  $labelPerangkat = \App\Models\Servis::labelPerangkat();
  $labelKerusakan = \App\Models\Servis::labelKerusakan();
@endphp

<x-admin-layout>
  <x-slot:title>Dashboard</x-slot:title>

  {{-- Key Stats --}}
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Total Servis</p>
          <p class="text-3xl font-extrabold text-gray-800 mt-1">{{ $totalServis }}</p>
        </div>
        <div class="w-11 h-11 rounded-xl bg-yellow-50 flex items-center justify-center"><i class="fas fa-tools text-yellow-500"></i></div>
      </div>
    </div>
    <div class="stat-card">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Revenue</p>
          <p class="text-xl font-extrabold text-green-600 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center"><i class="fas fa-coins text-green-500"></i></div>
      </div>
    </div>
    <div class="stat-card">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">SLA Compliance</p>
          <p class="text-3xl font-extrabold {{ $slaCompliance >= 80 ? 'text-green-600' : ($slaCompliance >= 60 ? 'text-yellow-600' : 'text-red-600') }} mt-1">{{ $slaCompliance }}%</p>
        </div>
        <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center"><i class="fas fa-clock text-blue-500"></i></div>
      </div>
    </div>
    <div class="stat-card">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Overdue</p>
          <p class="text-3xl font-extrabold {{ $overdueCount > 0 ? 'text-red-600' : 'text-green-600' }} mt-1">{{ $overdueCount }}</p>
        </div>
        <div class="w-11 h-11 rounded-xl {{ $overdueCount > 0 ? 'bg-red-50' : 'bg-green-50' }} flex items-center justify-center">
          <i class="fas fa-exclamation-triangle {{ $overdueCount > 0 ? 'text-red-500' : 'text-green-500' }}"></i>
        </div>
      </div>
    </div>
  </div>

  {{-- Second row stats --}}
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Selesai</p>
          <p class="text-2xl font-extrabold text-green-600 mt-1">{{ $totalSelesai }}</p>
        </div>
        <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center"><i class="fas fa-check-circle text-green-500"></i></div>
      </div>
    </div>
    <div class="stat-card">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Proses</p>
          <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $totalProses }}</p>
        </div>
        <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center"><i class="fas fa-spinner text-amber-500"></i></div>
      </div>
    </div>
    <div class="stat-card">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Pelanggan</p>
          <p class="text-2xl font-extrabold text-blue-600 mt-1">{{ $totalUsers }}</p>
        </div>
        <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center"><i class="fas fa-users text-blue-500"></i></div>
      </div>
    </div>
    <div class="stat-card">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Cabang Aktif</p>
          <p class="text-2xl font-extrabold text-violet-600 mt-1">{{ $totalCabang }}</p>
        </div>
        <div class="w-11 h-11 rounded-xl bg-violet-50 flex items-center justify-center"><i class="fas fa-store text-violet-500"></i></div>
      </div>
    </div>
  </div>

  {{-- Charts Row --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    {{-- Monthly Trend --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
      <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-chart-line text-yellow-500"></i> Tren Servis per Bulan</h3>
      <canvas id="chartTrend" height="200"></canvas>
    </div>

    {{-- Status Breakdown --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
      <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-chart-doughnut text-violet-500"></i> Distribusi Status</h3>
      <canvas id="chartStatus" height="200"></canvas>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    {{-- Top Kerusakan --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
      <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-bug text-red-500"></i> Kerusakan Terpopuler</h3>
      <canvas id="chartKerusakan" height="200"></canvas>
    </div>

    {{-- Revenue Trend --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
      <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-chart-bar text-green-500"></i> Revenue per Bulan</h3>
      <canvas id="chartRevenue" height="200"></canvas>
    </div>
  </div>

  {{-- Teknisi Performance & Cabang Stats --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    {{-- Teknisi Performance --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
      <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-user-cog text-indigo-500"></i> Performa Teknisi</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead>
            <tr class="bg-gray-50/50">
              <th class="px-4 py-2 text-left text-xs font-semibold text-gray-400 uppercase">Teknisi</th>
              <th class="px-4 py-2 text-center text-xs font-semibold text-gray-400 uppercase">Aktif</th>
              <th class="px-4 py-2 text-center text-xs font-semibold text-gray-400 uppercase">Selesai</th>
              <th class="px-4 py-2 text-center text-xs font-semibold text-gray-400 uppercase">Avg Jam</th>
              <th class="px-4 py-2 text-right text-xs font-semibold text-gray-400 uppercase">Revenue</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            @foreach($teknisiPerformance as $t)
            <tr class="hover:bg-gray-50/50">
              <td class="px-4 py-3">
                <p class="font-medium text-gray-700">{{ $t->name }}</p>
                <p class="text-xs text-gray-400">{{ $t->cabang?->nama ?? 'Tanpa cabang' }}</p>
              </td>
              <td class="px-4 py-3 text-center"><span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">{{ $t->servis_aktif }}</span></td>
              <td class="px-4 py-3 text-center"><span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">{{ $t->servis_selesai }}</span></td>
              <td class="px-4 py-3 text-center text-gray-600">{{ $t->avg_completion_hours ? $t->avg_completion_hours . ' jam' : '-' }}</td>
              <td class="px-4 py-3 text-right font-bold text-yellow-600">Rp {{ number_format($t->revenue, 0, ',', '.') }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    {{-- Cabang Stats --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
      <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-store text-violet-500"></i> Statistik per Cabang</h3>
      <div class="space-y-4">
        @foreach($cabangStats as $c)
        <div class="bg-gray-50 rounded-xl p-4">
          <div class="flex items-center justify-between mb-2">
            <h4 class="font-semibold text-gray-700">{{ $c->nama }}</h4>
            <span class="text-xs text-gray-400">{{ $c->teknisi_count }} teknisi</span>
          </div>
          <div class="grid grid-cols-3 gap-3 text-center">
            <div>
              <p class="text-lg font-bold text-gray-800">{{ $c->servis_count }}</p>
              <p class="text-xs text-gray-400">Total</p>
            </div>
            <div>
              <p class="text-lg font-bold text-green-600">{{ $c->servis_selesai }}</p>
              <p class="text-xs text-gray-400">Selesai</p>
            </div>
            <div>
              <p class="text-lg font-bold text-amber-600">{{ $c->servis_aktif }}</p>
              <p class="text-xs text-gray-400">Aktif</p>
            </div>
          </div>
        </div>
        @endforeach
        @if($cabangStats->isEmpty())
        <p class="text-center text-gray-400 py-8"><i class="fas fa-store text-3xl mb-2 block"></i>Belum ada cabang.</p>
        @endif
      </div>
    </div>
  </div>

  {{-- Recent Activity --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-history text-amber-500"></i> Aktivitas Terbaru</h3>
    <div class="space-y-3">
      @forelse($recentLogs as $log)
      <div class="flex items-start gap-3 text-sm">
        <div class="w-2 h-2 rounded-full mt-2 flex-shrink-0 {{ match($log->action) { 'login' => 'bg-blue-400', 'create' => 'bg-green-400', 'delete' => 'bg-red-400', 'update' => 'bg-yellow-400', default => 'bg-gray-400' } }}"></div>
        <div class="flex-1">
          <p class="text-gray-700">{{ $log->description }}</p>
          <p class="text-xs text-gray-400 mt-0.5">{{ $log->user?->name ?? 'System' }} · {{ $log->created_at->diffForHumans() }}</p>
        </div>
      </div>
      @empty
      <p class="text-center text-gray-400 py-4">Belum ada aktivitas tercatat.</p>
      @endforelse
    </div>
    <a href="{{ route('admin.audit.index') }}" class="block mt-4 text-center text-sm text-yellow-600 hover:text-yellow-700 font-medium">Lihat Semua Log →</a>
  </div>

  {{-- Charts Script --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const chartColors = {
        yellow: 'rgba(245, 158, 11, 0.8)',
        green: 'rgba(34, 197, 94, 0.8)',
        blue: 'rgba(59, 130, 246, 0.8)',
        red: 'rgba(239, 68, 68, 0.8)',
        violet: 'rgba(139, 92, 246, 0.8)',
        amber: 'rgba(217, 119, 6, 0.8)',
        orange: 'rgba(249, 115, 22, 0.8)',
        lime: 'rgba(132, 204, 22, 0.8)',
      };

      // Monthly Trend
      new Chart(document.getElementById('chartTrend'), {
        type: 'line',
        data: {
          labels: {!! json_encode($monthlyTrend->pluck('bulan')) !!},
          datasets: [{
            label: 'Total Servis',
            data: {!! json_encode($monthlyTrend->pluck('total')) !!},
            borderColor: chartColors.yellow,
            backgroundColor: 'rgba(245, 158, 11, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: chartColors.yellow,
          }, {
            label: 'Selesai',
            data: {!! json_encode($monthlyTrend->pluck('selesai')) !!},
            borderColor: chartColors.green,
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: chartColors.green,
          }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
      });

      // Status Breakdown
      new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
          labels: {!! json_encode($statusBreakdown->keys()) !!},
          datasets: [{
            data: {!! json_encode($statusBreakdown->values()) !!},
            backgroundColor: [chartColors.blue, chartColors.amber, chartColors.orange, chartColors.lime, chartColors.green],
            borderWidth: 0,
          }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
      });

      // Top Kerusakan
      new Chart(document.getElementById('chartKerusakan'), {
        type: 'bar',
        data: {
          labels: {!! json_encode($topKerusakan->pluck('jenis_kerusakan')->map(fn($k) => $labelKerusakan[$k] ?? $k)) !!},
          datasets: [{
            label: 'Jumlah',
            data: {!! json_encode($topKerusakan->pluck('total')) !!},
            backgroundColor: [chartColors.red, chartColors.yellow, chartColors.blue, chartColors.green, chartColors.violet],
            borderRadius: 8,
          }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
      });

      // Revenue Trend
      new Chart(document.getElementById('chartRevenue'), {
        type: 'bar',
        data: {
          labels: {!! json_encode($monthlyTrend->pluck('bulan')) !!},
          datasets: [{
            label: 'Revenue (Rp)',
            data: {!! json_encode($monthlyTrend->pluck('revenue')) !!},
            backgroundColor: chartColors.green,
            borderRadius: 8,
          }]
        },
        options: {
          responsive: true,
          plugins: { legend: { display: false } },
          scales: {
            y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + (v / 1000000).toFixed(1) + 'jt' } }
          }
        }
      });
    });
  </script>
</x-admin-layout>
