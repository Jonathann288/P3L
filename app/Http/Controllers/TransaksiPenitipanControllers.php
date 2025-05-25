<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\transaksipenitipan;
use App\Models\detailtransaksipenitipan;
use App\Models\Barang;
use App\Models\Penitip;
use Carbon\Carbon;

class TransaksiPenitipanControllers extends Controller
{
    public function showBarangTitipan()
    {
        $penitipId = Auth::guard('penitip')->user()->id_penitip;
        $penitip = Penitip::find($penitipId);

        $transaksiIds = TransaksiPenitipan::where('id_penitip', $penitipId)->pluck('id_transaksi_penitipan');

        $detailBarang = DetailTransaksiPenitipan::with(['barang', 'transaksipenitipan'])
            ->whereIn('id_transaksi_penitipan', $transaksiIds)
            ->get();

        return view('penitip.barang-titipan', compact('detailBarang', 'penitip'));
    }

    public function search(Request $request)
    {
        $penitip = Auth::guard('penitip')->user();
        $search = $request->input('search');

        if ($search) {
            $detailBarang = detailtransaksipenitipan::whereHas('barang', function ($query) use ($search) {
                $query->where('nama_barang', 'like', "%{$search}%");
            })
                ->orWhereHas('transaksipenitipan', function ($query) use ($search) {
                    // Coba cek search ini cocok dengan tanggal_penitipan atau tanggal_akhir_penitipan
                    // Karena input text, kita coba ubah input ke tanggal
                    $date = date('Y-m-d', strtotime($search));

                    // Pastikan tanggal valid supaya query aman
                    if ($date) {
                        $query->where('tanggal_penitipan', $date)
                            ->orWhere('tanggal_akhir_penitipan', $date);
                    }
                })
                ->get();
        } else {
            $detailBarang = detailtransaksipenitipan::whereHas('transaksipenitipan', function ($query) use ($penitip) {
                $query->where('id_penitip', $penitip->id_penitip);
            })->get();
        }
        return view('penitip.barang-titipan', compact('detailBarang', 'penitip'));
    }

    public function perpanjangMasaPenitipan($id)
    {
        $detail = detailtransaksipenitipan::with('transaksipenitipan')->findOrFail($id);
        $tanggalAkhir = \Carbon\Carbon::parse($detail->transaksipenitipan->tanggal_akhir_penitipan);
        $sekarang = Carbon::now();

        // Cek jika masa penitipan sudah lewat (tanggal akhir lebih kecil dari sekarang)
        if ($tanggalAkhir->greaterThanOrEqualTo($sekarang)) {
            return redirect()->back()->with('error', 'Masa penitipan belum habis, belum bisa diperpanjang.');
        }

        // Kalau sudah lewat, perpanjang 30 hari lagi
        $tanggalBaru = $tanggalAkhir->addDays(30);

        $detail->transaksipenitipan->update([
            'tanggal_akhir_penitipan' => $tanggalBaru,
        ]);

        // Optional: reset status_perpanjangan jadi false atau hapus kalau ingin
        $detail->update([
            'status_perpanjangan' => false, // atau hapus kolom ini kalau tidak diperlukan
        ]);

        return redirect()->back()->with('success', 'Masa penitipan berhasil diperpanjang 30 hari.');
    }

}