@extends('layouts.app')

@section('content')

<header class="py-4">
  <div class="container mx-auto flex justify-between items-center px-4 max-w-7xl">

    <a href="{{ url('/') }}">
      <img src="{{ asset('assets/images/logo 170x50.png') }}" 
           alt="Logo Ruang Kabar" 
           class="w-[170px] h-[50px] object-contain" />
    </a>

    <a href="/profil"
       class="flex items-center gap-2 text-blue-700 bg-blue-100 hover:bg-blue-200 px-5 py-2 rounded font-semibold transition">
      <i class="bi bi-person-circle text-lg"></i>
      Profil
    </a>
  </div>
</header>

<div class="w-screen border-b-4 border-gray-200 mt-1 ml-[-50vw] left-1/2 relative"></div>


<div class="max-w-7xl ml-12 mx-auto px-4 py-10">
  <h2 class="text-2xl font-bold mb-2 pb-2">Paket Berlangganan</h2>

  <div class="w-10 h-1 mt-1 mb-10 bg-blue-500"></div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

    <div class="bg-white rounded-xl shadow-md p-6 flex flex-col justify-between border border-gray-200">
    <div>
        <h3 class="text-lg font-semibold mb-4">Uji Coba 7 Hari</h3>
        <ul class="text-sm space-y-2 text-gray-700 mb-4">
        <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-blue-500"></i> Akses artikel premium selama 7 hari</li>
        <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-blue-500"></i> Lencana Verifikasi</li>
        <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-blue-500"></i> Newsletter mingguan eksklusif</li>
        </ul>
    </div>
    <div class="mt-6 border-t pt-4">
        <p class="text-gray-700 font-semibold">Gratis / 7 hari</p>

        @if (Auth::user()->trial_used)
        <button class="mt-2 inline-block bg-gray-300 text-gray-500 px-4 py-2 rounded font-semibold w-full cursor-not-allowed" disabled>
            Sudah Digunakan
        </button>
        @else
        <form action="{{ route('subscription.trial') }}" method="POST">
            @csrf
            <button type="submit" class="mt-2 inline-block bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700 w-full text-center">
            Mulai Uji Coba
            </button>
        </form>
        @endif
    </div>
    </div>


    <div class="bg-white rounded-xl shadow-md p-6 flex flex-col justify-between border border-gray-200">
      <div>
        <h3 class="text-lg font-semibold mb-4">1 Bulan</h3>
        <ul class="text-sm space-y-2 text-gray-700 mb-4">
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-blue-500"></i> Akses artikel premium selama 1 Bulan</li>
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-blue-500"></i> Lencana Verifikasi</li>
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-blue-500"></i> Newsletter mingguan ekslusif</li>
        </ul>
      </div>
      <div class="mt-6 border-t pt-4">
        <p class="text-gray-700 font-semibold">Rp 90.000 / bulan</p>

        <form action="{{ route('subscription.subscribe', ['type' => '1month']) }}" method="POST">
            @csrf
            <button type="submit" class="mt-2 inline-block bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700 w-full text-center">
                Berlangganan
            </button>
        </form>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 flex flex-col justify-between border border-gray-200">
      <div>
        <h3 class="text-lg font-semibold mb-4">2 Bulan</h3>
        <ul class="text-sm space-y-2 text-gray-700 mb-4">
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-blue-500"></i> Akses artikel premium selama 2 Bulan</li>
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-blue-500"></i> Lencana Verifikasi</li>
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-blue-500"></i> Newsletter mingguan ekslusif</li>
        </ul>
      </div>
      <div class="mt-6 border-t pt-4">
        <p class="text-gray-700 font-semibold">Rp 170.000 / 2 bulan</p>

        <form action="{{ route('subscription.subscribe', ['type' => '2months']) }}" method="POST">
            @csrf
            <button type="submit" class="mt-2 inline-block bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700 w-full text-center">
                Berlangganan
            </button>
        </form>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 flex flex-col justify-between border border-gray-200">
      <div>
        <h3 class="text-lg font-semibold mb-4">3 Bulan</h3>
        <ul class="text-sm space-y-2 text-gray-700 mb-4">
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-blue-500"></i> Akses artikel premium selama 3 Bulan</li>
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-blue-500"></i> Lencana Verifikasi</li>
          <li class="flex items-start gap-2"><i class="bi bi-check-circle-fill text-blue-500"></i> Newsletter mingguan ekslusif</li>
        </ul>
      </div>
      <div class="mt-6 border-t pt-4">
        <p class="text-gray-700 font-semibold">Rp 250.000 / 3 bulan</p>

        <form action="{{ route('subscription.subscribe', ['type' => '3months']) }}" method="POST">
            @csrf
            <button type="submit" class="mt-2 inline-block bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700 w-full text-center">
                Berlangganan
            </button>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection
