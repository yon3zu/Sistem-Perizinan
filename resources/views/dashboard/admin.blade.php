@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Administrator')
@section('breadcrumb', 'Ringkasan sistem')

@section('content')
<div class="space-y-5">

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 gap-3 lg:grid-cols-3">
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['total_karyawan'] }}</div>
            <div class="text-xs text-gray-500">Total Karyawan</div>
        </div>

        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="w-9 h-9 bg-purple-100 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['total_user'] }}</div>
            <div class="text-xs text-gray-500">Total Pengguna</div>
        </div>

        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="w-9 h-9 bg-indigo-100 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['total_izin'] }}</div>
            <div class="text-xs text-gray-500">Total Izin</div>
        </div>

        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['pending'] }}</div>
            <div class="text-xs text-gray-500">Pending</div>
        </div>

        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="w-9 h-9 bg-orange-100 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                </svg>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['sedang_keluar'] }}</div>
            <div class="text-xs text-gray-500">Sedang Di Luar</div>
        </div>

        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['hari_ini'] }}</div>
            <div class="text-xs text-gray-500">Izin Hari Ini</div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-4 gap-3">
        <a href="{{ route('admin.users.index') }}"
           class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 hover:border-blue-200 hover:shadow-md transition text-center">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="text-xs font-semibold text-gray-700">Pengguna</div>
        </a>

        <a href="{{ route('admin.karyawan.index') }}"
           class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 hover:border-blue-200 hover:shadow-md transition text-center">
            <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="text-xs font-semibold text-gray-700">Karyawan</div>
        </a>

        <a href="{{ route('admin.teams.index') }}"
           class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 hover:border-blue-200 hover:shadow-md transition text-center">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                </svg>
            </div>
            <div class="text-xs font-semibold text-gray-700">Tim</div>
        </a>

        <a href="{{ route('admin.laporan.index') }}"
           class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 hover:border-blue-200 hover:shadow-md transition text-center">
            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="text-xs font-semibold text-gray-700">Laporan</div>
        </a>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Aktivitas Terbaru</h2>
            <a href="{{ route('admin.laporan.index') }}" class="text-xs text-blue-600 font-medium">Laporan Lengkap</a>
        </div>
        @if($recentIzin->isEmpty())
            <div class="py-10 text-center text-gray-400 text-sm">Belum ada data izin</div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($recentIzin as $izin)
                <div class="flex items-center gap-3 px-4 py-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-semibold text-xs flex-shrink-0">
                        {{ strtoupper(substr($izin->karyawan->nama, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-gray-800">{{ $izin->karyawan->nama }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ $izin->tujuan }} &bull; {{ $izin->tanggal->format('d M Y') }}</div>
                    </div>
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full flex-shrink-0
                        @if($izin->status === 'pending') bg-amber-100 text-amber-700
                        @elseif($izin->status === 'disetujui') bg-green-100 text-green-700
                        @elseif($izin->status === 'ditolak') bg-red-100 text-red-700
                        @else bg-blue-100 text-blue-700 @endif">
                        {{ $izin->getStatusLabel() }}
                    </span>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
