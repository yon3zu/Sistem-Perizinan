@extends('layouts.app')

@section('title', 'Manajemen Tim')
@section('page-title', 'Manajemen Tim')
@section('breadcrumb', 'Kelola tim dalam sistem')

@section('content')
<div class="space-y-4">

    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-500">{{ $teams->total() }} tim</p>
        <a href="{{ route('admin.teams.create') }}"
           class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Tim
        </a>
    </div>

    @if($teams->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-gray-100">
            <svg class="w-16 h-16 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-gray-400">Belum ada tim</p>
        </div>
    @else
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($teams as $team)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-gray-800">{{ $team->nama }}</div>
                        <div class="text-xs text-gray-500">{{ $team->users_count }} anggota</div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.teams.edit', $team->id) }}"
                       class="flex-1 py-2 text-center text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('admin.teams.destroy', $team->id) }}"
                          onsubmit="return confirm('Hapus tim {{ $team->nama }}? Anggota tim tidak akan dihapus.')"
                          class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full py-2 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $teams->links() }}</div>
    @endif
</div>
@endsection
