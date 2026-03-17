<?php

namespace App\Http\Controllers\Katim;

use App\Http\Controllers\Controller;
use App\Models\IzinKeluar;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function index()
    {
        $pendingIzin = IzinKeluar::where('katim_id', Auth::id())
            ->where('status', 'pending')
            ->with(['karyawan', 'karyawan.team', 'pencatat'])
            ->latest()
            ->paginate(10);

        $allIzin = IzinKeluar::where('katim_id', Auth::id())
            ->with(['karyawan', 'karyawan.team', 'pencatat'])
            ->latest()
            ->paginate(10, ['*'], 'all_page');

        $stats = [
            'pending'   => IzinKeluar::where('katim_id', Auth::id())->where('status', 'pending')->count(),
            'disetujui' => IzinKeluar::where('katim_id', Auth::id())->where('status', 'disetujui')->count(),
            'ditolak'   => IzinKeluar::where('katim_id', Auth::id())->where('status', 'ditolak')->count(),
            'selesai'   => IzinKeluar::where('katim_id', Auth::id())->where('status', 'selesai')->count(),
        ];

        return view('katim.approval.index', compact('pendingIzin', 'allIzin', 'stats'));
    }

    public function show($id)
    {
        $izin = IzinKeluar::where('katim_id', Auth::id())
            ->with(['karyawan', 'karyawan.team', 'pencatat', 'approver'])
            ->findOrFail($id);

        return view('katim.approval.show', compact('izin'));
    }

    public function approve(Request $request, $id)
    {
        $izin = IzinKeluar::where('katim_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $request->validate([
            'catatan_katim' => ['nullable', 'string', 'max:1000'],
        ]);

        $izin->update([
            'status'        => 'disetujui',
            'approved_by'   => Auth::id(),
            'approved_at'   => now(),
            'catatan_katim' => $request->catatan_katim,
        ]);

        return redirect()->route('approval.index')
            ->with('success', 'Izin ' . $izin->karyawan->nama . ' telah disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $izin = IzinKeluar::where('katim_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $request->validate([
            'catatan_katim' => ['required', 'string', 'max:1000'],
        ]);

        $izin->update([
            'status'        => 'ditolak',
            'approved_by'   => Auth::id(),
            'approved_at'   => now(),
            'catatan_katim' => $request->catatan_katim,
        ]);

        return redirect()->route('approval.index')
            ->with('success', 'Izin ' . $izin->karyawan->nama . ' telah ditolak.');
    }
}
