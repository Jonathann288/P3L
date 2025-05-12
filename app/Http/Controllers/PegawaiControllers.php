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

    public function showlistPegawai()
    {
        try {
            // Ambil semua data pegawai beserta jabatannya
            $pegawai = Pegawai::with('jabatan')->get();
            $jabatan = Jabatan::all();
            // Return ke view dengan data pegawai
            return view('admin.DashboardPegawai', compact('pegawai','jabatan'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
        // Cari Pegawai berdasarkan ID
        $pegawai = Pegawai::with('jabatan')->find($id); // Load relasi jabatan
        if (!$pegawai) {
            return redirect()->back()->with('error', 'Pegawai tidak ditemukan');
        }

        // Validasi data yang diterima
        $validateData = $request->validate([
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
            'nama_pegawai' => 'required|string|max:255',
            'tanggal_lahir_pegawai' => 'required|date',
            'nomor_telepon_pegawai' => 'required|string|max:20',
        ]);

        // Update data pegawai
        $pegawai->id_jabatan = $validateData['id_jabatan'];
        $pegawai->nama_pegawai = $validateData['nama_pegawai'];
        $pegawai->tanggal_lahir_pegawai = $validateData['tanggal_lahir_pegawai'];
        $pegawai->nomor_telepon_pegawai = $validateData['nomor_telepon_pegawai'];
        $pegawai->save();

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Pegawai berhasil diperbarui');
    }


    public function index()
    {
        $pegawai = Pegawai::all();
        return response()->json($pegawai);
    }
    
    public function showLogin()
    {
        $pegawai = Auth::user(); // atau request()->user()
        return response()->json([
            'message' => 'Data organisasi login berhasil diambil',
            'Pegawai' => $pegawai
        ]);
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
        $pegawai = Pegawai::find($id);
        if (!$pegawai) {
            return response()->json(['message' => 'pegawai tidak ditemukan'], 404);
        }

        $pegawai->delete();
        return response()->json(['message' => 'pegawai berhasil dihapus']);
    }

    // Cari organisasi berdasarkan nama
    public function searchByNama(Request $request)
    {
        $keyword = $request->input('keyword');
        $results = Pegawai::where('nama_pegawai', 'LIKE', '%' . $keyword . '%')->get();

        return response()->json($results);
    }
}
