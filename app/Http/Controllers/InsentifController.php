<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
class InsentifController extends Controller
{
    public function index(Request $request){
        $query = Invoice::select('nama_mekanik', DB::raw('COUNT(*) as total_pengerjaan'), DB::raw('SUM(total_harga) as total'))
            ->groupBy('nama_mekanik');

        // Filter by month if month is provided
        if ($request->has('month') && $request->month != '') {
            $query->whereYear('created_at', '=', date('Y', strtotime($request->month)))
                  ->whereMonth('created_at', '=', date('m', strtotime($request->month)));
        }

        $laporanInsentif = $query->paginate(10);

        return view('insentif', compact('laporanInsentif'));
    }
    public function printInsentif($nama_mekanik, Request $request)
    {
        $query = Invoice::select('nama_mekanik', DB::raw('COUNT(*) as total_pengerjaan'), DB::raw('SUM(total_harga) as total'))
            ->where('nama_mekanik', $nama_mekanik)
            ->groupBy('nama_mekanik');

        // Filter by month if month is provided
        if ($request->has('month') && $request->month != '') {
            $query->whereYear('created_at', '=', date('Y', strtotime($request->month)))
                  ->whereMonth('created_at', '=', date('m', strtotime($request->month)));
        }

        $laporanInsentif = $query->get();

        if ($laporanInsentif->isEmpty()) {
            abort(404, "Insentif not found for mekanik: " . $nama_mekanik);
        }

        // Load the PDF view with the 'laporanInsentif' data
        $pdf = Pdf::loadView('printpdfinsentifperdata', compact('laporanInsentif'));

        // Download the PDF
        return $pdf->download('insentif_' . $nama_mekanik . '.pdf');
    }
    public function printAllInsentif(Request $request)
{
    $query = Invoice::select('nama_mekanik', DB::raw('COUNT(*) as total_pengerjaan'), DB::raw('SUM(total_harga) as total'))
        ->groupBy('nama_mekanik');

    // Filter by search keyword
    if ($request->has('search') && $request->search != '') {
        $query->where('nama_mekanik', 'like', '%' . $request->search . '%');
    }

    // Filter by month if month is provided
    if ($request->has('month') && $request->month != '') {
        $query->whereYear('tanggal_invoice', '=', date('Y', strtotime($request->month)))
              ->whereMonth('tanggal_invoice', '=', date('m', strtotime($request->month)));
    }

    $laporanInsentif = $query->get();

    // Load the PDF view with the 'laporanInsentif' data
    $pdf = Pdf::loadView('printpdfinsentif', compact('laporanInsentif'));

    // Download the PDF
    return $pdf->download('laporan_insentif_keseluruhan.pdf');
}
}
