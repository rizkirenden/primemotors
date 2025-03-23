<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jualpart;
use App\Models\Datasparepat;
use App\Models\Partkeluar;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Auth\Events\Validated;

class JualpartController extends Controller
{
    public function index()
    {
        $spareparts = Datasparepat::all();

        $jualparts = Jualpart::paginate(10);
        return view ('jualpart', compact('jualparts','spareparts'));
    }

public function getSparepart($kode_barang)
{
    $sparepart = Datasparepat::where('kode_barang', $kode_barang)->first();

    if ($sparepart) {
        return response()->json($sparepart);
    } else {
        return response()->json(['error' => 'Sparepart not found'], 404);
    }
}
    public function store(Request $request){
        $request->Validate([
            'kode_barang' => 'required',
            'nama_part' => 'required',
            'stn' => 'required',
            'tipe' => 'required',
            'merk' => 'required',
            'tanggal_keluar' => 'required',
            'tanggal_pembayaran' => 'required',
            'jumlah' => 'required|integer|min:1',
            'harga_toko' => 'required|numeric',
            'margin_persen' => 'required|numeric',
            'harga_jual' => 'sometimes|numeric',
            'discount' => 'required',
            'total_harga_part' => 'required',
            'status' => 'required',
            'metode_pembayaran' => 'required',
            'nama_pelanggan' => 'required',
            'alamat_pelanggan' => 'required',
            'nomor_pelanggan' => 'required',
        ]);


        return redirect()->route('jualpart');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_barang' => 'required',
            'nama_part' => 'required',
            'stn' => 'required',
            'tipe' => 'required',
            'merk' => 'required',
            'tanggal_keluar' => 'required',
            'jumlah' => 'required',
            'harga_toko' => 'required',
            'harga_jual' => 'required',
            'margin_persen' => 'required',
            'discount' => 'required',
            'total_harga_part' => 'required',
        ]);
        $jualpart = Jualpart::findOrFail($id);

        return redirect()->route('jualpart');
    }
    public function destroy($id)
    {
        // Find the mekanik by ID
        $jualpart = Jualpart::findOrFail($id);

        // Delete the mekanik record
        $jualpart->delete();

        // Redirect back to the index route after deleting
        return redirect()->route('jualpart');
    }
}
