<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\IzinKeluar;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IzinController extends Controller
{
    public function index(Request $request)
    {
        $query = IzinKeluar::with(['karyawan', 'karyawan.team', 'katim', 'pencatat'])
            ->latest();

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            // Default: tampilkan hari ini
            $query->whereDate('tanggal', today());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $izinList = $query->paginate(15)->withQueryString();

        $stats = [
            'total_hari_ini'   => IzinKeluar::whereDate('tanggal', today())->count(),
            'sedang_keluar'    => IzinKeluar::whereDate('tanggal', today())->where('status', 'disetujui')->count(),
            'pending_approval' => IzinKeluar::whereDate('tanggal', today())->where('status', 'pending')->count(),
            'selesai'          => IzinKeluar::whereDate('tanggal', today())->where('status', 'selesai')->count(),
        ];

        return view('izin.index', compact('izinList', 'stats'));
    }

    public function create()
    {
        $karyawanList = Karyawan::where('is_active', true)
            ->with('team')
            ->orderBy('nama')
            ->get();

        $katimList = User::where('role', 'katim')
            ->where('is_active', true)
            ->with('team')
            ->get();

        return view('izin.create', compact('karyawanList', 'katimList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id'         => ['required', 'exists:karyawan,id'],
            'tanggal'             => ['required', 'date'],
            'tanggal_kembali'     => ['required', 'date', 'gte:tanggal'],
            'jam_keluar_rencana'  => ['required', 'date_format:H:i'],
            'jam_kembali_rencana' => ['required', 'date_format:H:i'],
            'tujuan'              => ['required', 'string', 'max:255'],
            'keterangan'          => ['nullable', 'string', 'max:1000'],
            'bukti'               => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'katim_id'            => ['nullable', 'exists:users,id'],
        ], [
            'tanggal_kembali.gte' => 'Tanggal kembali tidak boleh sebelum tanggal berangkat.',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti')) {
            $buktiPath = $request->file('bukti')->store('bukti', 'public');
        }

        $katimId = $request->katim_id ?: null;
        $status  = $katimId ? 'pending' : 'disetujui';

        $izin = IzinKeluar::create([
            'karyawan_id'         => $request->karyawan_id,
            'dicatat_oleh'        => Auth::id(),
            'katim_id'            => $katimId,
            'tanggal'             => $request->tanggal,
            'tanggal_kembali'     => $request->tanggal_kembali,
            'jam_keluar_rencana'  => $request->jam_keluar_rencana,
            'jam_kembali_rencana' => $request->jam_kembali_rencana,
            'jam_keluar_aktual'   => $request->jam_keluar_rencana,
            'tujuan'              => $request->tujuan,
            'keterangan'          => $request->keterangan,
            'bukti'               => $buktiPath,
            'status'              => $status,
            'approved_by'         => $katimId ? null : Auth::id(),
            'approved_at'         => $katimId ? null : now(),
        ]);

        $pesan = $katimId
            ? 'Izin berhasil dicatat, menunggu persetujuan katim.'
            : 'Izin berhasil dicatat dan langsung disetujui.';

        return redirect()->route('izin.show', $izin->id)
            ->with('success', $pesan);
    }

    public function show($id)
    {
        $izin = IzinKeluar::with(['karyawan', 'karyawan.team', 'pencatat', 'katim', 'approver'])
            ->findOrFail($id);

        return view('izin.show', compact('izin'));
    }

    public function konfirmasiKembali(Request $request, $id)
    {
        $izin = IzinKeluar::findOrFail($id);

        if (!$izin->isDisetujui()) {
            return back()->with('error', 'Hanya izin yang sudah disetujui yang bisa dikonfirmasi kembali.');
        }

        $request->validate([
            'jam_kembali_aktual' => ['required', 'date_format:H:i'],
        ]);

        $izin->update([
            'jam_kembali_aktual' => $request->jam_kembali_aktual,
            'status'             => 'selesai',
        ]);

        return redirect()->route('izin.show', $izin->id)
            ->with('success', 'Konfirmasi kembali berhasil. Status izin selesai.');
    }

    public function semuaIzin(Request $request)
    {
        $tanggal = $request->get('tanggal', today()->toDateString());

        $izinList = IzinKeluar::with(['karyawan', 'karyawan.team', 'katim', 'pencatat'])
            ->whereDate('tanggal', $tanggal)
            ->latest()
            ->get();

        $sedangDiLuar = IzinKeluar::with(['karyawan', 'karyawan.team', 'pencatat'])
            ->whereDate('tanggal', $tanggal)
            ->where('status', 'disetujui')
            ->get();

        $stats = [
            'total'            => $izinList->count(),
            'sedang_keluar'    => $sedangDiLuar->count(),
            'pending_approval' => $izinList->where('status', 'pending')->count(),
            'selesai'          => $izinList->where('status', 'selesai')->count(),
            'ditolak'          => $izinList->where('status', 'ditolak')->count(),
        ];

        return view('izin.monitor', compact('izinList', 'sedangDiLuar', 'stats', 'tanggal'));
    }
}
