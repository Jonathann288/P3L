<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Pembeli;
use App\Models\Penitip;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class AuthControllerAPI extends Controller
{
    /**
     * Login API for multiple user types
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $email = $request->email;
            $password = $request->password;

            $user = null;
            $role = '';
            $tokenName = '';

            // Cek Pegawai
            $user = Pegawai::with('jabatan')->where('email_pegawai', $email)->first();
            if ($user && Hash::check($password, $user->password_pegawai)) {
                if (!$user->jabatan) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Akun pegawai belum terdaftar'
                    ], 401);
                }

                $namaJabatan = strtolower($user->jabatan->nama_jabatan);
                if (!in_array($namaJabatan, ['kurir', 'hunter'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Akun pegawai tidak diizinkan login'
                    ], 401);
                }

                $role = 'pegawai';
                $tokenName = 'pegawai-token';
            }

            // Cek Pembeli jika belum ketemu Pegawai
            if (!$role) {
                $user = Pembeli::where('email_pembeli', $email)->first();
                if ($user && Hash::check($password, $user->password_pembeli)) {
                    $role = 'pembeli';
                    $tokenName = 'pembeli-token';
                }
            }

            // Cek Penitip jika belum ketemu
            if (!$role) {
                $user = Penitip::where('email_penitip', $email)->first();
                if ($user && Hash::check($password, $user->password_penitip)) {
                    $role = 'penitip';
                    $tokenName = 'penitip-token';
                }
            }

            if (!$user || !$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah'
                ], 401);
            }

            // Hapus token lama
            $user->tokens()->delete();

            // Buat token baru
            $token = $user->createToken($tokenName)->plainTextToken;

            // Format data user sesuai kebutuhan (bisa disesuaikan)
            $userData = $user; // Kalau mau bisa buat fungsi formatUserData

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'role' => $role,
                'user' => $userData,
                'token' => $token,
                'token_type' => 'Bearer'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Logout API - revoke current user token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            // Hapus token saat ini
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout dari semua perangkat - revoke all user tokens
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function logoutAll(Request $request): JsonResponse
    {
        try {
            // Hapus semua token user
            $request->user()->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout dari semua perangkat berhasil'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /** 
     * Get current authenticated user info
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            // Tentukan role berdasarkan model
            $role = '';
            if ($user instanceof Pembeli) {
                $role = 'pembeli';
            } elseif ($user instanceof Penitip) {
                $role = 'penitip';
            } elseif ($user instanceof Pegawai) {
                $role = 'pegawai';
                // Load jabatan relationship if not already loaded
                if (!$user->relationLoaded('jabatan')) {
                    $user->load('jabatan');
                }

                // Validasi ulang jabatan pada saat mengambil data user
                if (!$user->jabatan) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Akun anda belum terdaftar'
                    ], 401);
                }

                $namaJabatan = strtolower($user->jabatan->nama_jabatan);

                if (!in_array($namaJabatan, ['kurir', 'hunter'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Akun anda belum terdaftar'
                    ], 401);
                }
            }

            $userData = $this->formatUserData($user, $role);
            //DEBUGING
             \Illuminate\Support\Facades\Log::info('Data User untuk /me:', $userData);

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $userData,
                    'role' => $role
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format user data berdasarkan role
     *
     * @param  mixed  $user
     * @param  string  $role
     * @return array
     */
    private function formatUserData($user, $role)
    {
        $baseData = [
            'id' => $user->id_pegawai ?? $user->id_pembeli ?? $user->id_penitip ?? null
        ];

        switch ($role) {
            case 'pembeli':
                return array_merge($baseData, [
                    'id_pembeli' => $user->id_pembeli,
                    'nama_pembeli' => $user->nama_pembeli,
                    'email_pembeli' => $user->email_pembeli,
                    // Sesuaikan nama key dan properti di sini
                    'nomor_telepon_pembeli' => $user->nomor_telepon_pembeli,
                    'tanggal_lahir' => $user->tanggal_lahir,
                    'total_poin' => $user->total_poin,
                    'foto_pembeli' => $user->foto_pembeli
                ]);

            case 'penitip':
                return array_merge($baseData, [
                    'id_penitip' => $user->id,
                    'nama_penitip' => $user->nama_penitip,
                    'nomor_ktp' => $user->nomor_ktp,
                    'email_penitip' => $user->email_penitip,
                    'tanggal_lahir' => $user->tanggal_lahir,
                    'no_telepon_penitip' => $user->nomor_telepon_penitip ?? null,
                    'rating_penitip' => $user->Rating_penitip ?? 0,
                    'saldo_penitip' => $user->saldo_penitip ?? 0,
                    'poin_penitip' => $user->total_poin ?? 0,
                    'jumlah_penjualan' => $user->jumlah_penjualan ?? 0
                ]);

            case 'pegawai':
                $pegawaiData = array_merge($baseData, [
                    'id_pegawai' => $user->id,
                    'nama_pegawai' => $user->nama_pegawai,
                    'email_pegawai' => $user->email_pegawai,
                    'nomor_telepon_pegawai' => $user->nomor_telepon_pegawai ?? null,
                    'tanggal_lahir_pegawai' => $user->tanggal_lahir_pegawai ?? null,
                    'id_jabatan' => $user->id_jabatan,
                    'jabatan' => $user->jabatan ? [
                        'id' => $user->jabatan->id,
                        'nama_jabatan' => $user->jabatan->nama_jabatan ?? null,
                        // Add other jabatan fields as needed
                    ] : null
                ]);
                return $pegawaiData;

            default:
                return $baseData;
        }
    }
}