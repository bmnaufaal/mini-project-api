<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Penjualan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_nota' => 'required|unique:penjualan',
            'tgl' => 'required',
            'kode_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Invalid input'
            ]);
        } else {
            $create = Penjualan::create(
                [
                    'id_nota' => $request->id_nota,
                    'tgl' => $request->tgl,
                    'kode_pelanggan' => $request->kode_pelanggan,
                    'subtotal' => 0
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
        $data = Penjualan::all();
        return response()->json([
            'status' => 'Success',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_nota' => 'required|exists:penjualan,id_nota',
            'tgl' => 'required',
            'kode_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Invalid input'
            ], 400);
        } else {
            try {
                Penjualan::where('id_nota', $id)->update(
                    [
                        'id_nota' => $request->id_nota,
                        'tgl' => $request->tgl,
                        'kode_pelanggan' => $request->kode_pelanggan
                    ]
                );
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

    public function delete($id)
    {
        if (Penjualan::where('id_nota', $id)->exists()) {
            $delete = Penjualan::find($id)->delete();
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
                'message' => 'id_nota tidak ditemukan'
            ], 400);
        }
    }

    public function getAllIDNota()
    {
        $penjualan = Penjualan::select('id_nota')->get();
        return response()->json([
            'message' => 'Success',
            'data' => $penjualan
        ]);
    }
}
