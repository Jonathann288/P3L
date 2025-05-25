<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\Keranjang;
use App\Models\Alamat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TransaksiPenjualanControllers extends Controller
{
    public function tambahKeranjang(Request $request, $id)
    {
        try {
            $pembeli = Auth::guard('pembeli')->user();

            if (!$pembeli) {
                return redirect()->back()->with('error', 'Anda harus login terlebih dahulu.');
            }

            $barang = Barang::find($id);

            if (!$barang) {
                return redirect()->back()->with('error', 'Barang tidak ditemukan.');
            }

            // Check if item already exists in cart
            $existingItem = Keranjang::where('pembeli_id', $pembeli->id)
                ->where('id_barang', $id)
                ->first();

            if ($existingItem) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with([
                        'error' => 'Item sudah ada di keranjang Anda.',
                        'existing_item_id' => $existingItem->id
                    ]);
            }

            // Create new cart item
            Keranjang::create([
                'pembeli_id' => $pembeli->id,
                'id_barang' => $id,
                'nama_pembeli' => $pembeli->nama_pembeli,
                'nama_barang' => $barang->nama_barang,
                'harga_barang' => $barang->harga_barang,
                'jumlah' => 1
            ]);

            return redirect()
                ->back()
                ->with('success', 'Barang berhasil ditambahkan ke keranjang!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $pembeli = Auth::guard('pembeli')->user();
        $keranjang = Keranjang::where('pembeli_id', $pembeli->id)
            ->with('barang')
            ->get();

        return view('keranjang.index', compact('keranjang', 'pembeli'));
    }

    public function hapusKeranjang($id)
    {
        $keranjang = Keranjang::findOrFail($id);
        $keranjang->delete();

        return redirect()->back()->with('success', 'Barang berhasil dihapus dari keranjang!');
    }

    public function tampilKeranjang()
    {
        $pembeli = Auth::guard('pembeli')->user();
        $keranjang = Keranjang::with('barang')
            ->where('pembeli_id', $pembeli->id)
            ->get();

        return view('pembeli.cartPembeli', compact('keranjang', 'pembeli'));
    }
    // public function updateJumlah(Request $request, $id)
    // {
    //     $request->validate([
    //         'jumlah' => 'required|numeric|min:1|max:99'
    //     ]);

    //     $keranjang = Keranjang::findOrFail($id);
    //     $keranjang->jumlah = $request->jumlah;
    //     $keranjang->save();

    //     return redirect()->back()->with('success', 'Jumlah barang berhasil diupdate!');
    // }

    // KeranjangController.php
    public function searchCart(Request $request)
    {
        $search = $request->input('search');

        $pembeli = Auth::guard('pembeli')->user();

        if (!$pembeli) {
            return redirect()->back()->with('error', 'Anda harus login sebagai pembeli.');
        }

        $keranjang = $pembeli->keranjang()
            ->when($search, function ($query) use ($search) {
                return $query->where('nama_barang', 'like', '%' . $search . '%');
            })
            ->get();

        return view('pembeli.cartPembeli', compact('keranjang'));
    }

    public function cartPembeli()
    {
        $pembeli = Auth::guard('pembeli')->user();

        $keranjang = $pembeli->keranjang()->with('barang', 'pembeli')->get();

        return view('pembeli.cartPembeli', compact('keranjang'));
    }


    public function checkOutPembeli()
    {
        $pembeli = Auth::guard('pembeli')->user();

        // Ambil semua barang yang ada di keranjang pembeli ini
        $keranjang = Keranjang::where('pembeli_id', $pembeli->id)->get();

        // Ambil alamat lewat relasi pembeli
        $alamat = $pembeli->alamat;

        // Hitung subtotal dari semua barang di keranjang
        $subtotal = $keranjang->sum(function ($item) {
            return $item->harga_barang * $item->jumlah;
        });


        return view('pembeli.checkOutPembeli', compact('keranjang', 'pembeli', 'subtotal', 'alamat'));
    }


}
