<?php

namespace App\Http\Controllers;
use \App\Models\Pegawai;
use \App\Models\transaksipenitipan; // Sesuai dengan nama model yang didefinisikan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GudangControllers extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::all();
        return response()->json($pegawai);
    }

    public function showDashboardGudang()
    {
        $pegawai = Auth::guard('pegawai')->user();

        if (!$pegawai) {
            return redirect()->route('gudang.DashboardGudang')->with('error', 'Silakan login terlebih dahulu');
        }

        return view('gudang.DashboardGudang', compact('pegawai'));
    }

    public function showTitipanBarang()
    {
        $titipans = transaksipenitipan::with('penitip')->get();
        return view('gudang.DashboardTitipanBarang', compact('titipans'));
    }

    public function updateTitipanBarang(Request $request, $id)
    {
        // Validasi data
        $validated = $request->validate([
            'nama_penitip' => 'required|string|max:255',
            'email_penitip' => 'required|email|max:255',
            'tanggal_penitipan' => 'required|date',
            'tanggal_akhir_penitipan' => 'nullable|date',
            'tanggal_batas_pengambilan' => 'nullable|date',
            'tanggal_pengambilan_barang' => 'nullable|date',
            'foto_barang.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Ambil data transaksi - gunakan nama model yang benar
            $titipan = transaksipenitipan::findOrFail($id);

            // Update data penitip terkait
            $penitip = $titipan->penitip;
            if ($penitip) {
                $penitip->nama_penitip = $validated['nama_penitip'];
                $penitip->email_penitip = $validated['email_penitip'];
                $penitip->save();
            }

            // Update data titipan
            $titipan->tanggal_penitipan = $validated['tanggal_penitipan'];
            $titipan->tanggal_akhir_penitipan = $validated['tanggal_akhir_penitipan'];
            $titipan->tanggal_batas_pengambilan = $validated['tanggal_batas_pengambilan'];
            $titipan->tanggal_pengambilan_barang = $validated['tanggal_pengambilan_barang'];

            // Jika ada foto baru diupload
            if ($request->hasFile('foto_barang')) {
                $fotoPaths = [];
                foreach ($request->file('foto_barang') as $foto) {
                    $path = $foto->store('foto_barang', 'public');
                    $fotoPaths[] = $path;
                }
                $titipan->foto_barang = $fotoPaths;
            }

            $titipan->save();

            return redirect()->route('gudang.DashboardTitipanBarang')->with('success', 'Data titipan berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}