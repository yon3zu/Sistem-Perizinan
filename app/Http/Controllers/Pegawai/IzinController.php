<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\IzinKeluar;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IzinController extends Controller
{
    public function index()
    {
        $izinList = IzinKeluar::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('izin.index', compact('izinList'));
    }

    public function create()
    {
        return view('izin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'            => ['required', 'date', 'after_or_equal:today'],
            'jam_keluar_rencana' => ['required', 'date_format:H:i'],
            'jam_kembali_rencana'=> ['required', 'date_format:H:i', 'after:jam_keluar_rencana'],
            'tujuan'             => ['required', 'string', 'max:255'],
            'keterangan'         => ['nullable', 'string', 'max:1000'],
            'bukti'              => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti')) {
            $buktiPath = $request->file('bukti')->store('bukti', 'public');
        }

        $izin = IzinKeluar::create([
            'user_id'             => Auth::id(),
            'tanggal'             => $request->tanggal,
            'jam_keluar_rencana'  => $request->jam_keluar_rencana,
            'jam_kembali_rencana' => $request->jam_kembali_rencana,
            'tujuan'              => $request->tujuan,
            'keterangan'          => $request->keterangan,
            'bukti'               => $buktiPath,
            'status'              => 'pending',
        ]);

        // Notify katim
        $katim = User::where('team_id', Auth::user()->team_id)
            ->where('role', 'katim')
            ->first();

        if ($katim) {
            Notifikasi::create([
                'user_id' => $katim->id,
                'title'   => 'Permohonan Izin Baru',
                'message' => Auth::user()->name . ' mengajukan izin keluar pada ' . $izin->tanggal->format('d/m/Y'),
                'type'    => 'izin_baru',
            ]);
        }

        return redirect()->route('izin.show', $izin->id)
            ->with('success', 'Permohonan izin berhasil diajukan.');
    }

    public function show($id)
    {
        $izin = IzinKeluar::where('user_id', Auth::id())
            ->with(['user', 'approver'])
            ->findOrFail($id);

        return view('izin.show', compact('izin'));
    }

    public function konfirmasiKembali(Request $request, $id)
    {
        $izin = IzinKeluar::where('user_id', Auth::id())
            ->findOrFail($id);

        if (!$izin->isDisetujui()) {
            return back()->with('error', 'Izin ini tidak dapat dikonfirmasi.');
        }

        $request->validate([
            'jam_kembali_aktual' => ['required', 'date_format:H:i'],
        ]);

        $izin->update([
            'jam_kembali_aktual' => $request->jam_kembali_aktual,
            'jam_keluar_aktual'  => $izin->jam_keluar_aktual ?? $izin->jam_keluar_rencana,
            'status'             => 'selesai',
        ]);

        return redirect()->route('izin.show', $izin->id)
            ->with('success', 'Konfirmasi kembali berhasil.');
    }
}
