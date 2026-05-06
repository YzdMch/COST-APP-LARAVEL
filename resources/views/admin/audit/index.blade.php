<x-admin-layout>
  <x-slot:title>Audit Log</x-slot:title>

  <div class="mb-6">
    <h2 class="text-xl font-bold text-gray-800">Log Aktivitas</h2>
    <p class="text-sm text-gray-400">Semua aksi penting tercatat untuk keamanan dan akuntabilitas</p>
  </div>

  {{-- Filters --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
      <div>
        <label class="text-xs font-semibold text-gray-500 mb-1 block">Aksi</label>
        <select name="action" class="border border-gray-200 rounded-xl py-2 px-3 text-sm focus:ring-2 focus:ring-yellow-400">
          <option value="">Semua</option>
          @foreach($actionLabels as $key => $label)
          <option value="{{ $key }}" {{ request('action') == $key ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="text-xs font-semibold text-gray-500 mb-1 block">Dari</label>
        <input type="date" name="dari" value="{{ request('dari') }}" class="border border-gray-200 rounded-xl py-2 px-3 text-sm focus:ring-2 focus:ring-yellow-400">
      </div>
      <div>
        <label class="text-xs font-semibold text-gray-500 mb-1 block">Sampai</label>
        <input type="date" name="sampai" value="{{ request('sampai') }}" class="border border-gray-200 rounded-xl py-2 px-3 text-sm focus:ring-2 focus:ring-yellow-400">
      </div>
      <div class="flex-1 min-w-[180px]">
        <label class="text-xs font-semibold text-gray-500 mb-1 block">Cari</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari deskripsi..."
          class="w-full border border-gray-200 rounded-xl py-2 px-3 text-sm focus:ring-2 focus:ring-yellow-400">
      </div>
      <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm px-4 py-2 rounded-xl transition">
        <i class="fas fa-search mr-1"></i> Filter
      </button>
      <a href="{{ route('admin.audit.index') }}" class="text-gray-400 hover:text-gray-600 text-sm px-3 py-2 transition">Reset</a>
    </form>
  </div>

  {{-- Log Table --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="bg-gray-50/50">
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Waktu</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">User</th>
            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-400 uppercase">Aksi</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Deskripsi</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase">IP</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($logs as $log)
          <tr class="hover:bg-gray-50/50 transition">
            <td class="px-5 py-3 text-gray-400 text-xs whitespace-nowrap">
              {{ $log->created_at->format('d M Y') }}<br>
              <span class="text-gray-300">{{ $log->created_at->format('H:i:s') }}</span>
            </td>
            <td class="px-5 py-3">
              <p class="font-medium text-gray-700">{{ $log->user?->name ?? 'System' }}</p>
              <p class="text-xs text-gray-400">{{ $log->user?->role ?? '-' }}</p>
            </td>
            <td class="px-5 py-3 text-center">
              <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $actionColors[$log->action] ?? 'bg-gray-100 text-gray-700' }}">
                {{ $actionLabels[$log->action] ?? $log->action }}
              </span>
            </td>
            <td class="px-5 py-3 text-gray-600 max-w-md">
              <p>{{ $log->description }}</p>
              @if($log->model_type)
              <p class="text-xs text-gray-400 mt-0.5">{{ $log->model_type }} #{{ $log->model_id }}</p>
              @endif
            </td>
            <td class="px-5 py-3 text-xs text-gray-400 font-mono">{{ $log->ip_address ?? '-' }}</td>
          </tr>
          @empty
          <tr><td colspan="5" class="px-6 py-14 text-center text-gray-300"><i class="fas fa-file-alt text-4xl mb-2 block"></i>Belum ada log aktivitas.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($logs->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $logs->withQueryString()->links() }}</div>
    @endif
  </div>
</x-admin-layout>
