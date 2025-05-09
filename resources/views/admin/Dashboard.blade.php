<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin - Pegawai</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            display: inline-block;
        }

        .edit-form {
            display: none;
            margin-top: 10px;
        }

        .tambah-pegawai-btn {
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .tambah-pegawai-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Dashboard Admin</h1>

    <h2>Daftar Pegawai</h2>

    <!-- Tombol Tambah Pegawai -->
    <a href="{{ url('/registerPegawai') }}">
        <button class="tambah-pegawai-btn">Tambah Pegawai</button>
    </a>

    <table id="pegawaiTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Tanggal Lahir</th>
                <th>No Telepon</th>
                <th>Email</th>
                <th>Jabatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pegawais as $pegawai)
                <tr id="pegawai-row-{{ $pegawai->id_pegawai }}">
                    <td>{{ $pegawai->id }}</td>
                    <td>{{ $pegawai->nama_pegawai }}</td>
                    <td>{{ $pegawai->tanggal_lahir_pegawai }}</td>
                    <td>{{ $pegawai->nomor_telepon_pegawai }}</td>
                    <td>{{ $pegawai->email_pegawai }}</td>
                    <td>{{ $pegawai->jabatan->nama_jabatan ?? '-' }}</td>
                    <td>
                        <button onclick="showEditForm({{ $pegawai->id_pegawai }})">Edit</button>
                        <form method="POST" action="{{ url('/pegawai/' . $pegawai->id_pegawai) }}" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>

                        <!-- Form Edit -->
                        <div id="edit-form-{{ $pegawai->id_pegawai }}" class="edit-form">
                            <form method="POST" action="{{ url('/pegawai/' . $pegawai->id_pegawai) }}">
                                @csrf
                                @method('PUT')
                                <input type="text" name="nama_pegawai" value="{{ $pegawai->nama_pegawai }}" required>
                                <input type="date" name="tanggal_lahir_pegawai" value="{{ $pegawai->tanggal_lahir_pegawai }}" required>
                                <input type="text" name="nomor_telepon_pegawai" value="{{ $pegawai->nomor_telepon_pegawai }}" required>
                                <select name="id_jabatan" required>
                                    @foreach ($jabatans as $jabatan)
                                        <option value="{{ $jabatan->id_jabatan }}" {{ $pegawai->id_jabatan == $jabatan->id_jabatan ? 'selected' : '' }}>{{ $jabatan->nama_jabatan }}</option>
                                    @endforeach
                                </select>
                                <button type="submit">Simpan</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        function showEditForm(id) {
            var form = document.getElementById('edit-form-' + id);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
