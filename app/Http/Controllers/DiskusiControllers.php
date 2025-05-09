<?php

namespace App\Http\Controllers;

use App\Models\Diskusi;
use Illuminate\Http\Request;

class DiskusiControllers extends Controller
{
    public function store(Request $request)
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return back()->with('error', 'Anda harus login terlebih dahulu.');
        }

        $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'pesan' => 'required|string',
        ]);

        $diskusi = Diskusi::create([
            'id_barang' => $request->id_barang,
            'id_pembeli' => auth()->user()->id,
            'pesan' => $request->pesan,
            'tanggal_diskusi' => now(),
        ]);

        // Jika request via JavaScript (expects JSON)
        if ($request->expectsJson()) {
            return response()->json([
                'nama' => auth()->user()->nama ?? 'Pengguna',
                'pesan' => $diskusi->pesan,
            ]);
        }

        return back()->with('success', 'Komentar berhasil dikirim.');
    }
}
