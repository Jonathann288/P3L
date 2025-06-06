<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang - Dashboard Gudang</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans bg-gray-100 text-gray-800 grid grid-cols-1 md:grid-cols-[250px_1fr] min-h-screen">

    <!-- Sidebar Navigation -->
    <div class="bg-gray-800 text-white p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-xl font-semibold mb-8">Gudang</h2>
            <nav>
                <div class="space-y-4">
                    <a href="{{ route('gudang.DashboardGudang') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-user-circle mr-2"></i>
                        <span>Profile Saya</span>
                    </a>
                    <a href="{{ route('gudang.DashboardTitipanBarang') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-dolly mr-2"></i>
                        <span>Tambah Titip Barang</span>
                    </a>
                    <a href="{{ route('gudang.DaftarBarang') }}"
                        class="flex items-center space-x-4 p-3 bg-gray-700 rounded-lg">
                        <i class="fas fa-boxes mr-2"></i>
                        <span>Daftar Barang</span>
                    </a>
                     <a href="{{ route('gudang.showPerpanjanganPage') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-boxes mr-2"></i>
                        <span>Daftar Barang</span>
                    </a>
                </div>
            </nav>
        </div>
        <!-- Bottom buttons -->
        <div class="space-y-4 mt-auto">
            <button class="w-full py-2 bg-red-600 text-white rounded-lg hover:bg-red-500">
                Keluar
            </button>
        </div>
    </div>

    <!-- PHP Helper Function for Status Classes - MOVED TO TOP -->
    @php
        function getStatusClass($status)
        {
            $statusClasses = [
                'Tersedia' => 'bg-green-100 text-green-800',
                'Terjual' => 'bg-blue-100 text-blue-800',
                'Barang untuk donasi' => 'bg-yellow-100 text-yellow-800',
                'Didonasikan' => 'bg-purple-100 text-purple-800',
                'Barang siap diambil kembali' => 'bg-indigo-100 text-indigo-800',
                'Diambil kembali' => 'bg-gray-300 text-gray-700',
            ];

            return $statusClasses[$status] ?? 'bg-gray-100 text-gray-800';
        }
    @endphp

    <!-- Main Content -->
    <div class="p-8 bg-gray-100">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-semibold text-gray-800">Daftar Barang</h1>
        </div>

        <!-- Search Section -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Cari Barang</h2>
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" id="searchInput"
                        placeholder="Cari berdasarkan kode, nama, kategori, status, atau petugas..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onkeyup="searchBarang()">
                </div>
                <div class="flex gap-2">
                    <button onclick="clearSearch()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-times mr-2"></i>Reset
                    </button>
                    <button onclick="searchBarang()"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                </div>
            </div>
        </div>

        <!-- Daftar Barang -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Semua Barang</h2>
                <div class="text-sm text-gray-600">
                    Total: <span id="totalItems">{{ $barang->count() ?? 0 }}</span> barang |
                    Ditampilkan: <span id="visibleItems">{{ $barang->count() ?? 0 }}</span> barang
                </div>
            </div>

            @if(isset($barang) && $barang->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Kode Barang</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama Barang</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Kategori</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Harga</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Masa Penitipan</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Petugas Penanggung Jawab</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="barangTableBody">
                            @foreach($barang as $item)
                                <tr class="border-b hover:bg-gray-50 barang-row"
                                    data-searchable="{{ strtolower($item->id . ' ' . $item->nama_barang . ' ' . ($item->kategoribarang->nama_kategori ?? '') . ' ' . ($item->status_barang ?? '') . ' ' . ($item->pegawai_penanggungjawab->nama_pegawai ?? '')) }}">
                                    <td class="px-4 py-3">{{ $item->id }}</td>
                                    <td class="px-4 py-3">{{ $item->nama_barang }}</td>
                                    <td class="px-4 py-3">{{ $item->kategoribarang->nama_kategori ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">Rp {{ number_format($item->harga_barang, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $statusClass = getStatusClass($item->status_barang);
                                        @endphp
                                        <span class="px-2 py-1 text-xs rounded-full {{ $statusClass }}">
                                            {{ $item->status_barang ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ $item->masa_penitipan ?? 'N/A' }} hari</td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-900">
                                                {{ $item->pegawai_penanggungjawab->nama_pegawai ?? 'Belum ada penanggung jawab' }}
                                            </div>
                                            <div class="text-gray-500">
                                                {{ $item->pegawai_penanggungjawab->email_pegawai ?? '-' }}
                                            </div>
                                            <div class="text-gray-500">
                                                {{ $item->pegawai_penanggungjawab->nomor_telepon_pegawai ?? '-' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button onclick="openDetailModal({{ json_encode($item) }})"
                                            class="text-blue-600 hover:text-blue-800 mr-3" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button onclick="openEditModal({{ json_encode($item) }})"
                                            class="text-green-600 hover:text-green-800" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- No results message (hidden by default) -->
                <div id="noResultsMessage" class="text-center py-8 hidden">
                    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Tidak ada barang yang ditemukan</p>
                    <p class="text-gray-400 text-sm">Coba gunakan kata kunci yang berbeda</p>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada barang yang terdaftar</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl w-full m-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Detail Barang</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="detailContent" class="space-y-4">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl w-full m-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Edit Barang</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang</label>
                        <input type="text" id="edit_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select id="edit_id_kategori" name="id_kategori"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                            @foreach($kategoris ?? [] as $kategori)
                                @if(is_object($kategori))
                                    <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                    <input type="text" id="edit_nama_barang" name="nama_barang"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Barang</label>
                        <input type="number" id="edit_harga_barang" name="harga_barang" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Berat Barang (kg)</label>
                        <input type="number" id="edit_berat_barang" name="berat_barang" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Barang</label>
                        <select id="edit_status_barang" name="status_barang"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Tersedia">Tersedia</option>
                            <option value="Terjual">Terjual</option>
                            <option value="Barang untuk donasi">Barang untuk donasi</option>
                            <option value="Didonasikan">Didonasikan</option>
                            <option value="Barang siap diambil kembali">Barang siap diambil kembali</option>
                            <option value="Diambil kembali">Diambil kembali</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Masa Penitipan (hari)</label>
                        <input type="number" id="edit_masa_penitipan" name="masa_penitipan"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Barang</label>
                    <textarea id="edit_deskripsi_barang" name="deskripsi_barang" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <!-- Ganti bagian foto di edit modal dengan kode ini -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Barang</label>
                    <input type="file" id="edit_foto_barang" name="foto_barang[]" multiple accept="image/*"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <small class="text-gray-500">Maksimal 5 foto, format: jpeg, png, jpg, gif, webp (max 2MB per
                        file)</small>
                    <!-- Container untuk menampilkan foto yang sudah ada -->
                    <div id="existing-photos-container"></div>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rating Barang</label>
                        <input type="number" id="edit_rating_barang" name="rating_barang" step="0.1" min="0" max="5"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Garansi Barang</label>
                        <input type="date" id="edit_garansi_barang" name="garansi_barang"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
        // Status class mapping for JavaScript
        const statusClasses = {
            'Tersedia': 'bg-green-100 text-green-800',
            'Terjual': 'bg-blue-100 text-blue-800',
            'Barang untuk donasi': 'bg-yellow-100 text-yellow-800',
            'Didonasikan': 'bg-purple-100 text-purple-800',
            'Barang siap diambil kembali': 'bg-indigo-100 text-indigo-800',
            'Diambil kembali': 'bg-gray-300 text-gray-700'
        };

        function getStatusClass(status) {
            return statusClasses[status] || 'bg-gray-100 text-gray-800';
        }

        // Search functionality - IMPROVED VERSION
        function searchBarang() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase().trim();
            const rows = document.querySelectorAll('.barang-row');
            const noResultsMessage = document.getElementById('noResultsMessage');
            const visibleItemsSpan = document.getElementById('visibleItems');
            const tableBody = document.getElementById('barangTableBody');
            let visibleCount = 0;

            console.log('Searching for:', searchInput); // Debug log

            if (searchInput === '') {
                rows.forEach(row => {
                    row.style.display = '';
                    visibleCount++;
                });
                noResultsMessage.classList.add('hidden');
            } else {
                rows.forEach((row, index) => {
                    const searchableText = row.getAttribute('data-searchable');
                    console.log(`Row ${index}:`, searchableText); // Debug log

                    if (searchableText && searchableText.includes(searchInput)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (visibleCount === 0) {
                    noResultsMessage.classList.remove('hidden');
                    tableBody.style.display = 'none';
                } else {
                    noResultsMessage.classList.add('hidden');
                    tableBody.style.display = '';
                }
            }

            visibleItemsSpan.textContent = visibleCount;
            console.log('Visible items:', visibleCount);
        }

        function clearSearch() {
            document.getElementById('searchInput').value = '';
            const rows = document.querySelectorAll('.barang-row');
            const noResultsMessage = document.getElementById('noResultsMessage');
            const visibleItemsSpan = document.getElementById('visibleItems');
            const tableBody = document.getElementById('barangTableBody');

            rows.forEach(row => {
                row.style.display = '';
            });

            noResultsMessage.classList.add('hidden');
            tableBody.style.display = '';

            visibleItemsSpan.textContent = rows.length;
        }

        document.getElementById('searchInput').addEventListener('input', function () {
            searchBarang();
        });

        document.getElementById('searchInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchBarang();
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            console.log('Page loaded, initializing search...');
            const rows = document.querySelectorAll('.barang-row');
            console.log('Total rows found:', rows.length);

            if (rows.length > 0) {
                console.log('Sample searchable data:', rows[0].getAttribute('data-searchable'));
            }
        });

        // Perbaikan untuk fungsi openDetailModal dalam DaftarBarang.blade.php

        function openDetailModal(barang) {
            const modal = document.getElementById('detailModal');
            const content = document.getElementById('detailContent');

            // Function untuk menangani foto barang
            function getFotoBarangHTML(fotoBarang) {
                if (!fotoBarang) {
                    return '<p class="text-gray-500">Tidak ada foto</p>';
                }

                let fotos = [];

                // Handle jika foto_barang adalah string JSON
                if (typeof fotoBarang === 'string') {
                    try {
                        fotos = JSON.parse(fotoBarang);
                    } catch (e) {
                        // Jika bukan JSON, anggap sebagai path tunggal
                        fotos = [fotoBarang];
                    }
                }
                // Handle jika foto_barang sudah array
                else if (Array.isArray(fotoBarang)) {
                    fotos = fotoBarang;
                }
                // Handle jika foto_barang adalah object atau tipe lain
                else {
                    fotos = [fotoBarang];
                }

                // Filter foto yang valid
                fotos = fotos.filter(foto => foto && foto.trim() !== '');

                if (fotos.length === 0) {
                    return '<p class="text-gray-500">Tidak ada foto</p>';
                }

                // Generate HTML untuk foto
                let fotoHTML = '<div class="grid grid-cols-2 gap-2">';
                fotos.forEach((foto, index) => {
                    // Pastikan path foto benar
                    let fotoPath = foto;
                    if (!foto.startsWith('http') && !foto.startsWith('/')) {
                        // Jika path relatif, tambahkan base URL Laravel
                        fotoPath = `{{ asset('') }}${foto}`;
                    }

                    fotoHTML += `
                <div class="relative">
                    <img src="${fotoPath}" 
                         alt="Foto Barang ${index + 1}" 
                         class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity"
                         onclick="openImagePreview('${fotoPath}')"
                         onerror="this.src='{{ asset('images/no-image.png') }}'; this.onerror=null;">
                    <span class="absolute top-1 right-1 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                        ${index + 1}/${fotos.length}
                    </span>
                </div>
            `;
                });
                fotoHTML += '</div>';

                return fotoHTML;
            }

            content.innerHTML = `
        <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Kode Barang</label>
                    <p class="text-gray-900 font-semibold">${barang.id || 'N/A'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Nama Barang</label>
                    <p class="text-gray-900 font-semibold">${barang.nama_barang || 'N/A'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Kategori</label>
                    <p class="text-gray-900">${barang.kategoribarang ? barang.kategoribarang.nama_kategori : 'N/A'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Harga</label>
                    <p class="text-gray-900 font-semibold">Rp ${barang.harga_barang ? new Intl.NumberFormat('id-ID').format(barang.harga_barang) : '0'}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Status</label>
                    <span class="px-2 py-1 text-xs rounded-full ${getStatusClass(barang.status_barang)}">
                        ${barang.status_barang || 'N/A'}
                    </span>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Masa Penitipan</label>
                    <p class="text-gray-900">${barang.masa_penitipan || 'N/A'} hari</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Berat Barang</label>
                    <p class="text-gray-900">${barang.berat_barang || 'N/A'} kg</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Rating</label>
                    <p class="text-gray-900">★ ${barang.rating_barang || '0'}/5</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Garansi</label>
                    <p class="text-gray-900">${barang.garansi_barang ? new Date(barang.garansi_barang).toLocaleDateString('id-ID') : 'N/A'}</p>
                </div>
            </div>
        </div>
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-600">Deskripsi Barang</label>
            <p class="text-gray-900 mt-2 p-3 bg-gray-50 rounded-lg">${barang.deskripsi_barang || 'Tidak ada deskripsi'}</p>
        </div>
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-600 mb-3">Foto Barang</label>
            ${getFotoBarangHTML(barang.foto_barang)}
        </div>
    `;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Function untuk preview gambar full size
        function openImagePreview(imageSrc) {
            // Buat modal preview image
            const previewModal = document.createElement('div');
            previewModal.id = 'imagePreviewModal';
            previewModal.className = 'fixed inset-0 bg-black bg-opacity-90 z-[60] flex items-center justify-center';
            previewModal.innerHTML = `
        <div class="relative max-w-4xl max-h-full p-4">
            <button onclick="closeImagePreview()" class="absolute top-2 right-2 text-white hover:text-gray-300 text-2xl z-10">
                <i class="fas fa-times"></i>
            </button>
            <img src="${imageSrc}" alt="Preview" class="max-w-full max-h-full object-contain rounded-lg">
        </div>
    `;

            document.body.appendChild(previewModal);

            // Close on click outside
            previewModal.addEventListener('click', function (e) {
                if (e.target === previewModal) {
                    closeImagePreview();
                }
            });
        }

        function closeImagePreview() {
            const previewModal = document.getElementById('imagePreviewModal');
            if (previewModal) {
                previewModal.remove();
            }
        }

        // Debug function untuk cek data barang
        function debugBarangData(barang) {
            console.log('Debug Barang Data:');
            console.log('- ID:', barang.id);
            console.log('- Nama:', barang.nama_barang);
            console.log('- Foto Barang:', barang.foto_barang);
            console.log('- Foto Type:', typeof barang.foto_barang);

            if (barang.foto_barang) {
                if (typeof barang.foto_barang === 'string') {
                    try {
                        const parsed = JSON.parse(barang.foto_barang);
                        console.log('- Parsed Foto:', parsed);
                    } catch (e) {
                        console.log('- Foto is not JSON, treating as single path');
                    }
                }
            }
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Edit Modal Functions
        // Edit Modal Functions - PERBAIKAN
        function openEditModal(barang) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');

            // Set form action
            form.action = `/UpdateBarang/${barang.id_barang}`;

            // Populate form fields
            document.getElementById('edit_id').value = barang.id;
            document.getElementById('edit_id_kategori').value = barang.id_kategori;
            document.getElementById('edit_nama_barang').value = barang.nama_barang;
            document.getElementById('edit_harga_barang').value = barang.harga_barang;
            document.getElementById('edit_berat_barang').value = barang.berat_barang || '';
            document.getElementById('edit_status_barang').value = barang.status_barang || 'Tersedia';
            document.getElementById('edit_masa_penitipan').value = barang.masa_penitipan || '';
            document.getElementById('edit_deskripsi_barang').value = barang.deskripsi_barang || '';
            document.getElementById('edit_rating_barang').value = barang.rating_barang || '';
            document.getElementById('edit_garansi_barang').value = barang.garansi_barang ? barang.garansi_barang.split(' ')[0] : '';

            // HAPUS BARIS INI - TIDAK BISA SET VALUE UNTUK INPUT FILE
            // document.getElementById('edit_foto_barang').value = barang.foto_barang || '';

            // TAMBAHKAN: Reset input file dan tampilkan preview foto yang sudah ada
            document.getElementById('edit_foto_barang').value = ''; // Reset file input
            showExistingPhotos(barang.foto_barang); // Tampilkan foto yang sudah ada

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Fungsi untuk menampilkan foto yang sudah ada di edit modal
        function showExistingPhotos(fotoBarang) {
            const container = document.getElementById('existing-photos-container');

            if (!container) {
                // Jika container belum ada, buat container baru
                const photoContainer = document.createElement('div');
                photoContainer.id = 'existing-photos-container';
                photoContainer.className = 'mt-2';

                // Insert setelah input file
                const fileInput = document.getElementById('edit_foto_barang');
                fileInput.parentNode.insertBefore(photoContainer, fileInput.nextSibling);
            }

            let fotos = [];

            if (fotoBarang) {
                if (typeof fotoBarang === 'string') {
                    try {
                        fotos = JSON.parse(fotoBarang);
                    } catch (e) {
                        fotos = [fotoBarang];
                    }
                } else if (Array.isArray(fotoBarang)) {
                    fotos = fotoBarang;
                }
            }

            fotos = fotos.filter(foto => foto && foto.trim() !== '');

            if (fotos.length > 0) {
                document.getElementById('existing-photos-container').innerHTML = `
            <div class="mt-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Saat Ini:</label>
                <div class="grid grid-cols-3 gap-2">
                    ${fotos.map((foto, index) => {
                    let fotoPath = foto.startsWith('http') || foto.startsWith('/') ? foto : `{{ asset('') }}${foto}`;
                    return `
                            <div class="relative group">
                                <img src="${fotoPath}" 
                                     alt="Foto ${index + 1}" 
                                     class="w-full h-20 object-cover rounded border cursor-pointer hover:opacity-80"
                                     onclick="openImagePreview('${fotoPath}')"
                                     onerror="this.src='{{ asset('images/no-image.png') }}'; this.onerror=null;">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all rounded"></div>
                            </div>
                        `;
                }).join('')}
                </div>
                <p class="text-xs text-gray-500 mt-1">Pilih file baru untuk mengganti foto yang sudah ada</p>
            </div>
        `;
            } else {
                document.getElementById('existing-photos-container').innerHTML = `
            <p class="text-sm text-gray-500 mt-2">Belum ada foto</p>
        `;
            }
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeDetailModal();
            }
        });

        document.getElementById('editModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</body>

</html>