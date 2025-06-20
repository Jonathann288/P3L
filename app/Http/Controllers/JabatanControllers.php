<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jabatan;

class JabatanControllers extends Controller
{
    public function index()
    {
        $jabatan = Jabatan::all();
        return response()->json($jabatan);
    }

    public function update(Request $request, $id)
    {
        $jabatan = Jabatan::find($id);
        if (!$jabatan) {
            return response()->json(['message' => 'Jabatan tidak ditemukan'], 404);
        }

        $validateData = $request-> validate([
            'nama_jabatan' => 'required|string|max:255',
        ]);

        $jabatan->update($validateData);

        return response()->json([
            'jabatan' => $jabatan,
            'message' => 'Jabatan berhasil diperbarui'
        ]);
    }

    public function destroy($id)
    {
        $jabatan = Jabatan::find($id);
        if (!$jabatan) {
            return response()->json(['message' => 'Jabatan tidak ditemukan'], 404);
        }

        $jabatan->delete();
        return response()->json(['message' => 'Jabatan berhasil dihapus']);
    }

    // Cari organisasi berdasarkan nama
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $results = Jabatan::where('nama_jabatan', 'LIKE', '%' . $keyword . '%')->get();

        return response()->json($results);
    }
}
