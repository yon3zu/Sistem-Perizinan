<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('profil.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'     => 'required|string|max:100',
            'phone'    => 'nullable|string|max:20',
            'foto'     => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required'        => 'Nama wajib diisi.',
            'foto.image'           => 'File harus berupa gambar.',
            'foto.mimes'           => 'Format foto: jpeg, jpg, png, webp.',
            'foto.max'             => 'Ukuran foto maksimal 2MB.',
            'password.min'         => 'Password minimal 6 karakter.',
            'password.confirmed'   => 'Konfirmasi password tidak cocok.',
        ]);

        $data = [
            'name'  => $request->name,
            'phone' => $request->phone,
        ];

        // Handle foto upload
        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $path = $request->file('foto')->store('foto-profil', 'public');
            $data['foto'] = $path;
        }

        // Handle password change
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('profil.index')->with('success', 'Profil berhasil diperbarui!');
    }

    public function hapusFoto()
    {
        $user = auth()->user();
        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
            $user->update(['foto' => null]);
        }
        return redirect()->route('profil.index')->with('success', 'Foto profil berhasil dihapus.');
    }
}
