<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
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
                <i class="fas fa-user-circle text-4xl text-gray-700 mb-2"></i>
                <h1 class="text-xl font-bold">Register</h1>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="text-sm font-semibold">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-2 bg-gray-200 rounded @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="text-sm font-semibold">Email</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required
                        placeholder="contoh: user@gmail.com"
                        class="w-full px-4 py-2 bg-gray-200 rounded @error('email') border-red-500 @enderror"
                    >
                    @error('email')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ show: false }">
                    <label for="password" class="text-sm font-semibold">Password</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" id="password" name="password" required
                            class="w-full px-4 py-2 bg-gray-200 rounded @error('password') border-red-500 @enderror">
                        <span @click="show = !show" class="absolute right-3 top-2.5 cursor-pointer">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </span>
                    </div>
                    @error('password')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ show: false }">
                    <label for="password_confirmation" class="text-sm font-semibold">Konfirmasi Password</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-4 py-2 bg-gray-200 rounded">
                        <span @click="show = !show" class="absolute right-3 top-2.5 cursor-pointer">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white py-2 rounded font-semibold">
                    Daftar
                </button>

                <p class="text-center text-sm mt-4">
                    Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
                </p>
            </form>

        </div>
    </div>
</body>
</html>
