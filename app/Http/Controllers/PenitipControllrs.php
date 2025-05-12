<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penitip;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenitipControllrs extends Controller
{   

    public function showlistPenitip()
    {
        try {
            $pegawaiLogin = Auth::guard('pegawai')->user();
            // Ambil semua data pegawai beserta jabatannya
            $penitip = penitip::all();
            // Return ke view dengan data pegawai
            return view('CustomerService.DashboardPenitip', compact('penitip','pegawaiLogin'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function registerPenitip(Request $request)
    {   
        $request->validate([
            'nama_penitip' => 'required|string|max:255',
            'nomor_ktp' => 'required|string|max:16|unique:penitip,nomor_ktp',
            'email_penitip' => 'required|string|email|max:255|unique:penitip,email_penitip',
            'tanggal_lahir' => 'required|date',
            'password_penitip' => 'required|string|min:8',
            'nomor_telepon_penitip' => 'required|string|max:20',
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048', // maksimal 2MB
        ]);

        // Generate ID Penitip otomatis
        $lastPenitip = DB::table('penitip')
            ->select('id')
            ->where('id', 'like', 'P%')
            ->orderByDesc('id')
            ->first();

        $newNumber = $lastPenitip ? ((int) substr($lastPenitip->id, 2)) + 1 : 1;
        $newId = 'P' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        // Upload Foto KTP
        if ($request->hasFile('foto_ktp')) {
            $imageName = time() . '.' . $request->foto_ktp->extension();
            $request->foto_ktp->move(public_path('uploads'), $imageName);
            $foto_ktp_path = 'uploads/' . $imageName;
        } else {
            $foto_ktp_path = null;
        }

        // Simpan data penitip ke database
        Penitip::create([
            'id' => $newId,
            'nama_penitip' => $request->nama_penitip,
            'nomor_ktp' => $request->nomor_ktp,
            'email_penitip' => $request->email_penitip,
            'tanggal_lahir' => $request->tanggal_lahir,
            'password_penitip' => Hash::make($request->password_penitip),
            'nomor_telepon_penitip' => $request->nomor_telepon_penitip,
            'saldo_penitip' => 0, // Default saldo penitip
            'total_poin' => 0,    // Default poin penitip
            'badge' => null,      // Default badge penitip
            'jumlah_penjualan' => 0, // Default jumlah penjualan
            'foto_profil' => null, // Default foto profil, bisa diubah
            'rating_penitip' => null, // Default rating penitip
            'foto_ktp' => foto_ktp_path, // Simpan nama file foto KTP
        ]);

        // Redirect atau beri pesan sukses
        return redirect()->route('CustomerService.DashboardPegawai')->with('success', 'Penitip berhasil ditambahkan.');
    }

    public function index()
    {
        $penitip = Penitip::all();
        return response()->json($penitip);
    }

    public function showLogin()
    {
        try {
            $penitip = Auth::user();

            return response()->json([
                "status" => true,
                "message" => "Get Successful",
                "penitip" => $penitip
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Something went wrong",
                "penitip" => $e->getMessage()
            ], 400);
        }
    }

    public function show()
    {
        try {
            // Ambil data pembeli yang sedang login
            $penitip = Auth::guard('penitip')->user();

            // Return view dengan data pembeli
            return view('penitip.profilPenitip', compact('penitip'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $penitip = Penitip::find($id);
        if (!$penitip) {
            return response()->json(['message' => 'Penitip tidak ditemukan'], 404);
        }

        $validateData = $request->validate([
            'nama_penitip' => 'required|string|max:255',
            'nomor_telepon_penitip' => 'required|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Jika ada file gambar baru di-request
        if ($request->hasFile('foto_profil')) {
            // Hapus file lama jika bukan default
            if ($penitip->foto_profil && $penitip->foto_profil !== 'images/default.png') {
                \Storage::disk('public')->delete($penitip->foto_profil);
            }

            // Upload gambar baru
            $fotoBaru = $request->file('foto_profil')->store('images', 'public');
            $validateData['foto_profil'] = $fotoBaru;
        }

        $penitip->update($validateData);

        return response()->json([
            'penitip' => $penitip,
            'message' => 'Data penitip berhasil diperbarui'
        ]);
    }

    public function destroy($id)
    {
        $penitip = Penitip::find($id);
        if (!$penitip) {
            return response()->json(['message' => 'Penitip tidak ditemukan'], 404);
        }

        $penitip->delete();
        return response()->json(['message' => 'Penitip berhasil dihapus']);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $results = Penitip::where('nama_penitip', 'LIKE', '%' . $keyword . '%')->get();

        return response()->json($results);
    }

}