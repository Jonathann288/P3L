<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\merchandise;
use App\Models\ClaimMerchandise;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class MerchendiseControllersAPI extends Controller
{
    public function index()
    {
        try {
            $merchandise = merchandise::all();

            return response()->json([
                'success' => true,
                'message' => 'Data merchandise berhasil diambil',
                'data' => $merchandise
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function claimMerchandise(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_merchandise' => 'required|integer|exists:merchandise,id_merchandise',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $token = $request->bearerToken();
            $tokenModel = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
            if (!$tokenModel) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Silakan login terlebih dahulu'
                ], 401);
            }

            $pembeli = $tokenModel->tokenable;
            if (!$pembeli) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Silakan login terlebih dahulu'
                ], 401);
            }

            $merchandise = Merchandise::find($request->id_merchandise);
            if (!$merchandise) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Merchandise tidak ditemukan'
                ], 404);
            }

            if ($merchandise->stok_merchandise <= 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Stok merchandise habis'
                ], 400);
            }

            if ($pembeli->total_poin < $merchandise->harga_merchandise) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Poin tidak cukup untuk klaim merchandise ini',
                    'poin_dibutuhkan' => $merchandise->harga_merchandise,
                    'poin_anda' => $pembeli->total_poin
                ], 400);
            }

            // Kurangi poin pembeli
            $pembeli->total_poin -= $merchandise->harga_merchandise;
            $pembeli->save();

            // Kurangi stok merchandise
            $merchandise->stok_merchandise -= 1;
            $merchandise->save();

            // Generate id unik untuk claimmerchandise
            $newId = $this->generateClaimMerchandiseId();

            // Simpan transaksi klaim merchandise dengan id yang sudah digenerate
            $transaksi = Claimmerchandise::create([
                'id' => $newId,
                'id_pembeli' => $pembeli->id_pembeli,
                'id_merchandise' => $merchandise->id_merchandise,
                'tanggal_claim' => now(),
                'tanggal_pengambilan' => null,
                'status' => 'belum_diambil', // misal status default
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Merchandise berhasil diklaim',
                'data' => [
                    'id_transaksi' => $transaksi->id,
                    'merchandise' => [
                        'nama' => $merchandise->nama_merchandise,
                        'poin_dikurangi' => $merchandise->harga_merchandise,
                        'foto' => $merchandise->foto_merchandise ? asset('images/' . $merchandise->foto_merchandise) : null,
                    ],
                    'pembeli' => [
                        'nama' => $pembeli->nama_pembeli,
                        'poin_sebelum' => $pembeli->total_poin + $merchandise->harga_merchandise,
                        'poin_sesudah' => $pembeli->total_poin,
                    ],
                    'tanggal_klaim' => $transaksi->tanggal_claim,
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat melakukan klaim merchandise',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Fungsi generate ID unik CMxx
    private function generateClaimMerchandiseId()
    {
        $last = Claimmerchandise::orderBy('id', 'desc')->first();

        if (!$last) {
            return 'CM01';
        }

        $lastNumber = intval(substr($last->id, 2));
        $newNumber = $lastNumber + 1;

        return 'CM' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
    }


}
