@extends('layouts.loading')

@section('content')
    <div class="container mx-auto py-12 px-4">
        <div class="flex justify-center">
            <div class="w-full max-w-3xl">
                <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                    <div class="bg-blue-600 text-white px-6 py-4">
                        <div class="flex items-center justify-between">
                            <!-- Tombol Kembali -->
                            <a href="{{ route('pembeli.Shop-Pembeli') }}"
                                class="flex items-center hover:text-blue-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Kembali
                            </a>

                            <!-- Spacer kosong agar teks ke kanan -->
                            <div class="flex-grow"></div>

                            <!-- Judul Checkout di kanan -->
                            <h2 class="text-2xl font-semibold text-right">Checkout</h2>
                        </div>

                    </div>

                    <div class="p-6">
                        <form method="POST" action="{{ route('checkout.proses') }}">
                            @csrf

                            <!-- Delivery Method Section -->
                            <div class="mb-8">
                                <h4 class="mb-4 text-gray-900 text-xl font-semibold">Metode Pengiriman</h4>

                                <label
                                    class="flex items-start p-4 border border-gray-300 rounded-lg cursor-pointer mb-3 hover:border-blue-600 hover:bg-gray-50 transition">
                                    <input type="radio" name="metode_pengantaran" id="pickup" value="ambil_sendiri" checked
                                        onchange="toggleAlamat()" class="mt-1 mr-3 text-blue-600">
                                    <div>
                                        <div class="flex items-center text-gray-900 font-medium">
                                            <i class="fas fa-store mr-2"></i> Ambil Sendiri
                                        </div>
                                        <p class="text-gray-500 text-sm mt-1">Ambil barang digudang
                                        </p>
                                    </div>
                                </label>

                                <label
                                    class="flex items-start p-4 border border-gray-300 rounded-lg cursor-pointer hover:border-blue-600 hover:bg-gray-50 transition">
                                    <input type="radio" name="metode_pengantaran" id="delivery" value="diantar_kurir"
                                        onchange="toggleAlamat()" class="mt-1 mr-3 text-blue-600">
                                    <div>
                                        <div class="flex items-center text-gray-900 font-medium">
                                            <i class="fas fa-truck mr-2"></i> Diantar Kurir
                                        </div>
                                        <p class="text-gray-500 text-sm mt-1">Kurir akan mengantarkan barang ke alamat Anda</p>
                                    </div>
                                </label>
                            </div>

                            <!-- Address Section (Hidden by default) -->
                            <div class="mb-8" id="alamat_section" style="display: none;">
                                <h4 class="mb-4 text-gray-900 text-xl font-semibold">Alamat Pengantaran</h4>
                                <div class="mb-3">
                                    <select name="alamat" id="alamat"
                                        class="block w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-gray-900 bg-white">
                                        <option value="">Pilih Alamat</option>
                                        @foreach ($alamat as $item)
                                            <option value="{{ $item->id }}">{{ $item->alamat }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <!-- Order Summary Section -->
                            <div class="mb-8">
                                <h4 class="mb-4 text-gray-900 text-xl font-semibold">Detail Pemesanan</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full border border-gray-200 rounded-lg">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th
                                                    class="text-left py-3 px-4 font-semibold text-gray-700 border-b border-gray-200">
                                                    Nama Pembeli</th>
                                                <th
                                                    class="text-left py-3 px-4 font-semibold text-gray-700 border-b border-gray-200">
                                                    Barang</th>
                                                <th
                                                    class="text-left py-3 px-4 font-semibold text-gray-700 border-b border-gray-200">
                                                    Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalSubtotal = 0;
                                            @endphp
                                            @foreach ($keranjang as $item)
                                                @php
                                                    $lineTotal = $item->harga_barang * $item->jumlah;
                                                    $totalSubtotal += $lineTotal;
                                                @endphp
                                                <tr class="border-b border-gray-200">
                                                    <td class="py-3 px-4">{{ $pembeli->nama_pembeli }}</td>
                                                    <td class="py-3 px-4">{{ $item->nama_barang }}</td>
                                                    <td class="py-3 px-4 font-semibold">
                                                        Rp {{ number_format($lineTotal, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                            <!-- Payment Summary -->
                            <div class="bg-gray-100 rounded-lg p-4 mb-8">
                                <div class="flex justify-between mb-2 text-gray-600">
                                    <span>Subtotal</span>
                                    <span id="subtotal-amount">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between mb-2 text-gray-600">
                                    <span>Ongkir</span>
                                    <span id="delivery-fee">Rp 0</span>
                                </div>
                                <hr class="border-gray-300 my-3" />
                                <div class="flex justify-between font-bold text-gray-900">
                                    <span>Total</span>
                                    <span id="total-amount">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Hidden inputs untuk kirim data ke backend -->
                            @foreach ($keranjang as $item)
                                <input type="hidden" name="items[]" value="{{ $item->id }}">
                            @endforeach

                            <!-- Checkout Button -->
                            <div>
                                <button type="submit"
                                    class="w-full bg-blue-600 text-white py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition">
                                    <i class="fas fa-shopping-bag mr-2"></i> Complete Purchase
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAlamat() {
            const deliveryMethod = document.querySelector('input[name="metode_pengantaran"]:checked').value;
            const alamatSection = document.getElementById("alamat_section");
            
            if (deliveryMethod === "diantar_kurir") {
                alamatSection.style.display = "block";
            } else {
                alamatSection.style.display = "none";
            }

            calculateTotal();
        }

        function calculateTotal() {
            // Subtotal dari server (total semua item)
            let subtotalText = '{{ $totalSubtotal }}';
            let subtotal = parseInt(subtotalText);

            const deliveryMethod = document.querySelector('input[name="metode_pengantaran"]:checked').value;
            let deliveryCost = 0;
            
            if (deliveryMethod === "diantar_kurir") {
                // Apply shipping cost rules
                deliveryCost = subtotal >= 1500000 ? 0 : 100000;
            }

            const total = subtotal + deliveryCost;

            // Update subtotal & total display
            document.getElementById('subtotal-amount').textContent = "Rp " + subtotal.toLocaleString('id-ID');
            document.getElementById('total-amount').textContent = "Rp " + total.toLocaleString('id-ID');
            document.getElementById('delivery-fee').textContent = "Rp " + deliveryCost.toLocaleString('id-ID');
        }

        // Jalankan saat halaman dimuat agar total sesuai pilihan default
        document.addEventListener('DOMContentLoaded', () => {
            toggleAlamat();
        });
    </script>
@endsection