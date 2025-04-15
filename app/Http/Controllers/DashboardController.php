<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datamekanik;
use App\Models\Datasparepat;
use App\Models\Dataservice;
use App\Models\Datashowroom;
use App\Models\Invoice;
use App\Models\Partmasuk;
use App\Models\Partkeluar;
use App\Models\Jualpart; // Add Jualpart model
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua data dari masing-masing model
        $mekanik = Datamekanik::count();
        $sparepat = Datasparepat::count();
        $service = Dataservice::count();
        $showroom = Datashowroom::count();

        // Ambil tahun unik dari field tanggal_invoice (untuk semua data)
        $years = Invoice::selectRaw('YEAR(tanggal_invoice) as year')
            ->union(
                Jualpart::selectRaw('YEAR(tanggal_pembayaran) as year')
            )
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Ambil data invoice dan kelompokkan berdasarkan bulan
        $invoices = Invoice::all();
        $invoiceData = $this->getInvoiceDataByMonth($invoices);

        // Ambil data part keluar dan kelompokkan berdasarkan bulan
        $partKeluar = Partkeluar::where('status', 'approved')->get();
        $partKeluarData = $this->getPartKeluarDataByMonth($partKeluar);

        // Ambil data part masuk dan kelompokkan berdasarkan bulan
        $partMasuk = Partmasuk::all();
        $partMasukData = $this->getPartMasukDataByMonth($partMasuk);

        // Ambil data penjualan part (Jualpart) dan kelompokkan berdasarkan bulan
        $jualParts = Jualpart::all();
        $jualPartData = $this->getJualPartDataByMonth($jualParts);

        // Kirim data ke view
        return view('dasboard', compact(
            'mekanik',
            'sparepat',
            'service',
            'showroom',
            'invoiceData',
            'years',
            'partKeluarData',
            'partMasukData',
            'jualPartData'
        ));
    }

    private function getInvoiceDataByMonth($invoices)
    {
        $data = array_fill(0, 12, 0);

        foreach ($invoices as $invoice) {
            $month = Carbon::parse($invoice->tanggal_invoice)->month - 1;
            $data[$month] += $invoice->total_harga;
        }

        return $data;
    }

    private function getPartKeluarDataByMonth($partKeluar)
    {
        $data = array_fill(0, 12, 0);

        foreach ($partKeluar as $part) {
            $month = Carbon::parse($part->tanggal_keluar)->month - 1;
            $data[$month] += $part->jumlah;
        }

        return $data;
    }

    private function getPartMasukDataByMonth($partMasuk)
    {
        $data = array_fill(0, 12, 0);

        foreach ($partMasuk as $part) {
            $month = Carbon::parse($part->tanggal_masuk)->month - 1;
            $data[$month] += $part->jumlah;
        }

        return $data;
    }

    // New method to get Jualpart data by month
    private function getJualPartDataByMonth($jualParts)
    {
        $data = array_fill(0, 12, 0);

        foreach ($jualParts as $part) {
            $month = Carbon::parse($part->tanggal_pembayaran)->month - 1;
            $data[$month] += $part->total_transaksi;
        }

        return $data;
    }

    public function getDataByYear(Request $request)
    {
        $year = $request->query('year');

        // Ambil data invoice berdasarkan tahun yang dipilih
        $invoices = Invoice::whereYear('tanggal_invoice', $year)->get();
        $invoiceData = $this->getInvoiceDataByMonth($invoices);

        // Ambil data part keluar berdasarkan tahun yang dipilih
        $partKeluar = Partkeluar::whereYear('tanggal_keluar', $year)
            ->where('status', 'approved')
            ->get();
        $partKeluarData = $this->getPartKeluarDataByMonth($partKeluar);

        // Ambil data part masuk berdasarkan tahun yang dipilih
        $partMasuk = Partmasuk::whereYear('tanggal_masuk', $year)->get();
        $partMasukData = $this->getPartMasukDataByMonth($partMasuk);

        // Ambil data Jualpart berdasarkan tahun yang dipilih
        $jualParts = Jualpart::whereYear('tanggal_pembayaran', $year)->get();
        $jualPartData = $this->getJualPartDataByMonth($jualParts);

        return response()->json([
            'invoiceData' => $invoiceData,
            'partKeluarData' => $partKeluarData,
            'partMasukData' => $partMasukData,
            'jualPartData' => $jualPartData
        ]);
    }
}
