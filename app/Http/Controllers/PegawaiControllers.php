<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\Organisasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PegawaiControllers extends Controller
{

    public function showlistPegawai()
    {
        try {
            $pegawaiLogin = Auth::guard('pegawai')->user();
            // Ambil semua data pegawai beserta jabatannya
            $pegawai = Pegawai::with('jabatan')->get();
            $jabatan = Jabatan::all();
            // Return ke view dengan data pegawai
            return view('admin.DashboardPegawai', compact('pegawai', 'jabatan', 'pegawaiLogin'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function registerPegawai(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'id_jabatan' => 'required|exists:jabatan,id_jabatan',
                'nama_pegawai' => 'required|string|max:255',
                'tanggal_lahir_pegawai' => 'required|date',
                'nomor_telepon_pegawai' => 'required|string|max:20',
                'email_pegawai' => 'required|string|email|max:255|unique:pegawai,email_pegawai',
                'password_pegawai' => 'required|string|min:8',
            ]);

            $lastPegawai = DB::table('pegawai')
                ->select('id')
                ->where('id', 'like', 'PG%')
                ->orderByRaw('CAST(SUBSTRING(id, 3) AS UNSIGNED) DESC')
                ->first();

            $newNumber = $lastPegawai ? ((int) substr($lastPegawai->id, 2)) + 1 : 1;
            $newId = 'PG' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);

            // Simpan ke database
            Pegawai::create([
                'id' => $newId,
                'id_jabatan' => $request->id_jabatan,
                'nama_pegawai' => $request->nama_pegawai,
                'tanggal_lahir_pegawai' => $request->tanggal_lahir_pegawai,
                'nomor_telepon_pegawai' => $request->nomor_telepon_pegawai,
                'email_pegawai' => $request->email_pegawai,
                'password_pegawai' => Hash::make($request->password_pegawai),
            ]);

            // Redirect ke dashboard admin
            return redirect()->route('admin.DashboardPegawai')->with('success', 'Pegawai berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, tampilkan pesan error
            return redirect()->back()->with('error', 'Gagal menambahkan Pegawai: ' . $e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $pegawai = Pegawai::find($id);
            if (!$pegawai) {
                return redirect()->back()->with('error', 'Pegawai tidak ditemukan');
            }

            $validatedData = $request->validate([
                'id_jabatan' => 'required|exists:jabatan,id_jabatan',
                'nama_pegawai' => 'required|string|max:255',
                'tanggal_lahir_pegawai' => 'required|date',
                'nomor_telepon_pegawai' => 'required|string|max:20',
            ]);

            $pegawai->update($validatedData);

            return redirect()->route('admin.DashboardPegawai')->with('success', 'Pegawai berhasil diperbarui');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, tampilkan pesan error
            return redirect()->back()->with('error', 'Gagal mengubah data Pegawai: ' . $e->getMessage());
        }
    }


    public function index()
    {
        $pegawai = Pegawai::all();
        return response()->json($pegawai);
    }

    public function showLoginAdmin()
    {
        $pegawai = Auth::guard('pegawai')->user();

        // Passing data ke view
        return view('admin.dashboard', compact('pegawai'));
    }

    public function showLoginPegawai()
    {
        $pegawai = Auth::guard('pegawai')->user();

        // Passing data ke view
        return view('admin.DashboardPegawai', compact('pegawai'));
    }
    public function showLoginOwner()
    {
        $pegawai = Auth::guard('pegawai')->user();

        // Passing data ke view
        return view('owner.DashboardOwner', compact('pegawai'));
    }
    public function showLoginCS()
    {
        $pegawai = Auth::guard('pegawai')->user();

        // Passing data ke view
        return view('CustomerService.DashboardCS', compact('pegawai'));
    }
    // COPY INI
    public function showLoginGudang()
    {
        $pegawai = Auth::guard('pegawai')->user();

        // Passing data ke view
        return view('gudang.DashboardGudang', compact('pegawai'));
    }
    // SAMPE INI 

    public function show($id)
    {
        $pegawai = Pegawai::find($id);
        if (!$pegawai) {
            return response()->json(['message' => 'pegawai tidak ditemukan'], 404);
        }
        return response()->json($pegawai);
    }

    public function destroy($id)
    {
        try {
            $pegawai = Pegawai::findOrFail($id);
            $pegawai->delete();

            return redirect()->route('admin.DashboardPegawai')->with('success', 'Pegawai berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pegawai: ' . $e->getMessage());
        }
    }

    // Cari organisasi berdasarkan nama
    public function searchPegawai(Request $request)
    {
        try {
            $keyword = $request->input('keyword');

            // Mulai query dengan eager loading jabatan
            $query = Pegawai::with('jabatan');

            if ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama_pegawai', 'LIKE', "%{$keyword}%")
                        ->orWhere('email_pegawai', 'LIKE', "%{$keyword}%")
                        ->orWhereHas('jabatan', function ($q2) use ($keyword) {
                            $q2->where('nama_jabatan', 'LIKE', "%{$keyword}%");
                        });
                });
            }

            $pegawai = $query->get();

            $pegawaiLogin = Auth::guard('pegawai')->user();
            $jabatan = Jabatan::all();

            return view('admin.DashboardPegawai', compact('pegawai', 'pegawaiLogin', 'jabatan'));
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, tampilkan pesan error
            return redirect()->back()->with('error', 'Search Pegawai hanya nama, email, dan jabatan saja');
        }
    }

    public function resetPassword($id)
    {
        try {
            // Find the employee by ID
            $pegawai = Pegawai::findOrFail($id);

            // Get the birth date and format it as a string (YYYY-MM-DD)
            $birthDate = $pegawai->tanggal_lahir_pegawai;

            // Update the password to be the hashed birth date
            $pegawai->password_pegawai = Hash::make($birthDate);
            $pegawai->save();

            // Return a success response for the AJAX request
            return response()->json(['success' => true, 'message' => 'Password berhasil direset ke tanggal lahir']);
        } catch (\Exception $e) {
            // Return an error response for the AJAX request
            return response()->json(['success' => false, 'message' => 'Gagal mereset password: ' . $e->getMessage()], 500);
        }
    }

    

}