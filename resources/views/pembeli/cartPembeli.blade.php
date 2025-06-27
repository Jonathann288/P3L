@extends('layouts.loading')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">

        <div class="bg-blue-600 text-white px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Back Button -->
                <a href="{{ route('pembeli.Shop-Pembeli') }}" class="inline-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z" clip-rule="evenodd" />
                    </svg>
                    Lanjut Belanja
                </a>

                <div>
                    <h1 class="text-3xl font-bold text-white-900">Keranjang Belanja</h1>
                    <p class="text-white-600 mt-1">Selamat berbelanja, <span class="font-semibold text-white-600">{{ $pembeli->nama_pembeli ?? 'Pelanggan' }}</span></p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
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
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414l-1.293-1.293 1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">{{ session('error') }}</h3>
                    </div>
                </div>
            </div>
        @endif

        @if(empty($keranjang))
            <div class="bg-white rounded-xl shadow-sm border border-white-200 overflow-hidden">
                <div class="text-center py-16 px-6">
                    <div class="mx-auto mb-6 h-48 w-48 flex items-center justify-center bg-white-50">
                        <img src="{{ asset('images/boxKosong.png') }}" alt="">
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Keranjang Anda Kosong</h3>
                    <p class="text-gray-500 max-w-md mx-auto mb-6">Belum ada barang yang ditambahkan ke keranjang belanja Anda. Mulai jelajahi produk kami sekarang!</p>
                    <div class="flex justify-center gap-3">
                        <a href="{{ route('pembeli.Shop-Pembeli') }}" class="inline-flex items-center px-6 py-3 text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222 1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z" />
                                <path d="M16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                            Mulai Belanja
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="divide-y divide-gray-200">
                    @foreach($keranjang as $id => $item)
                    <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                        <div class="flex items-start space-x-4">
                            <!-- Product Image -->
                            <div class="flex-shrink-0 w-32 h-32 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                @if(isset($item['gambar']))
                                    <img src="{{ asset('storage/' . $item['gambar']) }}" alt="{{ $item['nama_barang'] }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col h-full">
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start gap-2">
                                            <div class="min-w-0">
                                                <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $item['nama_barang'] }}</h3>
                                                <p class="text-sm text-gray-500 mt-1">Kode: {{ $item['id_barang'] }}</p>
                                                <p class="text-sm text-gray-500 mt-1">Jumlah: {{ $item['jumlah'] }} pcs</p>
                                                <form action="{{ route('keranjang.hapus') }}" method="POST" class="mt-2">
                                                    @csrf
                                                    <input type="hidden" name="id_barang" value="{{ $item['id_barang'] }}">
                                                    <button type="submit" class="inline-flex items-center gap-2 text-sm font-semibold text-red-600 border border-red-600 px-4 py-2 rounded-lg hover:bg-red-600 hover:text-white transition-all duration-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-400">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-3h4a1 1 0 011 1v1H9V5a1 1 0 011-1zm-3 4h10M9 10v6m3-6v6m3-6v6" />
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-bold text-gray-900">Rp {{ number_format($item['harga_barang'], 0, ',', '.') }}</p>
                                                <p class="text-sm text-gray-500">Per unit</p>
                                                <p class="text-lg font-bold text-blue-600 mt-2">Rp {{ number_format($item['harga_barang'] * $item['jumlah'], 0, ',', '.') }}</p>
                                                <p class="text-sm text-gray-500">Subtotal</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Checkout Summary -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <form id="checkout-form" action="{{ url('pembeli/checkout') }}" method="POST">
                        @csrf
                        <div class="flex justify-end">
                            <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                Checkout
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
