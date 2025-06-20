<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestDonasi;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Validator;

class RequestDonasiControllers extends Controller
{
    public function create()
    {

        return view('requestBarang');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'deskripsi_request' => 'required|string',
    //         'tanggal_request' => 'required|date',
    //     ]);

    //     // Menetapkan id_organisasi secara otomatis
    //     $id_organisasi = auth()->user()->organisasi_id ?? 1;

    //     // Ambil request terakhir berdasarkan id_organisasi
    //     $lastRequest = RequestDonasi::where('id_organisasi', $id_organisasi)
    //         ->orderBy('id', 'desc')
    //         ->first();

    // if ($lastRequest && preg_match('/^R(\d{2,})$/', $lastRequest->id, $m)) {
    //     $newNumber = (int) $m[1] + 1;
    // } else {
    //     $newNumber = 0;
    // }

    // // Buat ID baru (misalnya: R000, R001, dst.)
    // $generatedId = 'R' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

    //     RequestDonasi::create([
    //         'id' => $generatedId,
    //         'deskripsi_request' => $request->deskripsi_request,
    //         'tanggal_request' => $request->tanggal_request,
    //         'id_organisasi' => $id_organisasi,
    //         'status_request' => 'pending',
    //     ]);

    //     return redirect()->route('requestBarang.create')->with('success', 'Request barang berhasil dikirim!');
    // }

    public function store(Request $request)
    {   
        try{
            $request->validate([
                'deskripsi_request' => 'required|string',
            ]);

            $organisasi = auth('organisasi')->user();

            if (!$organisasi || !$organisasi->id_organisasi) {
                return redirect()->back()->withErrors('Organisasi tidak ditemukan.');
            }

            // Ambil semua ID request donasi milik organisasi ini
            $ids = RequestDonasi::where('id_organisasi', $organisasi->id_organisasi)
                ->pluck('id')
                ->toArray();

            // Hitung ID terbesar
            $maxNumber = 0;
            foreach ($ids as $id) {
                if (preg_match('/^R(\d{1,})$/', $id, $match)) {
                    $num = (int) $match[1];
                    if ($num > $maxNumber) {
                        $maxNumber = $num;
                    }
                }
            }

            $generatedId = 'R' . str_pad($maxNumber + 1, 3, '0', STR_PAD_LEFT);

            RequestDonasi::create([
                'id' => $generatedId,
                'id_organisasi' => $organisasi->id_organisasi,
                'deskripsi_request' => $request->deskripsi_request,
                'tanggal_request' => now(),
                'status_request' => 'pending',
            ]);

            return redirect()->back()->with('success', 'Request donasi berhasil ditambahkan.');
        }catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan menambahkan Request Donasi: ' . $e->getMessage());
        }
    }

    public function requestDonasiOrganisasi(Request $request)
    {
        $organisasi = Auth::guard('organisasi')->user();

        // Ambil query pencarian dari input
        $search = $request->input('search');

        // Mulai query request donasi milik organisasi
        $query = $organisasi->requestDonasi()->orderBy('tanggal_request', 'desc');

        // Jika ada keyword pencarian, filter berdasarkan deskripsi
        if (!empty($search)) {
            $query->where('deskripsi_request', 'like', '%' . $search . '%');
        }

        // Ambil hasil
        $requestdonasi = $query->get();

        return view('organisasi.requestDonasiOrganisasi', compact('organisasi', 'requestdonasi'));
    }

    public function destroy($id)
    {
        try {
            $organisasiId = Auth::guard('organisasi')->id();

            // Pastikan hanya request milik organisasi yang sedang login yang bisa dihapus
            $requestDonasi = RequestDonasi::where('id', $id)
                ->where('id_organisasi', $organisasiId)
                ->firstOrFail();

            $requestDonasi->delete();

            return redirect()->back()->with('success', 'Request donasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus request: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {   
        try{
            $request->validate([
                'deskripsi_request' => 'required|string',
            ]);

            $organisasiId = Auth::guard('organisasi')->id();

            $requestDonasi = RequestDonasi::where('id', $id)
                ->where('id_organisasi', $organisasiId)
                ->firstOrFail();

            $requestDonasi->update([
                'deskripsi_request' => $request->deskripsi_request,
                'tanggal_request' => now(),
            ]);

            return redirect()->back()->with('success', 'Request donasi berhasil diperbarui.');
        }catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melakukan Update request: ' . $e->getMessage());
        }
    }

    public function showlistRequestDonasi()
    {
        try {
            $pegawaiLogin = Auth::guard('pegawai')->user();
            $requestdonasi = requestdonasi::where('status_request', 'pending')->get();
            
            // Ambil barang yang statusnya "tersedia" (atau status lain yang diinginkan)
            $barangs = barang::where('status_barang', 'di donasikan')->get();

            return view('owner.DashboardDonasi', compact('requestdonasi', 'pegawaiLogin', 'barangs'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



}