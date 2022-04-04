<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\ItemPenjualanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenjualanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Pelanggan
Route::post('/createDataPelanggan', [PelangganController::class, 'create']);
Route::get('/getAllDataPelanggan', [PelangganController::class, 'read']);
Route::put('/updateDataPelanggan/{id}', [PelangganController::class, 'update']);
Route::delete('/deleteDataPelanggan/{id}', [PelangganController::class, 'delete']);
Route::get('/getAllIDPelanggan', [PelangganController::class, 'getAllIDPelanggan']);

// Barang
Route::post('/createDataBarang', [BarangController::class, 'create']);
Route::get('/getAllDataBarang', [BarangController::class, 'read']);
Route::put('/updateDataBarang/{kode}', [BarangController::class, 'update']);
Route::delete('/deleteDataBarang/{kode}', [BarangController::class, 'delete']);
Route::get('/getAllKodeBarang', [BarangController::class, 'getAllKodeBarang']);

// Penjualan
Route::post('/createDataPenjualan', [PenjualanController::class, 'create']);
Route::get('/getAllDataPenjualan', [PenjualanController::class, 'read']);
Route::put('/updateDataPenjualan/{id}', [PenjualanController::class, 'update']);
Route::delete('/deleteDataPenjualan/{id}', [PenjualanController::class, 'delete']);
Route::get('/getAllIDNota', [PenjualanController::class, 'getAllIDNota']);

// Item Penjualan
Route::post('/createDataItemPenjualan', [ItemPenjualanController::class, 'create']);
Route::get('/getAllDataItemPenjualan', [ItemPenjualanController::class, 'read']);
Route::put('/updateDataItemPenjualan/{id}/{kode}', [ItemPenjualanController::class, 'update']);
Route::delete('/deleteDataItemPenjualan/{id}/{kode}', [ItemPenjualanController::class, 'delete']);
