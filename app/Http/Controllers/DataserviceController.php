<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dataservice;
use App\Models\Datamekanik;
use App\Models\Datasparepat;
use App\Models\Partkeluar;

class DataserviceController extends Controller
{
    // Menampilkan semua data service
    public function index()
    {
        // Ambil semua data service dengan pagination
        $dataservices = Dataservice::paginate(10);

        // Ambil semua data mekanik
        $mekaniks = Datamekanik::all();
        $spareparts = Datasparepat::all();
        return view('dataservice', compact('dataservices', 'mekaniks','spareparts'));
    }

    // Menyimpan data service baru
    public function store(Request $request)
    {
        $request->validate([
            'no_spk' => 'required|unique:dataservices,no_spk',
            'tanggal' => 'required|date',
            'costumer' => 'required',
            'contact_person' => 'required',
            'masuk' => 'required|date',
            'keluar' => 'nullable|date',
            'no_polisi' => 'required',
            'tahun' => 'required|integer',
            'tipe_mobile' => 'required',
            'warna' => 'required',
            'no_rangka' => 'required',
            'no_mesin' => 'required',
            'keluhan_costumer' => 'required',
            'status' => 'required',
        ]);

        // Simpan data service
        $dataservice = Dataservice::create($request->all());


        return redirect()->route('dataservice')->with('success', 'Data service berhasil disimpan!');
    }
    // Mengupdate data service
    public function update(Request $request, $id)
    {
        $request->validate([
            'no_spk' => 'required|unique:dataservices,no_spk,' . $id,
            'tanggal' => 'required|date',
            'costumer' => 'required',
            'contact_person' => 'required',
            'masuk' => 'required|date',
            'keluar' => 'nullable|date',
            'no_polisi' => 'required',
            'nama_mekanik' => 'required',
            'tahun' => 'required|integer',
            'tipe_mobile' => 'required',
            'warna' => 'required',
            'no_rangka' => 'required',
            'no_mesin' => 'required',
            'keluhan_costumer' => 'required',
            'kode_barang' => 'nullable|exists:datasparepats,kode_barang',
            'nama_part' => 'nullable',
            'stn' => 'nullable',
            'merk' => 'nullable',
            'tipe' => 'nullable',
            'jumlah' => 'nullable|integer|min:0', // Ubah min menjadi 0 agar bisa kosong
            'tanggal_keluar' => 'nullable|date',
            'uraian_pekerjaan' => 'nullable',
            'uraian_jasa_perbaikan' => 'nullable',
            'status' => 'required',
        ]);

        // Ambil data service yang akan diupdate
        $dataservice = Dataservice::findOrFail($id);

        // Update data service
        $dataservice->update($request->all());

        // Jika kode_barang dan jumlah diisi, simpan data ke Partkeluar
        if ($request->kode_barang && $request->jumlah && $request->jumlah > 0) {
            $sparepart = Datasparepat::where('kode_barang', $request->kode_barang)->first();

            // Validasi jika stok 0 atau jumlah yang diminta melebihi stok yang tersedia
            if ($sparepart->jumlah == 0) {
                return redirect()->back()->with('error', 'Stok barang sudah habis!');
            }

            if ($sparepart->jumlah < $request->jumlah) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi!');
            }

            // Cek apakah data Partkeluar sudah ada untuk dataservice ini
            $partKeluar = Partkeluar::where('dataservice_id', $id)->first();

            if ($partKeluar) {
                // Jika sudah ada, update data Partkeluar
                $partKeluar->update([
                    'dataservice_id' => $id, // Hubungkan dengan dataservice
                    'kode_barang' => $request->kode_barang,
                    'nama_part' => $request->nama_part,
                    'stn' => $sparepart->stn,
                    'tipe' => $sparepart->tipe,
                    'merk' => $sparepart->merk,
                    'tanggal_keluar' => $request->tanggal_keluar,
                    'jumlah' => $request->jumlah,
                ]);
            } else {
                // Jika belum ada, buat data Partkeluar baru
                Partkeluar::create([
                    'dataservice_id' => $id, // Hubungkan dengan dataservice
                    'kode_barang' => $request->kode_barang,
                    'nama_part' => $request->nama_part,
                    'stn' => $sparepart->stn,
                    'tipe' => $sparepart->tipe,
                    'merk' => $sparepart->merk,
                    'tanggal_keluar' => $request->tanggal_keluar,
                    'jumlah' => $request->jumlah,
                ]);
            }
        }

        return redirect()->route('dataservice')->with('success', 'Data service berhasil diupdate!');
    }

    // Menghapus data service
    public function destroy($id)
    {
        $dataservice = Dataservice::findOrFail($id);

        // Kembalikan stok di tabel datasparepats
        $sparepart = Datasparepat::where('kode_barang', $dataservice->kode_barang)->first();
        if ($sparepart) {
            $sparepart->jumlah += $dataservice->jumlah; // Tambah stok kembali
            $sparepart->save();
        }

        // Hapus data part keluar
        $partKeluar = Partkeluar::where('kode_barang', $dataservice->kode_barang)->first();
        if ($partKeluar) {
            $partKeluar->delete();
        }

        // Hapus data service
        $dataservice->delete();

        return redirect()->route('dataservice')->with('success', 'Data service berhasil dihapus!');
    }
}
