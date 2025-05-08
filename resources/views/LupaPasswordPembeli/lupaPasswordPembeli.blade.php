<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password Pembeli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Lupa Password Pembeli</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('LupaPasswordPembeli.lupaPasswordPembeli.post') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Alamat Email</label>
                                <input type="email" id="email_pembeli" name="email_pembeli"
                                    class="form-control @error('email_pembeli') is-invalid @enderror" required
                                    autofocus value="{{ old('email_pembeli') }}">
                                @error('email_pembeli')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Kirim Link Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>