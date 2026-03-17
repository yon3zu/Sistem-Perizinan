@extends('layouts.app')

@section('title', 'Detail Permohonan Izin')
@section('page-title', 'Detail Permohonan')
@section('breadcrumb', 'Tinjau permohonan izin karyawan')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">

    <!-- Karyawan Info -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-700 font-bold text-2xl flex-shrink-0">
                {{ strtoupper(substr($izin->karyawan->nama, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">{{ $izin->karyawan->nama }}</h2>
                <p class="text-sm text-gray-500">{{ $izin->karyawan->nip }}
                    @if($izin->karyawan->team) &bull; {{ $izin->karyawan->team->nama }} @endif
                </p>
                @if($izin->karyawan->jabatan)
                <p class="text-xs text-gray-400">{{ $izin->karyawan->jabatan }}</p>
                @endif
            </div>
            <div class="ml-auto">
                <span class="text-xs font-semibold px-3 py-1 rounded-full
                    @if($izin->status === 'disetujui') bg-green-100 text-green-700
                    @elseif($izin->status === 'pending') bg-yellow-100 text-yellow-700
                    @elseif($izin->status === 'ditolak') bg-red-100 text-red-700
                    @else bg-blue-100 text-blue-700 @endif">
                    {{ $izin->getStatusLabel() }}
                </span>
            </div>
        </div>
    </div>

    <!-- Detail Izin -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
        <h3 class="font-bold text-gray-800">Detail Izin</h3>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Tanggal</div>
                <div class="text-sm font-semibold text-gray-800">{{ $izin->tanggal->format('d/m/Y') }}</div>
                <div class="text-xs text-gray-500">{{ $izin->tanggal->translatedFormat('l') }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Jam</div>
                <div class="text-sm font-semibold text-gray-800">{{ substr($izin->jam_keluar_rencana, 0, 5) }} - {{ substr($izin->jam_kembali_rencana, 0, 5) }}</div>
            </div>
        </div>

        <div>
            <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Tujuan</div>
            <div class="text-sm text-gray-800">{{ $izin->tujuan }}</div>
        </div>

        @if($izin->keterangan)
        <div>
            <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Keterangan</div>
            <div class="text-sm text-gray-800">{{ $izin->keterangan }}</div>
        </div>
        @endif

        <div class="flex items-center justify-between text-sm pt-2 border-t border-gray-100">
            <span class="text-gray-500">Dicatat oleh:</span>
            <span class="font-medium text-gray-800">{{ $izin->pencatat->name }}</span>
        </div>
        <div class="flex items-center justify-between text-sm">
            <span class="text-gray-500">Waktu pencatatan:</span>
            <span class="text-gray-600">{{ $izin->created_at->format('d/m/Y H:i') }}</span>
        </div>

        @if($izin->bukti)
        <div>
            <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Bukti</div>
            <a href="{{ Storage::url($izin->bukti) }}" target="_blank"
               class="inline-flex items-center gap-2 text-blue-600 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
                Lihat Bukti
            </a>
        </div>
        @endif
    </div>

    <!-- Approval Form -->
    @if($izin->isPending())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4" x-data="{ action: null }">
        <h3 class="font-bold text-gray-800">Tindakan Persetujuan</h3>

        <!-- Quick Approve -->
        <div class="bg-green-50 rounded-xl p-4 border border-green-200" x-show="action !== 'tolak'">
            <p class="text-sm text-green-700 font-medium mb-3">Setujui izin ini?</p>
            <form method="POST" action="{{ route('approval.approve', $izin->id) }}">
                @csrf
                <div class="mb-3">
                    <textarea name="catatan_katim" rows="2" placeholder="Catatan (opsional)..."
                              class="w-full px-3 py-2 border border-green-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-green-500 bg-white resize-none"></textarea>
                </div>
                <button type="submit"
                        class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition">
                    Setujui Izin
                </button>
            </form>
        </div>

        <!-- Reject Form (toggle) -->
        <div x-data="{ show: false }">
            <button @click="show = !show"
                    class="w-full py-2.5 border border-red-300 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-50 transition">
                Tolak Izin
            </button>
            <div x-show="show" x-cloak class="mt-3 bg-red-50 rounded-xl p-4 border border-red-200">
                <form method="POST" action="{{ route('approval.reject', $izin->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm font-semibold text-red-700 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                        <textarea name="catatan_katim" rows="3" placeholder="Tuliskan alasan penolakan..."
                                  required
                                  class="w-full px-3 py-2 border border-red-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-red-500 bg-white resize-none"></textarea>
                        @error('catatan_katim')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                            class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition">
                        Konfirmasi Tolak
                    </button>
                </form>
            </div>
        </div>
    </div>
    @elseif($izin->status !== 'pending')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                @if($izin->status === 'disetujui' || $izin->status === 'selesai') bg-green-100
                @else bg-red-100 @endif">
                <svg class="w-5 h-5 @if($izin->status === 'disetujui' || $izin->status === 'selesai') text-green-600 @else text-red-600 @endif" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <div class="text-sm font-semibold text-gray-800">Izin sudah {{ $izin->getStatusLabel() }}</div>
                @if($izin->approved_at)
                <div class="text-xs text-gray-500">{{ $izin->approved_at->format('d/m/Y H:i') }} oleh {{ $izin->approver?->name }}</div>
                @endif
                @if($izin->catatan_katim)
                <div class="text-xs text-gray-600 mt-1">Catatan: {{ $izin->catatan_katim }}</div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Back -->
    <a href="{{ route('approval.index') }}"
       class="flex items-center gap-2 text-gray-500 hover:text-gray-700 text-sm font-medium transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Daftar Persetujuan
    </a>
</div>
@endsection
