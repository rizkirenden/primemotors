<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partkeluar;
use App\Models\Datasparepat;

class PartkeluarController extends Controller
{
    public function index()
    {
        $partKeluars = PartKeluar::paginate(10); // Ambil semua data part keluar
        $spareparts = Datasparepat::all();
        return view('partkeluar', compact('partKeluars','spareparts')); // Kirim data ke view
    }

    // Menyimpan data part keluar
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|exists:datasparepats,kode_barang',
            'nama_part' => 'required',
            'stn' => 'required',
            'tipe' => 'required',
            'merk' => 'required',
            'tanggal_keluar' => 'required|date',
            'jumlah' => 'required|integer|min:1',
        ]);

        // Cek stok tersedia
        $sparepart = Datasparepat::where('kode_barang', $request->kode_barang)->first();
        if ($sparepart->jumlah < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi!');
        }

        // Simpan data part keluar
        $partKeluar = PartKeluar::create($request->all());

        // Kurangi stok di tabel datasparepats
        $sparepart->jumlah -= $request->jumlah;
        $sparepart->save();

        return redirect()->route('partkeluar')->with('success', 'Data part keluar berhasil disimpan!');
    }

    // Menghapus data part keluar
    public function destroy($id)
    {
        $partKeluar = PartKeluar::findOrFail($id);

        // Kembalikan stok di tabel datasparepats
        $sparepart = Datasparepat::where('kode_barang', $partKeluar->kode_barang)->first();
        if ($sparepart) {
            $sparepart->jumlah += $partKeluar->jumlah; // Tambah stok kembali
            $sparepart->save();
        }

        // Hapus data part keluar
        $partKeluar->delete();

        return redirect()->route('partkeluar')->with('success', 'Data part keluar berhasil dihapus!');
    }
}
