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
        // Validasi data
        $validated = $request->validate([
            'nama_penitip' => 'required|string|max:255',
            'email_penitip' => 'required|email|max:255',
            'tanggal_penitipan' => 'required|date',
            'tanggal_akhir_penitipan' => 'nullable|date',
            'tanggal_batas_pengambilan' => 'nullable|date',
            'tanggal_pengambilan_barang' => 'nullable|date',
            'foto_barang.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Ambil data transaksi
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

            // Handle foto barang - SELALU hapus foto lama saat ada update
            if ($request->hasFile('foto_barang')) {
                // Hapus semua foto lama
                $this->deleteOldPhotos($titipan);

                // Upload foto baru
                $fotoPaths = [];
                foreach ($request->file('foto_barang') as $foto) {
                    // Generate unique filename
                    $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                    $path = $foto->storeAs('foto_barang', $filename, 'public');
                    $fotoPaths[] = $path;
                }

                // Set foto_barang sebagai array, akan otomatis di-convert ke JSON oleh mutator
                $titipan->foto_barang = $fotoPaths;
            }

            $titipan->save();

            return redirect()->route('gudang.DashboardTitipanBarang')->with('success', 'Data titipan berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    private function deleteOldPhotos($titipan)
    {
        $oldFotos = $titipan->foto_barang;

        if (empty($oldFotos)) {
            return;
        }

        // Pastikan $oldFotos adalah array
        if (is_string($oldFotos)) {
            $oldFotos = json_decode($oldFotos, true);
        }

        if (!is_array($oldFotos)) {
            return;
        }

        // Hapus setiap file foto lama
        foreach ($oldFotos as $oldFoto) {
            if (!empty($oldFoto)) {
                $cleanPath = trim($oldFoto);

                // Cek dan hapus file dari storage
                if (Storage::disk('public')->exists($cleanPath)) {
                    Storage::disk('public')->delete($cleanPath);
                }
            }
        }
    }

    public function deleteTitipanBarang($id)
    {
        try {
            $titipan = transaksipenitipan::findOrFail($id);

            // Hapus foto-foto terkait
            $this->deleteOldPhotos($titipan);

            // Hapus record dari database
            $titipan->delete();

            return redirect()->route('gudang.DashboardTitipanBarang')->with('success', 'Data titipan berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}