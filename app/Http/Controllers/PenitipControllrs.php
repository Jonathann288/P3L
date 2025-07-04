<?php

namespace App\Http\Controllers;

use Exception;
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
            return view('CustomerService.DashboardPenitip', compact('penitip', 'pegawaiLogin'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }







    public function registerPenitip(Request $request)
    {
        try {
            $request->validate([
                'nama_penitip' => 'required|string|max:255',
                'nomor_ktp' => 'required|string|max:16',
                'email_penitip' => 'required|string|email|max:255|unique:penitip,email_penitip',
                'tanggal_lahir' => 'required|date',
                'password_penitip' => 'required|string|min:8',
                'nomor_telepon_penitip' => 'required|string|max:20',
                // 'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048', // maksimal 2MB
            ]);

            // Generate ID Penitip otomatis
            $lastPenitip = DB::table('penitip')
                ->select('id')
                ->where('id', 'like', 'P%')
                ->orderByDesc('id')
                ->first();

            $newNumber = $lastPenitip ? ((int) substr($lastPenitip->id, 2)) + 1 : 1;
            $newId = 'P' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            // // Upload Foto KTP
            // if ($request->hasFile('foto_ktp')) {
            //     $imageName = time() . '.' . $request->foto_ktp->extension();
            //     $request->foto_ktp->move(public_path('uploads'), $imageName);
            //     $foto_ktp_path = 'uploads/' . $imageName;
            // } else {
            //     $foto_ktp_path = null;
            // }

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
                'foto_ktp' => null, // Simpan nama file foto KTP
            ]);

            // Redirect atau beri pesan sukses
            return redirect()->route('CustomerService.DashboardPenitip')->with('success', 'Penitip berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, tampilkan pesan error
            return redirect()->back()->with('error', 'Gagal menambahkan Penitip: ' . $e->getMessage());
        }
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
        try {
            // Validasi input
            $request->validate([
                'nama_penitip' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'nomor_telepon_penitip' => 'required|string|max:15',
            ]);

            // Cari data penitip berdasarkan ID
            $penitip = Penitip::find($id);

            if (!$penitip) {
                return redirect()->back()->with('error', 'Data Penitip tidak ditemukan.');
            }

            // Update data
            $penitip->nama_penitip = $request->nama_penitip;
            $penitip->tanggal_lahir = $request->tanggal_lahir;
            $penitip->nomor_telepon_penitip = $request->nomor_telepon_penitip;
            $penitip->save();

            // Redirect dengan pesan sukses
            return redirect()->route('CustomerService.DashboardPenitip')->with('success', 'Data Penitip berhasil diupdate.');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, tampilkan pesan error
            return redirect()->back()->with('error', 'Gagal melakukan update Penitip: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        // Cari data penitip berdasarkan ID
        $penitip = Penitip::findOrFail($id);

        if (!$penitip) {
            return redirect()->back()->with('error', 'Data Penitip tidak ditemukan.');
        }

        // Hapus data
        $penitip->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('CustomerService.DashboardPenitip')->with('success', 'Data Penitip berhasil dihapus.');
    }


    public function search(Request $request)
    {
        try {
            $keyword = $request->input('keyword');

            $query = Penitip::query();

            if ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama_penitip', 'LIKE', "%{$keyword}%");
                    // Jika ingin cari di kolom lain, bisa ditambahkan orWhere
                    // ->orWhere('email', 'LIKE', "%{$keyword}%")
                });
            }

            $penitip = $query->get();
            $pegawaiLogin = Auth::guard('pegawai')->user();
            // Kirim data ke view, misalnya 'admin.DashboardPenitip'
            return view('CustomerService.DashboardPenitip', compact('penitip', 'pegawaiLogin'));
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, tampilkan pesan error
            return redirect()->back()->with('error', 'Cari Penitip hanya nama saja');
        }
    }


}