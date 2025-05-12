<?php

namespace App\Http\Controllers;

use App\Models\Diskusi;
use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;

class DiskusiControllers extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'pesan' => 'required|string|max:1000',
        ]);

        // Check if user is logged in
        if (!Auth::guard('pembeli')->check()) {
            return redirect()->route('loginPembeli')
                ->with('error', 'Silahkan login terlebih dahulu untuk berdiskusi.');
        }

        try {
            // Create new discussion
            $diskusi = new Diskusi();
            $diskusi->id_barang = $request->id_barang;
            $diskusi->id_pembeli = Auth::guard('pembeli')->id();
            $diskusi->pesan = $request->pesan;
            $diskusi->tanggal_diskusi = now();
            $diskusi->save();

            // Redirect back with success message
            return redirect()->back()->with('success', 'Diskusi berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Log error
            \Log::error('Error adding discussion: ' . $e->getMessage());

            // Redirect back with error message
            return response()->json([
                'nama' => Auth::guard('pembeli')->user()->nama_pembeli,
                'pesan' => $request->pesan,
                'tanggal' => now()->translatedFormat('d F Y') // contoh: 11 Mei 2025
            ]);


        }
    }

    public function show($id_barang)
    {
        // Find the product
        $barang = Barang::with(['diskusi.pembeli', 'diskusi.pegawai'])
            ->findOrFail($id_barang);

        // Get discussions sorted by date (newest first)
        $diskusi = $barang->diskusi()->orderBy('tanggal_diskusi', 'desc')->get();

        return view('diskusi.show', compact('barang', 'diskusi')); // ← INI dia
    }


    public function reply(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'pesan' => 'required|string|max:1000',
        ]);

        // Check if staff is logged in
        if (!Auth::guard('pegawai')->check()) {
            return redirect()->route('loginPegawai')
                ->with('error', 'Silahkan login terlebih dahulu untuk menanggapi diskusi.');
        }

        try {
            // Create new discussion as reply
            $diskusi = new Diskusi();
            $diskusi->id_barang = $request->id_barang;
            $diskusi->id_pegawai = Auth::guard('pegawai')->id();
            $diskusi->pesan = $request->pesan;
            $diskusi->tanggal_diskusi = now();
            $diskusi->save();

            // Redirect back with success message
            return redirect()->back()->with('success', 'Tanggapan berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Log error
            Log::error('Error adding reply: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->back()
                ->with('error', 'Gagal menambahkan tanggapan. Silahkan coba lagi.')
                ->withInput();
        }
    }
}