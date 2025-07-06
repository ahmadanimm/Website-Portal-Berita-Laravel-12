<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-200 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white px-6 py-4 rounded-t-md shadow mb-4">
            <a href="{{ url('/') }}" class="text-sm font-semibold text-black flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
            </a>
        </div>
        <div class="bg-white shadow-md rounded p-8 w-full max-w-md">
            <div class="flex flex-col items-center mb-6">
                <i class="fas fa-envelope-open-text text-4xl text-gray-700 mb-2"></i>
                <h1 class="text-xl font-bold">Lupa Password</h1>
            </div>

            @if (session('status'))
                <div class="mb-4 text-sm text-green-600 text-center font-semibold">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="text-sm font-semibold">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-2 bg-gray-200 rounded">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-blue-700 hover:bg-blue-800 text-white py-2 rounded font-semibold">
                    Kirim Link Reset Password
                </button>

                <p class="text-center text-sm mt-4">
                    Sudah ingat password? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>
