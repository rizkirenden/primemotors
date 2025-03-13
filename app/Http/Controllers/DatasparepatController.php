<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datasparepat;
use Barryvdh\DomPDF\Facade\Pdf;
class DatasparepatController extends Controller
{
    public function index(){
        $sparepats = Datasparepat::paginate(10);
        return view('datasparepat', compact('sparepats'));
    }
    public function create(){
        return view('datasparepat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required',
            'nama_part' => 'required',
            'stn' => 'required',
            'tipe' => 'required',
            'merk' => 'required',
            'harga_toko' => 'required|numeric',
            'margin_persen' => 'required|numeric',
            'harga_jual' => 'sometimes|numeric',
            'jumlah' => 'sometimes|integer',
        ]);

        // Simpan data ke database
        Datasparepat::create($request->all());

        // Redirect setelah berhasil
        return redirect()->route('datasparepat');
    }


    public function edit($id)
    {
        $sparepat = Datasparepat::findOrFail($id);
        return view('sparepat.edit', compact('sparepats'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'kode_barang' => 'required',
            'nama_part' => 'required',
            'stn' => 'required',
            'tipe' => 'required',
            'merk' => 'required',
            'harga_toko' => 'required|numeric',
            'margin_persen' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'jumlah' => 'sometimes|integer',
        ]);
        $sparepat = Datasparepat::findOrFail($id);
        $sparepat->update($request->all());
        return redirect()->route('datasparepat');
    }

    public function destroy($id){
        $sparepat = Datasparepat::findOrFail($id);
        $sparepat->delete();
        return redirect()->route('datasparepat');
    }
    public function printPDF(Request $request)
{
    // Ambil parameter pencarian dan tanggal dari request
    $search = $request->input('search');
    $date = $request->input('date');

    // Query data mekanik berdasarkan pencarian dan tanggal
    $query = Datasparepat::query();

    if ($search) {
        $query->where('kode_barang', 'like', '%' . $search . '%')
              ->orWhere('nama_part', 'like', '%' . $search . '%')
              ->orWhere('stn', 'like', '%' . $search . '%')
              ->orWhere('merk', 'like', '%' . $search . '%')
              ->orWhere('jumlah', 'like', '%' . $search . '%')
              ->orWhere('harga_toko', 'like', '%' . $search . '%')
              ->orWhere('margin_persen', 'like', '%' . $search . '%')
              ->orWhere('harga_jual', 'like', '%' . $search . '%')
              ->orWhere('tipe', 'like', '%' . $search . '%');
    }

    // Ambil data yang sudah difilter
    $sparepats = $query->get();

    // Load view ke PDF
    $pdf = Pdf::loadView('printpdfdatasparepat', compact('sparepats'));

    return $pdf->stream('Data_Sparepat.pdf');
}
}
