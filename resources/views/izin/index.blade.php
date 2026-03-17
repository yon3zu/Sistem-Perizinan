@extends('layouts.app')

@section('title', 'Daftar Izin')
@section('page-title', 'Daftar Izin')
@section('breadcrumb', 'Semua catatan izin keluar')

@section('content')
<div class="space-y-4">

    <!-- Stats mini -->
    <div class="grid grid-cols-4 gap-2">
        <div class="bg-white rounded-xl p-3 text-center border border-gray-100">
            <div class="text-lg font-bold text-gray-800">{{ $stats['total_hari_ini'] }}</div>
            <div class="text-xs text-gray-500">Total</div>
        </div>
        <div class="bg-white rounded-xl p-3 text-center border border-gray-100">
            <div class="text-lg font-bold text-orange-600">{{ $stats['sedang_keluar'] }}</div>
            <div class="text-xs text-gray-500">Di Luar</div>
        </div>
        <div class="bg-white rounded-xl p-3 text-center border border-gray-100">
            <div class="text-lg font-bold text-yellow-600">{{ $stats['pending_approval'] }}</div>
            <div class="text-xs text-gray-500">Pending</div>
        </div>
        <div class="bg-white rounded-xl p-3 text-center border border-gray-100">
            <div class="text-lg font-bold text-blue-600">{{ $stats['selesai'] }}</div>
            <div class="text-xs text-gray-500">Selesai</div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="date" name="tanggal" value="{{ request('tanggal', today()->toDateString()) }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <select name="status" class="w-full sm:w-auto px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 sm:flex-none px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition">
                    Filter
                </button>
                <a href="{{ route('izin.index') }}" class="px-4 py-2.5 bg-gray-50 text-gray-500 rounded-xl text-sm font-medium hover:bg-gray-100 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-500">{{ $izinList->total() }} izin ditemukan</p>
        <a href="{{ route('izin.create') }}"
           class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Catat Izin
        </a>
    </div>

    @if($izinList->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-gray-100">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-gray-500 font-medium">Tidak ada data izin</p>
            <p class="text-gray-400 text-sm mt-1">Mulai catat izin karyawan</p>
            <a href="{{ route('izin.create') }}" class="mt-4 inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                Catat Izin Sekarang
            </a>
        </div>
    @else
        <!-- Mobile List -->
        <div class="space-y-2 sm:hidden">
            @foreach($izinList as $izin)
            <a href="{{ route('izin.show', $izin->id) }}" class="block bg-white rounded-2xl shadow-sm border border-gray-100 p-4 hover:border-blue-200 transition">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0
                        @if($izin->status === 'disetujui') bg-green-100 text-green-700
                        @elseif($izin->status === 'pending') bg-yellow-100 text-yellow-700
                        @elseif($izin->status === 'ditolak') bg-red-100 text-red-700
                        @else bg-blue-100 text-blue-700 @endif">
                        {{ strtoupper(substr($izin->karyawan->nama, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-semibold text-gray-800 text-sm truncate">{{ $izin->karyawan->nama }}</span>
                            <span class="flex-shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full
                                @if($izin->status === 'disetujui') bg-green-100 text-green-700
                                @elseif($izin->status === 'pending') bg-yellow-100 text-yellow-700
                                @elseif($izin->status === 'ditolak') bg-red-100 text-red-700
                                @else bg-blue-100 text-blue-700 @endif">
                                {{ $izin->getStatusLabel() }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-500 truncate mt-0.5">{{ $izin->tujuan }}</div>
                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-400">
                            <span>{{ $izin->tanggal->format('d/m/Y') }}</span>
                            <span>&bull;</span>
                            <span>{{ substr($izin->jam_keluar_rencana, 0, 5) }} - {{ substr($izin->jam_kembali_rencana, 0, 5) }}</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Desktop Table -->
        <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase">Karyawan</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase">Tujuan</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase">Tanggal</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase">Jam</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase">Katim</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase">Status</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 text-xs uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($izinList as $izin)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-800">{{ $izin->karyawan->nama }}</div>
                            <div class="text-xs text-gray-400">{{ $izin->karyawan->team?->nama ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-600 max-w-xs truncate">{{ $izin->tujuan }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $izin->tanggal->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-gray-600 text-xs">
                            <div>Keluar: {{ substr($izin->jam_keluar_rencana, 0, 5) }}</div>
                            <div>Kembali: {{ substr($izin->jam_kembali_rencana, 0, 5) }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $izin->katim?->name ?? 'Tanpa Katim' }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                @if($izin->status === 'disetujui') bg-green-100 text-green-700
                                @elseif($izin->status === 'pending') bg-yellow-100 text-yellow-700
                                @elseif($izin->status === 'ditolak') bg-red-100 text-red-700
                                @else bg-blue-100 text-blue-700 @endif">
                                {{ $izin->getStatusLabel() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('izin.show', $izin->id) }}"
                               class="text-blue-600 hover:text-blue-800 text-xs font-medium">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $izinList->links() }}</div>
    @endif
</div>
@endsection
