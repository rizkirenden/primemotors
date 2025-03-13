<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dataservice;
use App\Models\Datamekanik;
use App\Models\Datasparepat;
use App\Models\Partkeluar;
use App\Models\UraianPekerjaan;
use Barryvdh\DomPDF\Facade\Pdf;

class DataserviceController extends Controller
{
    // Menampilkan semua data service
    public function index()
    {
        // Ambil semua data service dengan pagination
        $dataservices = Dataservice::paginate(10);

        // Ambil semua data mekanik
        $mekaniks = Datamekanik::all();

        // Ambil semua data sparepart
        $spareparts = Datasparepat::all();

        // Ambil semua data uraian pekerjaan
        $uraianPekerjaans = UraianPekerjaan::all();

        foreach ($dataservices as $dataservice) {
            $dataservice->uraian_pekerjaan = $dataservice->uraian_pekerjaan_ids ? UraianPekerjaan::whereIn('id', json_decode($dataservice->uraian_pekerjaan_ids))->get() : [];
        }


        return view('dataservice', compact('dataservices', 'mekaniks', 'spareparts', 'uraianPekerjaans'));
    }

    // Menyimpan data service baru
    public function store(Request $request)
    {
        $request->validate([
            'no_spk' => 'required|unique:dataservices,no_spk',
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
            'kilometer' => 'required|regex:/^\d+(\.\d{1,2})?\s*KM$/i',
            'keluhan_costumer' => 'required',
            'status' => 'required',
            'uraian_pekerjaan_ids' => 'nullable|array', // ID uraian pekerjaan yang dipilih
            'uraian_pekerjaan_ids.*' => 'exists:uraian_pekerjaans,id', // Pastikan ID ada di tabel uraian_pekerjaans
        ]);

        // Clean the kilometer input
        $kilometer = str_replace(' KM', '', $request->kilometer);
        $kilometer = (float) $kilometer;

        // Create the dataservice
        $dataservice = Dataservice::create(array_merge($request->except('kilometer', 'uraian_pekerjaan_ids'), [
            'kilometer' => $kilometer,
            // Menyimpan ID uraian pekerjaan yang dipilih
            'uraian_pekerjaan_ids' => $request->uraian_pekerjaan_ids ? json_encode($request->uraian_pekerjaan_ids) : null,
        ]));

        return redirect()->route('dataservice')->with('success', 'Data service berhasil disimpan!');
    }
    // Mengupdate data service
    public function update(Request $request, $id)
{
    $request->validate([
        'no_spk' => 'required|unique:dataservices,no_spk,' . $id,
        'costumer' => 'required',
        'contact_person' => 'required',
        'masuk' => 'required|date|date_format:Y-m-d H:i:s',
        'keluar' => 'nullable|date|date_format:Y-m-d H:i:s',
        'no_polisi' => 'required',
        'nama_mekanik' => 'required',
        'tahun' => 'required|integer',
        'tipe_mobile' => 'required',
        'warna' => 'required',
        'no_rangka' => 'required',
        'no_mesin' => 'required',
        'kilometer' => 'required|numeric',
        'keluhan_costumer' => 'required',
        'kode_barang' => 'nullable|array',
        'kode_barang.*' => 'exists:datasparepats,kode_barang',
        'nama_part' => 'nullable|array',
        'nama_part.*' => 'nullable|string',
        'stn' => 'nullable|array',
        'stn.*' => 'nullable|string',
        'merk' => 'nullable|array',
        'merk.*' => 'nullable|string',
        'jumlah' => 'nullable|array',
        'jumlah.*' => 'integer|min:0',
        'tanggal_keluar' => 'nullable|array',
        'tanggal_keluar.*' => 'date',
        'uraian_jasa_perbaikan' => 'nullable|array',
        'uraian_jasa_perbaikan.*' => 'nullable|string',
        'harga_jasa_perbaikan' => 'nullable|array',
        'harga_jasa_perbaikan.*' => 'nullable|numeric|min:0',
        'status' => 'required',
    ]);

    // Ambil data service yang akan diupdate
    $dataservice = Dataservice::findOrFail($id);

    // Update data service
    $dataservice->update($request->except(['kode_barang', 'nama_part', 'stn', 'merk', 'jumlah', 'tanggal_keluar', 'uraian_jasa_perbaikan','harga_jasa_perbaikan']));

    // Initialize arrays if they are null
    $kode_barang = $request->kode_barang ?? [];
    $nama_part = $request->nama_part ?? [];
    $stn = $request->stn ?? [];
    $merk = $request->merk ?? [];
    $jumlah = $request->jumlah ?? [];
    $tanggal_keluar = $request->tanggal_keluar ?? [];
    $uraian_jasa_perbaikan = $request->uraian_jasa_perbaikan ?? [];
    $harga_jasa_perbaikan = $request->harga_jasa_perbaikan ?? [];

    // Jika ada input kode_barang, simpan data ke Partkeluar
    if (!empty($kode_barang)) {
        foreach ($kode_barang as $index => $kode_barang_item) {
            $tanggal_keluar_item = $tanggal_keluar[$index] ?? null;
            $jumlah_item = $jumlah[$index] ?? 0;
            $uraian_jasa_perbaikan_item = $uraian_jasa_perbaikan[$index] ?? null;

            // Skip if kode_barang is empty or jumlah <= 0
            if (!$kode_barang_item || $jumlah_item <= 0) {
                continue;
            }

            // Fetch spare part data
            $sparepart = Datasparepat::where('kode_barang', $kode_barang_item)->first();

            if (!$sparepart) {
                return redirect()->back()->with('error', 'Barang tidak ditemukan!');
            }

            // Validasi jika stok 0 atau jumlah yang diminta melebihi stok yang tersedia
            if ($sparepart->jumlah == 0) {
                return redirect()->back()->with('error', 'Stok barang sudah habis!');
            }

            if ($sparepart->jumlah < $jumlah_item) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi!');
            }

            // Cek jika Partkeluar dengan kode_barang dan tanggal_keluar yang sama sudah ada
            $existingPart = Partkeluar::where('kode_barang', $kode_barang_item)
                ->where('tanggal_keluar', $tanggal_keluar_item)
                ->where('dataservice_id', '!=', $id)
                ->first();

            if ($existingPart) {
                return redirect()->back()->with('error', 'Part dengan kode barang dan tanggal keluar yang sama sudah ada!');
            }

            // Cek jika Partkeluar dengan kode_barang dan tanggal_keluar yang sama sudah ada
            $partKeluar = Partkeluar::where('dataservice_id', $id)
                ->where('kode_barang', $kode_barang_item)
                ->where('tanggal_keluar', $tanggal_keluar_item)
                ->first();

            if ($partKeluar) {
                // Update data partKeluar
                $partKeluar->update([
                    'dataservice_id' => $id,
                    'kode_barang' => $kode_barang_item,
                    'nama_part' => $nama_part[$index] ?? null,
                    'stn' => $sparepart->stn,
                    'merk' => $sparepart->merk,
                    'tipe' => $sparepart->tipe,
                    'tanggal_keluar' => $tanggal_keluar_item,
                    'jumlah' => $jumlah_item,
                    'uraian_jasa_perbaikan' => $uraian_jasa_perbaikan_item,
                    'harga_jasa_perbaikan' => $harga_jasa_perbaikan[$index] ?? null,
                ]);
            } else {
                // Jika Partkeluar tidak ada, buat data partKeluar baru
                Partkeluar::create([
                    'dataservice_id' => $id,
                    'kode_barang' => $kode_barang_item,
                    'nama_part' => $nama_part[$index] ?? null,
                    'stn' => $sparepart->stn,
                    'merk' => $sparepart->merk,
                    'tipe' => $sparepart->tipe,
                    'jumlah' => $jumlah_item,
                    'tanggal_keluar' => $tanggal_keluar_item,
                    'uraian_jasa_perbaikan' => $uraian_jasa_perbaikan_item,
                    'harga_jasa_perbaikan' => $harga_jasa_perbaikan[$index] ?? null,
                ]);
            }

            // Kurangi stok spare part
            $sparepart->jumlah -= $jumlah_item;
            $sparepart->save();
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

    public function printspkawalPDF(Request $request)
    {
        // Ambil parameter pencarian dan tanggal dari request
        $search = $request->input('search');
        $dateStart = $request->input('date_start');
        $dateEnd = $request->input('date_end');

        // Query data dataservice berdasarkan pencarian dan tanggal
        $query = Dataservice::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('no_spk', 'like', '%' . $search . '%')
                  ->orWhere('costumer', 'like', '%' . $search . '%')
                  ->orWhere('contact_person', 'like', '%' . $search . '%')
                  ->orWhere('no_polisi', 'like', '%' . $search . '%')
                  ->orWhere('tahun', 'like', '%' . $search . '%')
                  ->orWhere('tipe_mobile', 'like', '%' . $search . '%')
                  ->orWhere('warna', 'like', '%' . $search . '%')
                  ->orWhere('no_rangka', 'like', '%' . $search . '%')
                  ->orWhere('no_mesin', 'like', '%' . $search . '%')
                  ->orWhere('kilometer', 'like', '%' . $search . '%')
                  ->orWhere('keluhan_costumer', 'like', '%' . $search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $search . '%')
                  ->orWhere('nama_part', 'like', '%' . $search . '%')
                  ->orWhere('stn', 'like', '%' . $search . '%')
                  ->orWhere('merk', 'like', '%' . $search . '%')
                  ->orWhere('jumlah', 'like', '%' . $search . '%')
                  ->orWhere('tanggal_keluar', 'like', '%' . $search . '%')
                  ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        if ($dateStart && $dateEnd) {
            $query->whereBetween('tanggal', [$dateStart, $dateEnd]);
        }

        // Ambil data yang sudah difilter
        $dataservices = $query->get();

        // Load view ke PDF
        $pdf = Pdf::loadView('printpdfdataspkawal', compact('dataservices'));

        // Download PDF
        return $pdf->download('Data_SPKAwal.pdf');
    }

    public function printspkakhirPDF(Request $request)
    {
        // Ambil parameter pencarian dan tanggal dari request
        $search = $request->input('search');
        $dateStart = $request->input('date_start');
        $dateEnd = $request->input('date_end');

        // Query data dataservice berdasarkan pencarian dan tanggal
        $query = Dataservice::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('no_spk', 'like', '%' . $search . '%')
                  ->orWhere('costumer', 'like', '%' . $search . '%')
                  ->orWhere('contact_person', 'like', '%' . $search . '%')
                  ->orWhere('no_polisi', 'like', '%' . $search . '%')
                  ->orWhere('tahun', 'like', '%' . $search . '%')
                  ->orWhere('tipe_mobile', 'like', '%' . $search . '%')
                  ->orWhere('warna', 'like', '%' . $search . '%')
                  ->orWhere('no_rangka', 'like', '%' . $search . '%')
                  ->orWhere('no_mesin', 'like', '%' . $search . '%')
                  ->orWhere('kilometer', 'like', '%' . $search . '%')
                  ->orWhere('keluhan_costumer', 'like', '%' . $search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $search . '%')
                  ->orWhere('nama_part', 'like', '%' . $search . '%')
                  ->orWhere('stn', 'like', '%' . $search . '%')
                  ->orWhere('merk', 'like', '%' . $search . '%')
                  ->orWhere('jumlah', 'like', '%' . $search . '%')
                  ->orWhere('tanggal_keluar', 'like', '%' . $search . '%')
                  ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        if ($dateStart && $dateEnd) {
            $query->whereBetween('tanggal', [$dateStart, $dateEnd]);
        }

        // Ambil data yang sudah difilter
        $dataservices = $query->with('partkeluar')->get();

        // Load view ke PDF
        $pdf = Pdf::loadView('printpdfdataspkakhir', compact('dataservices'));

        // Download PDF
        return $pdf->download('Data_SPKTerakhir.pdf');
    }
    public function printAwalPerData($id)
    {
        $dataservices = Dataservice::with('partkeluar')->findOrFail($id);
        $pdf = Pdf::loadView('printpdfdataspkawalperdata', compact('dataservices'));
        return $pdf->download('Data_SPKAwal_' . $dataservices->no_spk . '.pdf');
    }

    public function printAkhirPerData($id)
    {
        $dataservices = Dataservice::with('partkeluar')->findOrFail($id);
        $pdf = Pdf::loadView('printpdfdataspkakhirperdata', compact('dataservices'));
        return $pdf->download('Data_SPKAkhir_' . $dataservices->no_spk . '.pdf');
    }
}
