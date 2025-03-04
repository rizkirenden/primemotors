<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partkeluar;
use App\Models\Datasparepat;
use Barryvdh\DomPDF\Facade\Pdf;
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

    // Validasi jika stok 0 atau jumlah yang diminta melebihi stok yang tersedia
    if ($sparepart->jumlah == 0) {
        return redirect()->back()->with('error', 'Stok barang sudah habis!');
    }

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
    public function printPDF(Request $request)
{
    // Ambil parameter pencarian dan tanggal dari request
    $search = $request->input('search');
    $date = $request->input('date');

    // Query data mekanik berdasarkan pencarian dan tanggal
    $query = Partkeluar::query();

    if ($search) {
        $query->where('kode_barang', 'like', '%' . $search . '%')
              ->orWhere('nama_part', 'like', '%' . $search . '%')
              ->orWhere('stn', 'like', '%' . $search . '%')
              ->orWhere('merk', 'like', '%' . $search . '%')
              ->orWhere('jumlah', 'like', '%' . $search . '%')
              ->orWhere('tanggal_keluar', 'like', '%' . $search . '%')
              ->orWhere('tipe', 'like', '%' . $search . '%');
    }

    // Ambil data yang sudah difilter
    $partkeluars = $query->get();

    // Load view ke PDF
    $pdf = Pdf::loadView('printpdfpartkeluar', compact('partkeluars'));

    //return $pdf->stream('Data_Sparepat.pdf');

    // Download PDF
    return $pdf->download('Data_Partkeluar.pdf');


}
}
