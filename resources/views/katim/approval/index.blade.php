@extends('layouts.app')

@section('title', 'Persetujuan Izin')
@section('page-title', 'Persetujuan Izin')
@section('breadcrumb', 'Kelola izin yang memerlukan persetujuan Anda')

@section('content')
<div class="space-y-5">

    <!-- Stats -->
    <div class="grid grid-cols-4 gap-3">
        <div class="bg-white rounded-xl p-3 text-center border border-gray-100">
            <div class="text-xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
            <div class="text-xs text-gray-500">Pending</div>
        </div>
        <div class="bg-white rounded-xl p-3 text-center border border-gray-100">
            <div class="text-xl font-bold text-green-600">{{ $stats['disetujui'] }}</div>
            <div class="text-xs text-gray-500">Disetujui</div>
        </div>
        <div class="bg-white rounded-xl p-3 text-center border border-gray-100">
            <div class="text-xl font-bold text-red-600">{{ $stats['ditolak'] }}</div>
            <div class="text-xs text-gray-500">Ditolak</div>
        </div>
        <div class="bg-white rounded-xl p-3 text-center border border-gray-100">
            <div class="text-xl font-bold text-blue-600">{{ $stats['selesai'] }}</div>
            <div class="text-xs text-gray-500">Selesai</div>
        </div>
    </div>

    <!-- Pending approvals (urgent) -->
    @if($pendingIzin->isEmpty())
    <div class="bg-white rounded-2xl p-10 text-center border border-gray-100 shadow-sm">
        <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <p class="text-gray-700 font-semibold">Tidak ada izin yang menunggu persetujuan</p>
        <p class="text-gray-400 text-sm mt-1">Semua izin sudah diproses</p>
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-sm border border-yellow-200 overflow-hidden">
        <div class="bg-yellow-50 px-4 py-3 border-b border-yellow-100 flex items-center gap-2">
            <div class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></div>
            <h2 class="font-bold text-yellow-800 text-sm">Menunggu Persetujuan ({{ $pendingIzin->total() }})</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($pendingIzin as $izin)
            <div class="p-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-700 font-bold flex-shrink-0">
                        {{ strtoupper(substr($izin->karyawan->nama, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-gray-800 text-sm">{{ $izin->karyawan->nama }}</span>
                            <span class="text-xs text-gray-400">{{ $izin->tanggal->format('d/m/Y') }}</span>
                        </div>
                        <div class="text-xs text-gray-500 truncate">{{ $izin->tujuan }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">
                            {{ substr($izin->jam_keluar_rencana, 0, 5) }} - {{ substr($izin->jam_kembali_rencana, 0, 5) }}
                            @if($izin->karyawan->team) &bull; {{ $izin->karyawan->team->nama }} @endif
                        </div>
                        <div class="text-xs text-gray-400 mt-0.5">Dicatat oleh: {{ $izin->pencatat->name }}</div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="flex gap-2 mt-3 ml-13">
                    <form method="POST" action="{{ route('approval.approve', $izin->id) }}" class="flex-1">
                        @csrf
                        <button type="submit"
                                class="w-full py-2 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-semibold rounded-lg transition">
                            Setujui
                        </button>
                    </form>
                    <a href="{{ route('approval.show', $izin->id) }}"
                       class="flex-1 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg transition text-center">
                        Detail
                    </a>
                    <a href="{{ route('approval.show', $izin->id) }}"
                       class="flex-1 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg transition text-center">
                        Tolak
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @if($pendingIzin->hasPages())
        <div class="p-4 border-t border-gray-100">{{ $pendingIzin->links() }}</div>
        @endif
    </div>
    @endif

    <!-- All izin for this katim -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-4 py-3 border-b border-gray-100">
            <h2 class="font-bold text-gray-800 text-sm">Semua Izin Yang Ditujukan ke Saya</h2>
        </div>
        @if($allIzin->isEmpty())
            <div class="py-8 text-center text-gray-400 text-sm">Belum ada data izin</div>
        @else
        <div class="divide-y divide-gray-50">
            @foreach($allIzin as $izin)
            <a href="{{ route('approval.show', $izin->id) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs flex-shrink-0
                    @if($izin->status === 'disetujui') bg-green-100 text-green-700
                    @elseif($izin->status === 'pending') bg-yellow-100 text-yellow-700
                    @elseif($izin->status === 'ditolak') bg-red-100 text-red-700
                    @else bg-blue-100 text-blue-700 @endif">
                    {{ strtoupper(substr($izin->karyawan->nama, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-800">{{ $izin->karyawan->nama }}</span>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                            @if($izin->status === 'disetujui') bg-green-100 text-green-700
                            @elseif($izin->status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif($izin->status === 'ditolak') bg-red-100 text-red-700
                            @else bg-blue-100 text-blue-700 @endif">
                            {{ $izin->getStatusLabel() }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-500 truncate">{{ $izin->tujuan }}</div>
                    <div class="text-xs text-gray-400">{{ $izin->tanggal->format('d/m/Y') }}</div>
                </div>
            </a>
            @endforeach
        </div>
        @if($allIzin->hasPages())
        <div class="p-4 border-t border-gray-100">{{ $allIzin->links() }}</div>
        @endif
        @endif
    </div>

</div>
@endsection
