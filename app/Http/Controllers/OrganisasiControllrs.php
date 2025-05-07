<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisasi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class OrganisasiControllrs extends Controller
{
    public function index()
    {
        $organisasi = Organisasi::all();
        return view('organisasi.index', compact('organisasi'));
    }

    public function create()
    {
        return view('organisasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_organisasi' => 'required|string|max:255',
            'alamat_organisasi' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:11',
            'email_organisasi' => 'required|string|email|max:255|unique:organisasi,email_organisasi',
            'password_organisasi' => 'required|string|min:8',
        ]);

        $lastOrganisasi = DB::table('organisasi')
            ->select('id')
            ->where('id', 'like', 'OR%')
            ->orderByDesc('id')
            ->first();

        $newNumber = $lastOrganisasi ? ((int) substr($lastOrganisasi->id, 2)) + 1 : 1;
        $newId = 'OR' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        Organisasi::create([
            'id' => $newId,
            'nama_organisasi' => $request->nama_organisasi,
            'alamat_organisasi' => $request->alamat_organisasi,
            'nomor_telepon' => $request->nomor_telepon,
            'email_organisasi' => $request->email_organisasi,
            'password_organisasi' => Hash::make($request->password_organisasi),
        ]);

        return redirect()->route('organisasi.index')->with('success', 'Organisasi berhasil didaftarkan.');
    }

    public function show($id)
    {
        $organisasi = Organisasi::findOrFail($id);
        return view('organisasi.show', compact('organisasi'));
    }

    public function edit($id)
    {
        $organisasi = Organisasi::findOrFail($id);
        return view('organisasi.edit', compact('organisasi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_organisasi' => 'required|string|max:255',
            'alamat_organisasi' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:11',
        ]);

        $organisasi = Organisasi::findOrFail($id);
        $organisasi->update($request->only('nama_organisasi', 'alamat_organisasi', 'nomor_telepon'));

        return redirect()->route('organisasi.index')->with('success', 'Organisasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $organisasi = Organisasi::findOrFail($id);
        $organisasi->delete();
        return redirect()->route('organisasi.index')->with('success', 'Organisasi berhasil dihapus.');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $organisasi = Organisasi::where('nama_organisasi', 'LIKE', "%$keyword%")->get();
        return view('organisasi.index', compact('organisasi'));
    }
}

