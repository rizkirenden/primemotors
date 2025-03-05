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
        return view('dataservice', compact('dataservices', 'mekaniks','sparepart'));
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
            'nama_mekanik' => 'required', // Pastikan nama_mekanik diisi
            'tahun' => 'required|integer',
            'tipe' => 'required',
            'warna' => 'required',
            'no_rangka' => 'required',
            'no_mesin' => 'required',
            'keluhan_costumer' => 'required',
            'kode_barang' => 'nullable|exists:datasparepats,kode_barang',
            'nama_part' => 'nullable',
            'tanggal_keluar' => 'nullable|date',
            'jumlah' => 'nullable|integer|min:1',
            'uraian_pekerjaan' => 'nullable',
            'uraian_jasa_perbaikan' => 'nullable',
            'status' => 'required',
        ]);

        // Simpan data service
        $dataservice = Dataservice::create($request->all());

        // Jika kode_barang dan jumlah diisi, maka kurangi stok
        if ($request->kode_barang && $request->jumlah) {
            $sparepart = Datasparepat::where('kode_barang', $request->kode_barang)->first();

            // Validasi jika stok 0 atau jumlah yang diminta melebihi stok yang tersedia
            if ($sparepart->jumlah == 0) {
                return redirect()->back()->with('error', 'Stok barang sudah habis!');
            }

            if ($sparepart->jumlah < $request->jumlah) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi!');
            }

            // Simpan data part keluar
            $partKeluar = Partkeluar::create([
                'kode_barang' => $request->kode_barang,
                'nama_part' => $request->nama_part,
                'stn' => $sparepart->stn,
                'tipe' => $sparepart->tipe,
                'merk' => $sparepart->merk,
                'tanggal_keluar' => $request->tanggal_keluar,
                'jumlah' => $request->jumlah,
            ]);

            // Kurangi stok di tabel datasparepats
            $sparepart->jumlah -= $request->jumlah;
            $sparepart->save();
        }

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
            'nama_mekanik' => 'required', // Pastikan nama_mekanik diisi
            'tahun' => 'required|integer',
            'tipe' => 'required',
            'warna' => 'required',
            'no_rangka' => 'required',
            'no_mesin' => 'required',
            'keluhan_costumer' => 'required',
            'kode_barang' => 'nullable|exists:datasparepats,kode_barang',
            'nama_part' => 'nullable',
            'tanggal_keluar' => 'nullable|date',
            'jumlah' => 'nullable|integer|min:1',
            'uraian_pekerjaan' => 'nullable',
            'uraian_jasa_perbaikan' => 'nullable',
            'status' => 'required',
        ]);

        // Ambil data service yang akan diupdate
        $dataservice = Dataservice::findOrFail($id);

        // Update data service
        $dataservice->update($request->all());

        // Jika kode_barang dan jumlah diisi, maka update part keluar
        if ($request->kode_barang && $request->jumlah) {
            $sparepart = Datasparepat::where('kode_barang', $request->kode_barang)->first();

            // Validasi jika stok 0 atau jumlah yang diminta melebihi stok yang tersedia
            if ($sparepart->jumlah == 0) {
                return redirect()->back()->with('error', 'Stok barang sudah habis!');
            }

            if ($sparepart->jumlah < $request->jumlah) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi!');
            }

            // Update atau buat data part keluar
            $partKeluar = Partkeluar::where('kode_barang', $request->kode_barang)->first();
            if ($partKeluar) {
                $partKeluar->update([
                    'nama_part' => $request->nama_part,
                    'stn' => $sparepart->stn,
                    'tipe' => $sparepart->tipe,
                    'merk' => $sparepart->merk,
                    'tanggal_keluar' => $request->tanggal_keluar,
                    'jumlah' => $request->jumlah,
                ]);
            } else {
                $partKeluar = Partkeluar::create([
                    'kode_barang' => $request->kode_barang,
                    'nama_part' => $request->nama_part,
                    'stn' => $sparepart->stn,
                    'tipe' => $sparepart->tipe,
                    'merk' => $sparepart->merk,
                    'tanggal_keluar' => $request->tanggal_keluar,
                    'jumlah' => $request->jumlah,
                ]);
            }

            // Kurangi stok di tabel datasparepats
            $sparepart->jumlah -= $request->jumlah;
            $sparepart->save();
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
