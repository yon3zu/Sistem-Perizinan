<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IzinKeluar;
use App\Models\Karyawan;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = IzinKeluar::with(['karyawan', 'karyawan.team', 'pencatat', 'approver']);

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('team_id')) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('team_id', $request->team_id);
            });
        }

        if ($request->filled('karyawan_id')) {
            $query->where('karyawan_id', $request->karyawan_id);
        }

        $izinList = $query->latest()->paginate(20)->withQueryString();

        $teams        = Team::all();
        $karyawanList = Karyawan::orderBy('nama')->get();

        $totalQuery = IzinKeluar::query();
        if ($request->filled('tanggal_dari')) {
            $totalQuery->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $totalQuery->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('team_id')) {
            $totalQuery->whereHas('karyawan', function ($q) use ($request) {
                $q->where('team_id', $request->team_id);
            });
        }

        $stats = [
            'total'     => (clone $totalQuery)->count(),
            'pending'   => (clone $totalQuery)->where('status', 'pending')->count(),
            'disetujui' => (clone $totalQuery)->where('status', 'disetujui')->count(),
            'ditolak'   => (clone $totalQuery)->where('status', 'ditolak')->count(),
            'selesai'   => (clone $totalQuery)->where('status', 'selesai')->count(),
        ];

        return view('admin.laporan.index', compact('izinList', 'teams', 'karyawanList', 'stats'));
    }
}
