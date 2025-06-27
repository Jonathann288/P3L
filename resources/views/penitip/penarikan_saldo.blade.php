<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penarikan Saldo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" sizes="128x128" href="{{ asset('images/logo2.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
</head>

<body class="font-sans bg-gray-100 text-gray-800 grid grid-cols-1 md:grid-cols-[250px_1fr] min-h-screen">

    <div class="bg-gray-800 text-white p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-xl font-semibold mb-8">MyAccount</h2>
            <nav>
                <div class="space-y-4">
                    <a href="{{ route('penitip.profilPenitip') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg cursor-pointer">
                        <span>{{ $penitip->nama_penitip }}</span> </a>
                    <a href="{{ route('penitip.barang-titipan') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <span>Titipan</span> </a>
                    <a href="{{ route('penitip.penarikan.form') }}"
                        class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                        <span>Penarikan Saldo</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <div class="p-8 bg-gray-100">
        <h1 class="text-2xl font-bold mb-6">Pengajuan Penarikan Saldo</h1>

        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Saldo</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="font-semibold text-gray-600 block">ID Penitip:</span>
                    <span class="block">{{ $penitip->id }}</span>
                </div>
                <div>
                    <span class="font-semibold text-gray-600 block">Nama Penitip:</span>
                    <span class="block">{{ $penitip->nama_penitip }}</span>
                </div>
                <div>
                    <span class="font-semibold text-gray-600 block">Saldo Saat Ini:</span>
                    <span class="block text-xl font-bold text-green-600">Rp
                        {{ number_format($penitip->saldo_penitip, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <form id="penarikanForm" method="POST" action="{{ route('penitip.penarikan.proses') }}">
                @csrf
                <div class="mb-4">
                    <label for="nominal_penarikan" class="block text-gray-700 font-semibold mb-2">Nominal Penarikan
                        (Rp)</label>
                    <input type="number" id="nominal_penarikan" name="nominal_penarikan"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md" placeholder="masukin angka nominal mu "
                        required>
                </div>

                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Tarik Saldo
                </button>
            </form>
        </div>
    </div>

    <div id="toast"
        class="fixed bottom-5 right-5 bg-green-600 text-white px-5 py-3 rounded shadow-lg opacity-0 pointer-events-none transition-opacity duration-500">
        <span id="toast-message"></span>
    </div>

    <script>
       
        document.getElementById('penarikanForm').addEventListener('submit', function (e) {
            e.preventDefault(); 

            const nominalTarik = parseFloat(document.getElementById('nominal_penarikan').value);
            const saldoSaatIni = parseFloat('{{ $penitip->saldo_penitip }}');

            if (isNaN(nominalTarik) || nominalTarik <= 0) {
                alert('Mohon masukkan nominal penarikan yang valid.');
                return;
            }

            if (nominalTarik > saldoSaatIni) {
                alert('Saldo Anda tidak mencukupi untuk melakukan penarikan ini.');
                return;
            }

            const biayaPenarikan = nominalTarik * 0.05;
            const sisaSaldo = saldoSaatIni - nominalTarik - biayaPenarikan;

            const formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' });

            const pesanKonfirmasi = `Apakah Anda yakin akan menarik saldo sebesar ${formatter.format(nominalTarik)}?\n\n` +
                `Biaya penarikan (5%): ${formatter.format(biayaPenarikan)}\n` +
                `Sisa saldo Anda setelah penarikan adalah: ${formatter.format(sisaSaldo)}`;

           
            if (confirm(pesanKonfirmasi)) {
                this.submit();
            }
        });

       
        document.addEventListener('DOMContentLoaded', () => {
            const toast = document.getElementById('toast');
            const message = document.getElementById('toast-message');

            @if(session('success'))
                message.textContent = "{{ session('success') }}";
                toast.classList.remove('opacity-0');
                toast.classList.add('opacity-100'); 
                setTimeout(() => {
                    toast.classList.add('opacity-0');
                }, 3000);
            @endif

            @if(session('error'))
                message.textContent = "{{ session('error') }}";
                toast.style.backgroundColor = '#DC2626'; 
                toast.classList.remove('opacity-0');
                toast.classList.add('opacity-100'); 
                setTimeout(() => {
                    toast.classList.add('opacity-0');
                }, 3000);
            @endif
        });
    </script>
</body>

</html>