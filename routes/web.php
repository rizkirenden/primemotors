<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatamekanikController;
use App\Http\Controllers\DatashowroomController;
use App\Http\Controllers\DatasparepatController;
use App\Http\Controllers\PartkeluarController;
use App\Http\Controllers\PartmasukController;
use App\Http\Controllers\PenggunaController;
use App\Models\Datasparepat;
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

Route::get('/datamekanik', [DatamekanikController::class, 'index'])->name('datamekanik');
Route::post('/datamekanik', [DatamekanikController::class, 'store'])->name('datamekanik.store');
Route::get('/datamekanik/create', [DatamekanikController::class, 'create'])->name('datamekanik.create');
Route::get('datamekanik/{id}/edit', [DatamekanikController::class, 'edit'])->name('datamekanik.edit');
Route::put('datamekanik/{id}', [DatamekanikController::class, 'update'])->name('datamekanik.update');
Route::delete('datamekanik/{id}', [DatamekanikController::class, 'destroy'])->name('datamekanik.destroy');

Route::get('/datasparepat', [DatasparepatController::class, 'index'])->name('datasparepat');
Route::post('/datasparepat', [DatasparepatController::class, 'store'])->name('datasparepat.store');
Route::get('/datasparepat/create',[DatasparepatController::class, 'create'])->name('datasparepat.create');
Route::get('datasparepat/{id}/edit', [DatasparepatController::class, 'edit'])->name('datasparepat.edit');
Route::put('/datasparepat/{id}', [DataSparepatController::class, 'update'])->name('datasparepat.update');
Route::delete('datasparepat/{id}', [DatasparepatController::class, 'destroy'])->name('datasparepat.destroy');

Route::get('/partmasuk', [PartmasukController::class, 'index'])->name('partmasuk');
Route::post('/partmasuk', [PartmasukController::class, 'store'])->name('partmasuk.store');
Route::get('/spareparts/{kode_barang}', [PartmasukController::class, 'getSparepartByKode']);
Route::delete('/partmasuk/{id}', [PartmasukController::class, 'destroy'])->name('partmasuk.destroy');

Route::get('/partkeluar',[PartkeluarController::class, 'index'])->name('partkeluar');
Route::post('/partkeluar', [PartKeluarController::class, 'store'])->name('partkeluar.store');
Route::delete('/partkeluar/{id}', [PartKeluarController::class, 'destroy'])->name('partkeluar.destroy');

Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna');

Route::get('/datashowroom', [DatashowroomController::class, 'index'])->name('datashowroom');
Route::post('/datashowroom', [DatashowroomController::class,'store'])->name('datashowroom.store');
Route::get('/datashowroom/create', [DatashowroomController::class,'create'])->name('datashowroom.create');
Route::get('datashowroom/{id}/edit', [DatashowroomController::class,'edit'])->name('datashowroom.edit');
Route::put('datashowroom/{id}', [DatashowroomController::class, 'update'])->name('datashowroom.update');
Route::delete('datashowroom/{id}', [DatashowroomController::class,'destroy'])->name('datashowroom.destroy');
