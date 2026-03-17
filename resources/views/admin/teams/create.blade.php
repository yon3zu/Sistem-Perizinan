@extends('layouts.app')

@section('title', 'Tambah Tim')
@section('page-title', 'Tambah Tim')
@section('breadcrumb', 'Buat tim baru')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
            <h2 class="text-white font-bold text-lg">Tambah Tim Baru</h2>
        </div>
        <form method="POST" action="{{ route('admin.teams.store') }}" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Nama Tim <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                       placeholder="Contoh: Tim Alpha"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500
                              {{ $errors->has('nama') ? 'border-red-400' : 'border-gray-300' }}">
                @error('nama')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.teams.index') }}"
                   class="flex-1 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl text-sm text-center hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl text-sm transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
