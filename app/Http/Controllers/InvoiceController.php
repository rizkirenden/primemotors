<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import the DB facade
use App\Models\Dataservice;
use App\Models\Invoice;
use App\Models\Partkeluar;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Menampilkan daftar invoice.
     */
    public function index()
    {


        // Ambil semua invoice beserta relasi dataservice
        $invoices = Invoice::with('dataservice')->paginate(10);

        // Tampilkan view laporantransaksi dengan data invoices
        return view('laporantransaksi', compact('invoices'));
    }

    /**
     * Menyimpan invoice baru ke database.
     */
    public function store(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // Ambil data service beserta part yang digunakan dan relasi datasparepat
            $dataservice = Dataservice::with('partkeluar.datasparepat')->findOrFail($id);

            // Validasi jika tidak ada part yang digunakan
            if ($dataservice->partkeluar->isEmpty()) {
                return redirect()->back()->with('error', 'No parts found for this service!');
            }

            // Hitung total harga dari part yang digunakan
            $total_harga_part = 0;
            $total_harga_jasa_perbaikan = 0;

            foreach ($dataservice->partkeluar as $part) {
                $harga_jual = $part->datasparepat->harga_jual ?? 0; // Ambil harga_jual dari Datasparepat
                $total_harga_part += $part->jumlah * $harga_jual;

                // Hitung total harga_jasa_perbaikan
                $total_harga_jasa_perbaikan += $part->harga_jasa_perbaikan ?? 0;
            }

            // Hitung biaya jasa, discount, dan ppn
            $biaya_jasa = $total_harga_jasa_perbaikan; // Total harga_jasa_perbaikan menjadi biaya_jasa
            $discount_percent = $request->discount ?? 0; // Discount dalam persentase
            $ppn_percent = $request->ppn ?? 10; // PPN dalam persentase

            $discount = ($discount_percent / 100) * ($total_harga_part + $biaya_jasa);
            $ppn = ($ppn_percent / 100) * ($total_harga_part + $biaya_jasa - $discount);

            $total_harga = ($total_harga_part + $biaya_jasa - $discount) + $ppn;

            $lastInvoice = Invoice::orderBy('no_invoice', 'desc')->first();
            $lastInvoiceNumber = $lastInvoice ? (int)substr($lastInvoice->no_invoice, -4) : 0;
            $newInvoiceNumber = str_pad($lastInvoiceNumber + 1, 4, '0', STR_PAD_LEFT);
            $no_invoice = 'INV-' . date('Ymd') . '-' . $newInvoiceNumber;

            $firstPart = $dataservice->partkeluar->first();

            $invoice = Invoice::create([
                'no_invoice' => $no_invoice,
                'dataservice_id' => $dataservice->id,
                'kode_barang' => $firstPart->kode_barang ?? null,
                'tanggal_invoice' => now(),
                'nama_part' => $firstPart->nama_part ?? null,
                'jumlah' => $dataservice->partkeluar->sum('jumlah'),
                'harga_jual' => $firstPart->datasparepat->harga_jual ?? 0,
                'total_harga_part' => $total_harga_part,
                'biaya_jasa' => $biaya_jasa,
                'discount' => $discount_percent,
                'ppn' => $ppn_percent,
                'total_harga' => $total_harga,
                'nama_mekanik' => $dataservice->nama_mekanik,
            ]);

            DB::commit();

            return redirect()->route('laporantransaksi')->with('success', 'Invoice created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create invoice: ' . $e->getMessage());
        }
    }


    public function update(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'discount' => 'required|numeric', // Discount dalam persentase
        'ppn' => 'required|numeric', // PPN dalam persentase
    ]);

    // Ambil invoice yang akan diupdate
    $invoice = Invoice::findOrFail($id);

    // Hitung total harga baru
    $total_harga_part = $invoice->total_harga_part;
    $biaya_jasa = $invoice->biaya_jasa; // Biaya jasa tidak diubah
    $discount_percent = $request->discount; // Discount dalam persentase
    $ppn_percent = $request->ppn; // PPN dalam persentase

    // Hitung nilai discount dan ppn dalam rupiah
    $discount = ($discount_percent / 100) * ($total_harga_part + $biaya_jasa);
    $ppn = ($ppn_percent / 100) * ($total_harga_part + $biaya_jasa - $discount);

    // Hitung total harga keseluruhan
    $total_harga = ($total_harga_part + $biaya_jasa - $discount) + $ppn;

    // Update data invoice
    $invoice->update([
        'discount' => $discount_percent, // Simpan discount sebagai persentase
        'ppn' => $ppn_percent, // Simpan ppn sebagai persentase
        'total_harga' => $total_harga,
    ]);

    // Berikan response JSON
    return response()->json(['success' => true]);
}

    /**
     * Menghapus invoice.
     */
    public function destroy($id)
    {
        // Ambil invoice yang akan dihapus
        $invoice = Invoice::findOrFail($id);

        // Hapus invoice
        $invoice->delete();

        // Redirect ke halaman laporantransaksi dengan pesan sukses
        return redirect()->route('laporantransaksi')->with('success', 'Invoice deleted successfully!');
    }

    /**
     * Menampilkan invoice dalam format PDF.
     */
    public function printPDF(Request $request)
    {
        // Ambil parameter pencarian dan tanggal dari request
        $search = $request->input('search');
        $dateStart = $request->input('date_start');
        $dateEnd = $request->input('date_end');

        // Query data Invoice
        $query = Invoice::query();

        // Filter berdasarkan pencarian (search)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', '%' . $search . '%')
                  ->orWhere('tanggal_invoice', 'like', '%' . $search . '%')
                  ->orWhereHas('dataservice', function ($q) use ($search) {
                    $q->where('costumer', 'like', '%' . $search . '%'); // Pastikan ini sesuai dengan struktur tabel
                })
                ->orWhere('nama_mekanik', 'like', '%' . $search . '%')
                  ->orWhere('biaya_jasa', 'like', '%' . $search . '%')
                  ->orWhere('discount', 'like', '%' . $search . '%')
                  ->orWhere('ppn', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan rentang tanggal (date to date)
        if ($dateStart && $dateEnd) {
            $query->whereBetween('tanggal_invoice', [$dateStart, $dateEnd]);
        }

        // Ambil data yang sudah difilter
        $invoices = $query->with(['dataservice.partkeluar.datasparepat', 'datasparepat'])->get();

        // Pastikan data invoices ada
        if ($invoices->isEmpty()) {
            return response()->json(['message' => 'No invoices found for the specified filters'], 404);
        }

        // Load view ke PDF dengan data invoices
        $pdf = PDF::loadView('printpdfinvoiceall', compact('invoices'));

        // Download PDF
        return $pdf->download('Invoices.pdf');
    }


    public function print($id)
    {
        $invoice = Invoice::with(['dataservice.partkeluar.datasparepat', 'datasparepat'])->findOrFail($id);
        // Ambil data invoice berdasarkan ID
        $invoice = Invoice::with('dataservice')->findOrFail($id);

        // Load view PDF dengan data invoice
        $pdf = PDF::loadView('printpdfinvoice', compact('invoice'));

        // Download PDF
        return $pdf->download('invoice.pdf');
    }
}
