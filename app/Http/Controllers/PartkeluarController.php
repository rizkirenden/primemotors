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
    public function update(Request $request, $id)
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

    // Ambil data part keluar yang akan diupdate
    $partKeluar = PartKeluar::findOrFail($id);

    // Update stok di tabel datasparepats (kembalikan stok lama dan kurangi stok baru)
    $sparepart = Datasparepat::where('kode_barang', $partKeluar->kode_barang)->first();
    if ($sparepart) {
        $sparepart->jumlah += $partKeluar->jumlah; // Kembalikan stok lama
        $sparepart->save();
    }

    // Update data part keluar
    $partKeluar->update($request->all());

    // Update stok di tabel datasparepats (kurangi stok baru)
    $sparepart = Datasparepat::where('kode_barang', $request->kode_barang)->first();
    if ($sparepart) {
        $sparepart->jumlah -= $request->jumlah; // Kurangi stok baru
        $sparepart->save();
    }

    return redirect()->route('partkeluar')->with('success', 'Data part keluar berhasil diupdate!');
}
}
