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


        return view('dataservice', compact('dataservices', 'mekaniks', 'spareparts', 'uraianPekerjaans'));
    }

    // Menyimpan data service baru
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'no_spk' => 'required|unique:dataservices,no_spk',
            'costumer' => 'required',
            'contact_person' => 'required',
            'masuk' => 'required|date_format:Y-m-d\TH:i',
            'keluar' => 'nullable|date_format:Y-m-d\TH:i',
            'no_polisi' => 'required',
            'tahun' => 'required|integer',
            'tipe_mobile' => 'required',
            'warna' => 'required',
            'no_rangka' => 'required',
            'no_mesin' => 'required',
            'kilometer' => 'required|regex:/^\d+(\.\d{1,2})?\s*KM$/i',
            'keluhan_costumer' => 'required',
            'status' => 'required',
            'jenis_pekerjaan' => 'nullable|array',
            'jenis_pekerjaan.*' => 'nullable|string',
            'jenis_mobil' => 'nullable|array',
            'jenis_mobil.*' => 'nullable|string',
            'waktu_pengerjaan' => 'nullable|array',
            'waktu_pengerjaan.*' => 'nullable|integer',
            'ongkos_pengerjaan' => 'nullable|array',
            'ongkos_pengerjaan.*' => 'nullable|numeric',
        ]);

        // Bersihkan input kilometer dengan menghapus "KM"
        $kilometer = str_replace(' KM', '', $request->kilometer); // Hapus "KM"
        $kilometer = (float) $kilometer; // Konversi ke float

        // Simpan data ke dalam tabel dataservices
        $dataService = new DataService();
        $dataService->no_spk = $request->no_spk;
        $dataService->costumer = $request->costumer;
        $dataService->contact_person = $request->contact_person;
        $dataService->masuk = $request->masuk; // Pastikan format sudah benar
        $dataService->keluar = $request->keluar;
        $dataService->no_polisi = $request->no_polisi;
        $dataService->tahun = $request->tahun;
        $dataService->tipe_mobile = $request->tipe_mobile;
        $dataService->warna = $request->warna;
        $dataService->no_rangka = $request->no_rangka;
        $dataService->no_mesin = $request->no_mesin;
        $dataService->kilometer = $kilometer;
        $dataService->keluhan_costumer = $request->keluhan_costumer;
        $dataService->status = $request->status;

        // Jika jenis_pekerjaan adalah array, Anda mungkin perlu menyimpannya dengan relasi atau format JSON
        $dataService->jenis_pekerjaan = json_encode($request->jenis_pekerjaan);
        $dataService->jenis_mobil = json_encode($request->jenis_mobil);
        $dataService->waktu_pengerjaan = json_encode($request->waktu_pengerjaan);
        $dataService->ongkos_pengerjaan = json_encode($request->ongkos_pengerjaan);

        $dataService->save(); // Menyimpan data ke database

        return redirect()->route('dataservice')->with('success', 'Data service berhasil disimpan!');
    }
// Mengupdate data service awal
public function updateawal(Request $request, $id)
{
    // Validasi data
    $request->validate([
        'no_spk' => 'required|unique:dataservices,no_spk,'.$id,
        'costumer' => 'required',
        'contact_person' => 'required',
        'masuk' => 'required|date_format:Y-m-d\TH:i',
        'keluar' => 'nullable|date_format:Y-m-d\TH:i',
        'no_polisi' => 'required',
        'tahun' => 'required|integer',
        'tipe_mobile' => 'required',
        'warna' => 'required',
        'no_rangka' => 'required',
        'no_mesin' => 'required',
        'kilometer' => 'required|regex:/^\d+(\.\d{1,2})?\s*KM$/i',
        'keluhan_costumer' => 'required',
        'status' => 'required',
        'jenis_pekerjaan' => 'nullable|array',
        'jenis_pekerjaan.*' => 'nullable|string',
        'jenis_mobil' => 'nullable|array',
        'jenis_mobil.*' => 'nullable|string',
        'waktu_pengerjaan' => 'nullable|array',
        'waktu_pengerjaan.*' => 'nullable|integer',
        'ongkos_pengerjaan' => 'nullable|array',
        'ongkos_pengerjaan.*' => 'nullable|numeric',
    ]);

    // Bersihkan input kilometer dengan menghapus "KM"
    $kilometer = str_replace(' KM', '', $request->kilometer);
    $kilometer = (float) $kilometer;

    // Ambil data service yang akan diupdate
    $dataService = Dataservice::findOrFail($id);

    // Update data service
    $dataService->update([
        'no_spk' => $request->no_spk,
        'costumer' => $request->costumer,
        'contact_person' => $request->contact_person,
        'masuk' => $request->masuk,
        'keluar' => $request->keluar,
        'no_polisi' => $request->no_polisi,
        'tahun' => $request->tahun,
        'tipe_mobile' => $request->tipe_mobile,
        'warna' => $request->warna,
        'no_rangka' => $request->no_rangka,
        'no_mesin' => $request->no_mesin,
        'kilometer' => $kilometer,
        'keluhan_costumer' => $request->keluhan_costumer,
        'status' => $request->status,
        'jenis_pekerjaan' => json_encode($request->jenis_pekerjaan),
        'jenis_mobil' => json_encode($request->jenis_mobil),
        'waktu_pengerjaan' => json_encode($request->waktu_pengerjaan),
        'ongkos_pengerjaan' => json_encode($request->ongkos_pengerjaan),

    ]);

    return redirect()->route('dataservice')->with('success', 'Data service awal berhasil diperbarui!');
}
    // Mengupdate data service
    public function update(Request $request, $id)
    {
        // Bersihkan nilai harga_jasa_perbaikan dari karakter non-digit
        if ($request->has('harga_jasa_perbaikan')) {
            $harga_jasa_perbaikan = $request->harga_jasa_perbaikan;
            foreach ($harga_jasa_perbaikan as &$harga) {
                $harga = preg_replace('/[^0-9]/', '', $harga); // Remove non-digit characters
            }
            $request->merge(['harga_jasa_perbaikan' => $harga_jasa_perbaikan]);
        }

        // Bersihkan nilai kilometer (hilangkan karakter non-digit selain titik desimal)
        if ($request->has('kilometer')) {
            // Remove non-numeric characters (except the dot for decimal numbers)
            $kilometer = preg_replace('/[^0-9.]/', '', $request->kilometer);
            $request->merge(['kilometer' => $kilometer]);
        }

        // Validasi data
        $request->validate([
            'no_spk' => 'required|unique:dataservices,no_spk,' . $id,
            'costumer' => 'required',
            'contact_person' => 'required',
            'masuk' => 'required|date_format:Y-m-d\TH:i',
            'keluar' => 'nullable|date_format:Y-m-d\TH:i',
            'no_polisi' => 'required',
            'nama_mekanik' => 'required',
            'tahun' => 'required|integer',
            'tipe_mobile' => 'required',
            'warna' => 'required',
            'no_rangka' => 'required',
            'no_mesin' => 'required',
            'kilometer' => 'required|regex:/^\d+(\.\d{1,2})?$/i', // Only numbers and up to 2 decimal points
            'keluhan_costumer' => 'required',
            'kode_barang' => 'nullable|array',
            'kode_barang.*' => 'exists:datasparepats,kode_barang',
            'nama_part' => 'nullable|array',
            'nama_part.*' => 'nullable|string',
            'stn' => 'nullable|array',
            'stn.*' => 'nullable|string',
            'tipe' => 'nullable|array',
            'tipe.*' => 'nullable|string',
            'merk' => 'nullable|array',
            'merk.*' => 'nullable|string',
            'jumlah' => 'nullable|array',
            'jumlah.*' => 'integer|min:0',
            'tanggal_keluar' => 'nullable|array',
            'tanggal_keluar.*' => 'date',
            'status' => 'required',
        ]);

        // Ambil data service yang akan diupdate
        $dataservice = Dataservice::findOrFail($id);

        // Update data service
        $dataservice->update($request->except(['kode_barang', 'nama_part', 'stn','tipe', 'merk', 'jumlah', 'tanggal_keluar']));

        // Proses part keluar
        $kode_barang = $request->kode_barang ?? [];
        $nama_part = $request->nama_part ?? [];
        $stn = $request->stn ?? [];
        $merk = $request->merk ?? [];
        $tipe = $request->tipe ?? [];
        $jumlah = $request->jumlah ?? [];
        $tanggal_keluar = $request->tanggal_keluar ?? [];

        // Jika ada input kode_barang, simpan data ke Partkeluar
        if (!empty($kode_barang)) {
            foreach ($kode_barang as $index => $kode_barang_item) {
                $tanggal_keluar_item = $tanggal_keluar[$index] ?? null;
                $jumlah_item = $jumlah[$index] ?? 0;

                if (!$kode_barang_item || $jumlah_item <= 0) {
                    continue;
                }

                $sparepart = Datasparepat::where('kode_barang', $kode_barang_item)->first();

                if (!$sparepart) {
                    return redirect()->back()->with('error', 'Barang tidak ditemukan!');
                }

                if ($sparepart->jumlah == 0) {
                    return redirect()->back()->with('error', 'Stok barang sudah habis!');
                }

                if ($sparepart->jumlah < $jumlah_item) {
                    return redirect()->back()->with('error', 'Stok tidak mencukupi!');
                }

                $existingPart = Partkeluar::where('kode_barang', $kode_barang_item)
                    ->where('tanggal_keluar', $tanggal_keluar_item)
                    ->where('dataservice_id', '!=', $id)
                    ->first();

                if ($existingPart) {
                    return redirect()->back()->with('error', 'Part dengan kode barang dan tanggal keluar yang sama sudah ada!');
                }

                $partKeluar = Partkeluar::where('dataservice_id', $id)
                    ->where('kode_barang', $kode_barang_item)
                    ->where('tanggal_keluar', $tanggal_keluar_item)
                    ->first();

                if ($partKeluar) {
                    $partKeluar->update([
                        'dataservice_id' => $id,
                        'kode_barang' => $kode_barang_item,
                        'nama_part' => $nama_part[$index] ?? null,
                        'stn' => $sparepart->stn,
                        'merk' => $sparepart->merk,
                        'tipe' => $sparepart->tipe,
                        'tanggal_keluar' => $tanggal_keluar_item,
                        'jumlah' => $jumlah_item,
                    ]);
                } else {
                    Partkeluar::create([
                        'dataservice_id' => $id,
                        'kode_barang' => $kode_barang_item,
                        'nama_part' => $nama_part[$index] ?? null,
                        'stn' => $sparepart->stn,
                        'merk' => $sparepart->merk,
                        'tipe' => $sparepart->tipe,
                        'jumlah' => $jumlah_item,
                        'tanggal_keluar' => $tanggal_keluar_item,
                    ]);
                }

                $sparepart->save();
            }
        }

        return redirect()->route('dataservice')->with('success', 'Data service berhasil diupdate!');
    }

    // Menghapus data service
    public function destroy($id)
    {
        $dataservice = Dataservice::findOrFail($id);

        // Kembalikan stok untuk semua part keluar yang terkait jika status approved
        if ($dataservice->status === 'approved') {
            // Dapatkan semua part keluar yang terkait dengan dataservice ini
            $partKeluars = Partkeluar::where('dataservice_id', $dataservice->id)->get();

            foreach ($partKeluars as $partKeluar) {
                // Kembalikan stok untuk setiap part yang statusnya approved
                if ($partKeluar->status === 'approved') {
                    $sparepart = Datasparepat::where('kode_barang', $partKeluar->kode_barang)->first();
                    if ($sparepart) {
                        $sparepart->jumlah += $partKeluar->jumlah;
                        $sparepart->save();
                    }
                }

                // Hapus part keluar
                $partKeluar->delete();
            }
        } else {
            // Jika status bukan approved, cukup hapus part keluar terkait tanpa mengembalikan stok
            Partkeluar::where('dataservice_id', $dataservice->id)->delete();
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
