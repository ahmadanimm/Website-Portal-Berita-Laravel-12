<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200">

    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md">

            <div class="bg-white px-6 py-4 rounded-t-md shadow mb-4">
                <a href="{{ url('/') }}" class="text-sm font-semibold text-black flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
                </a>
            </div>

            <div class="bg-white border px-8 py-10 rounded-b-md shadow">

                <div class="text-center mb-6">
                    <i class="fas fa-user-circle text-4xl text-black"></i>
                    <h2 class="text-xl font-bold mt-2">Login</h2>
                </div>

                @if (session('status'))
                    <div class="mb-4 text-green-600 font-medium text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" required autofocus
                            value="{{ old('email') }}"
                            placeholder="contoh: user@gmail.com"
                            class="w-full mt-1 px-4 py-2 bg-gray-200 rounded focus:outline-none">

                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative mt-1">
                            <input type="password" name="password" id="password" required
                                   class="w-full px-4 py-2 bg-gray-200 rounded focus:outline-none pr-10">
                            <span class="absolute inset-y-0 right-3 flex items-center text-gray-600 cursor-pointer" onclick="togglePassword()">
                                <i id="toggle-password-icon" class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="remember" id="remember"
                               class="text-blue-600 border-gray-300 rounded">
                        <label for="remember" class="text-sm text-gray-700">Remember me</label>
                    </div>

                    <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white py-2 rounded font-semibold">
                        Login
                    </button>
                </form>

                <div class="mt-6 text-center text-sm">
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Daftar?</a>
                    <span class="text-gray-500"> atau </span>
                    <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">lupa password?</a>
                </div>

            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('toggle-password-icon');
            const isHidden = passwordInput.type === 'password';

            passwordInput.type = isHidden ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    </script>
</body>
</html>
