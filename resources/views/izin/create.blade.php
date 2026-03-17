@extends('layouts.app')

@section('title', 'Catat Izin Keluar')
@section('page-title', 'Catat Izin Keluar')
@section('breadcrumb', 'Isi form izin karyawan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-blue-600 px-6 py-4">
            <h2 class="text-white font-bold text-lg">Form Izin Keluar</h2>
            <p class="text-blue-200 text-sm">Isi data izin karyawan dengan lengkap dan benar</p>
        </div>

        <form method="POST" action="{{ route('izin.store') }}" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            <!-- Pilih Karyawan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Karyawan <span class="text-red-500">*</span>
                </label>
                <div x-data="{ search: '', selected: null, open: false }" class="relative">
                    <input type="hidden" name="karyawan_id" :value="selected ? selected.id : ''" required>
                    <div @click="open = !open"
                         class="w-full px-4 py-3 border rounded-xl text-sm cursor-pointer flex items-center justify-between
                                {{ $errors->has('karyawan_id') ? 'border-red-400 bg-red-50' : 'border-gray-300 bg-white hover:border-blue-400' }}">
                        <span :class="selected ? 'text-gray-800' : 'text-gray-400'" x-text="selected ? selected.nama + ' (' + selected.nip + ')' : 'Pilih karyawan...'"></span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                    <div x-show="open" x-cloak @click.away="open = false"
                         class="absolute z-30 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                        <div class="p-2 border-b border-gray-100">
                            <input type="text" x-model="search" placeholder="Cari nama atau NIP..."
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                                   @click.stop>
                        </div>
                        <div class="max-h-56 overflow-y-auto">
                            @foreach($karyawanList as $k)
                            <div @click="selected = { id: {{ $k->id }}, nama: '{{ addslashes($k->nama) }}', nip: '{{ $k->nip }}' }; open = false"
                                 x-show="search === '' || '{{ strtolower($k->nama) }} {{ $k->nip }}'.includes(search.toLowerCase())"
                                 class="px-4 py-3 hover:bg-blue-50 cursor-pointer flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-medium text-gray-800">{{ $k->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ $k->nip }}{{ $k->team ? ' &bull; ' . $k->team->nama : '' }}</div>
                                </div>
                                <svg x-show="selected && selected.id === {{ $k->id }}" class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @error('karyawan_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Keluar & Kembali -->
            <div x-data="{ isMultiDay: {{ old('tanggal_kembali') && old('tanggal_kembali') !== old('tanggal', today()->toDateString()) ? 'true' : 'false' }} }">
                <div class="grid grid-cols-2 gap-4 mb-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Keluar <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal" id="tanggal"
                               value="{{ old('tanggal', today()->toDateString()) }}" required
                               class="w-full px-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                      {{ $errors->has('tanggal') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                               @change="if(!isMultiDay) document.getElementById('tanggal_kembali').value = $event.target.value">
                        @error('tanggal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Kembali <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_kembali" id="tanggal_kembali"
                               value="{{ old('tanggal_kembali', today()->toDateString()) }}" required
                               class="w-full px-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                      {{ $errors->has('tanggal_kembali') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                               @change="isMultiDay = $event.target.value !== document.getElementById('tanggal').value">
                        @error('tanggal_kembali')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Badge izin berhari-hari -->
                <div x-show="isMultiDay"
                     class="flex items-center gap-2 px-3 py-2 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-700">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Izin berhari-hari — jam kembali akan diisi saat karyawan benar-benar pulang</span>
                </div>
            </div>

            <!-- Jam Keluar & Jam Kembali -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Jam Keluar <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="jam_keluar_rencana"
                           value="{{ old('jam_keluar_rencana', now()->format('H:i')) }}" required
                           class="w-full px-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                  {{ $errors->has('jam_keluar_rencana') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('jam_keluar_rencana')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Jam Kembali (Rencana) <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="jam_kembali_rencana" value="{{ old('jam_kembali_rencana') }}" required
                           class="w-full px-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                  {{ $errors->has('jam_kembali_rencana') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('jam_kembali_rencana')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tujuan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tujuan Keluar <span class="text-red-500">*</span>
                </label>
                <input type="text" name="tujuan" value="{{ old('tujuan') }}"
                       placeholder="Contoh: Bank BRI, Rumah Sakit, Kantor Pusat..."
                       required
                       class="w-full px-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                              {{ $errors->has('tujuan') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                @error('tujuan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Keterangan <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <textarea name="keterangan" rows="2" placeholder="Informasi tambahan jika diperlukan..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('keterangan') }}</textarea>
            </div>

            <!-- Bukti -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Bukti/Surat <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <input type="file" name="bukti" accept=".jpg,.jpeg,.png,.pdf"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-3 file:py-1 file:px-3 file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 file:rounded-lg">
                <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, PDF. Maks 2MB</p>
            </div>

            <!-- Pilih Katim (Opsional) -->
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Persetujuan Katim <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <select name="katim_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">-- Tanpa Persetujuan Katim (Langsung Disetujui) --</option>
                    @foreach($katimList as $katim)
                    <option value="{{ $katim->id }}" {{ old('katim_id') == $katim->id ? 'selected' : '' }}>
                        {{ $katim->name }}{{ $katim->team ? ' - ' . $katim->team->nama : '' }}
                    </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-2">
                    Jika memilih katim, izin akan berstatus <strong>Menunggu</strong> sampai katim menyetujui.
                    Jika tidak dipilih, izin langsung berstatus <strong>Disetujui</strong>.
                </p>
            </div>

            <!-- Submit -->
            <div class="flex gap-3 pt-2">
                <a href="{{ route('izin.index') }}"
                   class="flex-1 sm:flex-none px-6 py-3 border border-gray-300 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition text-center">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-xl text-sm font-bold transition shadow-sm shadow-blue-200">
                    Simpan Izin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
