@extends('layouts.app')

@section('title', 'Detail Izin')
@section('page-title', 'Detail Izin')
@section('breadcrumb', 'Informasi izin keluar karyawan')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">

    <!-- Header Status -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5
            @if($izin->status === 'disetujui') bg-green-50 border-b border-green-100
            @elseif($izin->status === 'pending') bg-yellow-50 border-b border-yellow-100
            @elseif($izin->status === 'ditolak') bg-red-50 border-b border-red-100
            @else bg-blue-50 border-b border-blue-100 @endif">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                            @if($izin->status === 'disetujui') bg-green-100 text-green-700
                            @elseif($izin->status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif($izin->status === 'ditolak') bg-red-100 text-red-700
                            @else bg-blue-100 text-blue-700 @endif">
                            {{ $izin->getStatusLabel() }}
                        </span>
                        <span class="text-xs text-gray-500">#{{ $izin->id }}</span>
                    </div>
                    <h2 class="text-lg font-bold text-gray-800">{{ $izin->karyawan->nama }}</h2>
                    <p class="text-sm text-gray-500">{{ $izin->karyawan->nip }}
                        @if($izin->karyawan->team) &bull; {{ $izin->karyawan->team->nama }} @endif
                    </p>
                </div>
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl font-bold flex-shrink-0
                    @if($izin->status === 'disetujui') bg-green-100 text-green-700
                    @elseif($izin->status === 'pending') bg-yellow-100 text-yellow-700
                    @elseif($izin->status === 'ditolak') bg-red-100 text-red-700
                    @else bg-blue-100 text-blue-700 @endif">
                    {{ strtoupper(substr($izin->karyawan->nama, 0, 1)) }}
                </div>
            </div>
        </div>

        <!-- Detail Info -->
        <div class="p-5 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Tanggal</div>
                    @if($izin->tanggal_kembali && $izin->tanggal_kembali->ne($izin->tanggal))
                        <div class="text-sm font-semibold text-gray-800">{{ $izin->tanggal->format('d M') }} – {{ $izin->tanggal_kembali->format('d M Y') }}</div>
                        <div class="inline-flex items-center gap-1 mt-0.5 text-xs font-medium bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $izin->tanggal->diffInDays($izin->tanggal_kembali) + 1 }} hari
                        </div>
                    @else
                        <div class="text-sm font-semibold text-gray-800">{{ $izin->tanggal->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $izin->tanggal->translatedFormat('l') }}</div>
                    @endif
                </div>
                <div>
                    <div class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Dicatat Oleh</div>
                    <div class="text-sm font-semibold text-gray-800">{{ $izin->pencatat->name }}</div>
                    <div class="text-xs text-gray-500">{{ $izin->created_at->format('H:i') }} WIB</div>
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Tujuan</div>
                <div class="text-sm text-gray-800">{{ $izin->tujuan }}</div>
            </div>

            @if($izin->keterangan)
            <div>
                <div class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Keterangan</div>
                <div class="text-sm text-gray-800">{{ $izin->keterangan }}</div>
            </div>
            @endif

            <!-- Waktu Grid -->
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-gray-50 rounded-xl p-3">
                    <div class="text-xs text-gray-400 mb-1">Jam Keluar (Rencana)</div>
                    <div class="text-base font-bold text-gray-800">{{ substr($izin->jam_keluar_rencana, 0, 5) }}</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <div class="text-xs text-gray-400 mb-1">Jam Kembali (Rencana)</div>
                    <div class="text-base font-bold text-gray-800">{{ substr($izin->jam_kembali_rencana, 0, 5) }}</div>
                </div>
                @if($izin->jam_keluar_aktual)
                <div class="bg-blue-50 rounded-xl p-3">
                    <div class="text-xs text-blue-400 mb-1">Jam Keluar (Aktual)</div>
                    <div class="text-base font-bold text-blue-700">{{ substr($izin->jam_keluar_aktual, 0, 5) }}</div>
                </div>
                @endif
                @if($izin->jam_kembali_aktual)
                <div class="bg-green-50 rounded-xl p-3">
                    <div class="text-xs text-green-400 mb-1">Jam Kembali (Aktual)</div>
                    <div class="text-base font-bold text-green-700">{{ substr($izin->jam_kembali_aktual, 0, 5) }}</div>
                </div>
                @endif
            </div>

            @if($izin->katim)
            <div class="flex items-center justify-between py-2 border-t border-gray-100">
                <div class="text-sm text-gray-600">Katim yang ditunjuk:</div>
                <div class="text-sm font-semibold text-gray-800">{{ $izin->katim->name }}</div>
            </div>
            @endif

            @if($izin->approver)
            <div class="flex items-center justify-between py-2 border-t border-gray-100">
                <div class="text-sm text-gray-600">Diproses oleh:</div>
                <div>
                    <div class="text-sm font-semibold text-gray-800 text-right">{{ $izin->approver->name }}</div>
                    <div class="text-xs text-gray-400 text-right">{{ $izin->approved_at?->format('d/m/Y H:i') }}</div>
                </div>
            </div>
            @endif

            @if($izin->catatan_katim)
            <div class="bg-gray-50 rounded-xl p-3">
                <div class="text-xs text-gray-400 mb-1">Catatan Katim</div>
                <div class="text-sm text-gray-800">{{ $izin->catatan_katim }}</div>
            </div>
            @endif

            @if($izin->bukti)
            <div>
                <div class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-2">Bukti</div>
                <a href="{{ Storage::url($izin->bukti) }}" target="_blank"
                   class="inline-flex items-center gap-2 text-blue-600 text-sm font-medium hover:text-blue-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    Lihat Bukti
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Timeline -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-bold text-gray-800 text-sm mb-4">Timeline Status</h3>
        <div class="space-y-3">
            <div class="flex items-start gap-3">
                <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-800">Izin Dicatat</div>
                    <div class="text-xs text-gray-500">{{ $izin->created_at->format('d/m/Y H:i') }} oleh {{ $izin->pencatat->name }}</div>
                </div>
            </div>

            @if($izin->katim)
            <div class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5
                    {{ in_array($izin->status, ['disetujui','ditolak','selesai']) ? 'bg-blue-600' : 'bg-gray-200' }}">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-800">
                        @if($izin->status === 'pending') Menunggu Persetujuan Katim
                        @elseif($izin->status === 'disetujui' || $izin->status === 'selesai') Disetujui Katim
                        @elseif($izin->status === 'ditolak') Ditolak Katim
                        @endif
                    </div>
                    @if($izin->approved_at)
                    <div class="text-xs text-gray-500">{{ $izin->approved_at->format('d/m/Y H:i') }} oleh {{ $izin->approver?->name }}</div>
                    @else
                    <div class="text-xs text-gray-400">Menunggu...</div>
                    @endif
                </div>
            </div>
            @endif

            @if($izin->isSelesai())
            <div class="flex items-start gap-3">
                <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-800">Karyawan Sudah Kembali</div>
                    <div class="text-xs text-gray-500">Jam kembali aktual: {{ substr($izin->jam_kembali_aktual, 0, 5) }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Action: Konfirmasi Kembali -->
    @if($izin->isDisetujui() && (auth()->user()->isSecurity() || auth()->user()->isAdmin()))
    <div class="bg-white rounded-2xl shadow-sm border border-green-200 p-5">
        <h3 class="font-bold text-gray-800 text-sm mb-1">Konfirmasi Karyawan Kembali</h3>
        <p class="text-xs text-gray-500 mb-4">Isi jam kembali aktual saat karyawan sudah tiba</p>
        <form method="POST" action="{{ route('izin.kembali', $izin->id) }}" class="flex gap-3">
            @csrf
            <div class="flex-1">
                <input type="time" name="jam_kembali_aktual" value="{{ now()->format('H:i') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl text-sm font-bold transition">
                Konfirmasi Kembali
            </button>
        </form>
    </div>
    @endif

    <!-- Back button -->
    <a href="{{ route('izin.index') }}"
       class="flex items-center gap-2 text-gray-500 hover:text-gray-700 text-sm font-medium transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Daftar Izin
    </a>

</div>
@endsection
