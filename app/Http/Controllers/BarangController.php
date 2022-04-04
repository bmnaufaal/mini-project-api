<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Item_Penjualan;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:barang',
            'nama' => 'required',
            'kategori' => 'required',
            'harga' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Invalid input'
            ]);
        } else {
            $create = Barang::create(
                [
                    'kode' => $request->kode,
                    'nama' => $request->nama,
                    'kategori' => $request->kategori,
                    'harga' => $request->harga,
                ]
            );
            if ($create) {
                return response()->json([
                    'status' => 'Success',
                    'message' => 'Data berhasil dimasukkan'
                ]);
            } else {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Data gagal dimasukkan'
                ]);
            }
        }
    }

    public function read()
    {
        $data = Barang::all();
        return response()->json([
            'status' => 'Success',
            'data' => $data
        ]);
    }

    public function update(Request $request, $kode)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|exists:barang,kode',
            'nama' => 'required',
            'kategori' => 'required',
            'harga' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Invalid input'
            ], 400);
        } else {
            try {
                $oldHargaBarang = Barang::where('kode', $kode)->get('harga');
                Barang::where('kode', $kode)->update(
                    [
                        'kode' => $request->kode,
                        'nama' => $request->nama,
                        'kategori' => $request->kategori,
                        'harga' => $request->harga,
                    ]
                );

                $newHargaBarang = Barang::where('kode', $kode)->get('harga');
                $qty = Item_Penjualan::join('barang', 'item_penjualan.kode_barang', '=', 'barang.kode')->where('kode', $kode)->get('qty');
                $oldSubtotal = Item_Penjualan::join('barang', 'item_penjualan.kode_barang', '=', 'barang.kode')->join('penjualan', 'item_penjualan.nota', '=', 'penjualan.id_nota')->where('kode', $kode)->get('subtotal');
                $newSubtotal = 0;
                $idNota = Item_Penjualan::join('barang', 'item_penjualan.kode_barang', '=', 'barang.kode')->join('penjualan', 'item_penjualan.nota', '=', 'penjualan.id_nota')->where('kode', $kode)->get('id_nota');
                foreach ($oldSubtotal as $key => $old) {
                    $newSubtotal = $old->subtotal - ($oldHargaBarang[0]['harga'] * $qty[$key]['qty']);
                    $newSubtotal = $newSubtotal + ($newHargaBarang[0]['harga']  * $qty[$key]['qty']);
                    Penjualan::where('id_nota', $idNota[$key]['id_nota'])->update(
                        [
                            'subtotal' => $newSubtotal
                        ]
                    );
                }

                return response()->json([
                    'status' => 'Success',
                    'message' => 'Data berhasil diubah'
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                $error = (array) $e;
                return response()->json([
                    'status' => 'Error',
                    'message' => $error['errorInfo'][2],
                ], 400);
            }
        }
    }

    public function delete($kode)
    {
        if (Barang::where('kode', $kode)->exists()) {
            $hargaBarang = Barang::where('kode', $kode)->get('harga');
            $qty = Item_Penjualan::join('barang', 'item_penjualan.kode_barang', '=', 'barang.kode')->where('kode', $kode)->get('qty');
            $oldSubtotal = Item_Penjualan::join('barang', 'item_penjualan.kode_barang', '=', 'barang.kode')->join('penjualan', 'item_penjualan.nota', '=', 'penjualan.id_nota')->where('kode', $kode)->get('subtotal');
            $newSubtotal = 0;
            $idNota = Item_Penjualan::join('barang', 'item_penjualan.kode_barang', '=', 'barang.kode')->join('penjualan', 'item_penjualan.nota', '=', 'penjualan.id_nota')->where('kode', $kode)->get('id_nota');
            $delete = Barang::find($kode)->delete();
            foreach ($oldSubtotal as $key => $old) {
                $newSubtotal = $old->subtotal - ($hargaBarang[0]['harga'] * $qty[$key]['qty']);
                Penjualan::where('id_nota', $idNota[$key]['id_nota'])->update(
                    [
                        'subtotal' => $newSubtotal
                    ]
                );
            }

            if ($delete) {
                return response()->json([
                    'status' => 'Success',
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Data gagal dihapus'
                ]);
            }
        } else {
            return response()->json([
                'status' => 'Error',
                'message' => 'Kode barang tidak ditemukan'
            ], 400);
        }
    }

    public function getAllKodeBarang()
    {
        $barang =  Barang::select('kode')->get();
        return response()->json([
            'message' => 'Success',
            'data' => $barang
        ]);
    }
}
