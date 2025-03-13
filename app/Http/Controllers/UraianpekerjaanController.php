<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UraianPekerjaan;

class UraianpekerjaanController extends Controller
{
    public function index()
    {
        $uraianPekerjaans = UraianPekerjaan::paginate(10); // Ambil semua data dari database
        return view('uraianpekerjaan', compact('uraianPekerjaans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_pekerjaan' => 'required|string',
            'jenis_mobil' => 'required|string',
            'waktu_pengerjaan' => 'required|integer',
            'ongkos_pengerjaan' => 'required|string',
        ]);

        // Hilangkan "RP" dari ongkos_pengerjaan sebelum disimpan
        $request->merge([
            'ongkos_pengerjaan' => str_replace(['RP ', '.', ','], '', $request->ongkos_pengerjaan),
        ]);

        // Simpan data menggunakan mass assignment
        UraianPekerjaan::create($request->all());

        return redirect()->route('uraianpekerjaan')->with('success', 'Data berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_pekerjaan' => 'required|string',
            'jenis_mobil' => 'required|string',
            'waktu_pengerjaan' => 'required|integer',
            'ongkos_pengerjaan' => 'required|string', // Ubah ke string karena ada format "RP"
        ]);

        // Hilangkan "RP" dari ongkos_pengerjaan sebelum disimpan
        $request->merge([
            'ongkos_pengerjaan' => str_replace(['RP ', '.', ','], '', $request->ongkos_pengerjaan),
        ]);

        // Update data menggunakan mass assignment
        $uraianPekerjaan = UraianPekerjaan::findOrFail($id);
        $uraianPekerjaan->update($request->all());

        return redirect()->route('uraianpekerjaan')->with('success', 'Data berhasil diupdate!');
    }

    public function destroy($id)
    {
        $uraianPekerjaan = UraianPekerjaan::findOrFail($id);
        $uraianPekerjaan->delete();

        return redirect()->route('uraianpekerjaan')->with('success', 'Data berhasil dihapus!');
    }
}
