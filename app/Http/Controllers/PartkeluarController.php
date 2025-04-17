<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Partkeluar;
use App\Models\Datasparepat;
use App\Models\JualpartItem;
use App\Models\Jualpart;
use Barryvdh\DomPDF\Facade\Pdf;
class PartkeluarController extends Controller
{
    public function index()
    {

        $partKeluars = PartKeluar::with(['jualpart', 'dataservice', 'datasparepat'])
                            ->orderBy('tanggal_keluar', 'desc')
                            ->paginate(10);

        $spareparts = Datasparepat::all();

        return view('partkeluar', compact('partKeluars', 'spareparts'));
    }

    // Menyimpan data part keluar
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|exists:datasparepats,kode_barang',
            'nama_part' => 'required',
            'stn' => 'required',
            'tipe' => 'required',
            'merk' => 'required',
            'tanggal_keluar' => 'required|date',
            'jumlah' => 'required|integer|min:1',
        ]);

        // Cek stok tersedia
        $sparepart = Datasparepat::where('kode_barang', $request->kode_barang)->first();

        // Validasi jika stok 0 atau jumlah yang diminta melebihi stok yang tersedia
        if ($sparepart->jumlah == 0) {
            return redirect()->back()->with('error', 'Stok barang sudah habis!');
        }

        if ($sparepart->jumlah < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi!');
        }

        // Simpan data part keluar dengan status pending
        $partKeluar = PartKeluar::create([
            ...$request->all(),
            'status' => 'pending', // Default status
        ]);

        return redirect()->route('partkeluar')->with('success', 'Data part keluar berhasil disimpan!');
    }

   // Menghapus data part keluar
public function destroy($id)
{
    $partKeluar = PartKeluar::findOrFail($id);

    // Kembalikan stok hanya jika status approved
    if ($partKeluar->status === 'approved') {
        $sparepart = Datasparepat::where('kode_barang', $partKeluar->kode_barang)->first();
        if ($sparepart) {
            $sparepart->jumlah += $partKeluar->jumlah;
            $sparepart->save();
        }
    }

    // Hapus juga jika ada relasi dengan jualpart
    if ($partKeluar->jualpart_id) {
        $jualpartItem = JualpartItem::where('kode_barang', $partKeluar->kode_barang)
                            ->where('jualpart_id', $partKeluar->jualpart_id)
                            ->first();

        if ($jualpartItem) {
            $jualpartItem->delete();

            $jualpart = Jualpart::find($partKeluar->jualpart_id);
            if ($jualpart) {
                $jualpart->total_transaksi = $jualpart->items()->sum('total_harga_part');
                $jualpart->save();
            }
        }
    }

    $partKeluar->delete();

    return redirect()->route('partkeluar')->with('success', 'Data part keluar berhasil dihapus dan stok telah dikembalikan jika diperlukan.');
}


public function update(Request $request, $id)
{
    $request->validate([
        'kode_barang' => 'required|exists:datasparepats,kode_barang',
        'nama_part' => 'required',
        'stn' => 'required',
        'tipe' => 'required',
        'merk' => 'required',
        'tanggal_keluar' => 'required|date',
        'jumlah' => 'required|integer|min:1',
    ]);

    DB::beginTransaction();

    try {
        // Ambil data part keluar
        $partKeluar = PartKeluar::findOrFail($id);

        // Kembalikan stok lama
        $sparepart = Datasparepat::where('kode_barang', $partKeluar->kode_barang)->first();
        if ($sparepart) {
            $sparepart->jumlah += $partKeluar->jumlah;
            $sparepart->save();
        }

        // Cek stok cukup
        if ($sparepart->jumlah < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi!');
        }

        // Update data part keluar
        $partKeluar->update($request->all());

        // Kurangi stok sesuai jumlah baru
        $sparepart->jumlah -= $request->jumlah;
        $sparepart->save();

        // Jika ada relasi jualpart, update juga jualpart_items
        if ($partKeluar->jualpart_id) {
            $jualpartItem = JualpartItem::where('kode_barang', $request->kode_barang)
                                ->where('jualpart_id', $partKeluar->jualpart_id)
                                ->first();

            if ($jualpartItem) {
                $jualpartItem->jumlah = $request->jumlah;
                $jualpartItem->tanggal_keluar = $request->tanggal_keluar;
                $jualpartItem->total_harga_part = $jualpartItem->harga_jual * $request->jumlah;
                $jualpartItem->save();

                // Update total_transaksi di jualpart
                $jualpart = Jualpart::find($jualpartItem->jualpart_id);
                if ($jualpart) {
                    $jualpart->total_transaksi = $jualpart->items()->sum('total_harga_part');
                    $jualpart->save();
                }
            }
        }

        DB::commit();

        return redirect()->route('partkeluar')->with('success', 'Data part keluar berhasil diupdate!');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate: ' . $e->getMessage());
    }
}


public function approve($id)
{
    $partKeluar = Partkeluar::findOrFail($id);

    // Jika status sudah approved, kembalikan pesan error
    if ($partKeluar->status === 'approved') {
        return redirect()->back()->with('error', 'Data sudah disetujui sebelumnya!');
    }

    // Ambil data sparepart yang terkait
    $sparepart = Datasparepat::where('kode_barang', $partKeluar->kode_barang)->first();

    // Validasi jika stok 0 atau jumlah yang diminta melebihi stok yang tersedia
    if ($sparepart->jumlah == 0) {
        return redirect()->back()->with('error', 'Stok barang sudah habis!');
    }

    if ($sparepart->jumlah < $partKeluar->jumlah) {
        return redirect()->back()->with('error', 'Stok tidak mencukupi!');
    }

    // Kurangi stok sparepart
    $sparepart->jumlah -= $partKeluar->jumlah;
    $sparepart->save();

    // Update status menjadi approved
    $partKeluar->update(['status' => 'approved']);

    return redirect()->route('partkeluar')->with('success', 'Data part keluar berhasil disetujui dan stok berhasil dikurangi!');
}

public function cancel($id)
{
    $partKeluar = Partkeluar::findOrFail($id);

    // Jika status sudah canceled, kembalikan pesan error
    if ($partKeluar->status === 'canceled') {
        return redirect()->back()->with('error', 'Data sudah dibatalkan sebelumnya!');
    }

    // Update status menjadi canceled
    $partKeluar->status = 'canceled';
    $partKeluar->save();

    return redirect()->route('partkeluar')->with('success', 'Status part keluar berhasil diperbarui menjadi canceled!');
}

public function printPDF(Request $request)
{
    // Ambil parameter pencarian dan tanggal dari request
    $search = $request->input('search');
    $dateStart = $request->input('date_start');
    $dateEnd = $request->input('date_end');

    // Query data Partkeluar
    $query = Partkeluar::query();

    // Filter berdasarkan pencarian (search)
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('kode_barang', 'like', '%' . $search . '%')
              ->orWhere('nama_part', 'like', '%' . $search . '%')
              ->orWhere('merk', 'like', '%' . $search . '%')
              ->orWhere('stn', 'like', '%' . $search . '%')
              ->orWhere('tipe', 'like', '%' . $search . '%')
              ->orWhere('jumlah', 'like', '%' . $search . '%')
              ->orWhere('tanggal_keluar', 'like', '%' . $search . '%');
        });
    }

    // Filter berdasarkan rentang tanggal (date to date)
    if ($dateStart && $dateEnd) {
        $query->whereBetween('tanggal_keluar', [$dateStart, $dateEnd]);
    }

    // Ambil data yang sudah difilter
    $partkeluars = $query->get();

    // Load view ke PDF
    $pdf = Pdf::loadView('printpdfpartkeluar', compact('partkeluars'))
    ->setPaper('a4', 'landscape');

    // Download PDF
    return $pdf->download('Data_Partkeluar.pdf');
}
}
