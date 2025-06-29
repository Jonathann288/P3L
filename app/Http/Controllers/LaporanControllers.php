<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksipenjualan;
use App\Models\Detailtransaksipenjualan;
use App\Models\Transaksipenitipan;
use App\Models\Detailtransaksipenitipan;
use App\Models\Barang;
use App\Models\Komisi;
use Carbon\Carbon;
use DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanControllers extends Controller
{
    public function showLaporanPage()
    {
        return view('owner.DashboardLaporan');
    }
    
    // Laporan Penjualan Bulanan
    public function laporanPenjualanBulanan()
    {
        $tahun = (int) request('tahun', date('Y'));
        $bulanIni = date('m');
        
        // Data penjualan bulanan dengan join ke detailtransaksipenjualan untuk mendapatkan total_harga
        $penjualanBulanan = Transaksipenjualan::join('detailtransaksipenjualan', 'transaksipenjualan.id_transaksi_penjualan', '=', 'detailtransaksipenjualan.id_transaksi_penjualan')
            ->selectRaw('MONTH(tanggal_transaksi) as bulan, COUNT(DISTINCT transaksipenjualan.id_transaksi_penjualan) as jumlah_terjual, SUM(detailtransaksipenjualan.total_harga) as total_penjualan')
            ->whereYear('tanggal_transaksi', $tahun)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
            
        $data = [];
        $totalTerjual = 0;
        $totalPenjualan = 0;
        
        // Inisialisasi data untuk semua bulan
        for ($i = 1; $i <= 12; $i++) {
            $data[$i] = [
                'bulan' => Carbon::create()->month($i)->format('F'),
                'jumlah_terjual' => 0,
                'total_penjualan' => 0
            ];
        }
        
        // Isi data dari database
        foreach ($penjualanBulanan as $penjualan) {
            $data[$penjualan->bulan]['jumlah_terjual'] = $penjualan->jumlah_terjual;
            $data[$penjualan->bulan]['total_penjualan'] = $penjualan->total_penjualan;
            
            $totalTerjual += $penjualan->jumlah_terjual;
            $totalPenjualan += $penjualan->total_penjualan;
        }
        
        // Buat data untuk grafik
        $chartLabels = [];
        $chartValues = [];
        
        foreach ($data as $bulan => $item) {
            $chartLabels[] = substr($item['bulan'], 0, 3); // Singkatan bulan (Jan, Feb, dll)
            $chartValues[] = $item['total_penjualan'];
        }
        
        // Buat grafik menggunakan Chart.js dan konversi ke base64
        $chartImage = $this->generateChartImage($chartLabels, $chartValues);
        
        $pdf = PDF::loadView('owner.pdf.laporan_penjualan_bulanan', [
            'data' => $data,
            'tahun' => $tahun,
            'totalTerjual' => $totalTerjual,
            'totalPenjualan' => $totalPenjualan,
            'tanggal_cetak' => Carbon::now()->format('d F Y'),
            'chartImage' => $chartImage
        ]);
        
        return $pdf->download('laporan_penjualan_bulanan_' . $tahun . '.pdf');
    }
    
    // Fungsi untuk menghasilkan gambar grafik
    private function generateChartImage($labels, $values)
    {
        // Buat gambar kosong
        $manager = new ImageManager(new Driver());
        $width = 800;
        $height = 400;
        $image = $manager->create($width, $height);
        
        // Warna
        $backgroundColor = '#f0f0f0';
        $barColor = '#6495ED'; // Cornflower blue
        $textColor = '#333333';
        $gridColor = '#dddddd';
        
        // Isi background
        $image->fill($backgroundColor);
        
        // Parameter grafik
        $margin = 50;
        $chartWidth = $width - (2 * $margin);
        $chartHeight = $height - (2 * $margin);
        $barCount = count($values);
        $barWidth = $chartWidth / $barCount * 0.6;
        $barSpacing = $chartWidth / $barCount * 0.4;
        
        // Cari nilai maksimum untuk skala
        $maxValue = max($values) > 0 ? max($values) : 100;
        $scale = $chartHeight / $maxValue;
        
        // Gambar grid horizontal dan label sumbu Y
        $gridLines = 5;
        for ($i = 0; $i <= $gridLines; $i++) {
            $y = $margin + $chartHeight - ($i * $chartHeight / $gridLines);
            
            // Gambar garis grid horizontal menggunakan drawRectangle sebagai pengganti rectangle
            $image->drawRectangle($margin, $y - 1, function ($rectangle) use ($gridColor, $width, $margin, $y) {
                $rectangle->size($width - $margin - $margin, 2);
                $rectangle->background($gridColor);
                $rectangle->border(0);
            });
            
            // Label sumbu Y (nilai)
            $value = number_format($maxValue * $i / $gridLines, 0, ',', '.');
            $image->text('Rp ' . $value, $margin - 5, $y, function ($font) use ($textColor) {
                $font->color($textColor);
                $font->size(10);
                $font->align('right');
                $font->valign('middle');
            });
        }
        
        // Gambar batang dan label sumbu X
        for ($i = 0; $i < $barCount; $i++) {
            $x = $margin + ($i * ($barWidth + $barSpacing)) + ($barSpacing / 2);
            $barHeight = $values[$i] * $scale;
            $y = $margin + $chartHeight - $barHeight;
            
            // Gambar batang menggunakan drawRectangle
            $image->drawRectangle($x, $y, function ($rectangle) use ($barColor, $barWidth, $barHeight, $margin, $chartHeight) {
                $rectangle->size($barWidth, $barHeight);
                $rectangle->background($barColor);
                $rectangle->border(0);
            });
            
            // Label sumbu X (bulan)
            $image->text($labels[$i], $x + ($barWidth / 2), $height - $margin + 15, function ($font) use ($textColor) {
                $font->color($textColor);
                $font->size(12);
                $font->align('center');
                $font->valign('middle');
            });
        }
        
        // Judul grafik
        $image->text('Grafik Penjualan Bulanan', $width / 2, 20, function ($font) use ($textColor) {
            $font->color($textColor);
            $font->size(16);
            $font->align('center');
            $font->valign('middle');
        });
        
        // Konversi gambar ke base64 menggunakan toDataUri() sebagai pengganti toBase64()
        $dataUri = $image->toJpeg(90)->toDataUri();
        
        // Ekstrak hanya bagian base64 dari data URI (hapus prefix "data:image/jpeg;base64,")
        $base64 = substr($dataUri, strpos($dataUri, ',') + 1);
        
        return $base64;
    }
    
    // Laporan Komisi Bulanan
    public function laporanKomisiBulanan()
    {
        $bulan = (int) request('bulan', date('m'));
        $tahun = (int) request('tahun', date('Y'));
        
        $namabulan = Carbon::create()->month($bulan)->format('F');
        
        // Data komisi bulanan per produk dengan eager loading semua relasi yang dibutuhkan
        $komisi = Komisi::with([
                'barang', 
                'penitip', 
                'pegawai', 
                'transaksipenjualan'
            ])
            ->whereHas('transaksipenjualan', function($query) use ($bulan, $tahun) {
                $query->whereMonth('tanggal_transaksi', $bulan)
                      ->whereYear('tanggal_transaksi', $tahun);
            })
            ->get();
            
        $pdf = PDF::loadView('owner.pdf.laporan_komisi_bulanan', [
            'komisi' => $komisi,
            'bulan' => $namabulan,
            'tahun' => $tahun,
            'tanggal_cetak' => Carbon::now()->format('d F Y')
        ]);
        
        return $pdf->download('laporan_komisi_bulanan_' . $namabulan . '_' . $tahun . '.pdf');
    }
    
    // Laporan Stok Gudang
    public function laporanStokGudang()
    {
        // Data stok barang di gudang dengan eager loading relasi yang dibutuhkan
        $barang = Barang::with([
                'kategori', 
                'detailTransaksiPenitipan.transaksiPenitipan.penitip',
                'detailTransaksiPenitipan.transaksiPenitipan.pegawai'
            ])
            ->where('status_barang', 'tidak laku')
            ->get();
            
        $pdf = PDF::loadView('owner.pdf.laporan_stok_bulanan', [
            'barang' => $barang,
            'tanggal_cetak' => Carbon::now()->format('d F Y')
        ]);
        
        return $pdf->download('laporan_stok_gudang_' . Carbon::now()->format('d_m_Y') . '.pdf');
    }
} 