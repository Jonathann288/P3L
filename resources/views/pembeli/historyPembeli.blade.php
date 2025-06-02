<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pembeli</title>
    <link rel="icon" type="image/png" sizes="128x128" href="{{ asset('images/logo2.png') }}"> {{-- Sebaiknya gunakan asset() untuk path gambar --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>


<body class="font-sans bg-gray-100 text-gray-800 grid grid-cols-1 md:grid-cols-[250px_1fr] min-h-screen">

    <div class="bg-gray-800 text-white p-6 flex flex-col justify-between min-h-0"> {{-- min-h-0 untuk flex di chrome --}}
        <div>
            <h2 class="text-xl font-semibold mb-8">MyAccount</h2>
            <nav>
                <div class="space-y-4">
                    <a href="{{ route('pembeli.profilPembeli') }}" class="flex items-center space-x-4 p-3 {{ request()->routeIs('pembeli.profilPembeli') ? 'bg-blue-600' : 'hover:bg-gray-700' }} rounded-lg">
                        <img src="{{ asset($pembeli->foto_pembeli ? $pembeli->foto_pembeli : 'images/default_profile.png') }}" alt="profile"
                            class="w-8 h-8 rounded-full object-cover">
                        <span>{{ $pembeli->nama_pembeli }}</span>
                    </a>
                    <a href="{{ route('pembeli.historyPembeli') }}" class="flex items-center space-x-4 p-3 {{ request()->routeIs('pembeli.historyPembeli') ? 'bg-blue-600' : 'hover:bg-gray-700' }} rounded-lg">
                        <i class="fa-solid fa-clock-rotate-left w-6 text-center"></i> {{-- w-6 text-center untuk alignment ikon --}}
                        <span>History</span>
                    </a>
                    <a href="{{ route('pembeli.AlamatPembeli') }}"
                        class="flex items-center space-x-4 p-3 {{ request()->routeIs('pembeli.AlamatPembeli') ? 'bg-blue-600' : 'hover:bg-gray-700' }} rounded-lg">
                        <i class="fas fa-map-marker-alt w-6 text-center"></i> {{-- Ganti ikon & alignment --}}
                        <span>Alamat</span>
                    </a>
                </div>
            </nav>
        </div>
        <div class="space-y-4 mt-auto">
            <a href="{{ route('pembeli.Shop-Pembeli') }}" class="block w-full py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-500">
                Kembali ke Toko
            </a>
        </div>
    </div>

    <div class="p-6 md:p-8 bg-gray-100 overflow-y-auto"> {{-- Tambahkan overflow-y-auto --}}
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl md:text-3xl font-semibold text-gray-800">History Pembelian</h1>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-300 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if ($transaksiPenjualan->isNotEmpty())
                @foreach ($transaksiPenjualan as $transaksi)
                    @foreach ($transaksi->detailTransaksiPenjualan as $detail)
                        @php
                            $fotoBarangPaths = [];
                            if ($detail->barang && $detail->barang->foto_barang) {
                                $fotoBarangData = $detail->barang->foto_barang;
                                if (is_string($fotoBarangData)) {
                                    $decoded = json_decode($fotoBarangData, true);
                                    $fotoBarangPaths = is_array($decoded) ? $decoded : [$fotoBarangData];
                                } elseif (is_array($fotoBarangData)) {
                                    $fotoBarangPaths = $fotoBarangData;
                                }
                            }
                            $fotoUtama = $fotoBarangPaths[0] ?? 'images/default.jpg';
                        @endphp
                        <div class="bg-white rounded-xl shadow-md p-6 transition hover:shadow-xl flex flex-col"> {{-- flex flex-col --}}
                            {{-- Bagian yang bisa diklik untuk modal --}}
                            <div class="cursor-pointer flex-grow" 
                                 onclick="openModalDetail(this)" 
                                 data-nama-barang="{{ $detail->barang->nama_barang ?? 'Nama Barang Tidak Tersedia' }}"
                                 data-harga-barang="{{ number_format($detail->barang->harga_barang ?? 0, 0, ',', '.') }}"
                                 data-foto-barang="{{ asset($fotoUtama) }}"
                                 data-metode-pengantaran="{{ $transaksi->metode_pengantaran }}"
                                 data-status-pembayaran="{{ $transaksi->status_pembayaran }}"
                                 data-tanggal-transaksi="{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d M Y') }}"
                                 data-tanggal-kirim="{{ $transaksi->tanggal_kirim ? \Carbon\Carbon::parse($transaksi->tanggal_kirim)->translatedFormat('d M Y') : '-' }}"
                                 data-pembayaran-class="{{ $transaksi->status_pembayaran == 'Lunas' ? 'text-green-500' : 'text-red-500' }}"
                                 data-total-harga="{{ number_format($detail->total_harga, 0, ',', '.') }}">
                                
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset($pembeli->foto_pembeli ? $pembeli->foto_pembeli : 'images/default_profile.png') }}" alt="profile"
                                            class="w-10 h-10 rounded-full object-cover">
                                        <div>
                                            <div class="text-lg font-semibold">{{ $pembeli->nama_pembeli }}</div>
                                            <div class="text-sm text-gray-500">{{ $pembeli->nomor_telepon_pembeli ? '(+62) ' . $pembeli->nomor_telepon_pembeli : '-'}}</div>
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-400">
                                        <i class="fa-regular fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d M Y') }}
                                    </div>
                                </div>

                                <div class="flex gap-4 mb-4">
                                    <img src="{{ asset($fotoUtama) }}" alt="{{ $detail->barang->nama_barang ?? 'Gambar Barang' }}"
                                        class="w-20 h-20 rounded-lg object-cover border">
                                    <div class="flex flex-col justify-center">
                                        <div class="text-md font-semibold">{{ $detail->barang->nama_barang ?? 'Barang Dihapus' }}</div>
                                        <div class="text-sm text-gray-600">Rp {{ number_format($detail->barang->harga_barang ?? 0, 0, ',', '.') }}</div>
                                    </div>
                                </div>

                                <div class="text-sm text-gray-700 space-y-1"> {{-- space-y-1 agar lebih rapat --}}
                                    <div><i class="fa-solid fa-truck mr-2 text-blue-600 w-4 text-center"></i> Pengantaran:
                                        <strong>{{ $transaksi->metode_pengantaran }}</strong>
                                    </div>
                                    <div><i class="fa-solid fa-money-bill-wave mr-2 text-green-600 w-4 text-center"></i> Pembayaran:
                                        <strong
                                            class="{{ $transaksi->status_pembayaran == 'Lunas' ? 'text-green-500' : 'text-red-500' }}">{{ $transaksi->status_pembayaran }}</strong>
                                    </div>
                                    <div><i class="fa-solid fa-calendar-check mr-2 text-indigo-600 w-4 text-center"></i> Dikirim:
                                        {{ $transaksi->tanggal_kirim ? \Carbon\Carbon::parse($transaksi->tanggal_kirim)->translatedFormat('d M Y') : '-' }}
                                    </div>
                                    <div><i class="fa-solid fa-calculator mr-2 text-purple-600 w-4 text-center"></i> Total Harga:
                                        <strong class="text-gray-800">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                            </div> {{-- Akhir bagian yang bisa diklik untuk modal --}}

                            {{-- Bagian Info Penjual dan Rating --}}
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                @php
                                    $penitipInfo = null; 
                                    if ($detail->barang) {
                                        $penitipInfo = $detail->barang->penitip_data; // Menggunakan accessor
                                    }
                                @endphp

                                @if ($penitipInfo)
                                    <div class="text-xs text-gray-600 mb-2">
                                        Dijual oleh: <strong class="text-gray-800">{{ $penitipInfo->nama_penitip }}</strong>
                                        <span class="ml-2 text-yellow-500 average-rating-penitip-{{ $penitipInfo->id_penitip }}">
                                            @if(isset($penitipInfo->rating_penitip) && $penitipInfo->rating_penitip > 0)
                                                <i class="fas fa-star text-xs"></i> {{ number_format($penitipInfo->rating_penitip, 1) }}/5.0
                                            @else
                                                <i class="far fa-star text-xs"></i> <span class="text-gray-500">(Belum ada rating)</span>
                                            @endif
                                        </span>
                                    </div>
                                @else
                                    <div class="text-xs text-gray-500 mb-2">
                                        Info penjual tidak tersedia.
                                    </div>
                                @endif
                                
                                <div>
                                    <h4 class="text-sm font-semibold mb-1 text-gray-700">Beri Rating Penjual:</h4>
                                    <div class="rating-stars flex items-center space-x-1" 
                                        data-detail-id="{{ $detail->id_detail_transaksi_penjualan }}"
                                        data-penitip-id="{{ $penitipInfo ? $penitipInfo->id_penitip : '' }}">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <button
                                                class="star text-2xl focus:outline-none {{ ($detail->rating_untuk_penitip ?? 0) >= $i ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition-colors duration-150"
                                                data-value="{{ $i }}">★</button>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-500 mt-1 block rating-feedback-{{ $detail->id_detail_transaksi_penjualan }}">
                                        @if($detail->rating_untuk_penitip)
                                            Anda memberi: {{ $detail->rating_untuk_penitip }} bintang
                                        @else
                                            Belum ada rating
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div> {{-- Akhir Card Item --}}
                    @endforeach
                @endforeach
            @else
                <p class="text-center text-gray-500 md:col-span-2">Belum ada riwayat pembelian.</p>
            @endif
        </div>
    </div>


    <div id="modalDetail" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-md p-6 relative shadow-lg">
            <button onclick="closeModalDetail()" class="absolute top-3 right-3 text-gray-500 hover:text-red-500">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
            <h2 class="text-xl font-bold text-gray-800 mb-6">Detail Pembelian</h2>

            <div class="flex gap-4 mb-4 items-center">
                <img id="modal-foto-barang" src="" alt="barang" class="w-20 h-20 rounded-lg object-cover border">
                <div class="flex flex-col justify-center">
                    <div id="modal-nama-barang" class="text-lg font-semibold"></div>
                    <div id="modal-harga-barang" class="text-sm text-gray-600"></div>
                </div>
            </div>

            <div class="text-sm text-gray-700 space-y-2">
                <div><i class="fa-solid fa-truck mr-2 text-blue-600 w-4 text-center"></i> Pengantaran: <strong id="modal-metode-pengantaran"></strong></div>
                <div><i class="fa-solid fa-money-bill-wave mr-2 text-green-600 w-4 text-center"></i> Pembayaran: 
                    <strong id="modal-status-pembayaran" class=""></strong>
                </div>
                <div><i class="fa-solid fa-calendar-alt mr-2 text-indigo-600 w-4 text-center"></i> Tanggal Transaksi: 
                    <span id="modal-tanggal-transaksi"></span>
                </div>
                <div><i class="fa-solid fa-truck-fast mr-2 text-yellow-600 w-4 text-center"></i> Dikirim:
                    <span id="modal-tanggal-kirim"></span>
                </div>
                <div><i class="fa-solid fa-calculator mr-2 text-purple-600 w-4 text-center"></i> Total Harga: 
                    <strong id="modal-total-harga" class="text-gray-800"></strong>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModalDetail(element) {
            document.getElementById('modal-foto-barang').src = element.dataset.fotoBarang;
            document.getElementById('modal-nama-barang').textContent = element.dataset.namaBarang;
            document.getElementById('modal-harga-barang').textContent = 'Rp ' + element.dataset.hargaBarang;
            document.getElementById('modal-metode-pengantaran').textContent = element.dataset.metodePengantaran;

            const statusEl = document.getElementById('modal-status-pembayaran');
            statusEl.textContent = element.dataset.statusPembayaran;
            // Hapus kelas warna sebelumnya dan tambahkan yang baru
            statusEl.className = ''; // Reset kelas
            statusEl.classList.add(element.dataset.pembayaranClass);


            document.getElementById('modal-tanggal-transaksi').textContent = element.dataset.tanggalTransaksi;
            document.getElementById('modal-tanggal-kirim').textContent = element.dataset.tanggalKirim;
            document.getElementById('modal-total-harga').textContent = 'Rp ' + element.dataset.totalHarga;


            document.getElementById('modalDetail').classList.remove('hidden');
        }

        function closeModalDetail() {
            document.getElementById('modalDetail').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const allRatingStarsContainers = document.querySelectorAll('.rating-stars');

            allRatingStarsContainers.forEach(starsContainer => {
                const stars = starsContainer.querySelectorAll('.star');
                stars.forEach(star => {
                    star.addEventListener('click', function () {
                        const detailId = starsContainer.dataset.detailId;
                        const ratingValue = this.dataset.value;
                        const penitipIdFromAttribute = starsContainer.dataset.penitipId;
                        const feedbackSpan = document.querySelector(`.rating-feedback-${detailId}`);

                        starsContainer.querySelectorAll('.star').forEach(s => {
                            s.classList.remove('text-yellow-400');
                            s.classList.add('text-gray-300');
                            if (parseInt(s.dataset.value) <= parseInt(ratingValue)) {
                                s.classList.remove('text-gray-300');
                                s.classList.add('text-yellow-400');
                            }
                        });
                        if (feedbackSpan) feedbackSpan.textContent = 'Menyimpan...';

                        fetch('{{ route("pembeli.submitRating") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                detail_transaksi_id: detailId,
                                rating: ratingValue
                            })
                        })
                        .then(response => {
                            const contentType = response.headers.get("content-type");
                            if (response.ok && contentType && contentType.indexOf("application/json") !== -1) {
                                return response.json();
                            } else if (!response.ok && contentType && contentType.indexOf("application/json") !== -1) {
                                return response.json().then(errorData => {
                                    throw { status: response.status, data: errorData };
                                });
                            } else {
                                return response.text().then(text => {
                                    let errorDetail = "Server mengembalikan respons yang tidak valid.";
                                     if (text.toLowerCase().includes("<!doctype html>")) {
                                        if (text.toLowerCase().includes("login")) {
                                            errorDetail = "Sesi Anda mungkin telah berakhir. Silakan login kembali.";
                                        } else if (text.toLowerCase().includes("not found")) {
                                            errorDetail = "Endpoint tidak ditemukan (404).";
                                        } else if (response.status === 500 || text.toLowerCase().includes("server error")) { // Periksa juga teks untuk "server error"
                                             errorDetail = "Terjadi error di server ("+response.status+").";
                                         }
                                     }
                                    throw new Error(`Error ${response.status}: ${errorDetail} (Received non-JSON)`);
                                });
                            }
                        })
                        .then(data => {
                            if (data.success === true) {
                                if (feedbackSpan) {
                                    feedbackSpan.textContent = data.message || ('Rating: ' + data.given_rating + ' bintang. Disimpan!');
                                }
                                
                                const receivedPenitipId = data.penitip_id; 
                                const newAverageRating = data.new_average_rating;

                                if (receivedPenitipId) {
                                    const averageRatingSpan = document.querySelector(`.average-rating-penitip-${receivedPenitipId}`);
                                    if (averageRatingSpan) {
                                        if (newAverageRating !== null && newAverageRating !== undefined && parseFloat(newAverageRating) > 0) {
                                            averageRatingSpan.innerHTML = `<i class="fas fa-star text-xs"></i> ${parseFloat(newAverageRating).toFixed(1)}/5.0`;
                                        } else {
                                            averageRatingSpan.innerHTML = `<i class="far fa-star text-xs"></i> <span class="text-gray-500">(Belum ada rating)</span>`;
                                        }
                                    }
                                }
                                
                            } else {
                                 if (feedbackSpan) feedbackSpan.textContent = 'Gagal: ' + (data.message || 'Format respons tidak sesuai.');
                            }
                        })
                        .catch(error => {
                            console.error('Fetch Error Details:', error);
                            let errorMessage = 'Terjadi kesalahan.';
                            if (error.status && error.data && error.data.message) {
                                errorMessage = `Error ${error.status}: ${error.data.message}`;
                                if (error.data.errors) {
                                    const validationMessages = Object.values(error.data.errors).flat().join(' ');
                                    errorMessage += ` Detail: ${validationMessages}`;
                                }
                            } else if (error.message) {
                                errorMessage = error.message;
                            }

                            if (feedbackSpan) {
                                feedbackSpan.textContent = errorMessage;
                            } else {
                                alert(errorMessage);
                            }
                        });
                    });
                });
            });
        });
    </script>
    
</body>
</html>