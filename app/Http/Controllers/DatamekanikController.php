<?php

namespace App\Http\Controllers;

use App\Models\Datamekanik; // Import your model
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DatamekanikController extends Controller
{
    // Index method to return the view
    public function index()
    {
        $mekaniks = Datamekanik::paginate(10); // You can adjust the pagination as needed
        return view('datamekanik', compact('mekaniks')); // Make sure your view is datamekanik.index
    }

    // Store method to handle form submission
    public function store(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'nama_mekanik' => 'required',
            'nomor_hp' => 'required',
            'alamat' => 'required',
            'tanggal_lahir' => 'required|date',
            'tanggal_masuk_karyawan' => 'required|date',
        ]);

        // Create a new record in the database
        Datamekanik::create($request->all());

        // Redirect after successfully storing the data
        return redirect()->route('datamekanik'); // Redirect to the index route
    }

    // Edit method to return the edit view with the specific mekanik data
    public function edit($id)
    {
        // Find the mekanik by ID
        $mekanik = Datamekanik::findOrFail($id); // Use findOrFail to ensure data is found
        return view('mekanik.edit', compact('mekanik')); // Pass the mekanik data to the edit view
    }

    // Update method to handle the update logic
    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
            'nama_mekanik' => 'required',
            'nomor_hp' => 'required',
            'alamat' => 'required',
            'tanggal_lahir' => 'required|date',
            'tanggal_masuk_karyawan' => 'required|date',
        ]);

        // Find the mekanik by ID
        $mekanik = Datamekanik::findOrFail($id); // Use findOrFail to get the mekanik

        // Update the mekanik record
        $mekanik->update($request->all());

        // Redirect back to the index route after updating
        return redirect()->route('datamekanik');
    }

    // Destroy method to delete a mekanik record
    public function destroy($id)
    {
        // Find the mekanik by ID
        $mekanik = Datamekanik::findOrFail($id);

        // Delete the mekanik record
        $mekanik->delete();

        // Redirect back to the index route after deleting
        return redirect()->route('datamekanik');
    }
    public function printPDF(Request $request)
    {
        // Ambil parameter pencarian dan tanggal dari request
        $search = $request->input('search');
        $date = $request->input('date');

        // Query data mekanik berdasarkan pencarian dan tanggal
        $query = Datamekanik::query();

        if ($search) {
            $query->where('nama_mekanik', 'like', '%' . $search . '%')
                  ->orWhere('nomor_hp', 'like', '%' . $search . '%')
                  ->orWhere('alamat', 'like', '%' . $search . '%');
        }

        if ($date) {
            $query->whereDate('tanggal_lahir', $date);
        }

        // Ambil data yang sudah difilter
        $mekaniks = $query->get();

        // Load view ke PDF
        $pdf = Pdf::loadView('printpdfdatamekanik', compact('mekaniks'));

        // Download PDF
        return $pdf->download('Data_Mekanik.pdf');

        // Atau tampilkan langsung di browser
        // return $pdf->stream('Data_Mekanik.pdf');
    }
}
