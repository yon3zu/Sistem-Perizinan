@extends('layouts.app')

@section('title', 'Tambah Karyawan')
@section('page-title', 'Tambah Karyawan')
@section('breadcrumb', 'Tambahkan data karyawan baru')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-teal-600 px-6 py-4">
            <h2 class="text-white font-bold text-lg">Form Tambah Karyawan</h2>
            <p class="text-teal-200 text-sm">Karyawan tidak memiliki akun login</p>
        </div>

        <form method="POST" action="{{ route('admin.karyawan.store') }}" class="p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}" required
                       placeholder="Nama lengkap karyawan"
                       class="w-full px-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500
                              {{ $errors->has('nama') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    NIP <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nip" value="{{ old('nip') }}" required
                       placeholder="Nomor Induk Pegawai"
                       class="w-full px-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500
                              {{ $errors->has('nip') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                @error('nip')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jabatan</label>
                <input type="text" name="jabatan" value="{{ old('jabatan') }}"
                       placeholder="Jabatan/posisi karyawan"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tim</label>
                <select name="team_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">-- Tidak Ada Tim --</option>
                    @foreach($teams as $team)
                    <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>
                        {{ $team->nama }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">No. HP</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       placeholder="08xxxxxxxxxx"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="flex items-center gap-3">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       {{ old('is_active', '1') ? 'checked' : '' }}
                       class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                <label for="is_active" class="text-sm font-medium text-gray-700">Karyawan aktif</label>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('admin.karyawan.index') }}"
                   class="flex-1 sm:flex-none px-6 py-3 border border-gray-300 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition text-center">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-teal-600 hover:bg-teal-700 text-white py-3 px-6 rounded-xl text-sm font-bold transition">
                    Simpan Karyawan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
