<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Item_Penjualan;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemPenjualanController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nota' => 'required|exists:penjualan,id_nota',
            'kode_barang' => 'required|exists:barang,kode',
            'qty' => 'required',
        ]);

        if (Item_Penjualan::where('nota', $request->nota)->where('kode_barang', $request->kode_barang)->exists()) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Invalid input'
            ]);
        } else {
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Invalid input'
                ]);
            } else {
                $create = Item_Penjualan::create(
                    [
                        'nota' => $request->nota,
                        'kode_barang' => $request->kode_barang,
                        'qty' => $request->qty
                    ]
                );

                $qty = Item_Penjualan::join('barang', 'item_penjualan.kode_barang', '=', 'barang.kode')->where('nota', $request->nota)->where('kode', $request->kode_barang)->get('qty');
                $hargaBarang = Barang::where('kode', $request->kode_barang)->get('harga');
                $subtotalTemp = Penjualan::where('id_nota', $request->nota)->get('subtotal');
                $subtotal = 0;

                foreach ($subtotalTemp as $s) {
                    $subtotal = $s->subtotal;
                }

                foreach ($qty as $quantity) {
                    foreach ($hargaBarang as $harga) {
                        $subtotal = $subtotal + ($quantity->qty * $harga->harga);
                    }
                }

                Penjualan::where('id_nota', $request->nota)->update(
                    [
                        'subtotal' => $subtotal
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
    }

    public function read()
    {
        $data = Item_Penjualan::all();
        return response()->json([
            'status' => 'Success',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id, $kode)
    {
        $validator = Validator::make($request->all(), [
            'qty' => 'required',
        ]);

        if (Item_Penjualan::where('nota', $id)->where('kode_barang', $kode)->exists()) {
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Invalid input'
                ], 400);
            } else {
                $currentQty = Item_Penjualan::join('barang', 'item_penjualan.kode_barang', '=', 'barang.kode')->where('nota', $id)->where('kode', $kode)->get('qty');
                $update = (Item_Penjualan::where('nota', $id)->where('kode_barang', $kode)->update(
                    [
                        'nota' => $id,
                        'kode_barang' => $kode,
                        'qty' => $request->qty
                    ]
                ));

                $qty = Item_Penjualan::join('barang', 'item_penjualan.kode_barang', '=', 'barang.kode')->where('nota', $id)->where('kode', $kode)->get('qty');
                $hargaBarang = Barang::where('kode', $kode)->get('harga');
                $subtotalTemp = Penjualan::where('id_nota', $id)->get('subtotal');
                $subtotal = 0;

                foreach ($subtotalTemp as $s) {
                    $subtotal = $s->subtotal;
                }

                foreach ($currentQty as $oldQuantity) {
                    foreach ($hargaBarang as $harga) {
                        $subtotal = $subtotal - ($oldQuantity->qty * $harga->harga);
                    }
                }

                foreach ($qty as $newQuantity) {
                    foreach ($hargaBarang as $harga) {
                        $subtotal = $subtotal + ($newQuantity->qty * $harga->harga);
                    }
                }

                Penjualan::where('id_nota', $id)->update(
                    [
                        'subtotal' => $subtotal
                    ]
                );

                if ($update) {
                    return response()->json([
                        'status' => 'Success',
                        'message' => 'Data berhasil diubah'
                    ]);
                } else {
                    return response()->json([
                        'status' => 'Error',
                        'message' => 'Data gagal diubah'
                    ]);
                }
            }
        } else {
            return response()->json([
                'status' => 'Error',
                'message' => 'Invalid input'
            ], 400);
        }
    }

    public function delete($id, $kode)
    {
        if (Item_Penjualan::where('nota', $id)->where('kode_barang', $kode)->exists()) {
            $currentQty = Item_Penjualan::join('barang', 'item_penjualan.kode_barang', '=', 'barang.kode')->where('nota', $id)->where('kode', $kode)->get('qty');
            $delete = Item_Penjualan::where('nota', $id)->where('kode_barang', $kode)->delete();

            $hargaBarang = Barang::where('kode', $kode)->get('harga');
            $subtotalTemp = Penjualan::where('id_nota', $id)->get('subtotal');
            $subtotal = 0;

            foreach ($subtotalTemp as $s) {
                $subtotal = $s->subtotal;
            }

            foreach ($currentQty as $oldQuantity) {
                foreach ($hargaBarang as $harga) {
                    $subtotal = $subtotal - ($oldQuantity->qty * $harga->harga);
                }
            }

            Penjualan::where('id_nota', $id)->update(
                [
                    'subtotal' => $subtotal
                ]
            );

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
                'message' => 'Invalid input'
            ], 400);
        }
    }
}
