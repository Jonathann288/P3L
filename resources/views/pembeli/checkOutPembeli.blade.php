@extends('layouts.loading')

@section('content')
    <div class="container mx-auto py-12 px-4">
        <div class="flex justify-center">
            <div class="w-full max-w-6xl">
                <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                    <!-- Header dengan Breadcrumb -->
                    <div class="bg-blue-600 text-white px-6 py-4">
                        <div class="flex items-center justify-between">
                            <!-- Back Button -->
                            <a href="{{ route('pembeli.cart') }}"
                                class="flex items-center hover:text-blue-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Kembali
                            </a>
                            <h2 class="text-2xl font-semibold flex items-center gap-2">
                                <img src="{{ asset('images/logo6.png') }}" alt="Reusemart Logo" class="h-14 w-17">
                                <span>| Checkout</span>
                            </h2>
                        </div>
                    </div>

                    <div class="p-6">
                        <form action="{{ route('pembeli.checkout.proses') }}" method="POST">
                            @csrf

                            <!-- Alert Messages -->
                            @if(session('success'))
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-green-800">{{ session('success') }}</h3>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414l-1.293-1.293 1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800">{{ session('error') }}</h3>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Delivery Method Section - Card Style -->
                            <div class="mb-8">
                                <h4 class="mb-4 text-gray-900 text-xl font-semibold">Metode Pengiriman</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Ambil Sendiri Card -->
                                    <label for="delivery-pickup" class="cursor-pointer">
                                        <input type="radio" id="delivery-pickup" name="metode_pengantaran" value="ambil_sendiri" checked
                                            class="hidden" onchange="toggleAlamat(); calculateTotal()">
                                        <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-blue-500 transition-all duration-200
                                            flex items-start" id="pickup-card">
                                            <div class="flex-shrink-0 mt-1">
                                                <div class="h-5 w-5 rounded-full border-2 border-gray-300 flex items-center justify-center mr-3">
                                                    <div class="h-3 w-3 rounded-full bg-blue-600 hidden" id="pickup-check"></div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                    <h3 class="text-lg font-semibold text-gray-900">Ambil Sendiri</h3>
                                                </div>
                                                <p class="text-gray-500 mt-2 text-sm">Ambil barang langsung di toko kami</p>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <!-- Antar Kurir Card -->
                                    <label for="delivery-courier" class="cursor-pointer">
                                        <input type="radio" id="delivery-courier" name="metode_pengantaran" value="diantar_kurir"
                                            class="hidden" onchange="toggleAlamat(); calculateTotal()">
                                        <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-blue-500 transition-all duration-200
                                            flex items-start" id="courier-card">
                                            <div class="flex-shrink-0 mt-1">
                                                <div class="h-5 w-5 rounded-full border-2 border-gray-300 flex items-center justify-center mr-3">
                                                    <div class="h-3 w-3 rounded-full bg-blue-600 hidden" id="courier-check"></div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                                    </svg>
                                                    <h3 class="text-lg font-semibold text-gray-900">Antar Kurir</h3>
                                                </div>
                                                <p class="text-gray-500 mt-2 text-sm">Barang akan dikirim ke alamat Anda</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Address Section (shown when courier delivery is selected) -->
                            <div id="address-section" class="mt-6 pt-4 border-t border-gray-200" style="display: none;">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-md font-medium text-gray-900">Alamat Pengiriman</h3>
                                </div>
                                
                                <div class="address-selection">
                                    @if($pembeli->alamat && count($pembeli->alamat) > 0)
                                        @foreach($pembeli->alamat as $alamat)
                                            <!-- Address Card -->
                                            <div class="address-card border rounded-lg p-4 mb-4 hover:border-blue-500 transition-colors duration-200">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div class="flex items-center gap-2">
                                                        
                                                        @if($alamat->status_default)
                                                            <!-- Address Type -->
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-800">
                                                                {{ $alamat->status_default }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Radio Button -->
                                                    <div class="flex items-center">
                                                        <input type="radio" 
                                                               name="selected_address" 
                                                               id="address-{{ $alamat->id }}" 
                                                               value="{{ $alamat->id }}"
                                                               class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                                               {{ $alamat->status_default ? 'checked' : '' }}
                                                               onchange="calculateTotal()">
                                                    </div>
                                                </div>
                                                
                                                <!-- Address Details -->
                                                <div class="address-details space-y-1">
                                                    <h3 class="text-lg font-semibold">{{ $pembeli->nama_pembeli }}</h3>
                                                    <p class="text-gray-600">{{ $pembeli->no_telp }}</p>
                                                    
                                                    <div class="text-gray-700">
                                                        <p><strong>Nama Jalan:</strong> {{ $alamat->nama_jalan }}</p>
                                                        <p><strong>Kelurahan:</strong> {{ $alamat->kelurahan }}</p>
                                                        <p><strong>Kecamatan:</strong> {{ $alamat->kecamatan }}</p>
                                                        <p><strong>Kabupaten:</strong> {{ $alamat->kabupaten }}</p>
                                                        <p><strong>Kode Pos:</strong> {{ $alamat->kode_pos }}</p>
                                                        <p><strong>Deskripsi Alamat:</strong> {{ $alamat->deskripsi_alamat }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- Empty State -->
                                        <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada alamat</h3>
                                            <p class="mt-1 text-sm text-gray-500">Tambahkan alamat pengiriman untuk melanjutkan</p>
                                            <div class="mt-6">
                                                <a href="{{ route('pembeli.AlamatPembeli') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                    </svg>
                                                    Tambah Alamat Baru
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Add New Address Button (shown when addresses exist) -->
                                    @if($pembeli->alamat && count($pembeli->alamat) > 0)
                                        <div class="add-new-address mt-4">
                                            <a href="{{ route('pembeli.AlamatPembeli') }}" class="w-full py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-400 hover:text-blue-600 transition-colors duration-200 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                </svg>
                                                Tambah Alamat Baru
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Points Section -->
                            <div class="mb-8">
                                <h4 class="mb-4 text-gray-900 text-xl font-semibold">Tukar Poin</h4>
                                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-6 border border-blue-200">
                                    <!-- Current Points Display -->
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">Poin Tersedia</h3>
                                                <p class="text-sm text-gray-600">1 Poin = Rp 100</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-blue-600" id="current-points">
                                                {{ number_format($pembeli->total_poin ?? 0, 0, ',', '.') }} Poin
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                ≈ Rp <span id="points-value">{{ number_format(($pembeli->total_poin ?? 0) * 100, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Points Input -->
                                    <div class="space-y-4">
                                        <div>
                                            <label for="poin_digunakan" class="block text-sm font-medium text-gray-700 mb-2">
                                                Jumlah Poin yang Ingin Ditukar
                                            </label>
                                            <div class="flex space-x-3">
                                                <div class="flex-1">
                                                    <input type="number" 
                                                           id="poin_digunakan" 
                                                           name="poin_digunakan"
                                                           min="0" 
                                                           max="{{ $pembeli->total_poin ?? 0 }}"
                                                           value="0"
                                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                           placeholder="Masukkan jumlah poin"
                                                           onchange="calculateTotal()"
                                                           oninput="updatePointsPreview()">
                                                </div>
                                                <button type="button" 
                                                        onclick="useMaxPoints()" 
                                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                                                    Gunakan Semua
                                                </button>
                                            </div>
                                            @error('poin_digunakan')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Points Preview -->
                                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                                <div class="text-center">
                                                    <div class="text-gray-600">Poin Digunakan</div>
                                                    <div class="font-semibold text-blue-600" id="used-points-display">0</div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="text-gray-600">Nilai Tukar</div>
                                                    <div class="font-semibold text-green-600" id="points-discount-display">Rp 0</div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="text-gray-600">Sisa Poin</div>
                                                    <div class="font-semibold text-gray-900" id="remaining-points-display">{{ number_format($pembeli->total_poin ?? 0, 0, ',', '.') }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Bonus Notification (hidden by default) -->
                                        <div id="bonus-notification" class="hidden">
                                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                                <div class="flex">
                                                    <svg class="h-5 w-5 text-purple-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <div>
                                                        <h3 class="text-sm font-medium text-purple-800">Bonus 20%!</h3>
                                                        <p class="text-sm text-purple-700 mt-1">Anda mendapatkan bonus 20% dari nilai tukar poin karena nilai tukar melebihi Rp 500.000</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Points Warning -->
                                        <div id="points-warning" class="hidden">
                                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                                <div class="flex">
                                                    <svg class="h-5 w-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <div>
                                                        <h3 class="text-sm font-medium text-yellow-800">Perhatian!</h3>
                                                        <p class="text-sm text-yellow-700 mt-1" id="points-warning-text"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Summary Section -->
                            <div class="mb-8">
                                <h4 class="mb-4 text-gray-900 text-xl font-semibold">Detail Pemesanan</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full border border-gray-200 rounded-lg">
                                        <thead class="bg-blue-50">
                                            <tr>
                                                <th class="text-left py-3 px-4 font-semibold text-gray-700 border-b border-gray-200">
                                                    Barang</th>
                                                <th class="text-left py-3 px-4 font-semibold text-gray-700 border-b border-gray-200">
                                                    Jumlah</th>
                                                <th class="text-left py-3 px-4 font-semibold text-gray-700 border-b border-gray-200">
                                                    Harga Satuan</th>
                                                <th class="text-left py-3 px-4 font-semibold text-gray-700 border-b border-gray-200">
                                                    Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalSubtotal = 0;
                                            @endphp
                                            @foreach ($keranjang as $id => $item)
                                                @php
                                                    $lineTotal = $item['harga_barang'] * $item['jumlah'];
                                                    $totalSubtotal += $lineTotal;
                                                @endphp
                                                <tr class="border-b border-gray-200">
                                                    <td class="py-3 px-4">{{ $item['nama_barang'] }}</td>
                                                    <td class="py-3 px-4">{{ $item['jumlah'] }}</td>
                                                    <td class="py-3 px-4">Rp {{ number_format($item['harga_barang'], 0, ',', '.') }}</td>
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
                            <div class="bg-white-100 rounded-lg p-6 mb-8">
                                <div class="space-y-4">
                                    <div class="flex justify-between">
                                        <strong class="text-gray-600">Total Item:</strong>
                                        <span class="font-medium">{{ count($keranjang) }} barang</span>
                                    </div>
                                    
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span class="font-medium" id="subtotal-amount">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</span>
                                    </div>

                                    <!-- Points Discount -->
                                    <div class="flex justify-between" id="points-discount-row" style="display: none;">
                                        <span class="text-gray-600">Diskon Poin:</span>
                                        <span class="font-medium text-green-600" id="points-discount">- Rp 0</span>
                                    </div>
                                    
                                    <!-- Subtotal After Points -->
                                    <div class="flex justify-between" id="subtotal-after-points-row" style="display: none;">
                                        <span class="text-gray-600">Subtotal Setelah Diskon:</span>
                                        <span class="font-medium" id="subtotal-after-points">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    <!-- Free Shipping Notification -->
                                    @if($totalSubtotal < 1500000)
                                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                                            <div class="flex items-start">
                                                <svg class="h-5 w-5 text-blue-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-blue-800">Gratis Ongkir!</p>
                                                    <p class="text-xs text-blue-600 mt-1" id="free-shipping-text">
                                                        Belanja Rp {{ number_format(1500000 - $totalSubtotal, 0, ',', '.') }} lagi untuk mendapatkan gratis ongkir
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-green-50 border border-green-100 rounded-lg p-4">
                                            <div class="flex items-start">
                                                <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-green-800">Selamat! Anda mendapatkan gratis ongkir</p>
                                                    <p class="text-xs text-green-600 mt-1">Pembelian minimal Rp 1.500.000</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Shipping Cost -->
                                    <div class="flex justify-between pt-4 border-t border-gray-200">
                                        <span class="text-gray-600">Ongkos Kirim:</span>
                                        <span class="font-medium" id="delivery-fee">
                                            <span class="text-green-600 font-semibold">Gratis</span>
                                        </span>
                                    </div>
                                    
                                    <!-- Grand Total -->
                                    <div class="flex justify-between pt-4 border-t border-gray-200">
                                        <span class="text-lg font-semibold text-gray-900">Total Pembayaran:</span>
                                        <span class="text-xl font-bold text-blue-600" id="total-amount">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden inputs for selected items -->
                            @foreach ($keranjang as $id => $item)
                                <input type="hidden" name="items[{{ $id }}][id]" value="{{ $id }}">
                                <input type="hidden" name="items[{{ $id }}][jumlah]" value="{{ $item['jumlah'] }}">
                            @endforeach

                            <!-- Checkout Button -->
                            <div>
                                <button type="submit"
                                    class="w-full bg-blue-600 text-white py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 inline" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                    Proses Checkout
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Constants for shipping calculation
        const FREE_SHIPPING_THRESHOLD = 1500000;
        const SHIPPING_COST = 100000;
        const POINTS_TO_RUPIAH = 100; // 1 poin = Rp 100
        const BONUS_THRESHOLD = 500000; // Bonus 20% jika nilai tukar > Rp 500,000
        const BONUS_PERCENTAGE = 0.2; // 20% bonus
        const MAX_POINTS = {{ $pembeli->total_poin ?? 0 }};
        const ORIGINAL_SUBTOTAL = {{ $totalSubtotal }};

        function toggleAlamat() {
            const deliveryMethod = document.querySelector('input[name="metode_pengantaran"]:checked').value;
            const addressSection = document.getElementById("address-section");
            const pickupCard = document.getElementById("pickup-card");
            const courierCard = document.getElementById("courier-card");
            const pickupCheck = document.getElementById("pickup-check");
            const courierCheck = document.getElementById("courier-check");

            if (deliveryMethod === "diantar_kurir") {
                addressSection.style.display = "block";
                pickupCard.classList.remove("border-blue-500", "bg-blue-50");
                courierCard.classList.add("border-blue-500", "bg-blue-50");
                pickupCheck.classList.add("hidden");
                courierCheck.classList.remove("hidden");
            } else {
                addressSection.style.display = "none";
                pickupCard.classList.add("border-blue-500", "bg-blue-50");
                courierCard.classList.remove("border-blue-500", "bg-blue-50");
                pickupCheck.classList.remove("hidden");
                courierCheck.classList.add("hidden");
            }
            
            // Recalculate total when delivery method changes
            calculateTotal();
        }

        function calculatePointsDiscount(pointsUsed) {
            if (pointsUsed <= 0) return 0;
            
            // Basic discount calculation (1 point = 100 rupiah)
            let pointsDiscount = pointsUsed * POINTS_TO_RUPIAH;
            
            // Apply bonus if eligible
            if (pointsDiscount > BONUS_THRESHOLD) {
                const bonusAmount = pointsDiscount * BONUS_PERCENTAGE;
                pointsDiscount += bonusAmount;
            }
            
            // Don't allow discount to exceed subtotal
            return Math.min(pointsDiscount, ORIGINAL_SUBTOTAL);
        }

        function updatePointsPreview() {
            const pointsInput = document.getElementById('poin_digunakan');
            let pointsValue = parseInt(pointsInput.value) || 0;
            
            // Validate points input
            if (pointsValue > MAX_POINTS) {
                pointsValue = MAX_POINTS;
                pointsInput.value = pointsValue;
            }
            
            if (pointsValue < 0) {
                pointsValue = 0;
                pointsInput.value = pointsValue;
            }

            // Calculate points discount
            const pointsDiscount = calculatePointsDiscount(pointsValue);
            const basicDiscount = pointsValue * POINTS_TO_RUPIAH;
            const hasBonus = basicDiscount > BONUS_THRESHOLD;
            const remainingPoints = MAX_POINTS - pointsValue;

            // Update displays
            document.getElementById('used-points-display').textContent = pointsValue.toLocaleString('id-ID');
            document.getElementById('points-discount-display').textContent = formatCurrency(pointsDiscount);
            document.getElementById('remaining-points-display').textContent = remainingPoints.toLocaleString('id-ID');

            // Show/hide points discount row in payment summary
            const pointsDiscountRow = document.getElementById('points-discount-row');
            const subtotalAfterPointsRow = document.getElementById('subtotal-after-points-row');
            if (pointsValue > 0) {
                pointsDiscountRow.style.display = 'flex';
                subtotalAfterPointsRow.style.display = 'flex';
                document.getElementById('points-discount').textContent = '- ' + formatCurrency(pointsDiscount);
            } else {
                pointsDiscountRow.style.display = 'none';
                subtotalAfterPointsRow.style.display = 'none';
            }

            // Show/hide bonus notification
            if (hasBonus) {
                document.getElementById('bonus-notification').classList.remove('hidden');
            } else {
                document.getElementById('bonus-notification').classList.add('hidden');
            }

            // Show warning if using too many points
            showPointsWarning(pointsValue, pointsDiscount);
        }

        function useMaxPoints() {
            // Calculate maximum points that can be used (don't exceed subtotal value)
            const maxValuePoints = Math.floor(ORIGINAL_SUBTOTAL / POINTS_TO_RUPIAH);
            const maxUsablePoints = Math.min(MAX_POINTS, maxValuePoints);
            
            document.getElementById('poin_digunakan').value = maxUsablePoints;
            updatePointsPreview();
            calculateTotal();
        }

        function showPointsWarning(pointsValue, pointsDiscount) {
            const warningDiv = document.getElementById('points-warning');
            const warningText = document.getElementById('points-warning-text');

            if (pointsDiscount >= ORIGINAL_SUBTOTAL) {
                warningDiv.classList.remove('hidden');
                warningText.textContent = 'Poin yang digunakan telah mencakup seluruh nilai belanja.';
            } else if (pointsValue > MAX_POINTS * 0.8) {
                warningDiv.classList.remove('hidden');
                warningText.textContent = 'Anda menggunakan sebagian besar poin Anda. Pastikan untuk menyisakan poin untuk transaksi selanjutnya.';
            } else {
                warningDiv.classList.add('hidden');
            }
        }

        function calculateTotal() {
            const deliveryMethod = document.querySelector('input[name="metode_pengantaran"]:checked').value;
            const pointsUsed = parseInt(document.getElementById('poin_digunakan').value) || 0;
            
            // Calculate points discount
            const pointsDiscount = calculatePointsDiscount(pointsUsed);
            
            // Calculate subtotal after points discount
            const subtotalAfterPoints = Math.max(0, ORIGINAL_SUBTOTAL - pointsDiscount);
            
            // Calculate shipping cost based on delivery method and subtotal after points
            let deliveryCost = 0;
            if (deliveryMethod === "diantar_kurir") {
                deliveryCost = subtotalAfterPoints >= FREE_SHIPPING_THRESHOLD ? 0 : SHIPPING_COST;
    }

    // Calculate final total
    const total = subtotalAfterPoints + deliveryCost;

    // Update all displays
    document.getElementById('subtotal-amount').textContent = formatCurrency(ORIGINAL_SUBTOTAL);
    document.getElementById('total-amount').textContent = formatCurrency(total);
    
    // Update shipping display
    const deliveryFeeElement = document.getElementById('delivery-fee');
    if (deliveryCost === 0) {
        deliveryFeeElement.innerHTML = '<span class="text-green-600 font-semibold">Gratis</span>';
    } else {
        deliveryFeeElement.textContent = formatCurrency(deliveryCost);
    }

    // Update free shipping notification
    updateFreeShippingNotification(subtotalAfterPoints);
    
    console.log('Calculation Summary:', {
        originalSubtotal: ORIGINAL_SUBTOTAL,
        pointsUsed: pointsUsed,
        pointsDiscount: pointsDiscount,
        subtotalAfterPoints: subtotalAfterPoints,
        deliveryCost: deliveryCost,
        finalTotal: total
    });
}

function updateFreeShippingNotification(subtotalAfterPoints) {
    const freeShippingText = document.getElementById('free-shipping-text');
    if (freeShippingText && subtotalAfterPoints < FREE_SHIPPING_THRESHOLD) {
        const remaining = FREE_SHIPPING_THRESHOLD - subtotalAfterPoints;
        freeShippingText.textContent = `Belanja Rp ${formatNumber(remaining)} lagi untuk mendapatkan gratis ongkir`;
    }
}

// Function to format currency
function formatCurrency(amount) {
    return 'Rp ' + formatNumber(amount);
}

// Function to format number with thousand separators
function formatNumber(amount) {
    return new Intl.NumberFormat('id-ID').format(Math.round(amount));
}

// Restrict input to numbers only
function restrictToNumbers(input) {
    input.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    input.addEventListener('keydown', function(e) {
        // Allow: backspace, delete, tab, escape, enter
        if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
            // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (e.keyCode === 65 && e.ctrlKey === true) ||
            (e.keyCode === 67 && e.ctrlKey === true) ||
            (e.keyCode === 86 && e.ctrlKey === true) ||
            (e.keyCode === 88 && e.ctrlKey === true) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    // Set initial state for cards
    const pickupCard = document.getElementById("pickup-card");
    const pickupCheck = document.getElementById("pickup-check");
    if (pickupCard && pickupCheck) {
        pickupCard.classList.add("border-blue-500", "bg-blue-50");
        pickupCheck.classList.remove("hidden");
    }
    
    // Initialize functions
    toggleAlamat();
    updatePointsPreview();
    calculateTotal();

    // Add event listeners
    const pointsInput = document.getElementById('points-to-use');
    if (pointsInput) {
        // Restrict input to numbers only
        restrictToNumbers(pointsInput);
        
        // Add input event listener
        pointsInput.addEventListener('input', function() {
            updatePointsPreview();
            calculateTotal();
        });
        
        // Add change event listener
        pointsInput.addEventListener('change', function() {
            updatePointsPreview();
            calculateTotal();
        });
    }
    
    // Add event listeners for delivery method changes
    const deliveryRadios = document.querySelectorAll('input[name="metode_pengantaran"]');
    deliveryRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            toggleAlamat();
        });
    });
    
    // Add event listeners for address selection changes
    const addressRadios = document.querySelectorAll('input[name="selected_address"]');
    addressRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            calculateTotal();
        });
    });
});
    </script>
@endsection