<?php
// COPY SEMUA
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\transaksipenitipan;
use App\Models\DetailTransaksiPenitipan;
use App\Models\Barang;
use App\Models\Penitip;
use App\Models\Pegawai;
use App\Models\Kategoribarang;
use Illuminate\Support\Facades\DB;
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

    public function showTitipanBarang()
    {
        $pegawaiLogin = Auth::guard('pegawai')->user();

        $detailBarang = DetailTransaksiPenitipan::with([
            'barang.kategori', 
            'transaksipenitipan.penitip', // agar bisa akses nama penitip juga
        ])->get();

        return view('gudang.DashboardTitipanBarang', compact('detailBarang', 'pegawaiLogin'));
    }


    public function search(Request $request)
    {   
        $penitip = Auth::guard('penitip')->user();
        $search = $request->input('search');

        if($search){
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
        }else{
            $detailBarang = detailtransaksipenitipan::whereHas('transaksipenitipan', function ($query) use ($penitip) {
                $query->where('id_penitip', $penitip->id_penitip);
            })->get();
        }
        return view('penitip.barang-titipan', compact('detailBarang', 'penitip'));
    }

    public function perpanjangMasaPenitipan($id)
    {
        $detail = detailtransaksipenitipan::with('transaksipenitipan')->findOrFail($id);
        $tanggalAkhir =  \Carbon\Carbon::parse($detail->transaksipenitipan->tanggal_akhir_penitipan);
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

    public function storeTitipanBarang(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'id_penitip' => 'required|exists:penitip,id_penitip',
            'nama_kategori' => 'required|string|max:255',
            'nama_sub_kategori' => 'nullable|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'harga_barang' => 'required|numeric|min:0',
            'deskripsi_barang' => 'required|string',
            'foto_barang.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanggal_penitipan' => 'required|date',
        ]);

        try {
            // Ambil pegawai yang sedang login
            $pegawai = Auth::guard('pegawai')->user();
            if (!$pegawai) {
                return redirect()->back()->with('error', 'Silakan login terlebih dahulu');
            }

            // Ambil data penitip
            $penitip = Penitip::findOrFail($validated['id_penitip']);

            // Tanggal penitipan
            $tanggalPenitipan = Carbon::parse($validated['tanggal_penitipan']);
            $tanggalAkhirPenitipan = $tanggalPenitipan->copy()->addDays(30);
            $tanggalBatasPengambilan = $tanggalAkhirPenitipan->copy()->addDays(7);

            // Upload foto
            $fotoPaths = [];
            if ($request->hasFile('foto_barang')) {
                foreach ($request->file('foto_barang') as $foto) {
                    $filename = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                    $path = $foto->storeAs('foto_barang', $filename, 'public');
                    $fotoPaths[] = $path;
                }
            }

            // Cek atau buat kategori baru
            $kategori = Kategoribarang::firstOrCreate([
                'nama_kategori' => $validated['nama_kategori'],
                'nama_sub_kategori' => $validated['nama_sub_kategori'] ?? '',
            ]);

            // Simpan barang baru
            $barang = new Barang;
            $barang->id_kategori = $kategori->id_kategori;
            $barang->nama_barang = $validated['nama_barang'];
            $barang->harga_barang = $validated['harga_barang'] ?? 0;
            $barang->deskripsi_barang = $validated['deskripsi_barang'] ?? '';
            $barang->foto_barang = json_encode($fotoPaths);
            $barang->status_barang = 'tersedia';
            $barang->masa_penitipan = 30;
            $barang->save();

            $last = DB::table('transaksipenitipan')
                ->select('id')
                ->where('id', 'like', 'T%')
                ->orderByDesc('id')
                ->first();

            $newNumber = $last ? ((int) substr($last->id, 1)) + 1 : 1;
            $newId = 'T' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            // Simpan transaksi penitipan
            $titipan = new Transaksipenitipan;
            $titipan->id = $newId; // gunakan $newId di sini
            $titipan->id_pegawai = $pegawai->id_pegawai;
            $titipan->id_penitip = $penitip->id_penitip;
            $titipan->tanggal_penitipan = $tanggalPenitipan;
            $titipan->tanggal_akhir_penitipan = $tanggalAkhirPenitipan;
            $titipan->tanggal_batas_pengambilan = $tanggalBatasPengambilan;
            $titipan->tanggal_pengambilan_barang = null;
            $titipan->save();

            // Simpan ke detail transaksi penitipan
            $detail = new Detailtransaksipenitipan;
            $detail->id_transaksi_penitipan = $titipan->id;
            $detail->id_barang = $barang->id_barang;
            $detail->status_perpanjangan = 'belum';
            $detail->save();

            return redirect()->route('gudang.DashboardTitipanBarang')->with('success',
                'Transaksi penitipan berhasil: Barang "' . $barang->nama_barang .
                '" dalam kategori "' . $kategori->nama_kategori .
                '" oleh penitip "' . $penitip->nama_penitip . '".'
            );

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


}
