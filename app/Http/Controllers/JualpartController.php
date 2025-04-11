<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jualpart;
use App\Models\Datasparepat;
use App\Models\Partkeluar;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $sparepart = Datasparepat::where('kode_barang', $request->kode_barang)->first();
        if (!$sparepart) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan!');
        }

        if ($sparepart->jumlah < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi!');
        }

        $jualpart = Jualpart::create($request->all());

        Partkeluar::create([
            'jualpart_id' => $jualpart->id,
            'kode_barang' => $request->kode_barang,
            'nama_part' => $request->nama_part,
            'stn' => $request->stn,
            'tipe' => $request->tipe,
            'merk' => $request->merk,
            'tanggal_keluar' => $request->tanggal_keluar,
            'jumlah' => $request->jumlah,
        ]);

        $sparepart->jumlah -= $request->jumlah;
        $sparepart->save();

        return redirect()->route('jualpart')->with('success', 'Data penjualan berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
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

        if ($request->kode_barang != $old_kode_barang || $request->jumlah != $old_jumlah) {
            $old_sparepart = Datasparepat::where('kode_barang', $old_kode_barang)->first();
            if ($old_sparepart) {
                $old_sparepart->jumlah += $old_jumlah;
                $old_sparepart->save();
            }

            $new_sparepart = Datasparepat::where('kode_barang', $request->kode_barang)->first();
            if (!$new_sparepart) {
                return redirect()->back()->with('error', 'Barang tidak ditemukan!');
            }

            if ($new_sparepart->jumlah < $request->jumlah) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi!');
            }

            $new_sparepart->jumlah -= $request->jumlah;
            $new_sparepart->save();
        }

        $jualpart->update($request->all());

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
            ]
        );

        return redirect()->route('jualpart')->with('success', 'Data penjualan berhasil diupdate!');
    }

    public function destroy($id)
    {
        $jualpart = Jualpart::findOrFail($id);

        $sparepart = Datasparepat::where('kode_barang', $jualpart->kode_barang)->first();
        if ($sparepart) {
            $sparepart->jumlah += $jualpart->jumlah;
            $sparepart->save();
        }

        Partkeluar::where('jualpart_id', $id)->delete();
        $jualpart->delete();

        return redirect()->route('jualpart')->with('success', 'Data penjualan berhasil dihapus!');
    }

    public function printPDF(Request $request)
    {
        $search = $request->input('search');
        $dateStart = $request->input('date_start');
        $dateEnd = $request->input('date_end');

        $query = Jualpart::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', '%' . $search . '%')
                    ->orWhere('nama_part', 'like', '%' . $search . '%')
                    ->orWhere('stn', 'like', '%' . $search . '%')
                    ->orWhere('tipe', 'like', '%' . $search . '%')
                    ->orWhere('nama_pelanggan', 'like', '%' . $search . '%');
            });
        }

        if ($dateStart && $dateEnd) {
            $query->whereBetween('tanggal_keluar', [$dateStart, $dateEnd]);
        }

        $jualparts = $query->with('jualpart')->get();

        $pdf = Pdf::loadView('printpdfdataspkakhir', compact('dataservices'));

        return $pdf->download('Jual_part.pdf');
    }

    public function printPDFPerData($id)
    {
        $jualpart = Jualpart::findOrFail($id);
        $pdf = Pdf::loadView('printpdfjualpart', compact('jualpart'));
        return $pdf->download('Data_JualPart.pdf');
    }
}
