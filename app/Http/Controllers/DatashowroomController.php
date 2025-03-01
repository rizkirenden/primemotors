<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datashowroom;

class DatashowroomController extends Controller
{
    // Menampilkan daftar showroom dengan pagination
    public function index()
    {
        $showrooms = Datashowroom::paginate(10); // Menampilkan 10 data per halaman
        return view('datashowroom', compact('showrooms'));
    }

    // Menampilkan form untuk menambahkan showroom baru
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
        $request->validate([
            'nomor_polisi' => 'required|string|max:10',
            'merk_model' => 'required|string|max:255',
            'tahun_pembuatan' => 'required|integer',
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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi upload foto
        ]);

        // Cari showroom berdasarkan ID
        $showroom = Datashowroom::findOrFail($id);

        // Cek apakah ada foto baru yang diupload
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($showroom->foto && file_exists(storage_path('app/public/' . $showroom->foto))) {
                unlink(storage_path('app/public/' . $showroom->foto)); // Hapus foto lama
            }

            // Simpan foto baru
            $fotoPath = $request->file('foto')->store('vehicle_photos', 'public');
            $showroom->foto = $fotoPath; // Update path foto
        }

        // Update data showroom di database, kecuali foto
        $showroom->update($request->except('foto'));

        // Redirect ke halaman showroom
        return redirect()->route('datashowroom');
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
        return redirect()->route('datashowroom');
    }
}
