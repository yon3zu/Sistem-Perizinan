@extends('layouts.app')
@section('title', 'Edit Profil')
@section('page-title', 'Profil Saya')
@section('breadcrumb', 'Kelola informasi akun Anda')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">

    <!-- Profile Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <!-- Header gradient -->
        <div class="h-24 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

        <!-- Avatar + Info -->
        <div class="px-6 pb-6">
            <div class="flex items-end gap-4 -mt-12 mb-4">
                <div class="relative group" x-data>
                    <div class="w-20 h-20 rounded-2xl ring-4 ring-white overflow-hidden shadow-md bg-blue-100">
                        @if($user->foto)
                            <img src="{{ Storage::url($user->foto) }}" id="fotoPreview"
                                 class="w-full h-full object-cover" alt="Foto Profil">
                        @else
                            <div id="fotoPlaceholder" class="w-full h-full flex items-center justify-center text-blue-600 font-bold text-3xl">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <img id="fotoPreview" class="w-full h-full object-cover hidden" alt="Foto Profil">
                        @endif
                    </div>
                    <!-- camera overlay -->
                    <label for="foto_input"
                           class="absolute inset-0 rounded-2xl bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </label>
                </div>
                <div class="pb-1">
                    <h2 class="text-lg font-bold text-gray-800">{{ $user->name }}</h2>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-sm text-gray-500">{{ $user->nip }}</span>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full
                            @if($user->role === 'admin') bg-purple-100 text-purple-700
                            @elseif($user->role === 'katim') bg-blue-100 text-blue-700
                            @else bg-gray-100 text-gray-600 @endif">
                            {{ $user->getRoleLabel() }}
                        </span>
                        @if($user->team)
                            <span class="text-xs text-gray-400">{{ $user->team->nama }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stats row -->
            <div class="grid grid-cols-3 gap-3 mb-6">
                @php
                    $totalCatat = \App\Models\IzinKeluar::where('dicatat_oleh', $user->id)->count();
                    $hariIni    = \App\Models\IzinKeluar::where('dicatat_oleh', $user->id)->whereDate('tanggal', today())->count();
                    $pending    = \App\Models\IzinKeluar::where('dicatat_oleh', $user->id)->where('status', 'pending')->count();
                @endphp
                @if($user->isSecurity())
                <div class="text-center p-3 bg-gray-50 rounded-xl">
                    <div class="text-xl font-bold text-gray-800">{{ $totalCatat }}</div>
                    <div class="text-xs text-gray-500">Total Dicatat</div>
                </div>
                <div class="text-center p-3 bg-blue-50 rounded-xl">
                    <div class="text-xl font-bold text-blue-700">{{ $hariIni }}</div>
                    <div class="text-xs text-gray-500">Hari Ini</div>
                </div>
                <div class="text-center p-3 bg-amber-50 rounded-xl">
                    <div class="text-xl font-bold text-amber-700">{{ $pending }}</div>
                    <div class="text-xs text-gray-500">Pending</div>
                </div>
                @elseif($user->isKatim())
                @php
                    $pendingApproval = \App\Models\IzinKeluar::where('katim_id', $user->id)->where('status', 'pending')->count();
                    $disetujui       = \App\Models\IzinKeluar::where('katim_id', $user->id)->whereIn('status', ['disetujui','selesai'])->count();
                    $totalApproval   = \App\Models\IzinKeluar::where('katim_id', $user->id)->count();
                @endphp
                <div class="text-center p-3 bg-gray-50 rounded-xl">
                    <div class="text-xl font-bold text-gray-800">{{ $totalApproval }}</div>
                    <div class="text-xs text-gray-500">Total Approval</div>
                </div>
                <div class="text-center p-3 bg-green-50 rounded-xl">
                    <div class="text-xl font-bold text-green-700">{{ $disetujui }}</div>
                    <div class="text-xs text-gray-500">Disetujui</div>
                </div>
                <div class="text-center p-3 bg-amber-50 rounded-xl">
                    <div class="text-xl font-bold text-amber-700">{{ $pendingApproval }}</div>
                    <div class="text-xs text-gray-500">Pending</div>
                </div>
                @else
                <div class="text-center p-3 bg-gray-50 rounded-xl col-span-3">
                    <div class="text-sm text-gray-500">Administrator Sistem</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data"
          x-data="{ showPass: false, showPass2: false }">
        @csrf
        @method('PUT')

        <!-- Hidden file input -->
        <input type="file" id="foto_input" name="foto" accept="image/*" class="hidden"
               onchange="previewFoto(this)">

        <!-- Info Dasar -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
            <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Informasi Dasar
            </h3>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">NIP</label>
                <input type="text" value="{{ $user->nip }}" disabled
                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-500 cursor-not-allowed">
                <p class="text-xs text-gray-400 mt-1">NIP tidak dapat diubah</p>
            </div>

            <div>
                <label for="name" class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                              {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="phone" class="block text-xs font-semibold text-gray-600 mb-1.5">No. HP / WhatsApp</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}"
                           placeholder="08xxxxxxxxxx"
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
            </div>
        </div>

        <!-- Foto Profil -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
            <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Foto Profil
            </h3>

            <div class="flex items-center gap-4">
                <label for="foto_input" class="cursor-pointer">
                    <div class="flex items-center gap-3 px-4 py-2.5 border-2 border-dashed border-gray-300 rounded-xl hover:border-blue-400 hover:bg-blue-50 transition">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <span class="text-sm text-gray-600">Pilih foto baru</span>
                    </div>
                </label>
                @if($user->foto)
                <form method="POST" action="{{ route('profil.hapusFoto') }}" onsubmit="return confirm('Hapus foto profil?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium">
                        Hapus foto
                    </button>
                </form>
                @endif
            </div>
            <p class="text-xs text-gray-400">Format: JPG, PNG, WEBP. Maksimal 2MB.</p>
            @error('foto') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Ganti Password -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
            <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Ganti Password
                <span class="text-xs text-gray-400 font-normal">(kosongkan jika tidak ingin mengubah)</span>
            </h3>

            <div>
                <label for="password" class="block text-xs font-semibold text-gray-600 mb-1.5">Password Baru</label>
                <div class="relative">
                    <input id="password" name="password" :type="showPass ? 'text' : 'password'"
                           placeholder="Minimal 6 karakter"
                           class="w-full pl-4 pr-10 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition
                                  {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    <button type="button" @click="showPass = !showPass"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-gray-600 mb-1.5">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input id="password_confirmation" name="password_confirmation"
                           :type="showPass2 ? 'text' : 'password'"
                           placeholder="Ulangi password baru"
                           class="w-full pl-4 pr-10 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <button type="button" @click="showPass2 = !showPass2"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <svg x-show="!showPass2" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="showPass2" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <button type="submit"
                class="w-full py-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold rounded-2xl transition-colors shadow-sm shadow-blue-200 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Simpan Perubahan
        </button>
    </form>
</div>

<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('fotoPreview');
            const placeholder = document.getElementById('fotoPlaceholder');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
