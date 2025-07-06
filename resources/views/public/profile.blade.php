@extends('layouts.app')

@section('content')

@include('partials.navbar-profile')

<div class="max-w-7xl ml-12 mr-12 mt-10 mx-auto px-4 py-10">

  @if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
      {{ session('error') }}
    </div>
  @endif
  
  <div class="flex flex-col md:flex-row items-center md:items-start md:gap-6 mb-10">

    <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center text-5xl">
    @if (Auth::user()->profile_photo)
        <img src="{{ asset('storage/profile_photos/' . Auth::user()->profile_photo) }}" alt="Foto Profil" class="w-full h-full object-cover">
    @else
        <i class="bi bi-person-fill text-gray-500"></i>
    @endif
    </div>

    <div class="flex-1 text-center md:text-left mt-4 md:mt-0">
      <h1 class="text-2xl md:text-3xl font-bold flex items-center justify-center md:justify-start gap-2">
        {{ Auth::user()->name }}
        @if (Auth::user()->is_subscribed)
          <i class="bi bi-patch-check-fill text-blue-600 text-lg"></i>
        @endif
      </h1>
      <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>

      <div class="mt-4 flex justify-center md:justify-start gap-3">
        <a href="{{ route('profile.edit') }}" class="bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700">Edit profil</a>
        @if (Auth::user()->is_subscribed)
          <form id="unsubscribe-form" method="POST" action="{{ route('subscription.unsubscribe') }}">
            @csrf
            <button type="button" onclick="showUnsubscribeModal()" class="bg-gray-300 text-gray-600 px-4 py-2 rounded font-semibold hover:bg-gray-400">
              Berhenti Berlangganan
            </button>
          </form>
        @else
          <a href="{{ route('subscription.index') }}" class="bg-blue-100 text-blue-700 px-4 py-2 rounded font-semibold hover:bg-blue-200">
            Berlangganan
          </a>
        @endif
      </div>

      <div id="unsubscribeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center w-96">
          <h2 class="text-gray-800 font-semibold text-lg mb-2">Konfirmasi</h2>
          <p class="text-sm text-gray-600 mb-4">Yakin ingin berhenti berlangganan? Akses premium akan hilang.</p>
          <div class="flex justify-center gap-4">
            <button onclick="submitUnsubscribe()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Ya, Berhenti</button>
            <button onclick="closeUnsubscribeModal()" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
          </div>
        </div>
      </div>

    </div>
  </div>


  <div x-data="{ tab: 'saved' }">
    <div class="flex flex-wrap justify-center md:justify-start gap-4 border-b pb-4 mb-6">
      <button
        @click="tab = 'saved'"
        :class="tab === 'saved' ? 'border-blue-600 text-blue-600 border-2' : 'border text-gray-600 hover:bg-gray-100'"
        class="flex items-center gap-2 px-4 py-2 rounded-full font-semibold text-sm"
      >
        <i class="bi bi-bookmark-fill"></i> Konten yang disimpan
      </button>

      <button
        @click="tab = 'liked'"
        :class="tab === 'liked' ? 'border-blue-600 text-blue-600 border-2' : 'border text-gray-600 hover:bg-gray-100'"
        class="flex items-center gap-2 px-4 py-2 rounded-full font-semibold text-sm"
      >
        <i class="bi bi-hand-thumbs-up-fill"></i> Konten yang disukai
      </button>

      <button
        @click="tab = 'subscriptions'"
        :class="tab === 'subscriptions' ? 'border-blue-600 text-blue-600 border-2' : 'border text-gray-600 hover:bg-gray-100'"
        class="flex items-center gap-2 px-4 py-2 rounded-full font-semibold text-sm"
      >
        <i class="bi bi-receipt"></i> Riwayat Berlangganan
      </button>
    </div>

    <div x-show="tab === 'saved'" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      @forelse ($savedArticles as $article)
        <a href="{{ route('article.show', $article->slug) }}" class="block bg-white rounded-xl shadow-md border p-2 transition-transform duration-300 transform hover:-translate-y-1">
          <div class="bg-white p-2 rounded-lg overflow-hidden">
            <div class="relative">
              <img src="{{ asset('storage/' . $article->thumbnail) }}" 
                   alt="{{ $article->title }}" 
                   class="w-full h-44 object-cover rounded-md">
              <span class="absolute top-2 left-2 bg-white text-black text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                {{ $article->category->name }}
              </span>
            </div>
          </div>
          <div class="p-4 text-left">
            <h3 class="font-semibold text-sm mb-3 leading-snug text-black hover:text-blue-600">
              {{ Str::limit($article->title, 80) }}
            </h3>
            <div class="w-10 h-1 bg-blue-500 mb-2"></div>
            <div class="flex items-center justify-between text-xs text-gray-600">
              <span>{{ $article->created_at->format('d/m/Y, H:i') }} WIB</span>
              @if ($article->is_premium)
                <span class="bg-yellow-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                  Premium
                </span>
              @endif
            </div>
          </div>
        </a>
      @empty
        <p class="text-sm text-gray-500 col-span-full">Tidak ada artikel yang disimpan.</p>
      @endforelse
    </div>

    <div x-show="tab === 'liked'" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      @forelse ($likedArticles as $article)
        <a href="{{ route('article.show', $article->slug) }}" class="block bg-white rounded-xl shadow-md border p-2 transition-transform duration-300 transform hover:-translate-y-1">
          <div class="bg-white p-2 rounded-lg overflow-hidden">
            <div class="relative">
              <img src="{{ asset('storage/' . $article->thumbnail) }}" 
                   alt="{{ $article->title }}" 
                   class="w-full h-44 object-cover rounded-md">
              <span class="absolute top-2 left-2 bg-white text-black text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                {{ $article->category->name }}
              </span>
            </div>
          </div>
          <div class="p-4 text-left">
            <h3 class="font-semibold text-sm mb-3 leading-snug text-black hover:text-blue-600">
              {{ Str::limit($article->title, 80) }}
            </h3>
            <div class="w-10 h-1 bg-blue-500 mb-2"></div>
            <div class="flex items-center justify-between text-xs text-gray-600">
              <span>{{ $article->created_at->format('d/m/Y, H:i') }} WIB</span>
              @if ($article->is_premium)
                <span class="bg-yellow-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                  Premium
                </span>
              @endif
            </div>
          </div>
        </a>
      @empty
        <p class="text-sm text-gray-500 col-span-full">Belum ada artikel yang disukai.</p>
      @endforelse
    </div>

    <div x-show="tab === 'subscriptions'" class="bg-white rounded-lg p-6 shadow-md">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Langganan Saya</h4>
        @forelse ($user->subscriptions as $subscription)
            <div class="border-b border-gray-200 py-4 last:border-b-0">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-medium text-gray-900">{{ $subscription->plan_name }}</span>
                    @if ($subscription->status === 'active')
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Aktif</span>
                    @elseif ($subscription->status === 'cancelled')
                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Dibatalkan</span>
                    @else
                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ ucfirst($subscription->status) }}</span>
                    @endif
                </div>
                <p class="text-sm text-gray-600">Mulai: {{ \Carbon\Carbon::parse($subscription->starts_at)->format('d M Y') }}</p>
                <p class="text-sm text-gray-600">Berakhir: {{ \Carbon\Carbon::parse($subscription->ends_at)->format('d M Y') }}</p>
                @if ($subscription->price) {{-- Asumsi ada kolom price --}}
                    <p class="text-sm text-gray-600">Harga: Rp{{ number_format($subscription->price, 0, ',', '.') }}</p>
                @endif
                {{-- Anda bisa menambahkan detail lain seperti transaction_id, payment_method, dll. --}}
            </div>
        @empty
            <p class="text-sm text-gray-500">Anda belum memiliki riwayat langganan.</p>
        @endforelse
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
  function showUnsubscribeModal() {
    document.getElementById('unsubscribeModal').classList.remove('hidden');
  }

  function closeUnsubscribeModal() {
    document.getElementById('unsubscribeModal').classList.add('hidden');
  }

  function submitUnsubscribe() {
    document.getElementById('unsubscribe-form').submit();
  }
</script>
@endpush

