<?php

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// <!DOCTYPE html>
// <html lang="en">

// <head>
//     <meta charset="UTF-8">
//     <meta name="viewport" content="width=device-width, initial-scale=1.0">
//     <title>Perpanjangan Masa Penitipan - Gudang</title>
//     <link rel="icon" type="image/png" sizes="128x128" href="{{ asset('images/logo2.png') }}">
//     <link rel="preconnect" href="https://fonts.googleapis.com">
//     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
//     <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
//         rel="stylesheet">
//     <script src="https://cdn.tailwindcss.com"></script>
//     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

//     <style>
//         body {
//             font-family: 'Plus Jakarta Sans', sans-serif;
//         }
//         .table-auto th, .table-auto td {
//             padding: 0.75rem;
//             border: 1px solid #e2e8f0;
//             text-align: left;
//         }
//         .table-auto th {
//             background-color: #f8fafc;
//             font-weight: 600;
//         }
//         .btn {
//             padding: 0.5rem 1rem;
//             border-radius: 0.375rem;
//             text-decoration: none;
//             color: white;
//             cursor: pointer;
//             transition: background-color 0.3s ease;
//         }
//         .btn-blue {
//             background-color: #3b82f6; /* blue-500 */
//         }
//         .btn-blue:hover {
//             background-color: #2563eb; /* blue-600 */
//         }
//     </style>
// </head>

// <body class="bg-gray-100 text-gray-800 grid grid-cols-1 md:grid-cols-[250px_1fr] min-h-screen">

//     <div class="bg-gray-800 text-white p-6 flex flex-col justify-between">
//         <div>
//             <h2 class="text-xl font-semibold mb-8">Gudang</h2>
//             <nav>
//                 <div class="space-y-4">
//                     <a href="{{ route('gudang.DashboardGudang') }}"
//                         class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
//                         <i class="fas fa-user-circle mr-2"></i>
//                         <span>Profile Saya</span>
//                     </a>
//                     <a href="{{ route('gudang.DashboardTitipanBarang') }}"
//                         class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
//                         <i class="fas fa-dolly mr-2"></i>
//                         <span>Tambah Titip Barang</span>
//                     </a>
//                     <a href="{{ route('gudang.DaftarBarang') }}"
//                         class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
//                         <i class="fas fa-boxes mr-2"></i>
//                         <span>Daftar Barang</span>
//                     </a>
//                     {{-- Tambahkan link ke halaman perpanjangan di sidebar jika diinginkan --}}
//                     <a href="{{ route('gudang.showPerpanjanganPage') }}"
//                         class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg"> {{-- Tambahkan kelas 'bg-blue-600' jika ini halaman aktif --}}
//                         <i class="fas fa-clock-rotate-left mr-2"></i>
//                         <span>Perpanjang Penitipan</span>
//                     </a>
//                 </div>
//             </nav>
//         </div>
//         <div class="space-y-4 mt-auto">
//             {{-- Ganti dengan route logout yang sesuai jika ada --}}
//             <form method="POST" action="#"> {{-- route('logout') --}}
//                 @csrf
//                 <button type="submit" class="w-full py-2 bg-red-600 text-white rounded-lg hover:bg-red-500">Keluar</button>
//             </form>
//         </div>
//     </div>

//     <div class="p-8 bg-gray-100">
//         <h1 class="text-3xl font-bold mb-6">Perpanjangan Masa Penitipan Barang</h1>

//         @if(session('success'))
//             <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
//                 <strong class="font-bold">Berhasil!</strong>
//                 <span class="block sm:inline">{{ session('success') }}</span>
//             </div>
//         @endif

//         @if(session('error'))
//             <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
//                 <strong class="font-bold">Gagal!</strong>
//                 <span class="block sm:inline">{{ session('error') }}</span>
//             </div>
//         @endif

//         <div class="bg-white shadow-md rounded-lg overflow-x-auto">
//             <table class="table-auto w-full">
//                 <thead>
//                     <tr>
//                         <th class="px-4 py-2">No.</th>
//                         <th class="px-4 py-2">ID Transaksi</th>
//                         <th class="px-4 py-2">Nama Penitip</th>
//                         <th class="px-4 py-2">Tanggal Penitipan</th>
//                         <th class="px-4 py-2">Tanggal Akhir Penitipan</th>
//                         <th class="px-4 py-2">Aksi</th>
//                     </tr>
//                 </thead>
//                 <tbody>
//                     @forelse($transaksis as $index => $transaksi)
//                         <tr>
//                             <td class="px-4 py-2">{{ $index + 1 }}</td>
//                             <td class="px-4 py-2">{{ $transaksi->id_transaksi_penitipan }}</td>
//                             <td class="px-4 py-2">{{ $transaksi->penitip ? $transaksi->penitip->nama_penitip : 'N/A' }}</td>
//                             <td class="px-4 py-2">{{ $transaksi->tanggal_penitipan ? \Carbon\Carbon::parse($transaksi->tanggal_penitipan)->format('d M Y') : 'N/A' }}</td>
//                             <td class="px-4 py-2">{{ $transaksi->tanggal_akhir_penitipan ? \Carbon\Carbon::parse($transaksi->tanggal_akhir_penitipan)->format('d M Y') : 'N/A' }}</td>
//                             <td class="px-4 py-2">
//                                 @if(!$transaksi->tanggal_pengambilan_barang) {{-- Tombol hanya muncul jika belum diambil penitip --}}
//                                     <form action="{{ route('gudang.prosesPerpanjangPenitipan', $transaksi->id_transaksi_penitipan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memperpanjang masa penitipan untuk transaksi ini selama 30 hari?');">
//                                         @csrf
//                                         <button type="submit" class="btn btn-blue text-sm">
//                                             <i class="fas fa-calendar-plus mr-1"></i> Perpanjang 30 Hari
//                                         </button>
//                                     </form>
//                                 @else
//                                     <span class="text-sm text-gray-500">Sudah Diambil</span>
//                                 @endif
//                             </td>
//                         </tr>
//                     @empty
//                         <tr>
//                             <td colspan="6" class="text-center px-4 py-2">Belum ada data transaksi penitipan yang bisa diperpanjang.</td>
//                         </tr>
//                     @endforelse
//                 </tbody>
//             </table>
//         </div>
//          @if($transaksis->hasPages())
//             <div class="mt-6">
//                 {{ $transaksis->links() }} {{-- Untuk paginasi jika Anda menambahkannya di controller --}}
//             </div>
//         @endif
//     </div>
//     <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
// </body>
// </html>
