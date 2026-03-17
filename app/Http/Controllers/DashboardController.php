<?php

namespace App\Http\Controllers;

use App\Models\IzinKeluar;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        if ($user->isKatim()) {
            return $this->katimDashboard();
        }

        // Default: security
        return $this->securityDashboard();
    }

    private function securityDashboard()
    {
        $stats = [
            'total_hari_ini'   => IzinKeluar::whereDate('tanggal', today())->count(),
            'sedang_keluar'    => IzinKeluar::whereDate('tanggal', today())->where('status', 'disetujui')->count(),
            'pending_approval' => IzinKeluar::whereDate('tanggal', today())->where('status', 'pending')->count(),
            'selesai'          => IzinKeluar::whereDate('tanggal', today())->where('status', 'selesai')->count(),
        ];

        $sedangDiLuar = IzinKeluar::with(['karyawan', 'karyawan.team'])
            ->whereDate('tanggal', today())
            ->where('status', 'disetujui')
            ->latest()
            ->get();

        $recentIzin = IzinKeluar::with(['karyawan', 'katim'])
            ->whereDate('tanggal', today())
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.security', compact('stats', 'sedangDiLuar', 'recentIzin'));
    }

    private function katimDashboard()
    {
        $user = Auth::user();

        $stats = [
            'pending'       => IzinKeluar::where('katim_id', $user->id)->where('status', 'pending')->count(),
            'disetujui'     => IzinKeluar::where('katim_id', $user->id)->where('status', 'disetujui')->count(),
            'selesai'       => IzinKeluar::where('katim_id', $user->id)->where('status', 'selesai')->count(),
            'hari_ini'      => IzinKeluar::where('katim_id', $user->id)->whereDate('tanggal', today())->count(),
        ];

        $pendingApprovals = IzinKeluar::where('katim_id', $user->id)
            ->where('status', 'pending')
            ->with(['karyawan', 'karyawan.team', 'pencatat'])
            ->latest()
            ->take(5)
            ->get();

        $sedangDiLuar = IzinKeluar::where('katim_id', $user->id)
            ->where('status', 'disetujui')
            ->whereDate('tanggal', today())
            ->with(['karyawan', 'karyawan.team'])
            ->get();

        return view('dashboard.katim', compact('stats', 'pendingApprovals', 'sedangDiLuar'));
    }

    private function adminDashboard()
    {
        $stats = [
            'total_karyawan' => Karyawan::where('is_active', true)->count(),
            'total_user'     => User::count(),
            'total_izin'     => IzinKeluar::count(),
            'hari_ini'       => IzinKeluar::whereDate('tanggal', today())->count(),
            'pending'        => IzinKeluar::where('status', 'pending')->count(),
            'sedang_keluar'  => IzinKeluar::whereDate('tanggal', today())->where('status', 'disetujui')->count(),
        ];

        $recentIzin = IzinKeluar::with(['karyawan', 'pencatat'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.admin', compact('stats', 'recentIzin'));
    }
}
