<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
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
        $jualparts = Jualpart::with('items')->paginate(10);
        return view('jualpart', compact('jualparts', 'spareparts'));
    }

    public function getSparepart($kode_barang)
    {
        $sparepart = Datasparepat::where('kode_barang', $kode_barang)->firstOrFail();
        return response()->json($sparepart);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
        'tanggal_pembayaran' => 'required|date',
        'metode_pembayaran' => 'required',
        'nama_pelanggan' => 'required',
        'alamat_pelanggan' => 'required',
        'nomor_pelanggan' => 'required',
        'items' => 'required|array|min:1',
        'items.*.kode_barang' => 'required',
        'items.*.tanggal_keluar' => 'required|date',
        'items.*.jumlah' => 'required|integer|min:1',
        'items.*.discount' => 'required|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();

        try {
            $jualpart = Jualpart::create([
                'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'nama_pelanggan' => $validated['nama_pelanggan'],
                'alamat_pelanggan' => $validated['alamat_pelanggan'],
                'nomor_pelanggan' => $validated['nomor_pelanggan'],
                'total_transaksi' => 0
            ]);

            $totalTransaksi = 0;

            foreach ($validated['items'] as $item) {
                $sparepart = Datasparepat::where('kode_barang', $item['kode_barang'])->firstOrFail();

                if ($sparepart->jumlah < $item['jumlah']) {
                    throw new \Exception("Insufficient stock for {$sparepart->nama_part}");
                }

                $hargaJual = $sparepart->harga_jual;
                $totalHarga = $hargaJual * $item['jumlah'];
                $discountAmount = ($totalHarga * $item['discount']) / 100;
                $totalHargaAfterDiscount = $totalHarga - $discountAmount;

                $jualpart->items()->create([
                    'kode_barang' => $sparepart->kode_barang,
                    'nama_part' => $sparepart->nama_part,
                    'stn' => $sparepart->stn,
                    'tipe' => $sparepart->tipe,
                    'merk' => $sparepart->merk,
                    'tanggal_keluar' => $item['tanggal_keluar'],
                    'jumlah' => $item['jumlah'],
                    'harga_toko' => $sparepart->harga_toko,
                    'margin_persen' => $sparepart->margin_persen,
                    'harga_jual' => $hargaJual,
                    'discount' => $item['discount'],
                    'total_harga_part' => $totalHargaAfterDiscount
                ]);

                Partkeluar::create([
                    'jualpart_id' => $jualpart->id,
                    'kode_barang' => $sparepart->kode_barang,
                    'nama_part' => $sparepart->nama_part,
                    'stn' => $sparepart->stn,
                    'tipe' => $sparepart->tipe,
                    'merk' => $sparepart->merk,
                    'tanggal_keluar' => $item['tanggal_keluar'],
                    'jumlah' => $item['jumlah'],
                ]);

                $sparepart->jumlah -= $item['jumlah'];
                $sparepart->save();

                $totalTransaksi += $totalHargaAfterDiscount;
            }

            $jualpart->update(['total_transaksi' => $totalTransaksi]);
            DB::commit();

            return redirect()->route('jualpart')->with('success', 'Data penjualan berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $jualpart = Jualpart::with('items')->findOrFail($id);
        return response()->json($jualpart);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
             'tanggal_pembayaran' => 'required|date',
        'metode_pembayaran' => 'required',
        'nama_pelanggan' => 'required',
        'alamat_pelanggan' => 'required',
        'nomor_pelanggan' => 'required',
        'items' => 'required|array|min:1',
        'items.*.kode_barang' => 'required',
        'items.*.tanggal_keluar' => 'required|date',
        'items.*.jumlah' => 'required|integer|min:1',
        'items.*.discount' => 'required|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();

        try {
            $jualpart = Jualpart::with('items')->findOrFail($id);
            $jualpart->update([
                'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'nama_pelanggan' => $validated['nama_pelanggan'],
                'alamat_pelanggan' => $validated['alamat_pelanggan'],
                'nomor_pelanggan' => $validated['nomor_pelanggan'],
            ]);

            $totalTransaksi = 0;
            $existingItemIds = [];

            foreach ($validated['items'] as $item) {
                $sparepart = Datasparepat::where('kode_barang', $item['kode_barang'])->firstOrFail();

                if (isset($item['id'])) {
                    $existingItem = $jualpart->items()->findOrFail($item['id']);
                    $quantityDiff = $item['jumlah'] - $existingItem->jumlah;

                    if ($quantityDiff > 0 && $sparepart->jumlah < $quantityDiff) {
                        throw new \Exception("Insufficient stock for {$sparepart->nama_part}");
                    }

                    $sparepart->jumlah -= $quantityDiff;
                    $sparepart->save();

                    $hargaJual = $sparepart->harga_jual;
                    $totalHarga = $hargaJual * $item['jumlah'];
                    $discountAmount = ($totalHarga * $item['discount']) / 100;
                    $totalHargaAfterDiscount = $totalHarga - $discountAmount;

                    $existingItem->update([
                        'tanggal_keluar' => $item['tanggal_keluar'],
                        'jumlah' => $item['jumlah'],
                        'discount' => $item['discount'],
                        'total_harga_part' => $totalHargaAfterDiscount
                    ]);

                    Partkeluar::where('jualpart_id', $jualpart->id)
                        ->where('kode_barang', $sparepart->kode_barang)
                        ->update([
                            'tanggal_keluar' => $item['tanggal_keluar'],
                            'jumlah' => $item['jumlah'],
                        ]);

                    $existingItemIds[] = $existingItem->id;
                    $totalTransaksi += $totalHargaAfterDiscount;
                } else {
                    if ($sparepart->jumlah < $item['jumlah']) {
                        throw new \Exception("Insufficient stock for {$sparepart->nama_part}");
                    }

                    $hargaJual = $sparepart->harga_jual;
                    $totalHarga = $hargaJual * $item['jumlah'];
                    $discountAmount = ($totalHarga * $item['discount']) / 100;
                    $totalHargaAfterDiscount = $totalHarga - $discountAmount;

                    $newItem = $jualpart->items()->create([
                        'kode_barang' => $sparepart->kode_barang,
                        'nama_part' => $sparepart->nama_part,
                        'stn' => $sparepart->stn,
                        'tipe' => $sparepart->tipe,
                        'merk' => $sparepart->merk,
                        'tanggal_keluar' => $item['tanggal_keluar'],
                        'jumlah' => $item['jumlah'],
                        'harga_toko' => $sparepart->harga_toko,
                        'margin_persen' => $sparepart->margin_persen,
                        'harga_jual' => $hargaJual,
                        'discount' => $item['discount'],
                        'total_harga_part' => $totalHargaAfterDiscount
                    ]);

                    Partkeluar::create([
                        'jualpart_id' => $jualpart->id,
                        'kode_barang' => $sparepart->kode_barang,
                        'nama_part' => $sparepart->nama_part,
                        'stn' => $sparepart->stn,
                        'tipe' => $sparepart->tipe,
                        'merk' => $sparepart->merk,
                        'tanggal_keluar' => $item['tanggal_keluar'],
                        'jumlah' => $item['jumlah'],
                    ]);

                    $sparepart->jumlah -= $item['jumlah'];
                    $sparepart->save();

                    $existingItemIds[] = $newItem->id;
                    $totalTransaksi += $totalHargaAfterDiscount;
                }
            }

            // Delete removed items
            $itemsToDelete = $jualpart->items()->whereNotIn('id', $existingItemIds)->get();
            foreach ($itemsToDelete as $item) {
                $sparepart = Datasparepat::where('kode_barang', $item->kode_barang)->first();
                if ($sparepart) {
                    $sparepart->jumlah += $item->jumlah;
                    $sparepart->save();
                }

                Partkeluar::where('jualpart_id', $jualpart->id)
                    ->where('kode_barang', $item->kode_barang)
                    ->delete();

                $item->delete();
            }

            $jualpart->update(['total_transaksi' => $totalTransaksi]);
            DB::commit();

            return redirect()->route('jualpart')->with('success', 'Data penjualan berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $jualpart = Jualpart::with('items')->findOrFail($id);

            foreach ($jualpart->items as $item) {
                $sparepart = Datasparepat::where('kode_barang', $item->kode_barang)->first();
                if ($sparepart) {
                    $sparepart->jumlah += $item->jumlah;
                    $sparepart->save();
                }
            }

            Partkeluar::where('jualpart_id', $id)->delete();
            $jualpart->items()->delete();
            $jualpart->delete();

            DB::commit();
            return redirect()->route('jualpart')->with('success', 'Data penjualan berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function printPDF(Request $request)
    {
        $query = Jualpart::with('items');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('invoice_number', 'like', '%'.$request->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->date_start && $request->date_end) {
            $query->whereHas('items', function($q) use ($request) {
                $q->whereBetween('tanggal_keluar', [$request->date_start, $request->date_end]);
            });
        }

        $jualparts = $query->get();
        $pdf = Pdf::loadView('printpdfjualpart', compact('jualparts'));
        return $pdf->download('Jual_part_'.now()->format('YmdHis').'.pdf');
    }

    public function printPDFPerData($id)
    {
        $jualpart = Jualpart::with('items')->findOrFail($id);
        $pdf = Pdf::loadView('printpdfjualpartperdata', compact('jualpart'));
        return $pdf->download('Data_JualPart_'.$id.'.pdf');
    }
}
