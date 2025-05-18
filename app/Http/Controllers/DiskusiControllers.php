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
        $validated = $request->validate([
            'id_barang' => ['required', 'integer'],
            'pesan' => ['required', 'string', 'max:1000']
        ]);

        $diskusi = Diskusi::create([
            'id_barang' => $validated['id_barang'],
            'id_pembeli' => Auth::guard('pembeli')->id(),
            'pesan' => $validated['pesan'],
            'tanggal_diskusi' => now()
        ]);

        return response()->json(['success' => true]);
    }



    public function show($id)
    {
        // Eager load relationships with proper ordering
        $barang = Barang::with([
            'diskusi' => function ($query) {
                $query->orderBy('tanggal_diskusi', 'asc')
                    ->with(['pembeli', 'pegawai']);
            }
        ])->findOrFail($id);

        // No need for separate $diskusi query since it's eager loaded
        return view('detail_barangPembeli', compact('barang'));
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