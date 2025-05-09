<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestDonasi;
use Illuminate\Support\Facades\DB;

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
    $request->validate([
        'deskripsi_request' => 'required|string',
        'tanggal_request' => 'required|date',
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
        'tanggal_request' => $request->tanggal_request,
        'status_request' => 'pending',
    ]);

    return redirect()->back()->with('success', 'Request donasi berhasil ditambahkan.');
}
}