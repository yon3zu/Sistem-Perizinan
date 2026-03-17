<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('team');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nip', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('team_id')) {
            $query->where('team_id', $request->team_id);
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $teams = Team::all();

        return view('admin.users.index', compact('users', 'teams'));
    }

    public function create()
    {
        $teams = Team::all();
        return view('admin.users.create', compact('teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'nip'      => ['required', 'string', 'max:50', 'unique:users,nip'],
            'role'     => ['required', Rule::in(['security', 'katim', 'admin'])],
            'team_id'  => ['nullable', 'exists:teams,id'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'is_active'=> ['boolean'],
        ]);

        User::create([
            'name'      => $request->name,
            'nip'       => $request->nip,
            'email'     => $request->nip . '@perizinan.local',
            'role'      => $request->role,
            'team_id'   => $request->team_id,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user  = User::findOrFail($id);
        $teams = Team::all();
        return view('admin.users.edit', compact('user', 'teams'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'nip'      => ['required', 'string', 'max:50', Rule::unique('users', 'nip')->ignore($user->id)],
            'role'     => ['required', Rule::in(['security', 'katim', 'admin'])],
            'team_id'  => ['nullable', 'exists:teams,id'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'is_active'=> ['boolean'],
        ]);

        $data = [
            'name'      => $request->name,
            'nip'       => $request->nip,
            'role'      => $request->role,
            'team_id'   => $request->team_id,
            'phone'     => $request->phone,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
