<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaksipenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiPenjualanControllersAPI extends Controller
{
    /**
     * Display a listing of transaksi penjualan
     */
    public function index(Request $request)
    {
        try {
            $query = Transaksipenjualan::with([
                'detailTransaksi.barang',
                'pembeli',
                'kurir'
            ]);

            // Filter berdasarkan status pengiriman jika ada
            if ($request->has('status_pengiriman')) {
                $query->where('status_pengiriman', $request->status_pengiriman);
            }

            // Filter berdasarkan metode pengantaran jika ada
            if ($request->has('metode_pengantaran')) {
                $query->where('metode_pengantaran', $request->metode_pengantaran);
            }

            // Filter berdasarkan tanggal jika ada
            if ($request->has('tanggal_mulai') && $request->has('tanggal_akhir')) {
                $query->whereBetween('tanggal_transaksi', [
                    $request->tanggal_mulai,
                    $request->tanggal_akhir
                ]);
            }

            $transaksi = $query->orderBy('tanggal_transaksi', 'desc')->get();

            // Format data sesuai kebutuhan
            $formattedData = $transaksi->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_pembeli' => $item->pembeli->nama ?? 'Tidak diketahui',
                    'barang_list' => $item->detailTransaksi->map(function ($detail) {
                        return [
                            'nama_barang' => $detail->barang->nama_barang ?? 'Tidak diketahui',
                            'harga_barang' => (float) $detail->harga_satuan,
                            'jumlah' => (int) $detail->jumlah,
                            'subtotal' => (float) $detail->subtotal
                        ];
                    }),
                    'tanggal_kirim' => $item->tanggal_kirim ? $item->tanggal_kirim->format('Y-m-d H:i:s') : null,
                    'status_pengiriman' => $item->status_pengiriman,
                    'metode_pengantaran' => $item->metode_pengantaran,
                    'ongkir' => (float) $item->ongkir ?? 0,
                    'total_harga' => (float) $item->total_harga,
                    'tanggal_transaksi' => $item->tanggal_transaksi->format('Y-m-d H:i:s'),
                    'kurir' => $item->kurir ? $item->kurir->nama_pegawai : null
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Data transaksi penjualan berhasil diambil',
                'data' => $formattedData,
                'total' => $transaksi->count()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data transaksi penjualan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified transaksi penjualan
     */
    public function show($id)
    {
        try {
            $transaksi = Transaksipenjualan::with([
                'detailTransaksi.barang',
                'pembeli',
                'kurir'
            ])->find($id);

            if (!$transaksi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ], 404);
            }

            $formattedData = [
                'id_transaksi' => $transaksi->id_transaksi,
                'nama_pembeli' => $transaksi->pembeli->nama ?? 'Tidak diketahui',
                'alamat_pembeli' => $transaksi->pembeli->alamat ?? null,
                'telepon_pembeli' => $transaksi->pembeli->no_telepon ?? null,
                'barang_list' => $transaksi->detailTransaksi->map(function ($detail) {
                    return [
                        'nama_barang' => $detail->barang->nama_barang ?? 'Tidak diketahui',
                        'harga_barang' => (float) $detail->harga_satuan,
                        'jumlah' => (int) $detail->jumlah,
                        'subtotal' => (float) $detail->subtotal
                    ];
                }),
                'tanggal_kirim' => $transaksi->tanggal_kirim ? $transaksi->tanggal_kirim->format('Y-m-d H:i:s') : null,
                'status_pengiriman' => $transaksi->status_pengiriman,
                'metode_pengantaran' => $transaksi->metode_pengantaran,
                'ongkir' => (float) $transaksi->ongkir ?? 0,
                'total_harga' => (float) $transaksi->total_harga,
                'tanggal_transaksi' => $transaksi->tanggal_transaksi->format('Y-m-d H:i:s'),
                'kurir' => $transaksi->kurir ? [
                    'nama_kurir' => $transaksi->kurir->nama_pegawai,
                    'telepon_kurir' => $transaksi->kurir->no_telepon ?? null
                ] : null
            ];

            return response()->json([
                'success' => true,
                'message' => 'Detail transaksi berhasil diambil',
                'data' => $formattedData
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaksi by status pengiriman
     */
    public function getByStatusPengiriman($status)
    {
        try {
            $transaksi = Transaksipenjualan::with([
                'detailTransaksi.barang',
                'pembeli',
                'kurir'
            ])
                ->where('status_pengiriman', $status)
                ->orderBy('tanggal_transaksi', 'desc')
                ->get();

            $formattedData = $transaksi->map(function ($item) {
                return [
                    'id_transaksi' => $item->id_transaksi,
                    'nama_pembeli' => $item->pembeli->nama ?? 'Tidak diketahui',
                    'barang_list' => $item->detailTransaksi->map(function ($detail) {
                        return [
                            'nama_barang' => $detail->barang->nama_barang ?? 'Tidak diketahui',
                            'harga_barang' => (float) $detail->harga_satuan,
                            'jumlah' => (int) $detail->jumlah
                        ];
                    }),
                    'tanggal_kirim' => $item->tanggal_kirim ? $item->tanggal_kirim->format('Y-m-d H:i:s') : null,
                    'status_pengiriman' => $item->status_pengiriman,
                    'ongkir' => (float) $item->ongkir ?? 0,
                    'total_harga' => (float) $item->total_harga
                ];
            });

            return response()->json([
                'success' => true,
                'message' => "Data transaksi dengan status '{$status}' berhasil diambil",
                'data' => $formattedData,
                'total' => $transaksi->count()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data transaksi berdasarkan status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get summary statistics
     */
    public function getSummary()
    {
        try {
            $summary = [
                'total_transaksi' => TransaksiPenjualan::count(),
                'total_pendapatan' => TransaksiPenjualan::sum('total_harga'),
                'transaksi_pending' => TransaksiPenjualan::where('status_pengiriman', 'pending')->count(),
                'transaksi_dikirim' => TransaksiPenjualan::where('status_pengiriman', 'dikirim')->count(),
                'transaksi_selesai' => TransaksiPenjualan::where('status_pengiriman', 'selesai')->count(),
                'transaksi_hari_ini' => TransaksiPenjualan::whereDate('tanggal_transaksi', today())->count(),
                'pendapatan_hari_ini' => TransaksiPenjualan::whereDate('tanggal_transaksi', today())->sum('total_harga')
            ];

            return response()->json([
                'success' => true,
                'message' => 'Summary data berhasil diambil',
                'data' => $summary
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil summary data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getDiantarKurir(Request $request)
    {
        try {
            $pegawai = $request->user(); // Ambil user dari token
            $pegawaiId = $pegawai->id_pegawai;


            $transaksi = transaksipenjualan::with([
                'detailTransaksi.barang',
                'pembeli',
                'kurir'
            ])
                ->where('metode_pengantaran', 'diantar_kurir')
                ->where('status_pembayaran', 'Lunas')
                ->where(function($query) {
                    $query->where('status_transaksi', 'dijadwalkan')
                        ->orWhere('status_transaksi', 'sedang_dikirim');
                })
                ->where('id_pegawai', $pegawaiId) // pakai ID dari token
                ->orderBy('tanggal_transaksi', 'desc')
                ->get();

            $formattedData = $transaksi->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_pembeli' => $item->pembeli->nama_pembeli ?? 'Tidak diketahui',
                    'metode_pengantaran' => $item->metode_pengantaran,
                    'status_pembayaran' => $item->status_pembayaran,
                    'barang_list' => $item->detailTransaksi->map(function ($detail) {
                        return [
                            'nama_barang' => $detail->barang->nama_barang ?? 'Tidak diketahui',
                            'harga_barang' => (float) $detail->harga_satuan,
                            'jumlah' => (int) $detail->jumlah
                        ];
                    }),
                    'tanggal_kirim' => $item->tanggal_kirim ? $item->tanggal_kirim->format('Y-m-d H:i:s') : null,
                    'status_pengiriman' => $item->status_pengiriman,
                    'ongkir' => (float) ($item->ongkir ?? 0),
                    'total_harga' => (float) $item->total_harga
                ];
            });

            return response()->json([
                'success' => true,
                'message' => "Data transaksi milik id_pegawai = $pegawaiId berhasil diambil",
                'data' => $formattedData,
                'total' => $transaksi->count()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data transaksi diantar_kurir dengan status pembayaran Lunas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatusTransaksi($id, Request $request)
    {
        $transaksi = Transaksipenjualan::where('id', $id)->first();

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        $transaksi->status_transaksi = $request->status_transaksi;
        $transaksi->save();

        return response()->json([
            'success' => true,
            'message' => 'Status transaksi berhasil diperbarui'
        ]);
    }

}