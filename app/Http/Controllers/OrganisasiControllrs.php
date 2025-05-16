<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisasi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrganisasiControllrs extends Controller
{   
    public function showlistOrganisasi()
    {
        try {
            $pegawaiLogin = Auth::guard('pegawai')->user();
            // Ambil semua data pegawai beserta jabatannya
            $organisasi = Organisasi::all();
            // Return ke view dengan data pegawai
            return view('admin.DashboardOrganisasi', compact('organisasi','pegawaiLogin'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function index()
    {
        $organisasi = Organisasi::all();
        return view('organisasi.index', compact('organisasi'));
    }

    public function create()
    {
        return view('organisasi.create');
    }

    public function showRegisterOrganisasi()
    {
        return view('registerOrganisasi');
    }

    public function registerOrganisasi(Request $request)
    {
        $request->validate([
            'nama_organisasi' => 'required|string|max:255',
            'alamat_organisasi' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:11',
            'email_organisasi' => 'required|string|email|max:255|unique:organisasi,email_organisasi',
            'password_organisasi' => 'required|string|min:8',
        ]);
        

        // Buat ID otomatis
        $last = DB::table('organisasi')->select('id')->where('id', 'like', 'OR%')->orderByDesc('id')->first();
        $newId = 'OR' . str_pad(($last ? intval(substr($last->id, 2)) + 1 : 1), 3, '0', STR_PAD_LEFT);

        $organisasi = Organisasi::create([
            'id' => $newId,
            'nama_organisasi' => $request->nama_organisasi,
            'alamat_organisasi' => $request->alamat_organisasi,
            'nomor_telepon' => $request->nomor_telepon,
            'email_organisasi' => $request->email_organisasi,
            'password_organisasi' => Hash::make($request->password_organisasi),
        ]);

        return redirect()->route('loginOrganisasi')->with('success', 'Akun berhasil dibuat, silakan login!');
    }


    public function showOrganisasi()
    {
        $organisasi = Auth::guard('organisasi')->user();
        return view('organisasi.profilOrganisasi', compact('organisasi'));
    }

    public function edit($id)
    {
        $organisasi = Organisasi::findOrFail($id);
        return view('organisasi.edit', compact('organisasi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_organisasi' => 'required|string|max:255',
            'alamat_organisasi' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:11',
        ]);

        $organisasi = Organisasi::findOrFail($id);
        $organisasi->update($request->only('nama_organisasi', 'alamat_organisasi', 'nomor_telepon'));

        return redirect()->route('admin.DashboardOrganisasi')->with('success', 'Organisasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $organisasi = Organisasi::findOrFail($id);
        $organisasi->delete();
        return redirect()->route('admin.DashboardOrganisasi')->with('success', 'Organisasi berhasil dihapus.');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = Organisasi::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_organisasi', 'LIKE', "%{$keyword}%");
                // Kalau mau cari kolom lain, bisa ditambahkan orWhere di sini
                // ->orWhere('alamat', 'LIKE', "%{$keyword}%")
            });
        }

        $organisasi = $query->get();

        $pegawaiLogin = Auth::guard('pegawai')->user();

        return view('admin.DashboardOrganisasi', compact('organisasi','pegawaiLogin'));
    }


    // public function updateProfil(Request $request)
    // {
    //     $request->validate([
    //         'nama_organisasi' => 'required|string|max:255',
    //         'email_organisasi' => 'required|email|max:255',
    //         'nomor_telepon' => 'required|string|max:11',
    //         'alamat_organisasi' => 'required|string|max:255',
    //     ]);

    //     $organisasi = Auth::guard('organisasi')->user();

    //     $organisasi->update([
    //         'nama_organisasi' => $request->nama_organisasi,
    //         'email_organisasi' => $request->email_organisasi,
    //         'nomor_telepon' => $request->nomor_telepon,
    //         'alamat_organisasi' => $request->alamat_organisasi,
    //     ]);

    //     return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    // }

}