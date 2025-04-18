<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datashowroom;
use Barryvdh\DomPDF\Facade\Pdf;

class DatashowroomController extends Controller
{
    // Menampilkan daftar showroom dengan pagination
    public function index()
    {
        $showrooms = Datashowroom::orderBy('created_at', 'desc') // Urutkan dari yang terbaru
                                  ->paginate(10); // Menampilkan 10 data per halaman
        return view('datashowroom', compact('showrooms'));
    }

    public function create()
    {
        return view('datashowroom.create');
    }

    // Menyimpan showroom baru ke database
    public function store(Request $request)
    {
        // Validasi data dari form
        $request->validate([
            'nomor_polisi' => 'required|string|max:10',
            'merk_model' => 'required|string|max:255',
            'tahun_pembuatan' => 'required|date',
            'nomor_rangka' => 'required|string|max:20',
            'harga' => 'required|numeric',
            'nomor_mesin' => 'required|string|max:20',
            'bahan_bakar' => 'required|string|max:20',
            'kapasitas_mesin' => 'required|integer',
            'jumlah_roda' => 'required|integer',
            'tanggal_registrasi' => 'required|date',
            'masa_berlaku_stnk' => 'required|date',
            'masa_berlaku_pajak' => 'required|date',
            'status_kepemilikan' => 'required|string|max:20',
            'kilometer' => 'required|integer',
            'fitur_keamanan' => 'required|string',
            'riwayat_servis' => 'required|string',
            'status' => 'required|in:terjual,tersedia',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ambil semua data dari request
        $data = $request->all();

        // Cek apakah ada foto yang diupload
        if ($request->hasFile('foto')) {
            // Simpan foto baru di storage
            $fotoPath = $request->file('foto')->store('vehicle_photos', 'public');
            $data['foto'] = $fotoPath; // Simpan path foto ke dalam data
        }

        // Simpan showroom ke database
        Datashowroom::create($data);

        // Redirect ke halaman showroom
        return redirect()->route('datashowroom');
    }

    // Menampilkan form untuk mengedit showroom yang sudah ada
    public function edit($id)
    {
        $showroom = Datashowroom::findOrFail($id); // Ambil showroom berdasarkan ID
        return view('datashowroom.edit', compact('showroom')); // Tampilkan form edit dengan data showroom
    }

    // Memperbarui data showroom yang ada
    public function update(Request $request, $id)
    {
        // Validasi data yang diterima dari form
        $validatedData = $request->validate([
            'nomor_polisi' => 'required|string|max:10',
            'merk_model' => 'required|string|max:255',
            'tahun_pembuatan' => 'required|date', // Diubah dari integer ke date
            'nomor_rangka' => 'required|string|max:20',
            'nomor_mesin' => 'required|string|max:20',
            'bahan_bakar' => 'required|string|max:20',
            'kapasitas_mesin' => 'required|integer',
            'jumlah_roda' => 'required|integer',
            'harga' => 'required|numeric',
            'tanggal_registrasi' => 'required|date',
            'masa_berlaku_stnk' => 'required|date',
            'masa_berlaku_pajak' => 'required|date',
            'status_kepemilikan' => 'required|string|max:20',
            'kilometer' => 'required|integer',
            'fitur_keamanan' => 'required|string',
            'riwayat_servis' => 'required|string',
            'status' => 'required|in:terjual,tersedia',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Cari showroom berdasarkan ID
        $showroom = Datashowroom::findOrFail($id);

        // Format harga sebelum disimpan
        if ($request->has('harga')) {
            $validatedData['harga'] = str_replace(['Rp', '.', ','], '', $request->harga);
        }

        // Cek apakah ada foto baru yang diupload
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($showroom->foto && file_exists(storage_path('app/public/' . $showroom->foto))) {
                unlink(storage_path('app/public/' . $showroom->foto));
            }

            // Simpan foto baru
            $fotoPath = $request->file('foto')->store('vehicle_photos', 'public');
            $validatedData['foto'] = $fotoPath;
        }

        // Update data showroom
        $showroom->update($validatedData);

        // Redirect ke halaman showroom dengan pesan sukses
        return redirect()->route('datashowroom')->with('success', 'Data berhasil diperbarui');
    }

    // Menghapus showroom dari database
    public function destroy($id)
    {
        // Cari showroom berdasarkan ID
        $showroom = Datashowroom::findOrFail($id);

        // Hapus foto jika ada
        if ($showroom->foto && file_exists(storage_path('app/public/' . $showroom->foto))) {
            unlink(storage_path('app/public/' . $showroom->foto)); // Hapus foto
        }

        // Hapus showroom
        $showroom->delete();

        // Redirect ke halaman showroom
        return redirect()->route('datashowroom')->with('success', 'Data Showroom berhasil dihapus');
    }

    public function printPDF(Request $request)
    {
        // Ambil parameter pencarian dan tanggal dari request
        $search = $request->input('search');
        $date = $request->input('date');

        // Query data showroom berdasarkan pencarian dan tanggal
        $query = Datashowroom::query();

        if ($search) {
            $query->where('nomor_polisi', 'like', '%' . $search . '%')
                ->orWhere('merk_model', 'like', '%' . $search . '%')
                ->orWhere('tahun_pembuatan', 'like', '%' . $search . '%')
                ->orWhere('nomor_rangka', 'like', '%' . $search . '%')
                ->orWhere('nomor_mesin', 'like', '%' . $search . '%')
                ->orWhere('bahan_bakar', 'like', '%' . $search . '%')
                ->orWhere('kapasitas_mesin', 'like', '%' . $search . '%')
                ->orWhere('jumlah_roda', 'like', '%' . $search . '%')
                ->orWhere('harga', 'like', '%' . $search . '%')
                ->orWhere('tanggal_registrasi', 'like', '%' . $search . '%')
                ->orWhere('masa_berlaku_stnk', 'like', '%' . $search . '%')
                ->orWhere('masa_berlaku_pajak', 'like', '%' . $search . '%')
                ->orWhere('status_kepemilikan', 'like', '%' . $search . '%')
                ->orWhere('kilometer', 'like', '%' . $search . '%')
                ->orWhere('fitur_keamanan', 'like', '%' . $search . '%')
                ->orWhere('riwayat_servis', 'like', '%' . $search . '%')
                ->orWhere('status', 'like', '%' . $search . '%');
        }

        // Ambil data yang sudah difilter
        $showrooms = $query->get();

        // Pastikan path foto benar
        foreach ($showrooms as $showroom) {
            if ($showroom->foto) {
                $showroom->foto = public_path('storage/' . $showroom->foto);
            } else {
                $showroom->foto = public_path('images/default-image.jpg');
            }
        }

        // Load view ke PDF
        $pdf = Pdf::loadView('printpdfshowroom', compact('showrooms'));

        // Stream PDF untuk preview
        // return $pdf->stream('Data_ShowRoom.pdf');

        // Download PDF
        return $pdf->download('Data_ShowRoom.pdf');
    }
}
