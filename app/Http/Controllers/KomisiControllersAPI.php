<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Penitip;
use App\Models\Komisi;
use App\Models\transaksipenitipan;
use App\Models\DetailTransaksiPenitipan;
use App\Models\DetailTransaksiPenjualan;
use App\Models\transaksipenjualan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class KomisiControllersAPI extends Controller
{ 
    // public function getKomisiHistory(Request $request)
    // {
    //     $token = $request->bearerToken();

    //     // Cari token di tabel personal_access_tokens
    //     $tokenModel = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
    //     if (!$tokenModel) {
    //         return response()->json(['message' => 'Unauthorized'], 403);
    //     }

    //     $user = $tokenModel->tokenable; // atau auth()->user() jika pakai guard default

    //     // Pastikan jabatannya hunter
    //     if (!$user || strtolower($user->jabatan->nama_jabatan) !== 'hunter') {
    //         return response()->json(['message' => 'Unauthorized'], 403);
    //     }
    //     $komisiList =Komisi::with([
    //         'pegawai',
    //         'transaksipenjualan.detailtrpj.barang'  // tanpa detailpenitipan dan penitip
    //     ])
    //     ->where('id_pegawai', $user->id)
    //     ->get()
    //     ->flatMap(function ($komisi) {
    //         return $komisi->transaksipenjualan?->detailtrpj?->map(function ($detail) use ($komisi) {
    //             $barang = $detail->barang;

    //             return [
    //                 'nama_barang' => $barang->nama_barang ?? 'Tidak diketahui',
    //                 'status_transaksi' => $komisi->transaksipenjualan->status_transaksi ?? 'Tidak diketahui',
    //                 'komisi_hunter' => $komisi->komisi_hunter ?? 0,
    //             ];
    //         })?? collect();
    //     })->values();

    //     return response()->json([
    //         'data' => $komisiList
    //     ]);
    // }

    public function getKomisiHistory(Request $request)
    {
        $token = $request->bearerToken();

        $tokenModel = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
        if (!$tokenModel) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = $tokenModel->tokenable;

        if (!$user || strtolower($user->jabatan->nama_jabatan) !== 'hunter') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Ambil data pegawai lengkap dengan komisi dan transaksi penjualan terkait
        $pegawai =Pegawai::with([
            'komisi.transaksipenjualan.detailtrpj.barang'
        ])->where('id_pegawai', $user->id_pegawai ?? $user->id)->first();


        if (!$pegawai) {
            return response()->json(['message' => 'Pegawai not found'], 404);
        }

        // Map komisi ke bentuk yang kamu inginkan
        $komisiFormatted = $pegawai->komisi->map(function ($komisi) {
            return [
                'id_komisi' => $komisi->id,
                'komisi_hunter' => $komisi->komisi_hunter,
                'status_transaksi' => $komisi->transaksipenjualan?->status_transaksi ?? 'Tidak diketahui',
                'nama_barang' => $komisi-> transaksipenjualan?-> detailtrpj?-> barang?-> nama_barang ?? 'Tidak diketahui',
            ];
        });

        return response()->json([
            'data' => [
                'id_pegawai' => $pegawai->id_pegawai,
                'nama_pegawai' => $pegawai->nama_pegawai,
                'komisi' => $komisiFormatted,
            ]
        ]);

    }


}