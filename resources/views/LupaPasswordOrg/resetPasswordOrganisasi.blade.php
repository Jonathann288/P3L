<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Organisasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-4 text-center">Reset Password Organisasi</h2>

        <form action="{{ route('organisasi.updatePassword') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id_organisasi" value="{{ Auth::guard('organisasi')->user()->id_organisasi }}">


            <div class="space-y-4">
                <div>
                    <label for="password_organisasi" class="block font-semibold">Password Baru:</label>
                    <input type="password" id="password_organisasi" name="password_organisasi" required
                        class="w-full p-2 border rounded-lg">
                </div>
            </div>

            <div class="flex justify-end mt-6 space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Simpan Password
                </button>
            </div>
        </form>

    </div>
</body>

</html>