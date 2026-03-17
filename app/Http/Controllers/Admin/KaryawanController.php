<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::with('team');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nip', 'like', '%' . $request->search . '%')
                  ->orWhere('jabatan', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('team_id')) {
            $query->where('team_id', $request->team_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        $karyawanList = $query->orderBy('nama')->paginate(15)->withQueryString();
        $teams        = Team::all();

        return view('admin.karyawan.index', compact('karyawanList', 'teams'));
    }

    public function create()
    {
        $teams = Team::all();
        return view('admin.karyawan.create', compact('teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => ['required', 'string', 'max:255'],
            'nip'       => ['required', 'string', 'max:50', 'unique:karyawan,nip'],
            'jabatan'   => ['nullable', 'string', 'max:100'],
            'team_id'   => ['nullable', 'exists:teams,id'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
        ]);

        Karyawan::create([
            'nama'      => $request->nama,
            'nip'       => $request->nip,
            'jabatan'   => $request->jabatan,
            'team_id'   => $request->team_id,
            'phone'     => $request->phone,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $karyawan = Karyawan::with(['team', 'izinKeluars' => function ($q) {
            $q->with('pencatat')->latest()->take(10);
        }])->findOrFail($id);

        return view('admin.karyawan.show', compact('karyawan'));
    }

    public function edit($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $teams    = Team::all();
        return view('admin.karyawan.edit', compact('karyawan', 'teams'));
    }

    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $request->validate([
            'nama'      => ['required', 'string', 'max:255'],
            'nip'       => ['required', 'string', 'max:50', Rule::unique('karyawan', 'nip')->ignore($karyawan->id)],
            'jabatan'   => ['nullable', 'string', 'max:100'],
            'team_id'   => ['nullable', 'exists:teams,id'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
        ]);

        $karyawan->update([
            'nama'      => $request->nama,
            'nip'       => $request->nip,
            'jabatan'   => $request->jabatan,
            'team_id'   => $request->team_id,
            'phone'     => $request->phone,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        if ($karyawan->izinKeluars()->exists()) {
            return back()->with('error', 'Karyawan tidak dapat dihapus karena memiliki data izin.');
        }

        $karyawan->delete();

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Karyawan berhasil dihapus.');
    }
}
