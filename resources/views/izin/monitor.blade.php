@extends('layouts.app')

@section('title', 'Monitor Izin')
@section('page-title', 'Monitor Izin')
@section('breadcrumb', 'Pantau status izin keluar real-time')

@section('content')
<div class="space-y-4">

    <!-- Date Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <form method="GET" class="flex gap-3">
            <div class="flex-1">
                <input type="date" name="tanggal" value="{{ $tanggal }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                Tampilkan
            </button>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-5 gap-2">
        <div class="bg-white rounded-xl p-3 text-center border border-gray-100 col-span-1">
            <div class="text-lg font-bold text-gray-800">{{ $stats['total'] }}</div>
            <div class="text-xs text-gray-500">Total</div>
        </div>
        <div class="bg-orange-50 rounded-xl p-3 text-center border border-orange-200 col-span-1">
            <div class="text-lg font-bold text-orange-600">{{ $stats['sedang_keluar'] }}</div>
            <div class="text-xs text-orange-500">Di Luar</div>
        </div>
        <div class="bg-yellow-50 rounded-xl p-3 text-center border border-yellow-200 col-span-1">
            <div class="text-lg font-bold text-yellow-600">{{ $stats['pending_approval'] }}</div>
            <div class="text-xs text-yellow-500">Pending</div>
        </div>
        <div class="bg-blue-50 rounded-xl p-3 text-center border border-blue-200 col-span-1">
            <div class="text-lg font-bold text-blue-600">{{ $stats['selesai'] }}</div>
            <div class="text-xs text-blue-500">Selesai</div>
        </div>
        <div class="bg-red-50 rounded-xl p-3 text-center border border-red-200 col-span-1">
            <div class="text-lg font-bold text-red-600">{{ $stats['ditolak'] }}</div>
            <div class="text-xs text-red-500">Ditolak</div>
        </div>
    </div>

    <!-- Sedang Di Luar (Priority) -->
    @if($sedangDiLuar->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-orange-200">
        <div class="flex items-center justify-between p-4 border-b border-orange-100 bg-orange-50 rounded-t-2xl">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-orange-500 rounded-full animate-pulse"></div>
                <h2 class="font-bold text-orange-800 text-sm">SEDANG DI LUAR ({{ $sedangDiLuar->count() }})</h2>
            </div>
            <a href="{{ route('izin.create') }}" class="text-xs bg-blue-600 text-white px-3 py-1.5 rounded-lg font-semibold hover:bg-blue-700 transition">
                + Catat Baru
            </a>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($sedangDiLuar as $izin)
            <div class="p-4 flex items-center gap-3">
                <div class="w-11 h-11 bg-orange-100 rounded-full flex items-center justify-center text-orange-700 font-bold flex-shrink-0">
                    {{ strtoupper(substr($izin->karyawan->nama, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-gray-800">{{ $izin->karyawan->nama }}</div>
                    <div class="text-xs text-gray-500">{{ $izin->karyawan->nip }}
                        @if($izin->karyawan->team) &bull; {{ $izin->karyawan->team->nama }} @endif
                    </div>
                    <div class="text-xs text-gray-600 mt-0.5">{{ $izin->tujuan }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">
                        Keluar: {{ substr($izin->jam_keluar_aktual ?? $izin->jam_keluar_rencana, 0, 5) }}
                        &bull; Rencana kembali: <span class="font-medium text-orange-600">{{ substr($izin->jam_kembali_rencana, 0, 5) }}</span>
                    </div>
                </div>
                <a href="{{ route('izin.show', $izin->id) }}"
                   class="flex-shrink-0 bg-green-600 text-white text-xs font-bold px-3 py-2 rounded-xl hover:bg-green-700 transition whitespace-nowrap">
                    Konfirmasi Kembali
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-green-50 rounded-2xl border border-green-200 p-6 text-center">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <p class="text-sm font-medium text-green-700">Tidak ada karyawan di luar saat ini</p>
    </div>
    @endif

    <!-- All Izin Today -->
    @if($izinList->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-4 border-b border-gray-50">
            <h2 class="font-bold text-gray-800 text-sm">Semua Izin - {{ \Carbon\Carbon::parse($tanggal)->format('d MMMM Y') }}</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($izinList as $izin)
            <a href="{{ route('izin.show', $izin->id) }}" class="flex items-center gap-3 p-4 hover:bg-gray-50 transition">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0
                    @if($izin->status === 'disetujui') bg-green-100 text-green-700
                    @elseif($izin->status === 'pending') bg-yellow-100 text-yellow-700
                    @elseif($izin->status === 'ditolak') bg-red-100 text-red-700
                    @else bg-blue-100 text-blue-700 @endif">
                    {{ strtoupper(substr($izin->karyawan->nama, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-gray-800">{{ $izin->karyawan->nama }}</span>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                            @if($izin->status === 'disetujui') bg-green-100 text-green-700
                            @elseif($izin->status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif($izin->status === 'ditolak') bg-red-100 text-red-700
                            @else bg-blue-100 text-blue-700 @endif">
                            {{ $izin->getStatusLabel() }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-500 truncate">{{ $izin->tujuan }}</div>
                    <div class="text-xs text-gray-400">
                        {{ substr($izin->jam_keluar_rencana, 0, 5) }} - {{ substr($izin->jam_kembali_rencana, 0, 5) }}
                        @if($izin->katim) &bull; Katim: {{ $izin->katim->name }} @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
