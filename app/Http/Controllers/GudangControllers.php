<?php

namespace App\Http\Controllers;
use \App\Models\Pegawai;
use \App\Models\transaksipenitipan;
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
        $titipan = transaksipenitipan::findOrFail($id);

        $validated = $request->validate([
            'id_pegawai' => 'required|integer',
            'id_penitip' => 'required|integer',
            'tanggal_penitipan' => 'required|date',
            'tanggal_akhir_penitipan' => 'nullable|date',
            'tanggal_batas_pengambilan' => 'nullable|date',
            'tanggal_pengambilan_barang' => 'nullable|date',
            'foto_barang.*' => 'nullable|image|max:2048',
        ]);

        $titipan->update($validated);

        if ($request->hasFile('foto_barang')) {
            if (is_array($titipan->foto_barang)) {
                foreach ($titipan->foto_barang as $foto) {
                    Storage::delete('public/' . $foto);
                }
            }

            $fotoPaths = [];
            foreach ($request->file('foto_barang') as $foto) {
                $fotoPaths[] = $foto->store('foto_barang', 'public');
            }

            $titipan->foto_barang = $fotoPaths;
            $titipan->save();
        }

        return redirect()->route('gudang.DashboardTitipanBarang')->with('success', 'Data titipan berhasil diperbarui.');
    }

}