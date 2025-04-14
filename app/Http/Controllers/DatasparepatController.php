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

    public function destroy($id)
    {
        try {
            $sparepat = Datasparepat::findOrFail($id);
            $sparepat->delete();

            return redirect()->route('datasparepat')
                   ->with('success', 'Data sparepart berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('datasparepat')
                   ->with('error', 'Gagal menghapus data sparepart: ' . $e->getMessage());
        }
    }
    public function printPDF(Request $request)
{
    $search = $request->input('search');
    $date = $request->input('date');

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

    $sparepats = $query->get();

    $pdf = Pdf::loadView('printpdfdatasparepat', compact('sparepats'))
                ->setPaper('a4', 'landscape');

    return $pdf->download('Data_Sparepat.pdf');
}

}
