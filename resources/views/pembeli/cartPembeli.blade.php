@extends('layouts.loading') {{-- Ganti dengan layout yang kamu pakai --}}

@section('content')
<div class="min-h-screen py-10 px-4 bg-gray-100">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-4 text-blue-600">Keranjang Belanja</h2>

        @php
            $cart = session('cart', []);
        @endphp

        @if(count($cart) === 0)
            <div class="text-center py-10 text-gray-600">
                <img src="{{ asset('images/boxKosong.png') }}" alt="Empty Cart" class="w-40 mx-auto mb-4">
                <p class="text-lg">Belum ada item di keranjang.</p>
                <a href="{{ route('pembeli.Shop-Pembeli') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Kembali Belanja
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($cart as $item)
                    <div class="flex items-center justify-between border-b pb-4">
                        <div class="flex items-center space-x-4">
                            <img src="{{ $item['foto'] ?? 'images/default.png' }}" alt="{{ $item['nama'] }}" class="w-20 h-20 object-cover rounded">
                            <div>
                                <h4 class="font-semibold">{{ $item['nama'] }}</h4>
                                <p class="text-gray-600">Qty: {{ $item['qty'] }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold text-blue-700">Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 text-right">
                <a href="{{ route('checkout') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Checkout
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
