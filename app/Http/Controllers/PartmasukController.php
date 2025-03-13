<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partmasuk; // Pastikan nama model benar
use App\Models\Datasparepat; // Pastikan nama model benar
use Barryvdh\DomPDF\Facade\Pdf;

class PartmasukController extends Controller
{
    // Menampilkan halaman part masuk
    public function index()
    {
        $partMasuks = Partmasuk::paginate(10); // Ambil semua data part masuk
        $spareparts = Datasparepat::all(); // Ambil semua data sparepart
        return view('partmasuk', compact('partMasuks', 'spareparts')); // Kirim data ke view
    }

    // Menyimpan data part masuk
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|exists:datasparepats,kode_barang', // Sesuaikan dengan nama tabel
            'nama_part' => 'required',
            'stn' => 'required',
            'tipe' => 'required',
            'merk' => 'required',
            'tanggal_masuk' => 'required|date',
            'jumlah' => 'required|integer|min:1',
        ]);

        // Simpan data part masuk
        $partMasuk = Partmasuk::create($request->all());

        // Update stok di tabel datasparepats
        $sparepart = Datasparepat::where('kode_barang', $request->kode_barang)->first();
        if ($sparepart) {
            $sparepart->jumlah += $request->jumlah; // Tambah stok
            $sparepart->save();
        } else {
            return redirect()->back()->with('error', 'Kode barang tidak ditemukan!');
        }

        return redirect()->route('partmasuk')->with('success', 'Data part masuk berhasil disimpan!');
    }

    // Method untuk mengambil data sparepart berdasarkan kode barang
    public function getSparepartByKode($kode_barang)
    {
        $sparepart = Datasparepat::where('kode_barang', $kode_barang)->first();

        if ($sparepart) {
            return response()->json([
                'nama_part' => $sparepart->nama_part,
                'stn' => $sparepart->stn,
                'tipe' => $sparepart->tipe,
                'merk' => $sparepart->merk,
            ]);
        } else {
            return response()->json(null);
        }
    }
    // Menghapus data part masuk
public function destroy($id)
{
    // Ambil data part masuk yang akan dihapus
    $partMasuk = Partmasuk::findOrFail($id);

    // Update stok di tabel datasparepats (kembalikan stok)
    $sparepart = Datasparepat::where('kode_barang', $partMasuk->kode_barang)->first();
    if ($sparepart) {
        $sparepart->jumlah -= $partMasuk->jumlah; // Kurangi stok
        $sparepart->save();
    }

    // Hapus data part masuk
    $partMasuk->delete();

    return redirect()->route('partmasuk')->with('success', 'Data part masuk berhasil dihapus!');
}
public function update(Request $request, $id)
{
    $request->validate([
        'kode_barang' => 'required|exists:datasparepats,kode_barang',
        'nama_part' => 'required',
        'stn' => 'required',
        'tipe' => 'required',
        'merk' => 'required',
        'tanggal_masuk' => 'required|date',
        'jumlah' => 'required|integer|min:1',
    ]);

    // Ambil data part masuk yang akan diupdate
    $partMasuk = Partmasuk::findOrFail($id);

    // Update stok di tabel datasparepats (kembalikan stok lama dan kurangi stok baru)
    $sparepart = Datasparepat::where('kode_barang', $partMasuk->kode_barang)->first();
    if ($sparepart) {
        $sparepart->jumlah -= $partMasuk->jumlah; // Kurangi stok lama
        $sparepart->save();
    }

    // Update data part masuk
    $partMasuk->update($request->all());

    // Update stok di tabel datasparepats (tambahkan stok baru)
    $sparepart = Datasparepat::where('kode_barang', $request->kode_barang)->first();
    if ($sparepart) {
        $sparepart->jumlah += $request->jumlah; // Tambah stok baru
        $sparepart->save();
    }

    return redirect()->route('partmasuk')->with('success', 'Data part masuk berhasil diupdate!');
}
public function printPDF(Request $request)
{
    // Ambil parameter dari request
    $search = $request->input('search');
    $dateStart = $request->input('date_start');
    $dateEnd = $request->input('date_end');

    // Query awal
    $query = Partmasuk::query();

    // Filter berdasarkan pencarian (search)
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('kode_barang', 'like', '%' . $search . '%')
              ->orWhere('nama_part', 'like', '%' . $search . '%')
              ->orWhere('stn', 'like', '%' . $search . '%')
              ->orWhere('merk', 'like', '%' . $search . '%')
              ->orWhere('tipe', 'like', '%' . $search . '%')
              ->orWhere('jumlah', 'like', '%' . $search . '%')
              ->orWhere('tanggal_masuk', 'like', '%' . $search . '%');
        });
    }

    // Filter berdasarkan rentang tanggal (date to date)
    if ($dateStart && $dateEnd) {
        $query->whereBetween('tanggal_masuk', [$dateStart, $dateEnd]);
    }

    // Ambil data yang sudah difilter
    $partmasuks = $query->get();

    // Load view ke PDF
    $pdf = Pdf::loadView('printpdfpartmasuk', compact('partmasuks'));

    // Download PDF
    return $pdf->download('Data_Partmasuk.pdf');
}
}
