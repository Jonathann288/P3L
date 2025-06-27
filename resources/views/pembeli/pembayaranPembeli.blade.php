@extends('layouts.loading')

@section('content')
    @php
        $orderNumber = date('Y.m.', strtotime($transaksi->tanggal_transaksi)) . $transaksi->id_transaksi_penjualan;
    @endphp
    <div class="container mx-auto py-8 px-4">
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 text-white px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold flex items-center">
                        <img src="{{ asset('images/logo6.png') }}" alt="Logo" class="h-12 mr-3">
                        | Pembayaran
                    </h2>
                    <div class="text-right">
                        <p class="text-sm font-medium">Selesaikan Pembayaran Sebelum:</p>
                        <div id="countdown-timer" class="text-3xl font-bold text-white mt-1">
                            01:00
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Number Section -->
            <div class="bg-blue-50 px-6 py-4 border-b">
                <div class="flex items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Tanggal Transaksi</h3>
                        <p class="text-lg font-medium text-gray-800">
                            {{ date('d/m/Y H:i', strtotime($transaksi->tanggal_transaksi)) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <!-- Order Summary Section -->
                <div class="mb-8">
                    <h4 class="mb-4 text-gray-900 text-xl font-semibold">Detail Pemesanan</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-blue-200 rounded-lg">
                            <thead class="bg-blue-50">
                                <tr>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700 border-b border-gray-200">
                                        Barang</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700 border-b border-gray-200">
                                        Nomor Pesanan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalSubtotal = 0;
                                    $totalBarang = 0;
                                @endphp

                                @foreach ($transaksi->detailTransaksi as $detail)
                                    @php
                                        $barang = $detail->barang;
                                        $lineTotal = $detail->harga * $detail->jumlah_barang;
                                        $totalSubtotal += $lineTotal;
                                        $totalBarang += $detail->jumlah_barang;
                                    @endphp
                                    <tr class="border-b border-gray-200">
                                        <td class="py-3 px-4">{{ $barang->nama_barang }}</td>
                                        <td class="py-3 px-4"><strong>{{ $orderNumber }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payment Proof Upload -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Upload Bukti Pembayaran</h3>

                    <!-- Status Check -->
                    @if($transaksi->status_pembayaran === 'Gagal' || $transaksi->status_transaksi === 'dibatalkan')

                    @else
                        <form id="payment-form" action="{{ route('prosesPembayaran', ['id' => $transaksi->id]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Improved Upload Area -->
                            <div class="mt-6">
                                <div class="upload-container relative group">
                                    <input id="payment_proof" name="payment_proof" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                                    <div class="upload-area border-2 border-dashed border-blue-300 rounded-xl p-8 text-center transition-all duration-300 group-hover:border-blue-500 group-hover:bg-blue-50">
                                        <div class="upload-icon mx-auto mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-400 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                        </div>
                                        <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition duration-200">
                                            Pilih File
                                        </button>
                                        <p class="text-xs text-gray-400 mt-3">Format: PNG, JPG, JPEG, PDF (maks. 5MB)</p>
                                    </div>
                                </div>

                                <!-- File Preview -->
                                <div id="file-preview" class="mt-4 hidden">
                                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-100">
                                        <div class="flex items-center space-x-3">
                                            <div class="file-icon bg-blue-100 p-2 rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p id="file-name" class="text-sm font-medium text-gray-700"></p>
                                                <p id="file-size" class="text-xs text-gray-500"></p>
                                            </div>
                                        </div>
                                        <button type="button" id="remove-file" class="text-red-500 hover:text-red-700 transition duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="mt-6">
                                <button type="submit" id="submit-btn" disabled
                                    class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 disabled:bg-blue-400 disabled:cursor-not-allowed">
                                    Bayar Sekarang
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Timeout Modal -->
            <div id="timeout-modal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <!-- Icon -->
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                        </div>

                        <!-- Title -->
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Waktu Pembayaran Habis</h3>

                        <!-- Message -->
                        <div class="mt-2 px-7 py-3">
                            <p class="text-sm text-gray-500">
                                Maaf, waktu untuk melakukan pembayaran telah berakhir. Silakan lakukan pemesanan ulang untuk
                                melanjutkan transaksi.
                            </p>
                        </div>

                        <!-- Buttons -->
                        <div class="items-center px-4 py-3">
                            <button id="back-to-cart"
                                class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 transition duration-200">
                                Kembali ke Keranjang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const countdownElement = document.getElementById('countdown-timer');
            const expiredTime = new Date('{{ $expired_time }}');
            const paymentForm = document.getElementById('payment-form');
            const timeoutModal = document.getElementById('timeout-modal');

            function updateCountdown() {
                const now = new Date();
                const diff = expiredTime - now;

                if (diff <= 0) {
                    // Waktu habis
                    countdownElement.textContent = "00:00";
                    countdownElement.classList.add('text-red-500', 'animate-pulse');
                    countdownElement.classList.remove('text-white');

                    // Tampilkan modal timeout
                    showTimeoutModal();
                    return;
                }

                // Hitung menit dan detik
                const minutes = Math.floor(diff / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                // Format tampilan
                const display = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                countdownElement.textContent = display;

                // Ubah warna menjadi merah ketika kurang dari 30 detik
                if (diff < 30000) {
                    countdownElement.classList.add('text-red-500', 'animate-pulse');
                    countdownElement.classList.remove('text-white');
                }
            }

            function showTimeoutModal() {
                timeoutModal.classList.remove('hidden');

                // Nonaktifkan form pembayaran
                if (paymentForm) {
                    paymentForm.style.pointerEvents = 'none';
                    paymentForm.style.opacity = '0.5';
                }

                // Kirim request untuk update status transaksi
                fetch(`{{ route('pembayaranPembeli', ['id' => $transaksi->id]) }}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                    }
                });
            }

            // Event listener untuk tombol kembali ke keranjang
            document.getElementById('back-to-cart')?.addEventListener('click', function () {
                window.location.href = "{{ route('pembeli.cart') }}";
            });

            // Mulai countdown
            updateCountdown();
            const countdownInterval = setInterval(updateCountdown, 1000);

            // Bersihkan interval ketika halaman di-unload
            window.addEventListener('beforeunload', function () {
                clearInterval(countdownInterval);
            });

            // File Upload Handling
            const fileInput = document.getElementById('payment_proof');
            const filePreview = document.getElementById('file-preview');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');
            const removeFileBtn = document.getElementById('remove-file');
            const submitBtn = document.getElementById('submit-btn');
            const uploadArea = document.querySelector('.upload-area');

            // Handle file selection
            fileInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    
                    // Validate file type
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
                    if (!validTypes.includes(file.type)) {
                        alert('Format file tidak didukung. Harap upload gambar (JPG, PNG) atau PDF.');
                        return;
                    }
                    
                    // Validate file size (5MB max)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar. Maksimal 5MB.');
                        return;
                    }
                    
                    // Display file info
                    fileName.textContent = file.name;
                    fileSize.textContent = formatFileSize(file.size);
                    filePreview.classList.remove('hidden');
                    submitBtn.disabled = false;
                    
                    // Change upload area appearance
                    uploadArea.classList.add('bg-green-50', 'border-green-300');
                    uploadArea.classList.remove('group-hover:border-blue-500', 'group-hover:bg-blue-50');
                }
            });

            // Handle drag and drop
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('border-blue-500', 'bg-blue-50');
            });

            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                if (!fileInput.files.length) {
                    this.classList.remove('border-blue-500', 'bg-blue-50');
                }
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('border-blue-500', 'bg-blue-50');
                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    const event = new Event('change');
                    fileInput.dispatchEvent(event);
                }
            });

            // Remove file
            removeFileBtn.addEventListener('click', function() {
                fileInput.value = '';
                filePreview.classList.add('hidden');
                submitBtn.disabled = true;
                uploadArea.classList.remove('bg-green-50', 'border-green-300');
                uploadArea.classList.add('group-hover:border-blue-500', 'group-hover:bg-blue-50');
            });

            // Format file size
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
            }
        });
    </script>

    <style>
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .upload-container {
            position: relative;
            overflow: hidden;
        }

        .upload-area {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .file-icon {
            transition: transform 0.2s ease;
        }

        .file-icon:hover {
            transform: scale(1.1);
        }

        #submit-btn {
            transition: all 0.3s ease;
        }

        #submit-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
@endsection