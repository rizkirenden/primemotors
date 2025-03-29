<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jualpart;
use App\Models\Datasparepat;
use App\Models\Partkeluar;

class JualpartController extends Controller
{
    public function index()
    {
        $spareparts = Datasparepat::all();
        $jualparts = Jualpart::paginate(10);
        return view('jualpart', compact('jualparts', 'spareparts'));
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

    public function store(Request $request)
    {
        // Bersihkan nilai harga dari format Rupiah
        $request->merge([
            'harga_toko' => preg_replace('/[^0-9]/', '', $request->harga_toko),
            'harga_jual' => preg_replace('/[^0-9]/', '', $request->harga_jual),
            'margin_persen' => preg_replace('/[^0-9.]/', '', $request->margin_persen),
            'total_harga_part' => preg_replace('/[^0-9]/', '', $request->total_harga_part)
        ]);

        $request->validate([
            'kode_barang' => 'required',
            'nama_part' => 'required',
            'stn' => 'required',
            'tipe' => 'required',
            'merk' => 'required',
            'tanggal_keluar' => 'required|date',
            'tanggal_pembayaran' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'harga_toko' => 'required|numeric',
            'margin_persen' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'discount' => 'required|numeric',
            'total_harga_part' => 'required|numeric',
            'metode_pembayaran' => 'required',
            'nama_pelanggan' => 'required',
            'alamat_pelanggan' => 'required',
            'nomor_pelanggan' => 'required',
        ]);

        // Cek stok tersedia
        $sparepart = Datasparepat::where('kode_barang', $request->kode_barang)->first();
        if (!$sparepart) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan!');
        }

        if ($sparepart->jumlah < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi!');
        }

        // Simpan data ke Jualpart
        $jualpart = Jualpart::create($request->all());

        // Simpan data minimal ke Partkeluar
        Partkeluar::create([
            'jualpart_id' => $jualpart->id,
            'kode_barang' => $request->kode_barang,
            'nama_part' => $request->nama_part,
            'stn' => $request->stn,
            'tipe' => $request->tipe,
            'merk' => $request->merk,
            'tanggal_keluar' => $request->tanggal_keluar,
            'jumlah' => $request->jumlah,
            // Field lainnya tidak disimpan ke partkeluar
        ]);

        // Kurangi stok di Datasparepat
        $sparepart->save();

        return redirect()->route('jualpart')->with('success', 'Data penjualan berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        // Bersihkan nilai harga dari format Rupiah
        $request->merge([
            'harga_toko' => preg_replace('/[^0-9]/', '', $request->harga_toko),
            'harga_jual' => preg_replace('/[^0-9]/', '', $request->harga_jual),
            'margin_persen' => preg_replace('/[^0-9.]/', '', $request->margin_persen),
            'total_harga_part' => preg_replace('/[^0-9]/', '', $request->total_harga_part)
        ]);

        $request->validate([
            'kode_barang' => 'required',
            'nama_part' => 'required',
            'stn' => 'required',
            'tipe' => 'required',
            'merk' => 'required',
            'tanggal_keluar' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'harga_toko' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'margin_persen' => 'required|numeric',
            'discount' => 'required|numeric',
            'total_harga_part' => 'required|numeric',
            'metode_pembayaran' => 'required',
            'nama_pelanggan' => 'required',
            'alamat_pelanggan' => 'required',
            'nomor_pelanggan' => 'required',
        ]);

        $jualpart = Jualpart::findOrFail($id);
        $old_kode_barang = $jualpart->kode_barang;
        $old_jumlah = $jualpart->jumlah;

        // Cek stok tersedia (hanya jika kode barang atau jumlah berubah)
        if ($request->kode_barang != $old_kode_barang || $request->jumlah != $old_jumlah) {
            $sparepart = Datasparepat::where('kode_barang', $request->kode_barang)->first();
            if (!$sparepart) {
                return redirect()->back()->with('error', 'Barang tidak ditemukan!');
            }

            // Kembalikan stok lama
            $old_sparepart = Datasparepat::where('kode_barang', $old_kode_barang)->first();
            if ($old_sparepart) {
                $old_sparepart->jumlah += $old_jumlah;
                $old_sparepart->save();
            }

            // Cek stok baru
            if ($sparepart->jumlah < $request->jumlah) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi!');
            }

            // Kurangi stok baru
            $sparepart->jumlah -= $request->jumlah;
            $sparepart->save();
        }

        // Update data di Jualpart
        $jualpart->update($request->all());

        // Update data minimal di Partkeluar
        Partkeluar::updateOrCreate(
            ['jualpart_id' => $id],
            [
                'kode_barang' => $request->kode_barang,
                'nama_part' => $request->nama_part,
                'stn' => $request->stn,
                'tipe' => $request->tipe,
                'merk' => $request->merk,
                'tanggal_keluar' => $request->tanggal_keluar,
                'jumlah' => $request->jumlah,
                // Field lainnya tidak diupdate ke partkeluar
            ]
        );

        return redirect()->route('jualpart')->with('success', 'Data penjualan berhasil diupdate!');
    }

    public function destroy($id)
    {
        $jualpart = Jualpart::findOrFail($id);

        // Kembalikan stok
        $sparepart = Datasparepat::where('kode_barang', $jualpart->kode_barang)->first();
        if ($sparepart) {
            $sparepart->jumlah += $jualpart->jumlah;
            $sparepart->save();
        }

        // Hapus data di Partkeluar
        Partkeluar::where('jualpart_id', $id)->delete();

        // Hapus data Jualpart
        $jualpart->delete();

        return redirect()->route('jualpart')->with('success', 'Data penjualan berhasil dihapus!');
    }
}
