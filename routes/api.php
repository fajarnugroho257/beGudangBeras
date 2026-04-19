<?php

use App\Http\Controllers\api\KardusController;
use App\Http\Controllers\api\KaryawanController;
use App\Http\Controllers\api\LaporanController;
use App\Http\Controllers\api\LoginController;
use App\Http\Controllers\api\NotaController;
use App\Http\Controllers\api\PembelianController;
use App\Http\Controllers\api\PengirimanController;
use App\Http\Controllers\api\SaldoController;
use App\Http\Controllers\api\SuplierController;
use App\Http\Controllers\KategoriController;
use App\Models\api\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login-api', App\Http\Controllers\api\LoginController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('pos')->middleware(['jwt.verify'])->group(function () {
    // Route::get('index', [KategoriController::class, 'index']);
});
Route::middleware('auth:api')->get('/kategori', function (Request $request) {
    return $request->user();
});
Route::middleware(['jwt.verify'])->group(function () {
    Route::post('/add-Pembelian', [PembelianController::class, 'store']);
    Route::post('/index-Pembelian', [PembelianController::class, 'index']);
    Route::get('/detail-Pembelian/{suplier_id}', [PembelianController::class, 'show']);
    Route::post('/edit-Pembelian', [PembelianController::class, 'update']);
    Route::post('/delete-Pembelian', [PembelianController::class, 'destroy']);
    Route::get('/test-cetak-image/{suplier_id}', [PembelianController::class, 'cetak_image']);
    Route::post('/cetak-laporan', [PembelianController::class, 'all_data']);
});
//
Route::get('/test-id-Pembelian/{id}', [PembelianController::class, 'last_pembelian_id']);

//
Route::get('/print', [PembelianController::class, 'print']);

// pengiriman
Route::middleware(['jwt.verify'])->group(function () {
    Route::post('/add-Pengiriman', action: [PengirimanController::class, 'store']);
    Route::post('/index-Pengiriman', [PengirimanController::class, 'index']);
    Route::get('/detail-Pengiriman/{pengiriman_id}', [PengirimanController::class, 'show']);
    Route::post('/edit-Pengiriman', [PengirimanController::class, 'update']);
    Route::get('/last_pengiriman_id/{id}', [PengirimanController::class, 'last_data_id']);
    Route::get('/get-karyawan', [PengirimanController::class, 'get_karyawan']);
    Route::post('/add-Pengiriman-Beban', [PengirimanController::class, 'store_beban']);
    Route::post('/detail-Pengiriman-Beban', [PengirimanController::class, 'list_beban_karyawan']);
    Route::post('/update-Status', [PengirimanController::class, 'update_Status']);
    //
    Route::get('/pengiriman-cetak-image/{pengiriman_id}', [PengirimanController::class, 'cetak_image']);
    Route::post('/delete-Pengiriman', [PengirimanController::class, 'destroy']);
    //
    Route::post('/edit-harga-real-Pengiriman', [PengirimanController::class, 'store_harga_real']);
});

// laporan
Route::middleware(['jwt.verify'])->group(function () {
    Route::post('/index-Laporan', [LaporanController::class, 'index']);
    Route::post('/detail-Laporan', [LaporanController::class, 'show']);
});
// Karyawan
Route::middleware(['jwt.verify'])->group(function () {
    Route::get('/index-Karyawan', [KaryawanController::class, 'index']);
    Route::post('/add-Karyawan', action: [KaryawanController::class, 'store']);
    Route::get('/detail-Karyawan/{id}', [KaryawanController::class, 'show']);
    Route::post('/edit-Karyawan', [KaryawanController::class, 'update']);
    Route::post('/gaji-print', [KaryawanController::class, 'gaji_checked']);
    // show gaji
    // Route::post('/gaji-Karyawan', [KaryawanController::class, 'show_gaji']);
    //
});

// kardus
Route::middleware(['jwt.verify'])->group(function () {
    Route::get('/index-Kardus', [KardusController::class, 'index']);
    Route::get('/detail-Kardus/{id}', [KardusController::class, 'show']);
    Route::post('/edit-Kardus', [KardusController::class, 'update']);
});

// nota
Route::middleware(['jwt.verify'])->group(function () {
    Route::post('/make-draft-nota', action: [NotaController::class, 'store']);
    Route::post('/index-draft-nota', action: [NotaController::class, 'index']);
    Route::get('/detail-draft-nota/{id}', action: [NotaController::class, 'show']);
    Route::post('/add-bayar-nota', action: [NotaController::class, 'add_bayar']);
    Route::post('/delete-bayar-nota', action: [NotaController::class, 'destroy']);
    Route::post('/show-bayar-nota', action: [NotaController::class, 'show_bayar']);
    Route::post('/edit-proses-bayar-nota', action: [NotaController::class, 'update']);
    Route::post('/update-bayar-nota', action: [NotaController::class, 'update_nota']);
    Route::post('/delete-nota', action: [NotaController::class, 'delete_nota']);

});

Route::middleware(['jwt.verify'])->group(function () {
    Route::get('/logout', action: [LoginController::class, 'logout']);
    Route::get('/non-detail-draft-nota/{id}', action: [NotaController::class, 'show']);
    Route::get('/gaji-Karyawan/{id}/{month}/{thn}', [KaryawanController::class, 'show_gaji']);
    Route::post('/update-gaji-Karyawan', [KaryawanController::class, 'update_gaji']);

    Route::get('/nota-cetak-image/{nota_id}', [NotaController::class, 'cetak_image']);
});

Route::middleware(['jwt.verify'])->group(function () {
    Route::post('/update-saldo', action: [SaldoController::class, 'store']);
});

// suplier
Route::middleware(['jwt.verify'])->group(function () {
    Route::get('/get-suplier', [SuplierController::class, 'index']);
    Route::post('/create-suplier', [SuplierController::class, 'store']);
    Route::get('/get-suplier-by-id/{id}', [SuplierController::class, 'show']);
    Route::post('/update-suplier/{id}', [SuplierController::class, 'update']);
});

// Route::get('/nota-cetak-image/{nota_id}', [NotaController::class, 'cetak_image']);
