<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $title ?? 'Ruang Kabar' }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-black font-sans min-h-screen flex flex-col">

  @include('partials.navbar')

  @yield('hero')

  <main class="flex-1 max-w-7xl mx-auto px-4 py-6 w-full">
    @yield('content')
  </main>

  @include('partials.footer')

</body>
</html>
