<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function index()
    {
        $penggunas = Pengguna::all();
        return view('pengguna', compact('penggunas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:penggunas,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:superadmin,admin,bengkel,owner',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        Pengguna::create($validated);

        return redirect()->route('pengguna')->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function update(Request $request, Pengguna $pengguna)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:penggunas,email,'.$pengguna->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:superadmin,admin,bengkel,owner',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $pengguna->update($validated);

        return redirect()->route('pengguna')->with('success', 'Pengguna berhasil diperbarui');
    }

    public function destroy(Pengguna $pengguna)
    {
        $pengguna->delete();
        return redirect()->route('pengguna')->with('success', 'Pengguna berhasil dihapus');
    }
}
