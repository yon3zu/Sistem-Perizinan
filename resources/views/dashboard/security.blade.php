@extends('layouts.app')

@section('title', 'Dashboard Security')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Selamat datang, ' . auth()->user()->name)

@section('content')
<div class="space-y-5">

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['total_hari_ini'] }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Total Izin Hari Ini</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="w-9 h-9 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-orange-600">{{ $stats['sedang_keluar'] }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Sedang Di Luar</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="w-9 h-9 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending_approval'] }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Pending Approval</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-green-600">{{ $stats['selesai'] }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Selesai Hari Ini</div>
        </div>
    </div>

    <!-- Quick Action -->
    <a href="{{ route('izin.create') }}"
       class="flex items-center justify-between bg-blue-600 hover:bg-blue-700 text-white rounded-2xl p-4 transition shadow-sm shadow-blue-200">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <div>
                <div class="font-bold text-sm">Catat Izin Keluar</div>
                <div class="text-xs text-blue-200">Klik untuk mencatat izin karyawan</div>
            </div>
        </div>
        <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>

    <!-- Sedang Di Luar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between p-4 border-b border-gray-50">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></div>
                <h2 class="font-bold text-gray-800 text-sm">Sedang Di Luar Sekarang</h2>
            </div>
            <span class="text-xs font-semibold bg-orange-100 text-orange-700 px-2 py-1 rounded-full">{{ $sedangDiLuar->count() }} orang</span>
        </div>

        @if($sedangDiLuar->isEmpty())
            <div class="p-8 text-center">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-400">Tidak ada karyawan di luar saat ini</p>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($sedangDiLuar as $izin)
                <div class="p-4 flex items-center gap-3">
                    <div class="w-9 h-9 bg-orange-100 rounded-full flex items-center justify-center text-orange-700 font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($izin->karyawan->nama, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-gray-800 text-sm">{{ $izin->karyawan->nama }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ $izin->tujuan }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">
                            Keluar: {{ $izin->jam_keluar_aktual ?? $izin->jam_keluar_rencana }}
                            &bull; Rencana kembali: {{ $izin->jam_kembali_rencana }}
                        </div>
                    </div>
                    <a href="{{ route('izin.show', $izin->id) }}"
                       class="flex-shrink-0 bg-blue-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-blue-700 transition">
                        Kembali
                    </a>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Izin Terbaru Hari Ini -->
    @if($recentIzin->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between p-4 border-b border-gray-50">
            <h2 class="font-bold text-gray-800 text-sm">Aktivitas Hari Ini</h2>
            <a href="{{ route('izin.index') }}" class="text-xs text-blue-600 font-medium">Lihat semua</a>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($recentIzin as $izin)
            <div class="p-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0
                    @if($izin->status === 'disetujui') bg-green-100 text-green-700
                    @elseif($izin->status === 'pending') bg-yellow-100 text-yellow-700
                    @elseif($izin->status === 'ditolak') bg-red-100 text-red-700
                    @else bg-blue-100 text-blue-700 @endif">
                    {{ strtoupper(substr($izin->karyawan->nama, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-800">{{ $izin->karyawan->nama }}</div>
                    <div class="text-xs text-gray-500 truncate">{{ $izin->tujuan }}</div>
                </div>
                <span class="flex-shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full
                    @if($izin->status === 'disetujui') bg-green-100 text-green-700
                    @elseif($izin->status === 'pending') bg-yellow-100 text-yellow-700
                    @elseif($izin->status === 'ditolak') bg-red-100 text-red-700
                    @else bg-blue-100 text-blue-700 @endif">
                    {{ $izin->getStatusLabel() }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
