@extends('layouts.loading')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Daftar Barang - Status: Disiapkan & Lunas</h1>

        @forelse ($transaksi as $item)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="text-sm text-gray-600 mb-2">
                    <span class="font-semibold">ID Transaksi:</span> {{ $item->id }} |
                    <span class="font-semibold">Tanggal:</span>
                    {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d M Y') }}
                </div>

                <div class="flex flex-wrap items-center gap-4 mb-4">
                    <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">
                        Status Pembayaran: {{ ucfirst($item->status_pembayaran) }}
                    </span>
                </div>

                <div>
                    <ul class="space-y-4">
                        @if ($item->detailTransaksi)
                            @foreach ($item->detailTransaksi as $detail)
                                <li class="border-b pb-2">
                                    <p><span class="font-medium text-gray-800">Nama Barang:</span>
                                        {{ $detail->barang->nama_barang ?? 'Barang tidak ditemukan' }}</p>
                                </li>
                            @endforeach
                        @else
                            <li class="text-red-500 text-sm">Detail transaksi tidak tersedia.</li>
                        @endif
                    </ul>
                </div>

                @if($item->status_transaksi !== 'Di siapkan')
                <form action="{{ route('ubah-status-transaksi', $item->id_transaksi_penjualan) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                        Tandai sebagai Disiapkan
                    </button>
                </form>
                @else
                    <button disabled
                        class="px-4 py-2 bg-gray-400 text-white text-sm rounded cursor-not-allowed">
                        Sudah Disiapkan
                    </button>
                @endif

            </div>
        @empty
            <p class="text-gray-500 text-center">Tidak ada barang yang sedang disiapkan.</p>
        @endforelse
    </div>
@endsection
