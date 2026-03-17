<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::withCount('users')->latest()->paginate(15);
        return view('admin.teams.index', compact('teams'));
    }

    public function create()
    {
        return view('admin.teams.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:teams,nama'],
        ]);

        Team::create(['nama' => $request->nama]);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Tim berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $team = Team::findOrFail($id);
        return view('admin.teams.edit', compact('team'));
    }

    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        $request->validate([
            'nama' => ['required', 'string', 'max:100', "unique:teams,nama,{$id}"],
        ]);

        $team->update(['nama' => $request->nama]);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Tim berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();

        return redirect()->route('admin.teams.index')
            ->with('success', 'Tim berhasil dihapus.');
    }
}
