@extends('layouts.loading')

@section('content')
    <div class="bg-blue-600 shadow-md">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <a href="{{ route('pembeli.Shop-Pembeli') }}" class="flex items-center text-white font-bold text-xl">
                    <img src="{{ asset('images/logo6.png') }}" alt="ReuseMart Logo" class="h-16 w-19 mr-2">
                    <span> | Keranjang Belanja</span>
                </a>
            </div>

            <div class="hidden sm:flex items-center space-x-4">
                <form action="{{ route('pembeli.Shop-Pembeli') }}" method="GET" class="flex">
                    <input type="text" name="search" placeholder="Cari barang..."
                        class="px-3 py-1.5 w-full max-w-xs rounded-l-lg border border-white text-sm focus:outline-none focus:ring-2 focus:ring-white bg-white text-gray-800">
                    <button type="submit"
                        class="px-4 py-1.5 bg-white text-blue-600 border border-white rounded-r-lg font-semibold hover:bg-blue-200 transition whitespace-nowrap">
                        Cari
                    </button>
                </form>

                <a href="{{ route('pembeli.profilPembeli') }}"
                    class="flex items-center space-x-2 text-white hover:text-blue-200 transition">
                    <img src="{{ asset($pembeli->foto_pembeli) }}" alt="profile" class="w-8 h-8 rounded-full object-cover">
                    <span>{{ auth('pembeli')->user()->nama_pembeli ?? 'Nama Pembeli' }}</span>
                </a>

            </div>

        </div>


        <div class="min-h-screen py-10 px-4 bg-gradient-to-b from-blue-50 to-gray-100">

            <div class="max-w-7xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-blue-600 px-6 py-4 flex items-center justify-between">
                    <a href="{{ route('pembeli.Shop-Pembeli') }}"
                        class="flex items-center text-white hover:text-blue-200 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>

                    <div class="w-6"></div>
                </div>


                <div class="p-6">
                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div>
                                <span class="block sm:inline">{{ session('error') }}</span>
                                @if(session('existing_item_id'))
                                    <div class="mt-2">
                                        <a href="#item-{{ session('existing_item_id') }}"
                                            class="text-blue-600 hover:text-blue-800 underline flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat item yang sudah ada
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if(session('success'))
                        <div
                            class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($keranjang->isEmpty())
                        <div class="text-center py-12">
                            <div class="max-w-md mx-auto">
                                <img src="{{ asset('images/boxKosong.png') }}" alt="Empty Cart"
                                    class="w-48 mx-auto mb-6 animate-bounce">
                                <h3 class="text-xl font-semibold text-gray-700 mb-2">Keranjang Belanja Kosong</h3>
                                <p class="text-gray-500 mb-6">Belum ada item di keranjang belanja Anda.</p>
                                <a href="{{ route('pembeli.Shop-Pembeli') }}"
                                    class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 shadow-md hover:shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Mulai Belanja
                                </a>
                            </div>
                        </div>
                    @else
                                    <div class="space-y-6">
                                        <form action="{{ route('keranjang.hapusTerpilih') }}" method="POST" id="formHapusTerpilih">
                                            @csrf
                                            @method('DELETE')

                                            {{-- Container untuk tombol delete, awalnya hidden --}}
                                            <div id="deleteSelectedContainer" class="hidden mb-4">
                                                <button type="submit"
                                                    class="flex items-center px-3 py-1.5 bg-red-100 text-red-600 text-sm rounded-lg hover:bg-red-200 transition duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Hapus Item Yang dipilih
                                                </button>
                                            </div>

                                            @foreach ($keranjang as $item)
                                                <div id="item-{{ $item->id }}"
                                                    class="flex flex-col sm:flex-row items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition duration-200 border border-gray-200">
                                                    <div class="flex items-center space-x-4 w-full sm:w-auto mb-4 sm:mb-0">
                                                        <input type="checkbox" name="selected_items[]" value="{{ $item->id }}"
                                                            class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500">

                                                        <div class="flex-shrink-0">
                                                            <div
                                                                class="h-16 w-16 bg-white rounded-lg flex items-center justify-center shadow-sm overflow-hidden">
                                                                @if($item->barang && $item->barang->foto_barang)
                                                                    @php
                                                                        // Cek apakah path gambar sudah berupa URL lengkap
                                                                        $imagePath = $item->barang->foto_barang;
                                                                        if (!Str::startsWith($imagePath, ['http://', 'https://'])) {
                                                                            $imagePath = asset('storage/' . $imagePath);
                                                                        }
                                                                    @endphp
                                                                    <img src="{{ $imagePath }}" alt="{{ $item->nama_barang }}"
                                                                        class="h-full w-full object-cover">
                                                                @else
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500"
                                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                                                    </svg>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <h4 class="font-semibold text-gray-800">{{ $item->nama_barang }}</h4>
                                                            <div class="flex items-center mt-1">
                                                                <span class="text-gray-600 mr-3">Jumlah:</span>
                                                                <span class="px-2 py-1 bg-white rounded border border-gray-300 text-gray-700">
                                                                    {{ $item->jumlah }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="flex flex-col sm:flex-row items-end sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                                                        <p class="text-lg font-semibold text-blue-700 whitespace-nowrap">
                                                            Rp {{ number_format($item->harga_barang, 0, ',', '.') }}
                                                        </p>
                                                        <form action="{{ route('keranjang.hapus', $item->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="flex items-center px-3 py-1.5 bg-red-100 text-red-600 text-sm rounded-lg hover:bg-red-200 transition duration-200">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </form>

                                        <div class="mt-8 p-6 bg-blue-50 rounded-xl border border-blue-100">
                                            <div class="flex flex-col sm:flex-row justify-between items-center">
                                                <div class="mb-4 sm:mb-0">
                                                    <p class="text-gray-600">Total Items: {{ $keranjang->sum('jumlah') }}</p>
                                                    <h3 class="text-2xl font-bold text-blue-800">
                                                        Rp {{ number_format($keranjang->sum(function ($item) {
                            return $item->harga_barang * $item->jumlah;
                        }), 0, ',', '.') }}
                                                    </h3>
                                                </div>
                                                <div class="flex space-x-3">
                                                    <a href="{{ route('pembeli.Shop-Pembeli') }}"
                                                        class="px-5 py-2.5 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition duration-300">
                                                        Lanjut Belanja
                                                    </a>
                                                    <a href="{{ route('pembeli.checkOutPembeli', $item->id_barang) }}"
                                                        class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-semibold rounded-lg text-sm px-5 py-3 text-center min-h-[44px]">
                                                        Checkout
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                    @endif
                </div>
            </div>
        </div>
@endsection

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
                const deleteContainer = document.getElementById('deleteSelectedContainer');
                const formHapusTerpilih = document.getElementById('formHapusTerpilih');

                function toggleDeleteButton() {
                    // Cek ada tidaknya checkbox yang dicentang
                    const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

                    // Jika ada yang dicentang, tampilkan tombol, kalau tidak sembunyikan
                    if (anyChecked) {
                        deleteContainer.classList.remove('hidden');
                    } else {
                        deleteContainer.classList.add('hidden');
                    }
                }

                // Pasang event listener pada tiap checkbox
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', toggleDeleteButton);
                });

                // Cek sebelum submit form hapus terpilih, pastikan ada item terpilih
                formHapusTerpilih.addEventListener('submit', function (e) {
                    const selectedItems = Array.from(checkboxes).filter(checkbox => checkbox.checked);
                    if (selectedItems.length === 0) {
                        e.preventDefault(); // Mencegah form disubmit jika tidak ada item terpilih
                        alert('Pilih minimal satu item untuk dihapus.');
                        return false;
                    }

                    // Konfirmasi sebelum menghapus
                    return confirm('Apakah Anda yakin ingin menghapus item yang dipilih?');
                });

                // Jalankan cek awal saat halaman dimuat untuk mengatur visibilitas tombol di awal
                toggleDeleteButton();
            });
        </script>
    @endsection