<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatamekanikController;
use App\Http\Controllers\DatashowroomController;
use App\Http\Controllers\DatasparepatController;
use App\Http\Controllers\PartkeluarController;
use App\Http\Controllers\PartmasukController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\DataserviceController;
use App\Http\Controllers\InsentifController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\UraianpekerjaanController;
use App\Http\Controllers\JualpartController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
});
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/get-invoice-data', [DashboardController::class, 'getInvoiceDataByYear']);
Route::get('/get-part-keluar-data', [DashboardController::class, 'getPartKeluarDataByYear']);
Route::get('/get-part-masuk-data', [DashboardController::class, 'getPartMasukDataByYear']);
Route::get('/get-data-by-year', [DashboardController::class, 'getDataByYear']);

Route::get('/datamekanik', [DatamekanikController::class, 'index'])->name('datamekanik');
Route::post('/datamekanik', [DatamekanikController::class, 'store'])->name('datamekanik.store');
Route::get('/datamekanik/create', [DatamekanikController::class, 'create'])->name('datamekanik.create');
Route::get('datamekanik/{id}/edit', [DatamekanikController::class, 'edit'])->name('datamekanik.edit');
Route::put('datamekanik/{id}', [DatamekanikController::class, 'update'])->name('datamekanik.update');
Route::delete('datamekanik/{id}', [DatamekanikController::class, 'destroy'])->name('datamekanik.destroy');
Route::get('/printpdfdatamekanik', [DataMekanikController::class, 'printPDF'])->name('printpdfdatamekanik');

Route::get('/datasparepat', [DatasparepatController::class, 'index'])->name('datasparepat');
Route::post('/datasparepat', [DatasparepatController::class, 'store'])->name('datasparepat.store');
Route::get('/datasparepat/create',[DatasparepatController::class, 'create'])->name('datasparepat.create');
Route::get('datasparepat/{id}/edit', [DatasparepatController::class, 'edit'])->name('datasparepat.edit');
Route::put('/datasparepat/{id}', [DataSparepatController::class, 'update'])->name('datasparepat.update');
Route::delete('datasparepat/{id}', [DatasparepatController::class, 'destroy'])->name('datasparepat.destroy');
Route::get('/printpdfdatasparepat', [DatasparepatController::class, 'printPDF'])->name('printpdfdatasparepat');


Route::get('/partmasuk', [PartmasukController::class, 'index'])->name('partmasuk');
Route::post('/partmasuk', [PartmasukController::class, 'store'])->name('partmasuk.store');
Route::put('/partmasuk/{id}', [PartmasukController::class, 'update'])->name('partmasuk.update');
Route::get('/spareparts/{kode_barang}', [PartmasukController::class, 'getSparepartByKode']);
Route::delete('/partmasuk/{id}', [PartmasukController::class, 'destroy'])->name('partmasuk.destroy');
Route::get('/printpdfpartmasuk', [PartmasukController::class, 'printPDF'])->name('printpdfpartmasuk');

Route::get('/partkeluar',[PartkeluarController::class, 'index'])->name('partkeluar');
Route::post('/partkeluar', [PartKeluarController::class, 'store'])->name('partkeluar.store');
Route::put('/partkeluar/{id}', [PartkeluarController::class, 'update'])->name('partkeluar.update');
Route::delete('/partkeluar/{id}', [PartKeluarController::class, 'destroy'])->name('partkeluar.destroy');
Route::get('/printpdfpartkeluar', [PartkeluarController::class, 'printPDF'])->name('printpdfpartkeluar');
Route::put('/partkeluar/{id}/approve', [PartkeluarController::class, 'approve'])->name('partkeluar.approve');
Route::put('/partkeluar/{id}/cancel', [PartkeluarController::class, 'cancel'])->name('partkeluar.cancel');

Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna');
Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
Route::put('/pengguna/{pengguna}', [PenggunaController::class, 'update'])->name('pengguna.update');
Route::delete('/pengguna/{pengguna}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/datashowroom', [DatashowroomController::class, 'index'])->name('datashowroom');
Route::post('/datashowroom', [DatashowroomController::class, 'store'])->name('datashowroom.store');
Route::get('/datashowroom/{id}/edit', [DatashowroomController::class, 'edit'])->name('datashowroom.edit');
Route::put('/datashowroom/{id}', [DatashowroomController::class, 'update'])->name('datashowroom.update');
Route::delete('/datashowroom/{id}', [DatashowroomController::class, 'destroy'])->name('datashowroom.destroy');
Route::get('/printpdfshowroom', [DatashowroomController::class, 'printPDF'])->name('printpdfshowroom');

Route::get('/dataservice', [DataserviceController::class, 'index'])->name('dataservice');
Route::post('/dataservice', [DataserviceController::class, 'store'])->name('dataservice.store');
Route::put('/dataservice/{id}', [DataserviceController::class, 'update'])->name('dataservice.update');
Route::put('/dataservice/{id}/updateawal', [DataserviceController::class, 'updateawal'])->name('dataservice.updateawal');
Route::delete('/dataservice/{id}', [DataserviceController::class, 'destroy'])->name('dataservice.destroy');
Route::get('/dataservice/{id}/check-partkeluar', [DataserviceController::class, 'checkPartkeluar']);
Route::get('/printpdfdataspkawal', [DataserviceController::class, 'printspkawalPDF'])->name('printpdfdataspkawal');
Route::get('/printpdfdatasakhir', [DataserviceController::class, 'printspkakhirPDF'])->name('printpdfdataspkakhir');
Route::get('/printpdfdataspkawalperdata/{id}', [DataserviceController::class, 'printAwalPerData'])->name('printpdfdataspkawal.perdata');
Route::get('/printpdfdataspkakhirperdata/{id}', [DataserviceController::class, 'printAkhirPerData'])->name('printpdfdataspkakhir.perdata');

Route::get('/laporantransaksi', [InvoiceController::class, 'index'])->name('laporantransaksi');
Route::post('/invoice/{id}', [InvoiceController::class, 'store'])->name('invoice.store');
Route::post('/invoice/update/{id}', [InvoiceController::class, 'update'])->name('invoice.update');
Route::delete('/invoice/{id}', [InvoiceController::class, 'destroy'])->name('invoice.destroy');
Route::get('/printpdfinvoice/{id}', [InvoiceController::class, 'print'])->name('invoice.print');
Route::get('/printpdfinvoiceall', [InvoiceController::class, 'printPDF'])->name('invoice.printall');

Route::get('/insentif', [InsentifController::class, 'index'])->name('insentif');
Route::get('/printpdfinsentifperdata/{nama_mekanik}', [InsentifController::class, 'printInsentif'])->name('insentif.print');
Route::get('/insentif/print-all', [InsentifController::class, 'printAllInsentif'])->name('printpdfinsentif');

Route::get('/uraianpekerjaan', [UraianpekerjaanController::class, 'index'])->name('uraianpekerjaan');
Route::post('/uraianpekerjaan', [UraianpekerjaanController::class, 'store'])->name('uraianpekerjaan.store');
Route::put('/uraianpekerjaan/{id}', [UraianpekerjaanController::class, 'update'])->name('uraianpekerjaan.update');
Route::delete('/uraianpekerjaan/{id}', [UraianpekerjaanController::class, 'destroy'])->name('uraianpekerjaan.destroy');
Route::get('/printpdfuraianpekerjaan', [UraianpekerjaanController::class, 'printPDF'])->name('printpdfuraianpekerjaan');

Route::get('/jualpart', [JualpartController::class, 'index'])->name('jualpart');
Route::post('/jualpart', [JualpartController::class, 'store'])->name('jualpart.store');
Route::put('/jualpart/{id}', [JualpartController::class, 'update'])->name('jualpart.update');
Route::get('/jualpart/{id}/edit', [JualPartController::class, 'edit'])->name('jualpart.edit');
Route::delete('jualpart/{id}', [JualpartController::class, 'destroy'])->name('jualpart.destroy');
Route::get('/jualpart/{id}/check-items', [JualpartController::class, 'checkItems']);
Route::get('/spareparts/{kode_barang}', [JualpartController::class, 'getSparepart']);
Route::get('/printpdfjualpart', [JualpartController::class, 'printPDF'])->name('printpdfjualpart');
Route::get('/printpdfjualpartperdata/{id}', [JualpartController::class, 'printPDFPerData'])->name('printpdfjualpart.perdata');

