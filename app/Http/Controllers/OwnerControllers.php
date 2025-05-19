<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\requestdonasi;
use App\Models\donasi;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class OwnerControllers extends Controller
{

    public function index()
    {
        $pegawai = Pegawai::all();
        return response()->json($pegawai);
    }

    public function showDashboard()
    {
        $pegawai = Auth::guard('pegawai')->user();

        if (!$pegawai) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        return view('owner.DashboardOwner', compact('pegawai'));
    }

    public function showDonasiDashboard()
    {
        $pegawai = Auth::guard('pegawai')->user();
        $requestDonasis = RequestDonasi::where('status_request', 'pending')->with('organisasi')->get();

        return view('owner.DashboardDonasi', compact('pegawai', 'requestDonasis'));
    }


    public function updateDonasi(Request $request, $id_barang, $id_request)
    {
        $request->validate([
            'tanggal_donasi' => 'required|date',
            'nama_penerima' => 'required|string|max:255',
            'status_barang' => 'required|string|max:255',
        ]);

        // Update donasi
        donasi::where('id_barang', $id_barang)
            ->where('id_request', $id_request)
            ->update([
                'tanggal_donasi' => $request->tanggal_donasi,
                'nama_penerima' => $request->nama_penerima,
            ]);

        // Update status barang
        barang::where('id_barang', $id_barang)
            ->update(['status_barang' => $request->status_barang]);

        return redirect()->route('owner.DashboardDonasi')->with('success', 'Donasi berhasil diperbarui.');
    }

    public function showDonasiHistory()
    {
        $requestDonasiHistory = RequestDonasi::whereIn('status_request', ['approved', 'rejected'])->get();
        return view('owner.DashboardHistoryDonasi', compact('requestDonasiHistory'));
    }
    public function updateStatus(Request $request, $id)
    {
        $requestDonasi = RequestDonasi::findOrFail($id);
        $requestDonasi->status_request = $request->status_request;
        $requestDonasi->save();

        return redirect()->back()->with('success', 'Status donasi diperbarui.');
    }
    public function approveDonasi($id)
    {
        RequestDonasi::where('id_request', $id)->update(['status_request' => 'approved']);
        return back()->with('success', 'Permintaan disetujui.');
    }

    public function rejectDonasi($id)
    {
        RequestDonasi::where('id_request', $id)->update(['status_request' => 'rejected']);
        return back()->with('success', 'Permintaan ditolak.');
    }

    public function showHistoryDonasi(Request $request)
    {
        $search = $request->input('search');

        $donasis = Donasi::with(['requestdonasi.organisasi', 'barang'])
            ->whereHas('requestdonasi', function ($query) {
                $query->whereIn('status_request', ['approved', 'rejected']);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_penerima', 'like', "%{$search}%")
                        ->orWhereHas('barang', function ($q2) use ($search) {
                            $q2->where('nama_barang', 'like', "%{$search}%");
                        })
                        ->orWhereHas('requestdonasi.organisasi', function ($q3) use ($search) {
                            $q3->where('nama_organisasi', 'like', "%{$search}%");
                        });
                });
            })
            ->get();

        return view('owner.DashboardHistoryDonasi', ['donasis' => $donasis]);
    }


    public function showDonasiPending()
    {
        $pegawai = Auth::guard('pegawai')->user();


        $requestDonasis = RequestDonasi::where('status_request', 'pending')->with('organisasi')->get();

        return view('owner.DashboardDonasi', compact('pegawai', 'requestDonasis'));
    }
    public function editHistoryDonasi($id_barang, $id_request)
    {
        $donasi = Donasi::where('id_barang', $id_barang)
            ->where('id_request', $id_request)
            ->with(['barang', 'requestdonasi.organisasi'])
            ->firstOrFail();

        return response()->json($donasi); // Untuk keperluan modal (AJAX)
    }


    public function updateHistoryDonasi(Request $request, $id_barang, $id_request)
    {
        $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'tanggal_donasi' => 'required|date',
            'status_request' => 'required|in:approved,rejected',
        ]);

        // Update langsung menggunakan where
        Donasi::where('id_barang', $id_barang)
            ->where('id_request', $id_request)
            ->update([
                'nama_penerima' => $request->nama_penerima,
                'tanggal_donasi' => $request->tanggal_donasi,
            ]);

        // Update status_request di tabel requestdonasi
        RequestDonasi::where('id_request', $id_request)
            ->update(['status_request' => $request->status_request]);

        return redirect()->route('owner.DashboardHistoryDonasi')->with('success', 'History donasi berhasil diperbarui.');
    }



}
