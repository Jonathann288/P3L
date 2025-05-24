<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gudang</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans bg-gray-100 text-gray-800 grid grid-cols-1 md:grid-cols-[250px_1fr] min-h-screen"
    x-data="titipanHandler()">

    <!-- Sidebar Navigation -->
    <div class="bg-gray-800 text-white p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-xl font-semibold mb-8">Gudang</h2>
            <nav>
                <div class="space-y-4">
                    <a href="{{ route('gudang.DashboardGudang') }}"
                        class="flex items-center space-x-4 p-3 bg-gray-700 rounded-lg">
                        <i class="fas fa-user-circle mr-2"></i>
                        <span>Profile Saya</span>
                    </a>
                    <a href="{{ route('gudang.DashboardTitipanBarang') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-dolly mr-2"></i>
                        <span>Tambah Titip Barang</span>
                    </a>
                </div>
            </nav>
        </div>
        <div class="space-y-4 mt-auto">
            <button class="w-full py-2 bg-red-600 text-white rounded-lg hover:bg-red-500">Keluar</button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-8 bg-gray-100">
        <!-- Flash Messages -->
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

        <h1 class="text-3xl font-bold mb-6">Daftar Titipan Barang</h1>
        <div class="flex justify-end mb-4">
            <button @click="showCreateModal = true"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                + Tambah Titipan Barang
            </button>
        </div>

        <!-- Modal Tambah Titipan Barang -->
        <div x-show="showCreateModal" x-transition
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
            style="display: none;">
            <div class="bg-white rounded-lg w-full max-w-xl overflow-y-auto max-h-[90vh] p-6"
                @click.away="showCreateModal = false">

                <h2 class="text-xl font-bold text-gray-800 mb-4">Tambah Titipan Barang</h2>

                <form action="{{ route('gudang.StoreTitipanBarang') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                       
                        <label for="id_penitip" class="block font-semibold mb-2">Pilih Penitip *</label>
                        <select name="id_penitip" id="id_penitip" required class="w-full border p-3 rounded-lg">
                            <option value="">-- Pilih Penitip --</option>
                            @foreach($penitips ?? [] as $penitip)
                                @if(is_object($penitip) && isset($penitip->id_penitip, $penitip->nama_penitip, $penitip->email_penitip))
                                    <option value="{{ $penitip->id_penitip }}">
                                        {{ $penitip->nama_penitip }} ({{ $penitip->email_penitip }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>


                    <div class="mb-4">
                        <label for="tanggal_penitipan" class="block font-semibold mb-2">Tanggal Titip *</label>
                        <input type="date" name="tanggal_penitipan" id="tanggal_penitipan" required
                            class="w-full border p-3 rounded-lg" />
                    </div>

                    <div class="mb-4">
                        <label for="foto_barang" class="block font-semibold mb-2">Foto Barang</label>
                        <input type="file" name="foto_barang[]" multiple accept="image/*"
                            class="w-full border p-3 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                        <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, GIF. Max 2MB per file.</p>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="showCreateModal = false"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded">Batal</button>
                        <button type="submit"
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <form method="GET" action="{{ route('gudang.SearchTitipan') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search_term" value="{{ request('search_term') }}"
                    placeholder="Cari berdasarkan semua field..."
                    class="col-span-1 md:col-span-2 border p-3 rounded-lg w-full" />

                <select name="status" class="border p-3 rounded-lg w-full">
                    <option value="">Status</option>
                    <option value="dalam_penitipan" {{ request('status') == 'dalam_penitipan' ? 'selected' : '' }}>Dalam
                        Penitipan</option>
                    <option value="diambil" {{ request('status') == 'diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                    <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="hampir_berakhir" {{ request('status') == 'hampir_berakhir' ? 'selected' : '' }}>Hampir
                        Berakhir</option>
                </select>

                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Cari</button>
            </form>
        </div>

        <!-- Tampilkan hasil pencarian jika ada -->
        @if(request('search_term'))
            <div class="mb-4 text-gray-600">
                Menampilkan hasil untuk: <strong>{{ request('search_term') }}</strong>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($titipans as $titipan)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="p-4">
                        <h2 class="text-xl font-semibold">
                            {{ $titipan->penitip ? $titipan->penitip->nama_penitip : 'Nama tidak tersedia' }}
                        </h2>
                        <p class="text-sm text-gray-600">
                            Tanggal Titip:
                            {{ $titipan->tanggal_penitipan ? $titipan->tanggal_penitipan->format('d M Y') : '-' }}
                        </p>

                        <!-- Status Badge -->
                        <div class="mt-2">
                            @if($titipan->tanggal_pengambilan_barang)
                                <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                    Sudah Diambil
                                </span>
                            @elseif($titipan->tanggal_batas_pengambilan && $titipan->tanggal_batas_pengambilan < now())
                                <span class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">
                                    Terlambat
                                </span>
                            @else
                                <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">
                                    Dalam Penitipan
                                </span>
                            @endif
                        </div>

                        <!-- Edit Button -->
                        <button @click="openModal({{ $titipan->id_transaksi_penitipan }}, {
                                            nama_penitip: '{{ $titipan->penitip ? $titipan->penitip->nama_penitip : '' }}',
                                            email_penitip: '{{ $titipan->penitip ? $titipan->penitip->email_penitip : '' }}',
                                            tanggal_penitipan: '{{ $titipan->tanggal_penitipan ? $titipan->tanggal_penitipan->format('Y-m-d') : '' }}',
                                            tanggal_akhir_penitipan: '{{ $titipan->tanggal_akhir_penitipan ? $titipan->tanggal_akhir_penitipan->format('Y-m-d') : '' }}',
                                            tanggal_batas_pengambilan: '{{ $titipan->tanggal_batas_pengambilan ? $titipan->tanggal_batas_pengambilan->format('Y-m-d') : '' }}',
                                            tanggal_pengambilan_barang: '{{ $titipan->tanggal_pengambilan_barang ? $titipan->tanggal_pengambilan_barang->format('Y-m-d') : '' }}'
                                        })"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded mt-3 transition-colors">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>

                        <!-- Detail Toggle -->
                        <div x-data="{ open: false }" class="mt-4">
                            <button @click="open = !open"
                                class="text-blue-600 hover:text-blue-800 hover:underline flex items-center">
                                <i class="fas fa-eye mr-1"></i>
                                <span x-text="open ? 'Sembunyikan Detail' : 'Lihat Detail'"></span>
                            </button>

                            <div x-show="open" x-transition.duration.300ms class="mt-4 text-sm space-y-2 border-t pt-4">
                                <div class="grid grid-cols-1 gap-2">
                                    <div>
                                        <strong class="text-gray-700">Email Penitip:</strong>
                                        <span
                                            class="text-gray-600">{{ $titipan->penitip ? $titipan->penitip->email_penitip : '-' }}</span>
                                    </div>

                                    <div>
                                        <strong class="text-gray-700">Tanggal Akhir Penitipan:</strong>
                                        <span
                                            class="text-gray-600">{{ $titipan->tanggal_akhir_penitipan ? $titipan->tanggal_akhir_penitipan->format('d M Y') : '-' }}</span>
                                    </div>

                                    <div>
                                        <strong class="text-gray-700">Tanggal Batas Pengambilan:</strong>
                                        <span
                                            class="text-gray-600">{{ $titipan->tanggal_batas_pengambilan ? $titipan->tanggal_batas_pengambilan->format('d M Y') : '-' }}</span>
                                    </div>

                                    <div>
                                        <strong class="text-gray-700">Tanggal Pengambilan Barang:</strong>
                                        <span
                                            class="text-gray-600">{{ $titipan->tanggal_pengambilan_barang ? $titipan->tanggal_pengambilan_barang->format('d M Y') : 'Belum diambil' }}</span>
                                    </div>
                                </div>


                                <!-- Foto Barang Section -  -->
                                <div class="mt-4">
                                    <strong class="text-gray-700 block mb-2">Foto Barang:</strong>

                                    @php
                                        $fotoBarang = $titipan->foto_barang;

                                        // Pastikan fotoBarang adalah array
                                        if (empty($fotoBarang)) {
                                            $fotoBarang = [];
                                        } elseif (is_string($fotoBarang)) {
                                            $decoded = json_decode($fotoBarang, true);
                                            $fotoBarang = is_array($decoded) ? $decoded : [];
                                        } elseif (!is_array($fotoBarang)) {
                                            $fotoBarang = [];
                                        }

                                        // Filter empty values
                                        $fotoBarang = array_filter($fotoBarang, function ($foto) {
                                            return !empty($foto);
                                        });
                                    @endphp

                                    @if(!empty($fotoBarang) && count($fotoBarang) > 0)
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach($fotoBarang as $index => $foto)
                                                @php
                                                    // Bersihkan path foto dari karakter aneh
                                                    $cleanFoto = trim($foto);
                                                    $fullPath = asset('storage/' . $cleanFoto);
                                                @endphp

                                                <div class="text-center">
                                                    <div class="relative group">
                                                        <img src="{{ $fullPath }}" alt="Foto Barang {{ $index + 1 }}"
                                                            class="w-full h-24 object-cover rounded border hover:shadow-lg transition-shadow cursor-pointer"
                                                            loading="lazy"
                                                            onerror="this.onerror=null; this.classList.add('opacity-50'); this.nextElementSibling.style.display='block'; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0zNSA2NUw1MCA0NUw2NSA2NUgzNVoiIGZpbGw9IiM5Q0EzQUYiLz4KPGNpcmNsZSBjeD0iNDAiIGN5PSIzNSIgcj0iNSIgZmlsbD0iIzlDQTNBRiIvPgo8L3N2Zz4=';"
                                                            onclick="openImageModal('{{ $fullPath }}', '{{ $titipan->penitip ? $titipan->penitip->nama_penitip : 'Foto Barang' }} - {{ $index + 1 }}')">

                                                        <!-- Overlay for hover effect -->
                                                        <div
                                                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded flex items-center justify-center">
                                                            <i
                                                                class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                                        </div>
                                                    </div>

                                                    <!-- Error message (hidden by default) -->
                                                    <div class="text-xs text-red-500 mt-1" style="display: none;">
                                                        Gagal memuat foto
                                                    </div>

                                                    <!-- File info -->
                                                    <div class="text-xs text-gray-400 mt-1 truncate" title="{{ $cleanFoto }}">
                                                        {{ basename($cleanFoto) }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    @else
                                        <div class="text-center py-4">
                                            <div
                                                class="w-24 h-24 mx-auto bg-gray-100 rounded border flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                                            </div>
                                            <p class="text-gray-500 italic mt-2">Tidak ada foto tersedia</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8">
                    <div class="text-gray-400">
                        <i class="fas fa-inbox text-4xl mb-4"></i>
                        <p class="text-lg">Belum ada data titipan barang</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal Edit -->
    <div x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" style="display: none;">

        <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            @click.away="closeModal()">

            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Edit Titipan Barang</h2>
                    <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form id="edit-form" method="POST" :action="getFormAction()" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-1 md:col-span-2">
                            <label class="block font-semibold mb-2 text-gray-700">Nama Penitip *</label>
                            <input type="text" name="nama_penitip" x-model="formData.nama_penitip"
                                class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block font-semibold mb-2 text-gray-700">Email Penitip *</label>
                            <input type="email" name="email_penitip" x-model="formData.email_penitip"
                                class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                        </div>

                        <div>
                            <label class="block font-semibold mb-2 text-gray-700">Tanggal Penitipan *</label>
                            <input type="date" name="tanggal_penitipan" x-model="formData.tanggal_penitipan"
                                class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                        </div>

                        <div>
                            <label class="block font-semibold mb-2 text-gray-700">Tanggal Akhir Penitipan</label>
                            <input type="date" name="tanggal_akhir_penitipan" x-model="formData.tanggal_akhir_penitipan"
                                class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block font-semibold mb-2 text-gray-700">Tanggal Batas Pengambilan</label>
                            <input type="date" name="tanggal_batas_pengambilan"
                                x-model="formData.tanggal_batas_pengambilan"
                                class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block font-semibold mb-2 text-gray-700">Tanggal Pengambilan Barang</label>
                            <input type="date" name="tanggal_pengambilan_barang"
                                x-model="formData.tanggal_pengambilan_barang"
                                class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block font-semibold mb-2 text-gray-700">Upload Foto Barang</label>
                            <input type="file" name="foto_barang[]" multiple accept="image/*"
                                class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                onchange="previewImages(this)">
                            <p class="text-sm text-gray-500 mt-1">Dapat memilih beberapa foto sekaligus. Format: JPG,
                                PNG, GIF. Maksimal 2MB per foto.</p>

                            <!-- Preview container -->
                            <div id="image-preview" class="mt-3 grid grid-cols-2 md:grid-cols-3 gap-2"
                                style="display: none;">
                                <!-- Preview images will be inserted here -->
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                        <button type="button" @click="closeModal()"
                            class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Image Viewer -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-60 flex items-center justify-center p-4"
        style="display: none;">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closeImageModal()"
                class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300 z-10">
                <i class="fas fa-times"></i>
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded">
            <div id="modalCaption" class="text-white text-center mt-4"></div>
        </div>
    </div>

    <!-- Alpine.js Logic -->
    <script>
        function titipanHandler() {
            return {
                showModal: false,
                showCreateModal: false,
                currentId: null,
                formData: {
                    nama_penitip: '',
                    email_penitip: '',
                    tanggal_penitipan: '',
                    tanggal_akhir_penitipan: '',
                    tanggal_batas_pengambilan: '',
                    tanggal_pengambilan_barang: ''
                },
                openModal(id, data) {
                    this.currentId = id;
                    this.formData = { ...data };
                    this.showModal = true;
                    document.body.style.overflow = 'hidden';
                },
                closeModal() {
                    this.showModal = false;
                    this.currentId = null;
                    this.formData = {
                        nama_penitip: '',
                        email_penitip: '',
                        tanggal_penitipan: '',
                        tanggal_akhir_penitipan: '',
                        tanggal_batas_pengambilan: '',
                        tanggal_pengambilan_barang: ''
                    };
                    document.body.style.overflow = 'auto';
                    // Clear image preview
                    const preview = document.getElementById('image-preview');
                    preview.innerHTML = '';
                    preview.style.display = 'none';
                },
                getFormAction() {
                    return `/UpdateTitipanBarang/${this.currentId}`;
                }
            }
        }

        // Image preview function
        function previewImages(input) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '';

            if (input.files && input.files.length > 0) {
                preview.style.display = 'grid';

                Array.from(input.files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const div = document.createElement('div');
                            div.className = 'relative';
                            div.innerHTML = `
                                    <img src="${e.target.result}" alt="Preview ${index + 1}" 
                                        class="w-full h-20 object-cover rounded border">
                                    <div class="text-xs text-gray-500 mt-1 truncate">${file.name}</div>
                                    <div class="text-xs text-gray-400">${(file.size / 1024).toFixed(1)} KB</div>
                                `;
                            preview.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                preview.style.display = 'none';
            }
        }

        // Image modal functions
        function openImageModal(src, caption) {
            document.getElementById('modalImage').src = src;
            document.getElementById('modalCaption').textContent = caption;
            document.getElementById('imageModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal on escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });

        
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>