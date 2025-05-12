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

        <form action="{{ route('resetPasswordOrganisasi.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="password_organisasi" class="block text-sm font-medium text-gray-700">Password Baru</label>
                <input type="password" name="password_organisasi" id="password_organisasi" class="w-full p-2 border rounded-lg" required>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Reset Password</button>
        </form>
    </div>
</body>
</html>
