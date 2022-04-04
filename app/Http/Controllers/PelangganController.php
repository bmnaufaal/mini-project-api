<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pelanggan' => 'required|unique:pelanggan',
            'nama' => 'required',
            'domisili' => 'required',
            'jenis_kelamin' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Invalid input'
            ]);
        } else {
            $create = Pelanggan::create(
                [
                    'id_pelanggan' => $request->id_pelanggan,
                    'nama' => $request->nama,
                    'domisili' => $request->domisili,
                    'jenis_kelamin' => $request->jenis_kelamin,
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
        $data = Pelanggan::all();
        return response()->json([
            'status' => 'Success',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'nama' => 'required',
            'domisili' => 'required',
            'jenis_kelamin' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Invalid input'
            ], 400);
        } else {
            try {
                Pelanggan::where('id_pelanggan', $id)->update(
                    [
                        'id_pelanggan' => $request->id_pelanggan,
                        'nama' => $request->nama,
                        'domisili' => $request->domisili,
                        'jenis_kelamin' => $request->jenis_kelamin,
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
        if (Pelanggan::where('id_pelanggan', $id)->exists()) {
            $delete = Pelanggan::find($id)->delete();
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
                'message' => 'id_pelanggan tidak ditemukan'
            ], 400);
        }
    }

    public function getAllIDPelanggan()
    {
        $pelanggan = Pelanggan::select('id_pelanggan')->get();
        return response()->json([
            'message' => 'Success',
            'data' => $pelanggan
        ]);
    }
}
