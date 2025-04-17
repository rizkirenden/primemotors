<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Dataservice;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('dataservice')
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);

        return view('laporantransaksi', compact('invoices'));
    }


    public function store(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $dataservice = Dataservice::with('partkeluar.datasparepat')->findOrFail($id);

            // Hitung total harga part (akan tetap 0 jika tidak ada part)
            $total_harga_part = 0;
            foreach ($dataservice->partkeluar as $part) {
                $jumlah = (float)$part->jumlah;
                $harga_jual = (float)($part->datasparepat->harga_jual ?? 0);
                $total_harga_part += $jumlah * $harga_jual;
            }

            // Handle ongkos pengerjaan
            $original_ongkos_array = [];

            if (is_array($dataservice->ongkos_pengerjaan)) {
                $original_ongkos_array = $dataservice->ongkos_pengerjaan;
            } elseif (is_string($dataservice->ongkos_pengerjaan)) {
                $decoded = json_decode($dataservice->ongkos_pengerjaan, true);
                if (is_array($decoded)) {
                    $original_ongkos_array = $decoded;
                }
            }

            $total_ongkos_pengerjaan = array_sum(array_map(function ($item) {
                return (float)$item;
            }, $original_ongkos_array));

            // Jenis pekerjaan
            $jenis_pekerjaan = $dataservice->jenis_pekerjaan;
            if (is_array($jenis_pekerjaan)) {
                $jenis_pekerjaan = implode(', ', array_filter($jenis_pekerjaan));
            }
            $jenis_pekerjaan = $jenis_pekerjaan ?? 'Unknown';

            // Diskon dan PPN
            $discount_part_percent = (float)($request->discount_part ?? 0);
            $discount_ongkos_percent = (float)($request->discount_ongkos_pengerjaan ?? 0);
            $ppn_percent = (float)($request->ppn ?? 10);

            $discount_part = ($discount_part_percent / 100) * $total_harga_part;
            $total_harga_part_after_discount = $total_harga_part - $discount_part;

            $discount_ongkos = ($discount_ongkos_percent / 100) * $total_ongkos_pengerjaan;
            $total_harga_jasa_after_discount = $total_ongkos_pengerjaan - $discount_ongkos;

            $ppn = ($ppn_percent / 100) * ($total_harga_part_after_discount + $total_harga_jasa_after_discount);
            $total_harga = $total_harga_part_after_discount + $total_harga_jasa_after_discount + $ppn;

            // Generate invoice number
            $lastInvoice = Invoice::orderBy('no_invoice', 'desc')->first();
            $lastInvoiceNumber = $lastInvoice ? (int)substr($lastInvoice->no_invoice, -4) : 0;
            $newInvoiceNumber = str_pad($lastInvoiceNumber + 1, 4, '0', STR_PAD_LEFT);
            $no_invoice = 'INV-' . date('Ymd') . '-' . $newInvoiceNumber;

            $firstPart = $dataservice->partkeluar->first();

            // Simpan invoice dengan nilai default jika tidak ada part
            Invoice::create([
                'no_invoice' => $no_invoice,
                'dataservice_id' => $dataservice->id,
                'kode_barang' => $firstPart->kode_barang ?? '-',
                'tanggal_invoice' => now(),
                'nama_part' => $firstPart->nama_part ?? 'TANPA PART',
                'jumlah' => (float)$dataservice->partkeluar->sum('jumlah'),
                'harga_jual' => (float)($firstPart->datasparepat->harga_jual ?? 0),
                'total_harga_part' => $total_harga_part,
                'discount_part' => $discount_part_percent,
                'jenis_pekerjaan' => $jenis_pekerjaan,
                'ongkos_pengerjaan' => json_encode($original_ongkos_array),
                'discount_ongkos_pengerjaan' => $discount_ongkos_percent,
                'total_harga_uraian_pekerjaan' => $total_harga_jasa_after_discount,
                'ppn' => $ppn_percent,
                'total_harga' => $total_harga,
                'nama_mekanik' => $dataservice->nama_mekanik,
            ]);

            DB::commit();
            return redirect()->route('laporantransaksi')->with('success', 'Invoice Berhasil Dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create invoice: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'discount_part' => 'required|numeric',
            'discount_ongkos_pengerjaan' => 'required|numeric',
            'ppn' => 'required|numeric',
        ]);

        $invoice = Invoice::findOrFail($id);

        $total_harga_part = $invoice->total_harga_part;

        $ongkos_pengerjaan_array = json_decode($invoice->ongkos_pengerjaan, true) ?? [];
        $total_ongkos_pengerjaan = array_sum(array_map('floatval', $ongkos_pengerjaan_array));

        $discount_part_percent = $request->discount_part;
        $discount_ongkos_percent = $request->discount_ongkos_pengerjaan;
        $ppn_percent = $request->ppn;

        $discount_part = ($discount_part_percent / 100) * $total_harga_part;
        $total_harga_part_after_discount = $total_harga_part - $discount_part;

        $discount_ongkos = ($discount_ongkos_percent / 100) * $total_ongkos_pengerjaan;
        $total_harga_jasa_after_discount = $total_ongkos_pengerjaan - $discount_ongkos;

        $ppn = ($ppn_percent / 100) * ($total_harga_part_after_discount + $total_harga_jasa_after_discount);
        $total_harga = $total_harga_part_after_discount + $total_harga_jasa_after_discount + $ppn;

        $invoice->update([
            'discount_part' => $discount_part_percent,
            'discount_ongkos_pengerjaan' => $discount_ongkos_percent,
            'ppn' => $ppn_percent,
            'total_harga' => $total_harga,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('laporantransaksi')->with('success', 'Invoice Berhasil Dihapus');
    }

    public function printPDF(Request $request)
    {
        $search = $request->input('search');
        $dateStart = $request->input('date_start');
        $dateEnd = $request->input('date_end');

        $query = Invoice::query();

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

        if ($dateStart && $dateEnd) {
            $query->whereBetween('tanggal_invoice', [$dateStart, $dateEnd]);
        }

        $invoices = $query->with(['dataservice.partkeluar.datasparepat', 'datasparepat'])->get();

        if ($invoices->isEmpty()) {
            return response()->json(['message' => 'No invoices found for the specified filters'], 404);
        }

        $pdf = PDF::loadView('printpdfinvoiceall', compact('invoices'));
        return $pdf->download('Invoices.pdf');
    }

    public function print($id)
    {
        $invoice = Invoice::with(['dataservice.partkeluar.datasparepat', 'datasparepat'])->findOrFail($id);
        $pdf = PDF::loadView('printpdfinvoice', compact('invoice'))
        ->setPaper('a4', 'landscape');
        return $pdf->download('invoice.pdf');
    }
}
