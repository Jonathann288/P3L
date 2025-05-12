<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Jabatan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PegawaiControllers extends Controller
{

    public function index()
    {
        $pegawai = Pegawai::all();
        return response()->json($pegawai);
    }

    public function showDashboard()
    {
        $pegawai = Auth::guard('pegawai')->user();

        if (!$pegawai) {
            return redirect()->route('admin.Dashboard')->with('error', 'Silakan login terlebih dahulu');
        }

        return view('admin.Dashboard', compact('pegawai'));
    }

    public function showlistPegawai(Request $request)
    {
        try {

            $search = $request->input('search');
    
            $query = Pegawai::with('jabatan');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    // Search in pegawai table
                    $q->where('nama_pegawai', 'LIKE', '%' . $search . '%')
                        // Search in related jabatan table using whereHas
                        ->orWhereHas('jabatan', function ($subQuery) use ($search) {
                            $subQuery->where('nama_jabatan', 'LIKE', '%' . $search . '%');
                        });
                });
            }

            // Get the filtered pegawai data
            $pegawai = $query->get();

            // Get all jabatan for dropdown
            $jabatan = Jabatan::all();

            // Return to view with data
            return view('admin.DashboardPegawai', compact('pegawai', 'jabatan', 'search'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function updateProfilAdmin(Request $request)
    {
        $pegawai = Auth::guard('pegawai')->user();

        if (!$pegawai) {
            return redirect()->route('admin.Dashboard')->with('error', 'Silakan login terlebih dahulu');
        }

        // Validasi input
        $validated = $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'nomor_telepon_pegawai' => 'required|string|max:20',
            'tanggal_lahir_pegawai' => 'required|date',
        ]);

        // Update data pegawai
        $pegawai->update($validated);

        return redirect()->route('admin.Dashboard')->with('success', 'Profil berhasil diperbarui');
    }


    public function registerPegawai(Request $request)
    {
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
    }


    public function update(Request $request, $id)
    {
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
    }





public function login(Request $request)
{
    $credentials = $request->only('email_pegawai', 'password_pegawai');

    $pegawai = Pegawai::where('email_pegawai', $credentials['email_pegawai'])->first();

    if ($pegawai && Hash::check($credentials['password_pegawai'], $pegawai->password_pegawai)) {
        Auth::guard('pegawai')->login($pegawai); // Gunakan guard pegawai

        if ($pegawai->id_jabatan == 2) {
            return redirect()->route('owner.DashboardOwner');
        } elseif ($pegawai->id_jabatan == 1) {
            return redirect()->route('admin.Dashboard');
        } else {
            return redirect()->back()->with('error', 'Jabatan tidak dikenali');
        }
    } else {
        return redirect()->back()->with('error', 'Email atau password salah');
    }
}



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
    public function searchByNama(Request $request)
    {
        $keyword = $request->input('keyword');
        $results = Pegawai::where('nama_pegawai', 'LIKE', '%' . $keyword . '%')->get();

        return response()->json($results);
    }
}
