<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Organisasi;
use App\Models\donasi;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class OwnerControllers extends Controller
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
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
    }

    return view('owner.DashboardOwner', compact('pegawai'));
}

public function showDonasiDashboard()
{
    $pegawai = Auth::guard('pegawai')->user();
    $donasis = donasi::with(['barang', 'requestdonasi'])->get();

    return view('owner.DashboardDonasi', compact('pegawai', 'donasis'));
}

public function updateDonasi(Request $request, $id_barang, $id_request)
{
    $request->validate([
        'tanggal_donasi' => 'required|date',
        'nama_penerima' => 'required|string|max:255',
        'status_barang' => 'required|string|max:255',
    ]);

    // Update donasi
    donasi::where('id_barang', $id_barang)
          ->where('id_request', $id_request)
          ->update([
              'tanggal_donasi' => $request->tanggal_donasi,
              'nama_penerima' => $request->nama_penerima,
          ]);

    // Update status barang
    barang::where('id_barang', $id_barang)
                      ->update(['status_barang' => $request->status_barang]);

    return redirect()->route('owner.DashboardDonasi')->with('success', 'Donasi berhasil diperbarui.');
}

public function showHistoryDonasi()
{
    $donasis = donasi::with(['requestdonasi.organisasi', 'barang'])->get();
    return view('owner.DashboardHistoryDonasi', compact('donasis'));
}


}
