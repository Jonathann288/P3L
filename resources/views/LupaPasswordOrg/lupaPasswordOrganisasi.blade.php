<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password Organisasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Efek blur untuk background */
        .background-blur {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            /* Memberikan layer semi-transparan */
            backdrop-filter: blur(10px);
            /* Efek blur */
            z-index: -1;
            /* Menempatkan di belakang elemen lainnya */
        }

        .form-section {
            background-color: #ffffff;
            padding: 3rem;
        }

        .info-section {
            background-color: #0d6efd;
            color: #ffffff;
            padding: 3rem;
        }

        .info-section h2 {
            font-weight: bold;
        }

        .info-section .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .icon-row i {
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }

        .card-custom {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 90vh;">
    <!-- Tambahkan elemen dengan kelas background-blur -->
    <div class="background-blur"></div>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row card-custom">
                    <!-- Left Side (Form) -->
                    <div class="col-md-6 form-section">
                        <div class="text-center mb-4">
                            <h4 class="mt-3">Reset Password Organisasi</h4>
                            <p class="text-muted">Masukkan email Anda untuk menerima link reset kata sandi.</p>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('LupaPasswordOrg.lupaPasswordOrganisasi.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email_organisasi" class="form-label">Email</label>
                                <input type="email" id="email_organisasi" name="email_organisasi"
                                    class="form-control @error('email_organisasi') is-invalid @enderror"
                                    value="{{ old('email_organisasi') }}" required autofocus>
                                @error('email_organisasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Kirim Reset Link</button>
                        </form>
                    </div>

                    <!-- Right Side (Info) -->
                    <div class="col-md-6 info-section d-flex flex-column justify-content-center align-items-start">
                        <div class="icon text-white mb-3">
                            <img src="{{ asset('images/logo6.png') }}" alt="ReUseMart Logo" height="60">
                        </div>

                        <h2>Lupa Password?</h2>
                        <p class="mt-2">
                            Jangan khawatir! Masukkan email terdaftar Anda dan kami akan mengirimkan tautan untuk
                            mengatur ulang kata sandi Anda.
                        </p>
                        <div class="icon-row mt-4 d-flex gap-4">
                            <span><i class="bi bi-shield-lock"></i> Aman</span>
                            <span><i class="bi bi-envelope-paper"></i> Cepat</span>
                            <span><i class="bi bi-person-check"></i> Mudah</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>