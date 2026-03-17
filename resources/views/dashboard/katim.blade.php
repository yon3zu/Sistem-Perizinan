@extends('layouts.app')

@section('title', 'Dashboard Ketua Tim')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Selamat datang, ' . auth()->user()->name)

@section('content')
<div class="space-y-5">

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="w-9 h-9 bg-yellow-100 rounded-xl flex items-center justify-center mb-2">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Perlu Disetujui</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="w-9 h-9 bg-orange-100 rounded-xl flex items-center justify-center mb-2">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                </svg>
            </div>
            <div class="text-2xl font-bold text-orange-600">{{ $sedangDiLuar->count() }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Sedang Di Luar</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center mb-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="text-2xl font-bold text-green-600">{{ $stats['disetujui'] }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Disetujui</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center mb-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="text-2xl font-bold text-blue-600">{{ $stats['hari_ini'] }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Hari Ini</div>
        </div>
    </div>

    <!-- Pending Approvals -->
    @if($pendingApprovals->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-yellow-200">
        <div class="flex items-center justify-between p-4 border-b border-yellow-100">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></div>
                <h2 class="font-bold text-gray-800 text-sm">Menunggu Persetujuan Anda</h2>
                <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $stats['pending'] }}</span>
            </div>
            <a href="{{ route('approval.index') }}" class="text-xs text-blue-600 font-medium">Lihat semua</a>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($pendingApprovals as $izin)
            <div class="p-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-700 font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr($izin->karyawan->nama, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-gray-800 text-sm">{{ $izin->karyawan->nama }}</div>
                    <div class="text-xs text-gray-500 truncate">{{ $izin->tujuan }}</div>
                    <div class="text-xs text-gray-400">
                        {{ $izin->tanggal->format('d/m/Y') }} &bull;
                        {{ substr($izin->jam_keluar_rencana, 0, 5) }} - {{ substr($izin->jam_kembali_rencana, 0, 5) }}
                    </div>
                </div>
                <a href="{{ route('approval.show', $izin->id) }}"
                   class="flex-shrink-0 bg-yellow-500 text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-yellow-600 transition">
                    Review
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <p class="text-sm font-medium text-gray-700">Tidak ada izin yang menunggu persetujuan</p>
        <p class="text-xs text-gray-400 mt-1">Semua izin sudah diproses</p>
    </div>
    @endif

    <!-- Sedang Di Luar -->
    @if($sedangDiLuar->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between p-4 border-b border-gray-50">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></div>
                <h2 class="font-bold text-gray-800 text-sm">Karyawan Sedang Di Luar</h2>
            </div>
            <span class="text-xs bg-orange-100 text-orange-700 font-semibold px-2 py-1 rounded-full">{{ $sedangDiLuar->count() }}</span>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($sedangDiLuar as $izin)
            <div class="p-4 flex items-center gap-3">
                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center text-orange-700 font-bold text-xs flex-shrink-0">
                    {{ strtoupper(substr($izin->karyawan->nama, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-800">{{ $izin->karyawan->nama }}</div>
                    <div class="text-xs text-gray-500 truncate">{{ $izin->tujuan }}</div>
                </div>
                <div class="text-xs text-gray-400 flex-shrink-0">
                    Kembali: {{ substr($izin->jam_kembali_rencana, 0, 5) }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Quick link to approval -->
    <a href="{{ route('approval.index') }}"
       class="flex items-center justify-between bg-white hover:bg-gray-50 rounded-2xl border border-gray-100 p-4 transition shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="font-semibold text-gray-800 text-sm">Kelola Persetujuan</div>
                <div class="text-xs text-gray-500">Lihat semua izin yang perlu diproses</div>
            </div>
        </div>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>

</div>
@endsection
