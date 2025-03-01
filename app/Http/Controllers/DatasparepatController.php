<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datasparepat;
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
        'harga_jual' => 'required|numeric',
        'jumlah' => 'sometimes|integer', // Opsional
    ]);

    Datasparepat::create($request->all());
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
}
