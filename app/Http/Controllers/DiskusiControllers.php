<?php

namespace App\Http\Controllers;

use App\Models\Diskusi;
use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'nama' => Auth::guard('pembeli')->user()->nama_pembeli,
                'pesan' => $validated['pesan'],
                'tanggal' => now()->translatedFormat('d F Y'),
            ]);
        }

        return redirect()->back()->with('success', 'Diskusi berhasil ditambahkan!');
    }

    public function show($id)
    {
        // Eager load relationships with proper ordering
        $barang = Barang::with([
            'diskusi' => function ($query) {
                $query->whereNull('id_diskusi_induk') // Get only parent discussions
                    ->orderBy('tanggal_diskusi', 'desc')
                    ->with(['pembeli', 'pegawai', 'replies.pembeli', 'replies.pegawai']);
            }
        ])->findOrFail($id);

        return view('detail_barangPembeli', compact('barang'));
    }

    public function reply(Request $request)
    {
        $validated = $request->validate([
            'id_barang' => 'required|exists:barang,id_barang',
            'id_diskusi_induk' => 'nullable|exists:diskusi,id_diskusi',
            'pesan' => 'required|string|max:1000',
        ]);


        try {
            // Create new discussion as reply
            $diskusi = new Diskusi();
            $diskusi->id_barang = $validated['id_barang'];
            $diskusi->id_diskusi_induk = $validated['id_diskusi_induk'];

            // Set the appropriate user ID based on who's logged in
            if (Auth::guard('pembeli')->check()) {
                $diskusi->id_pembeli = Auth::guard('pembeli')->id();
            } elseif (Auth::guard('pegawai')->check()) {
                $diskusi->id_pegawai = Auth::guard('pegawai')->id();
            } else {
                return redirect()->route('loginPembeli')
                    ->with('error', 'Silahkan login terlebih dahulu untuk membalas diskusi.');
            }

            $diskusi->pesan = $validated['pesan'];
            $diskusi->tanggal_diskusi = now();
            $diskusi->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'id_diskusi' => $diskusi->id_diskusi,
                    'nama' => Auth::guard('pembeli')->check() ? Auth::guard('pembeli')->user()->nama_pembeli : Auth::guard('pegawai')->user()->nama_pegawai,
                    'pesan' => $validated['pesan'],
                    'tanggal' => now()->translatedFormat('d F Y'),
                ]);
            }

            // Redirect back with success message
            return redirect()->back()->with('success', 'Balasan berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Log error
            Log::error('Error adding reply: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan balasan. Silahkan coba lagi.'
                ], 500);
            }

            // Redirect back with error message
            return redirect()->back()
                ->with('error', 'Gagal menambahkan balasan. Silahkan coba lagi.')
                ->withInput();
        }
    }
}