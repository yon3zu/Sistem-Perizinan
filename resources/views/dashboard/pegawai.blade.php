@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Selamat datang, ' . auth()->user()->name)

@section('content')
<div class="space-y-5">

    <!-- Active Permit Banner -->
    @if($activeIzin)
    <div class="bg-green-500 text-white rounded-2xl p-4 flex items-center gap-3 shadow-md">
        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <div class="font-semibold text-sm">Izin Aktif Hari Ini</div>
            <div class="text-green-100 text-xs truncate">{{ $activeIzin->tujuan }} — kembali {{ substr($activeIzin->jam_kembali_rencana, 0, 5) }}</div>
        </div>
        <a href="{{ route('izin.show', $activeIzin->id) }}"
           class="bg-white/20 hover:bg-white/30 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition flex-shrink-0">
            Detail
        </a>
    </div>
    @endif

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Total Izin</div>
        </div>

        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['pending'] }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Menunggu</div>
        </div>

        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['disetujui'] + $stats['selesai'] }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Disetujui</div>
        </div>

        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['ditolak'] }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Ditolak</div>
        </div>
    </div>

    <!-- Quick Action -->
    <a href="{{ route('izin.create') }}"
       class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-2xl p-4 shadow-md transition-all transform active:scale-95">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <div>
                <div class="font-bold text-base">Ajukan Izin Keluar</div>
                <div class="text-blue-100 text-xs">Buat permohonan izin baru</div>
            </div>
            <svg class="w-5 h-5 ml-auto text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </div>
    </a>

    <!-- Recent Activity -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Riwayat Izin Terbaru</h2>
            <a href="{{ route('izin.index') }}" class="text-xs text-blue-600 font-medium">Lihat Semua</a>
        </div>

        @if($recentIzin->isEmpty())
            <div class="py-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-gray-400 text-sm">Belum ada riwayat izin</p>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($recentIzin as $izin)
                <a href="{{ route('izin.show', $izin->id) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0
                        @if($izin->status === 'pending') bg-amber-100
                        @elseif($izin->status === 'disetujui') bg-green-100
                        @elseif($izin->status === 'ditolak') bg-red-100
                        @else bg-blue-100 @endif">
                        @if($izin->status === 'pending')
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @elseif($izin->status === 'disetujui')
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @elseif($izin->status === 'ditolak')
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        @else
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-gray-800 truncate">{{ $izin->tujuan }}</div>
                        <div class="text-xs text-gray-500">{{ $izin->tanggal->format('d M Y') }} · {{ substr($izin->jam_keluar_rencana, 0, 5) }}</div>
                    </div>
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full flex-shrink-0
                        @if($izin->status === 'pending') bg-amber-100 text-amber-700
                        @elseif($izin->status === 'disetujui') bg-green-100 text-green-700
                        @elseif($izin->status === 'ditolak') bg-red-100 text-red-700
                        @else bg-blue-100 text-blue-700 @endif">
                        {{ $izin->getStatusLabel() }}
                    </span>
                </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
