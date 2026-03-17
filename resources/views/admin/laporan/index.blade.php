@extends('layouts.app')

@section('title', 'Laporan Izin')
@section('page-title', 'Laporan Izin Keluar')
@section('breadcrumb', 'Data lengkap permohonan izin')

@section('content')
<div class="space-y-4">

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <form method="GET" class="space-y-3">
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Dari Tanggal</label>
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Sampai Tanggal</label>
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Tim</label>
                    <select name="team_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Tim</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ request('team_id') == $team->id ? 'selected' : '' }}>
                                {{ $team->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">
                    Filter
                </button>
                <a href="{{ route('admin.laporan.index') }}"
                   class="px-4 py-2 border border-gray-300 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
        <div class="bg-white rounded-xl p-3 border border-gray-100 text-center">
            <div class="text-xl font-bold text-gray-800">{{ $stats['total'] }}</div>
            <div class="text-xs text-gray-500">Total</div>
        </div>
        <div class="bg-white rounded-xl p-3 border border-amber-200 text-center">
            <div class="text-xl font-bold text-amber-700">{{ $stats['pending'] }}</div>
            <div class="text-xs text-amber-600">Pending</div>
        </div>
        <div class="bg-white rounded-xl p-3 border border-green-200 text-center">
            <div class="text-xl font-bold text-green-700">{{ $stats['disetujui'] }}</div>
            <div class="text-xs text-green-600">Disetujui</div>
        </div>
        <div class="bg-white rounded-xl p-3 border border-blue-200 text-center">
            <div class="text-xl font-bold text-blue-700">{{ $stats['selesai'] }}</div>
            <div class="text-xs text-blue-600">Selesai</div>
        </div>
    </div>

    <!-- Data Table -->
    @if($izinList->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-gray-100">
            <svg class="w-14 h-14 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-gray-400">Tidak ada data sesuai filter</p>
        </div>
    @else
        <!-- Mobile Cards -->
        <div class="space-y-2 sm:hidden">
            @foreach($izinList as $izin)
            <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <div class="font-semibold text-sm text-gray-800">{{ $izin->karyawan->nama }}</div>
                        <div class="text-xs text-gray-400">{{ $izin->karyawan->team?->nama ?? 'Tanpa Tim' }}</div>
                    </div>
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full
                        @if($izin->status === 'pending') bg-amber-100 text-amber-700
                        @elseif($izin->status === 'disetujui') bg-green-100 text-green-700
                        @elseif($izin->status === 'ditolak') bg-red-100 text-red-700
                        @else bg-blue-100 text-blue-700 @endif">
                        {{ $izin->getStatusLabel() }}
                    </span>
                </div>
                <div class="text-sm text-gray-700 font-medium truncate mb-1">{{ $izin->tujuan }}</div>
                <div class="flex items-center gap-3 text-xs text-gray-400">
                    <span>{{ $izin->tanggal->format('d M Y') }}</span>
                    <span>{{ substr($izin->jam_keluar_rencana, 0, 5) }} - {{ substr($izin->jam_kembali_rencana, 0, 5) }}</span>
                </div>
                <div class="text-xs text-gray-400 mt-1">Dicatat: {{ $izin->pencatat->name }}</div>
            </div>
            @endforeach
        </div>

        <!-- Desktop Table -->
        <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase whitespace-nowrap">Karyawan</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase whitespace-nowrap">Tim</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase whitespace-nowrap">Tanggal</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase whitespace-nowrap">Jam</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase whitespace-nowrap">Tujuan</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase whitespace-nowrap">Dicatat Oleh</th>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase whitespace-nowrap">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($izinList as $izin)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-800">{{ $izin->karyawan->nama }}</div>
                                <div class="text-xs text-gray-400">{{ $izin->karyawan->nip }}</div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">{{ $izin->karyawan->team?->nama ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap">{{ $izin->tanggal->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap text-xs">
                                {{ substr($izin->jam_keluar_rencana, 0, 5) }}<br>
                                <span class="text-gray-400">s/d {{ substr($izin->jam_kembali_rencana, 0, 5) }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 max-w-xs truncate">{{ $izin->tujuan }}</td>
                            <td class="px-4 py-3 text-gray-600 text-xs">{{ $izin->pencatat->name }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full whitespace-nowrap
                                    @if($izin->status === 'pending') bg-amber-100 text-amber-700
                                    @elseif($izin->status === 'disetujui') bg-green-100 text-green-700
                                    @elseif($izin->status === 'ditolak') bg-red-100 text-red-700
                                    @else bg-blue-100 text-blue-700 @endif">
                                    {{ $izin->getStatusLabel() }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">{{ $izinList->links() }}</div>
    @endif
</div>
@endsection
