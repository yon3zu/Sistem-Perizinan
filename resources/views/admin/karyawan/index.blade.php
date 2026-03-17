@extends('layouts.app')

@section('title', 'Manajemen Karyawan')
@section('page-title', 'Manajemen Karyawan')
@section('breadcrumb', 'Kelola data karyawan (non-login)')

@section('content')
<div class="space-y-4">

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama, NIP, atau jabatan..."
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-2">
                <select name="team_id" class="px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Tim</option>
                    @foreach($teams as $team)
                    <option value="{{ $team->id }}" {{ request('team_id') == $team->id ? 'selected' : '' }}>{{ $team->nama }}</option>
                    @endforeach
                </select>
                <select name="status" class="px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <button type="submit" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-500">{{ $karyawanList->total() }} karyawan</p>
        <a href="{{ route('admin.karyawan.create') }}"
           class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Karyawan
        </a>
    </div>

    @if($karyawanList->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-gray-100">
            <p class="text-gray-400">Tidak ada karyawan ditemukan</p>
        </div>
    @else
        <!-- Mobile List -->
        <div class="space-y-2 sm:hidden">
            @foreach($karyawanList as $karyawan)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center text-teal-700 font-bold flex-shrink-0">
                        {{ strtoupper(substr($karyawan->nama, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-800 text-sm">{{ $karyawan->nama }}</span>
                            @if(!$karyawan->is_active)
                                <span class="text-xs bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">Nonaktif</span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500">{{ $karyawan->nip }}</div>
                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-400">
                            @if($karyawan->jabatan)
                                <span>{{ $karyawan->jabatan }}</span>
                            @endif
                            @if($karyawan->team)
                                <span>&bull;</span>
                                <span>{{ $karyawan->team->nama }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <a href="{{ route('admin.karyawan.edit', $karyawan->id) }}"
                           class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('admin.karyawan.destroy', $karyawan->id) }}"
                              onsubmit="return confirm('Hapus karyawan {{ $karyawan->nama }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Desktop Table -->
        <div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase">Nama</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase">NIP</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase">Jabatan</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase">Tim</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase">Status</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 text-xs uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($karyawanList as $karyawan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-semibold text-xs">
                                    {{ strtoupper(substr($karyawan->nama, 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-800">{{ $karyawan->nama }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $karyawan->nip }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $karyawan->jabatan ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $karyawan->team?->nama ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full
                                {{ $karyawan->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $karyawan->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.karyawan.edit', $karyawan->id) }}"
                                   class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                                <form method="POST" action="{{ route('admin.karyawan.destroy', $karyawan->id) }}"
                                      onsubmit="return confirm('Hapus karyawan {{ $karyawan->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $karyawanList->links() }}</div>
    @endif
</div>
@endsection
