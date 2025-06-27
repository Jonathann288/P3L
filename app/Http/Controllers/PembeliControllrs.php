<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembeli;
use App\Models\Barang;
use App\Models\Keranjang;
use App\Models\TransaksiPenjualan;
use App\Models\DetailTransaksiPenitipan;
use App\Models\DetailTransaksiPenjualan; 
use App\Models\Penitip;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
class PembeliControllrs extends Controller
{
    public function showRegisterForm()
    {
        return view('registerPembeli'); // Pastikan view ini ada di resources/views/pembeli/register.blade.php
    }

    public function registerPembeli(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'nomor_telepon_pembeli' => 'required|string|max:20',
            'email_pembeli' => 'required|string|email|max:255|unique:pembeli,email_pembeli',
            'password_pembeli' => 'required|string|min:8',
            'foto_pembeli' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'total_poin' => 'nullable|integer'
        ]);

        // Generate ID unik
        $lastPembeli = DB::table('pembeli')
            ->select('id')
            ->where('id', 'like', 'PB%')
            ->orderByDesc('id')
            ->first();

        $newNumber = $lastPembeli ? ((int) substr($lastPembeli->id, 2)) + 1 : 1;
        $newId = 'PB' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $foto_pembeli_path = null;
        if ($request->hasFile('foto_pembeli')) {
            $foto_pembeli_path = $request->file('foto_pembeli')->store('images', 'public');
        }

        $pembeli = Pembeli::create([
            'id' => $newId,
            'nama_pembeli' => $request->nama_pembeli,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nomor_telepon_pembeli' => $request->nomor_telepon_pembeli,
            'email_pembeli' => $request->email_pembeli,
            'password_pembeli' => Hash::make($request->password_pembeli),
            'foto_pembeli' => $foto_pembeli_path,
            'total_poin' => $request->total_poin ?? 0
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function show()
    {
        try {
            // Ambil data pembeli yang sedang login
            $pembeli = Auth::guard('pembeli')->user();

            // Return view dengan data pembeli
            return view('pembeli.profilPembeli', compact('pembeli'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showHistory()
    {
        try {
            // Ambil data pembeli yang sedang login
            $pembeli = Auth::guard('pembeli')->user();

            // Ambil transaksi berdasarkan id_pembeli, eager load detail + barang, dan urutkan berdasarkan tanggal
            $transaksiPenjualan = transaksipenjualan::where('id_pembeli', $pembeli->id_pembeli)
                ->with(['detailTransaksiPenjualan.barang.detailtransaksipenitipan.transaksiPenitipan.penitip'])
                ->orderBy('tanggal_transaksi', 'desc')
                ->get();

            return view('pembeli.historyPembeli', compact('pembeli', 'transaksiPenjualan'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function update(Request $request)
    {
        try {
            $request->validate([
                'id_pembeli' => 'required|exists:pembeli,id_pembeli',
                'nama_pembeli' => 'required|string|max:255',
                'email_pembeli' => 'required|email',
                'nomor_telepon_pembeli' => 'required',
                'tanggal_lahir' => 'required|date',
            ]);

            $pembeli = Pembeli::find($request->id_pembeli);

            if (!$pembeli) {
                return redirect()->back()->with('error', 'Data Pembeli tidak ditemukan!');
            }

            $pembeli->nama_pembeli = $request->nama_pembeli;
            $pembeli->email_pembeli = $request->email_pembeli;
            $pembeli->nomor_telepon_pembeli = $request->nomor_telepon_pembeli;
            $pembeli->tanggal_lahir = $request->tanggal_lahir;
            $pembeli->save();

            return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateFoto(Request $request, $id)
    {
        $request->validate([
            'foto_pembeli' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $pembeli = Pembeli::findOrFail($id);

        if ($request->hasFile('foto_pembeli')) {
            $imageName = time() . '.' . $request->foto_pembeli->extension();
            $request->foto_pembeli->move(public_path('uploads'), $imageName);
            $pembeli->foto_pembeli = 'uploads/' . $imageName;
        }

        $pembeli->save();

        return redirect()->route('pembeli.profilPembeli')->with('success', 'Foto profil berhasil diupdate.');
    }

    public function submitRating(Request $request)
    {
        try {
            $request->validate([
                'detail_transaksi_id' => 'required|exists:detailtransaksipenjualan,id_detail_transaksi_penjualan',
                'rating' => 'required|integer|min:1|max:5',
            ]);

            $detailTransaksi = DetailTransaksiPenjualan::with([
                'transaksipenjualan', 
                'barang.detailTransaksiPenitipan.transaksiPenitipan.penitip' // Eager load path ke penitip
            ])->find($request->detail_transaksi_id);
            
            $pembeli = Auth::guard('pembeli')->user();

            if (!$detailTransaksi || !$detailTransaksi->transaksipenjualan) {
                return response()->json(['message' => 'Detail transaksi tidak ditemukan.'], 404);
            }
            
            if ($detailTransaksi->transaksipenjualan->id_pembeli !== $pembeli->id_pembeli) {
                return response()->json(['message' => 'Unauthorized. Transaksi ini bukan milik Anda.'], 403);
            }
            
            $barang = $detailTransaksi->barang;
            $penitip = null;

            if ($barang && $barang->penitip_data) { // Menggunakan accessor yang sudah dibuat
                $penitip = $barang->penitip_data;
            }
            
            if (!$penitip) {
                Log::warning("Penitip tidak ditemukan untuk barang_id: " . ($barang ? $barang->id_barang : 'N/A') . " melalui jalur penitipan. Detail Transaksi Penjualan ID: " . $detailTransaksi->id_detail_transaksi_penjualan);
                // Rating tetap disimpan, tapi penitipnya tidak ketemu untuk update rata-rata.
            }

            $detailTransaksi->rating_untuk_penitip = $request->rating;
            $detailTransaksi->save();
            
            $newAverageRating = null;
            if ($penitip) {
                $penitip->updateAverageRating();
                $newAverageRating = $penitip->rating_penitip;
            }

            return response()->json([
                'success' => true,
                'message' => $penitip ? 'Rating berhasil disimpan!' : 'Rating disimpan, namun data penitip tidak terjangkau untuk update rata-rata.',
                'new_average_rating' => $newAverageRating,
                'penitip_id' => $penitip ? $penitip->id_penitip : null, // Kirim penitip_id untuk update UI
                'detail_id' => $detailTransaksi->id_detail_transaksi_penjualan,
                'given_rating' => $detailTransaksi->rating_untuk_penitip
            ], $penitip ? 200 : 202); // 202 jika penitip tidak terupdate

        } catch (ValidationException $e) {
            Log::warning("Validation error in submitRating: " . $e->getMessage(), $e->errors());
            return response()->json(['message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error("General error in submitRating: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . " Trace: " . $e->getTraceAsString());
            return response()->json(['message' => 'Terjadi kesalahan pada server. Silakan coba lagi nanti.'], 500);
        }
    }

    public function showPembatalanTransaksi()
    {
        try {
            $pembeli = Auth::guard('pembeli')->user();

            $transaksiBisaDibatalkan = TransaksiPenjualan::where('id_pembeli', $pembeli->id_pembeli)
                ->whereIn('status_transaksi', ['sedang disiapkan', 'dibatalkan pembeli'])
                ->with('detailTransaksiPenjualan')
                ->orderBy('tanggal_transaksi', 'desc')
                ->get();

            return view('pembeli.pembatalanTransaksiValid', compact('pembeli', 'transaksiBisaDibatalkan'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



    public function batalkanTransaksi($id)
    {
        try {
            $pembeli = Auth::guard('pembeli')->user();

            $transaksi = TransaksiPenjualan::where('id_transaksi_penjualan', $id)
                ->where('id_pembeli', $pembeli->id_pembeli)
                ->where('status_transaksi', 'sedang disiapkan')
                ->with('detailTransaksiPenjualan.barang')
                ->first();

            if (!$transaksi) {
                return redirect()->back()->with('error', 'Transaksi tidak ditemukan atau tidak dapat dibatalkan.');
            }

            $totalHarga = $transaksi->detailTransaksiPenjualan->sum('total_harga');

            $poinReward = floor($totalHarga / 10000);

            $pembeli->total_poin += $poinReward;
            $pembeli->save();

            foreach ($transaksi->detailTransaksiPenjualan as $detail) {
                if ($detail->barang && $detail->barang->status_barang === 'laku') {
                    $detail->barang->status_barang = 'avaliable';
                    $detail->barang->save();
                }
            }

            $transaksi->status_transaksi = 'dibatalkan pembeli';
            $transaksi->status_pembayaran = 'dibatalkan';
            $transaksi->save();

            return redirect()->back()->with('success', 'Transaksi berhasil dibatalkan dan poin ditambahkan.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}