<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Dataservice;
use App\Models\Invoice;
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
            // Ambil dataservice beserta partkeluar dan datasparepat terkait
            $dataservice = Dataservice::with('partkeluar.datasparepat')->findOrFail($id);

            if ($dataservice->partkeluar->isEmpty()) {
                return redirect()->back()->with('error', 'No parts found for this service!');
            }

            // Hitung total harga part
            $total_harga_part = 0;
            foreach ($dataservice->partkeluar as $part) {
                $jumlah = (float)$part->jumlah;
                $harga_jual = (float)($part->datasparepat->harga_jual ?? 0);
                $total_harga_part += $jumlah * $harga_jual;
            }

            // Handle ongkos pengerjaan - check if it's an array and format values as float
            $ongkos_pengerjaan = $dataservice->ongkos_pengerjaan;

            if (is_array($ongkos_pengerjaan)) {
                // Konversi semua nilai dalam array menjadi float dan jumlahkan
                $ongkos_pengerjaan = array_sum(array_map(function($item) {
                    return (float)$item; // Pastikan setiap item dalam array menjadi angka desimal
                }, $ongkos_pengerjaan));
            } elseif (is_string($ongkos_pengerjaan)) {
                // Jika ongkos_pengerjaan berupa string yang menyerupai array, decode string JSON terlebih dahulu
                $ongkos_pengerjaan = json_decode($ongkos_pengerjaan);
                if (is_array($ongkos_pengerjaan)) {
                    // Konversi semua nilai dalam array menjadi float dan jumlahkan
                    $ongkos_pengerjaan = array_sum(array_map(function($item) {
                        return (float)$item;
                    }, $ongkos_pengerjaan));
                }
            }

            // Pastikan ongkos_pengerjaan adalah nilai numerik (float) dan format dengan dua angka desimal
            $ongkos_pengerjaan = number_format((float)$ongkos_pengerjaan, 2, '.', '');

            // Handle jenis pekerjaan - ensure it's a string
            $jenis_pekerjaan = $dataservice->jenis_pekerjaan;
            if (is_array($jenis_pekerjaan)) {
                $jenis_pekerjaan = implode(', ', array_filter($jenis_pekerjaan));
            }
            $jenis_pekerjaan = $jenis_pekerjaan ?? 'Unknown';

            // Hitung discount dan ppn
            $discount_part_percent = (float)($request->discount_part ?? 0);
            $discount_ongkos_percent = (float)($request->discount_ongkos_pengerjaan ?? 0);
            $ppn_percent = (float)($request->ppn ?? 10);

            $discount_part = ($discount_part_percent / 100) * $total_harga_part;
            $total_harga_part_after_discount = $total_harga_part - $discount_part;

            $discount_ongkos = ($discount_ongkos_percent / 100) * $ongkos_pengerjaan; // Sum the array values
            $total_harga_jasa_after_discount = $ongkos_pengerjaan - $discount_ongkos;

            $ppn = ($ppn_percent / 100) * ($total_harga_part_after_discount + $total_harga_jasa_after_discount);
            $total_harga = ($total_harga_part_after_discount + $total_harga_jasa_after_discount) + $ppn;

            // Generate invoice number
            $lastInvoice = Invoice::orderBy('no_invoice', 'desc')->first();
            $lastInvoiceNumber = $lastInvoice ? (int)substr($lastInvoice->no_invoice, -4) : 0;
            $newInvoiceNumber = str_pad($lastInvoiceNumber + 1, 4, '0', STR_PAD_LEFT);
            $no_invoice = 'INV-' . date('Ymd') . '-' . $newInvoiceNumber;

            $firstPart = $dataservice->partkeluar->first();

            // Create new invoice
            $invoice = Invoice::create([
                'no_invoice' => $no_invoice,
                'dataservice_id' => $dataservice->id,
                'kode_barang' => $firstPart->kode_barang ?? null,
                'tanggal_invoice' => now(),
                'nama_part' => $firstPart->nama_part ?? null,
                'jumlah' => (float)$dataservice->partkeluar->sum('jumlah'),
                'harga_jual' => (float)($firstPart->datasparepat->harga_jual ?? 0),
                'total_harga_part' => $total_harga_part,
                'discount_part' => $discount_part_percent,
                'jenis_pekerjaan' => $jenis_pekerjaan,  // Simpan sebagai string
                'ongkos_pengerjaan' => $ongkos_pengerjaan,  // Simpan sebagai angka desimal
                'discount_ongkos_pengerjaan' => $discount_ongkos_percent,
                'total_harga_uraian_pekerjaan' => $total_harga_jasa_after_discount,
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

    /**
     * Mengupdate invoice yang sudah ada.
     */
   /**
 * Mengupdate invoice yang sudah ada.
 */
public function update(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'discount_part' => 'required|numeric', // Discount part in percentage
        'discount_ongkos_pengerjaan' => 'required|numeric', // Discount for work cost in percentage
        'ppn' => 'required|numeric', // PPN (VAT) in percentage
    ]);

    // Ambil invoice yang akan diupdate
    $invoice = Invoice::findOrFail($id);

    // Hitung ulang total harga berdasarkan input baru
    $total_harga_part = $invoice->total_harga_part; // Total for the parts
    $ongkos_pengerjaan = $invoice->ongkos_pengerjaan; // Cost for the work (mechanic)

    $discount_part_percent = $request->discount_part; // Discount for parts in percentage
    $discount_ongkos_percent = $request->discount_ongkos_pengerjaan; // Discount for work cost in percentage
    $ppn_percent = $request->ppn; // PPN (VAT) percentage

    // Discount calculation for parts
    $discount_part = ($discount_part_percent / 100) * $total_harga_part;
    $total_harga_part_after_discount = $total_harga_part - $discount_part;

    // Discount calculation for work cost
    $discount_ongkos = ($discount_ongkos_percent / 100) * $ongkos_pengerjaan;
    $total_harga_jasa_after_discount = $ongkos_pengerjaan - $discount_ongkos;

    // Calculate PPN (VAT) after discounts
    $ppn = ($ppn_percent / 100) * ($total_harga_part_after_discount + $total_harga_jasa_after_discount);

    // Calculate final total price after discounts and PPN
    $total_harga = ($total_harga_part_after_discount + $total_harga_jasa_after_discount) + $ppn;

    // Update the invoice with new values
    $invoice->update([
        'discount_part' => $discount_part_percent, // Discount for parts
        'discount_ongkos_pengerjaan' => $discount_ongkos_percent, // Discount for work cost
        'ppn' => $ppn_percent, // PPN (VAT) percentage
        'total_harga' => $total_harga, // Final total price
    ]);

    // Return a success response
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
                        $q->where('costumer', 'like', '%' . $search . '%');
                    })
                    ->orWhere('nama_mekanik', 'like', '%' . $search . '%')
                    ->orWhere('ongkos_pengerjaan', 'like', '%' . $search . '%')
                    ->orWhere('discount_part', 'like', '%' . $search . '%')
                    ->orWhere('discount_ongkos_pengerjaan', 'like', '%' . $search . '%')
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
        $pdf = PDF::loadView('printpdfinvoice', compact('invoice'));
        return $pdf->download('invoice.pdf');
    }
}
