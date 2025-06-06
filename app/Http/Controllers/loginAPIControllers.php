<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Penitip;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth; 

/**
 * API Login untuk Penitip
 * 
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
class loginAPIControllers extends Controller
{
    public function login(Request $request)
{
    // Validasi credentials
    $penitip = Penitip::where('email_penitip', $request->email_penitip)->first();
    
    if ($penitip && Hash::check($request->password_penitip, $penitip->password_penitip)) {
        $token = $penitip->createToken('auth-token')->plainTextToken;
        
        return response()->json([
            'success' => true,
            'token' => $token,
            'penitip' => $penitip
        ]);
    }
    
    return response()->json([
        'success' => false,
        'message' => 'Invalid credentials'
    ]);
}
    public function loginAPIControllers(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'email_penitip' => 'required|email',
                'password_penitip' => 'required|min:6'
            ], [
                'email_penitip.required' => 'Email tidak boleh kosong',
                'email_penitip.email' => 'Format email tidak valid',
                'password_penitip.required' => 'Password tidak boleh kosong',
                'password_penitip.min' => 'Password minimal 6 karakter'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Cari penitip berdasarkan email
            $penitip = Penitip::where('email_penitip', $request->email_penitip)->first();

            // Cek apakah penitip ada dan password cocok
            if (!$penitip || !Hash::check($request->password_penitip, $penitip->password_penitip)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah'
                ], 401);
            }

            // === OPSI 1: Menggunakan Laravel Sanctum ===
            // Hapus token lama jika ada
            $penitip->tokens()->delete();

            // Buat token baru
            $token = $penitip->createToken('penitip-token', ['penitip'])->plainTextToken;

            // === OPSI 2: Menggunakan JWT (uncomment jika menggunakan JWT) ===
            // $token = JWTAuth::fromUser($penitip);

            // Login penitip ke guard
            Auth::guard('penitip')->login($penitip);

            // Log aktivitas login
            Log::info('Penitip login via API', [
                'id_penitip' => $penitip->id_penitip,
                'email' => $penitip->email_penitip,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'penitip' => [
                        'id_penitip' => $penitip->id_penitip,
                        'nama_penitip' => $penitip->nama_penitip,
                        'email_penitip' => $penitip->email_penitip,
                        'nomor_telepon_penitip' => $penitip->nomor_telepon_penitip,
                        'saldo_penitip' => $penitip->saldo_penitip,
                        'total_poin' => $penitip->total_poin,
                        'badge' => $penitip->badge,
                        'jumlah_penjualan' => $penitip->jumlah_penjualan,
                        'rating_penitip' => $penitip->rating_penitip,
                        'foto_profil' => $penitip->foto_profil
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error saat login penitip via API', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    /**
     * API Logout untuk Penitip
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiLogoutPenitip(Request $request)
    {
        try {
            $penitip = Auth::guard('sanctum')->user(); // Untuk Sanctum
            // $penitip = JWTAuth::parseToken()->authenticate(); // Untuk JWT

            if (!$penitip) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak valid atau sudah kedaluwarsa'
                ], 401);
            }

            // Log aktivitas logout
            Log::info('Penitip logout via API', [
                'id_penitip' => $penitip->id_penitip,
                'email' => $penitip->email_penitip,
                'ip' => $request->ip()
            ]);

            // === OPSI 1: Untuk Sanctum ===
            // Hapus token saat ini
            $request->user()->currentAccessToken()->delete();

            // Atau hapus semua token penitip
            // $penitip->tokens()->delete();

            // === OPSI 2: Untuk JWT (uncomment jika menggunakan JWT) ===
            // JWTAuth::invalidate(JWTAuth::getToken());

            // Logout dari guard
            Auth::guard('penitip')->logout();

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error saat logout penitip via API', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    /**
     * API untuk mendapatkan profil penitip yang sedang login
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiProfilePenitip(Request $request)
    {
        try {
            $penitip = Auth::guard('sanctum')->user(); // Untuk Sanctum
            // $penitip = JWTAuth::parseToken()->authenticate(); // Untuk JWT

            if (!$penitip) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak valid atau sudah kedaluwarsa'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data profil berhasil diambil',
                'data' => [
                    'penitip' => [
                        'id_penitip' => $penitip->id_penitip,
                        'nama_penitip' => $penitip->nama_penitip,
                        'email_penitip' => $penitip->email_penitip,
                        'nomor_telepon_penitip' => $penitip->nomor_telepon_penitip,
                        'saldo_penitip' => $penitip->saldo_penitip,
                        'total_poin' => $penitip->total_poin,
                        'badge' => $penitip->badge,
                        'jumlah_penjualan' => $penitip->jumlah_penjualan,
                        'rating_penitip' => $penitip->rating_penitip,
                        'foto_profil' => $penitip->foto_profil,
                        'tanggal_lahir' => $penitip->tanggal_lahir,
                        'nomor_ktp' => $penitip->nomor_ktp
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error saat mengambil profil penitip via API', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    /**
     * API untuk refresh token (opsional, untuk JWT)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiRefreshTokenPenitip(Request $request)
    {
        try {
            // Hanya untuk JWT
            $newToken = JWTAuth::refresh(JWTAuth::getToken());

            return response()->json([
                'success' => true,
                'message' => 'Token berhasil diperbarui',
                'data' => [
                    'token' => $newToken,
                    'token_type' => 'Bearer'
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error saat refresh token penitip', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui token'
            ], 401);
        }
    }
}