<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PegawaiControllers extends Controller
{

    public function showRegisterForm()
    {
        $jabatans = \App\Models\Jabatan::all();
        return view('registerPegawai', compact('jabatans'));
    }
public function adminDashboard()
{
    $pegawais = \App\Models\Pegawai::all(); // Ambil semua data pegawai
    $jabatans = \App\Models\Jabatan::all(); // Ambil semua data jabatan
    return view('admin.Dashboard', compact('pegawais', 'jabatans')); // Kirimkan pegawais dan jabatans ke view
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

    // Generate ID baru
    $lastPegawai = DB::table('pegawai')
        ->select('id')
        ->where('id', 'like', 'PG%')
        ->orderByDesc('id')
        ->first();

    if ($lastPegawai) {
        $lastNumber = (int) substr($lastPegawai->id, 2);
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }

    $newId = 'PG' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

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
    return redirect()->route('admin.dashboard')->with('success', 'Pegawai berhasil ditambahkan!');
}


    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::find($id);
        if (!$pegawai) {
            return response()->json(['message' => 'Pegawai tidak ditemukan'], 404);
        }

        $validateData = $request->validate([
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
            'nama_pegawai' => 'required|string|max:255',
            'tanggal_lahir_pegawai' => 'required|date',
            'nomor_telepon_pegawai' => 'required|string|max:20',
        ]);

        $pegawai->update($validateData);

        return response()->json([
            'Pegawai' => $pegawai,
            'message' => 'Pegawai berhasil diperbarui'
        ]);
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
