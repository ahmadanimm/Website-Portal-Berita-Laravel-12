@extends('layouts.main')

@section('hero')
<section x-data="{
    active: 0,
    totalSlides: {{ $latestPerCategory->filter(fn($category) => $category->articles->isNotEmpty())->count() }},
    nextSlide() {
        this.active = (this.active + 1) % this.totalSlides;
    },
    prevSlide() {
        this.active = (this.active - 1 + this.totalSlides) % this.totalSlides;
    }
}" class="relative h-[400px] w-full mb-12 overflow-hidden">

    <div class="absolute bottom-6 inset-x-0 z-10 mr-4"> 
      <div class="relative max-w-7xl mx-auto px-4 flex justify-end gap-3">
        <button
          @click.stop="prevSlide()"
          class="bg-white bg-opacity-30 rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-50 transition"
        >
          <i class="bi bi-chevron-left text-white text-xl"></i>
        </button>
        <button
          @click.stop="nextSlide()"
          class="bg-white bg-opacity-30 rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-50 transition"
        >
          <i class="bi bi-chevron-right text-white text-xl"></i>
        </button>
      </div>
    </div>

    {{-- Slide section --}}
    <div
      class="absolute inset-0 flex transition-transform duration-700 ease-in-out"
      :style="`transform: translateX(-${active * 100}%)`"
    >
      @foreach ($latestPerCategory as $index => $category)
        @php $article = $category->articles->sortByDesc('created_at')->first(); @endphp
        @if ($article)
          <div
            class="flex-shrink-0 w-full h-full relative z-20" 
            style="background-image: url('{{ asset('storage/' . $article->thumbnail) }}'); background-size: cover; background-position: center;"
          >
            <a href="{{ route('article.show', $article->slug) }}" class="absolute inset-0 z-30"></a>

            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent z-10"></div>

            <div class="relative max-w-7xl mx-auto px-8 py-12 text-white flex flex-col justify-end h-full z-20">
              <span class="inline-block bg-blue-700 px-3 py-1 rounded text-xs font-semibold mb-3 max-w-max">
                {{ $category->name }}
              </span>

              <time class="text-sm mb-3 block">
                {{ $article->created_at->format('d/m/Y, H:i') }} WIB
              </time>

              <span class="block bg-blue-600 w-[20px] h-[3px] mb-4 rounded"></span>

              <h1 class="text-2xl font-bold leading-tight max-w-xl">
                {{ $article->title }}
              </h1>
            </div>
          </div>
        @endif
      @endforeach
    </div>

</section>
@endsection

@section('content')
@php
  $isGuest = !auth()->check();
  $isSubscribed = auth()->check() && auth()->user()->is_subscribed;
@endphp

<section class="flex flex-col md:flex-row md:justify-between gap-8 max-w-7xl mx-auto mb-16 px-4">

  <div class="md:w-1/2">
    <h3 class="text-black font-bold leading-snug mb-8 text-xl">BERITA TERKINI</h3>

    <div class="flex flex-col gap-4">
      @foreach ($latestArticles as $article)
        @php
          $isLocked = $article->is_premium && ($isGuest || !$isSubscribed);
        @endphp

        @if ($isLocked)
          <div onclick="showPremiumModal()" class="cursor-pointer flex items-center gap-4 border border-gray-200 rounded-lg shadow-sm px-3 py-3 min-h-[140px] hover:-translate-y-1 hover:shadow-md transition">
            <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}" class="w-40 h-28 object-cover rounded-md flex-shrink-0">
            <div>
              <span class="inline-block bg-blue-700 text-white px-2 py-0.5 rounded text-xs font-semibold mb-2">
                {{ $article->category->name }}
              </span>
              <span class="inline-block bg-yellow-500 text-white px-2 py-0.5 rounded text-xs font-semibold ml-2">
                Premium
              </span>
              <time class="text-gray-600 text-xs mb-2 block">
                {{ $article->created_at->format('d/m/Y, H:i') }} WIB
              </time>
              <span class="block bg-blue-600 w-[20px] h-[3px] mb-4 rounded"></span>
              <h4 class="font-semibold text-sm">{{ $article->title }}</h4>
            </div>
          </div>
        @else
          <a href="{{ route('article.show', $article->slug) }}" class="flex items-center gap-4 border border-gray-200 rounded-lg shadow-sm px-3 py-3 min-h-[140px] hover:-translate-y-1 hover:shadow-md transition">
            <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}" class="w-40 h-28 object-cover rounded-md flex-shrink-0">
            <div>
              <span class="inline-block bg-blue-700 text-white px-2 py-0.5 rounded text-xs font-semibold mb-2">
                {{ $article->category->name }}
              </span>
              @if ($article->is_premium)
                <span class="inline-block bg-yellow-500 text-white px-2 py-0.5 rounded text-xs font-semibold ml-2">
                  Premium
                </span>
              @endif
              <time class="text-gray-600 text-xs mb-2 block">
                {{ $article->created_at->format('d/m/Y, H:i') }} WIB
              </time>
              <span class="block bg-blue-600 w-[20px] h-[3px] mb-4 rounded"></span>
              <h4 class="font-semibold text-sm">{{ $article->title }}</h4>
            </div>
          </a>
        @endif
      @endforeach
    </div>
  </div>

  <div class="md:w-1/2">
    <h3 class="text-black font-bold leading-snug mb-8 text-xl">TERPOPULER</h3>

    @foreach ($popularArticles as $article)
      @php
        $isLocked = $article->is_premium && ($isGuest || !$isSubscribed);
      @endphp

      @if ($isLocked)
        <div onclick="showPremiumModal()" class="cursor-pointer flex items-center gap-3 mb-3 p-2 border border-gray-200 rounded-md shadow-sm hover:shadow-md transition-transform transform hover:-translate-y-1">
          <img src="{{ asset('storage/' . $article->thumbnail) }}" class="w-28 h-20 object-cover rounded-md flex-shrink-0">
          <div>
            <span class="inline-block bg-blue-700 text-white px-2 py-0.5 rounded text-xs font-semibold mb-2">
              {{ $article->category->name ?? 'Tanpa Kategori' }}
            </span>
            <span class="inline-block bg-yellow-500 text-white px-2 py-0.5 rounded text-xs font-semibold ml-2">
              Premium
            </span>
            <time class="text-gray-600 text-xs mb-2 block">
              {{ $article->created_at->format('d/m/Y, H:i') }} WIB
            </time>
            <span class="block bg-blue-600 w-[20px] h-[2px] mb-2 rounded"></span>
            <h4 class="font-semibold text-sm">{{ $article->title }}</h4>
          </div>
        </div>
      @else
        <a href="{{ route('article.show', $article->slug) }}" class="block flex items-center gap-3 mb-3 p-2 border border-gray-200 rounded-md shadow-sm hover:shadow-md transition-transform transform hover:-translate-y-1">
          <img src="{{ asset('storage/' . $article->thumbnail) }}" class="w-28 h-20 object-cover rounded-md flex-shrink-0">
          <div>
            <span class="inline-block bg-blue-700 text-white px-2 py-0.5 rounded text-xs font-semibold mb-2">
              {{ $article->category->name ?? 'Tanpa Kategori' }}
            </span>
            @if ($article->is_premium)
              <span class="inline-block bg-yellow-500 text-white px-2 py-0.5 rounded text-xs font-semibold ml-2">
                Premium
              </span>
            @endif
            <time class="text-gray-600 text-xs mb-2 block">
              {{ $article->created_at->format('d/m/Y, H:i') }} WIB
            </time>
            <span class="block bg-blue-600 w-[20px] h-[2px] mb-2 rounded"></span>
            <h4 class="font-semibold text-sm">{{ $article->title }}</h4>
          </div>
        </a>
      @endif
    @endforeach
  </div>
</section>

<div id="premiumModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
  <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
    <h2 class="text-lg font-semibold mb-2">Konten Premium</h2>
    <p class="text-sm text-gray-600 mb-4">
      @guest
        Silakan login dan berlangganan untuk mengakses artikel premium.
      @else
        Silakan berlangganan untuk mengakses artikel premium.
      @endguest
    </p>
    <div class="flex justify-center gap-3">
      @guest
        <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Login</a>
      @endguest
      <a href="{{ route('subscription.index') }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Berlangganan</a>
    </div>
    <button onclick="closePremiumModal()" class="text-sm text-gray-500 mt-4 hover:underline">Tutup</button>
  </div>
</div>

<script>
  function showPremiumModal() {
    document.getElementById('premiumModal').classList.remove('hidden');
  }
  function closePremiumModal() {
    document.getElementById('premiumModal').classList.add('hidden');
  }
</script>



<section class="py-16 bg-white max-w-7xl mx-auto px-4">
  <div class="text-center mb-10">
    <div class="inline-block bg-blue-700 text-white px-4 py-1 rounded-full text-sm font-semibold mb-3">
      PENULIS TERBAIK
    </div>
    <h2 class="text-2xl md:text-3xl font-bold leading-snug">
      Jelajahi Semua Berita<br>yang Dibuat oleh Penulis
    </h2>
  </div>

  <div class="flex flex-wrap justify-center gap-6">
    @foreach ($topAuthors as $author)
      <a href="{{ route('author.show', $author->id) }}" class="border border-gray-300 rounded-2xl p-6 w-40 text-center shadow-sm transform transition duration-300 hover:scale-105 hover:shadow-md block">
        <img src="{{ asset('storage/' . $author->avatar) }}" alt="{{ $author->name }}" class="w-16 h-16 rounded-full object-cover mx-auto mb-3">
        <h4 class="text-base font-semibold mb-1">{{ $author->name }}</h4>
        <p class="text-xs text-gray-600">{{ $author->articles_count }} Berita</p>
      </a>
    @endforeach
  </div>
</section>


@php
  $isGuest = !auth()->check();
  $isSubscribed = auth()->check() && auth()->user()->is_subscribed;
@endphp

@foreach ($categoriesWithArticles as $category)
  @if ($category->articles->count())
    <section class="bg-white p-6 rounded-lg mb-12">
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end mb-6 gap-3">
        <div>
          <h2 class="text-3xl font-bold text-gray-900 leading-tight">Terbaru untuk Anda</h2>
          <h2 class="text-3xl font-bold text-gray-900 leading-tight">di Kategori {{ $category->name }}</h2>
        </div>
        <a href="{{ route('category.show', $category->slug) }}" class="text-blue-600 font-semibold text-sm border border-blue-600 rounded-full px-4 py-1 hover:bg-blue-600 hover:text-white transition">Lihat Semua</a>
      </div>

      <div class="flex flex-col lg:flex-row gap-6">
        @php $featured = $category->articles->first();
        $isLocked = $featured->is_premium && ($isGuest || !$isSubscribed);
        @endphp

        @if ($isLocked)
          <div onclick="showPremiumModal()" class="relative block flex-[1.5] min-h-[360px] rounded-lg overflow-hidden shadow-md bg-gray-100 group cursor-pointer">
            <img src="{{ asset('storage/' . $featured->thumbnail) }}" alt="{{ $featured->title }}" class="w-full h-[360px] object-cover group-hover:scale-105 transition duration-300">
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent text-white p-5 pt-16">
              <span class="bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded mb-2 inline-block">Unggulan</span>
              <span class="bg-yellow-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full ml-2 inline-block">Premium</span>
              <h3 class="text-2xl font-bold leading-snug">{{ $featured->title }}</h3>
              <p class="text-sm opacity-80">{{ $featured->created_at->translatedFormat('d F Y') }}</p>
            </div>
          </div>
        @else
          <a href="{{ route('article.show', $featured->slug) }}" class="relative block flex-[1.5] min-h-[360px] rounded-lg overflow-hidden shadow-md bg-gray-100 group">
            <img src="{{ asset('storage/' . $featured->thumbnail) }}" alt="{{ $featured->title }}" class="w-full h-[360px] object-cover group-hover:scale-105 transition duration-300">
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent text-white p-5 pt-16">
              <span class="bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded mb-2 inline-block">Unggulan</span>
              @if ($featured->is_premium)
                <span class="bg-yellow-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full ml-2 inline-block">Premium</span>
              @endif
              <h3 class="text-2xl font-bold leading-snug">{{ $featured->title }}</h3>
              <p class="text-sm opacity-80">{{ $featured->created_at->translatedFormat('d F Y') }}</p>
            </div>
          </a>
        @endif

        <div class="flex-1 flex flex-col gap-5">
          @foreach ($category->articles->skip(1) as $article)
            @php
              $isLocked = $article->is_premium && ($isGuest || !$isSubscribed);
            @endphp

            @if ($isLocked)
              <div onclick="showPremiumModal()" class="cursor-pointer flex items-center gap-4 border border-gray-200 rounded-lg shadow-sm p-3 hover:-translate-y-1 hover:shadow-md transition">
                <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}" class="w-28 h-20 object-cover rounded-md flex-shrink-0">
                <div>
                  <span class="bg-yellow-500 text-white text-[10px] font-bold px-2 py-0.5 rounded mb-1 inline-block">Premium</span>
                  <h4 class="text-base font-semibold">{{ $article->title }}</h4>
                  <p class="text-sm text-gray-600">{{ $article->created_at->translatedFormat('d F Y') }}</p>
                </div>
              </div>
            @else
              <a href="{{ route('article.show', $article->slug) }}" class="flex items-center gap-4 border border-gray-200 rounded-lg shadow-sm p-3 hover:-translate-y-1 hover:shadow-md transition">
                <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}" class="w-28 h-20 object-cover rounded-md flex-shrink-0">
                <div>
                  @if ($article->is_premium)
                    <span class="bg-yellow-500 text-white text-[10px] font-bold px-2 py-0.5 rounded mb-1 inline-block">Premium</span>
                  @endif
                  <h4 class="text-base font-semibold">{{ $article->title }}</h4>
                  <p class="text-sm text-gray-600">{{ $article->created_at->translatedFormat('d F Y') }}</p>
                </div>
              </a>
            @endif
          @endforeach
        </div>
      </div>
    </section>
  @endif
@endforeach

<div id="premiumModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
  <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
    <h2 class="text-lg font-semibold mb-2">Konten Premium</h2>
    <p class="text-sm text-gray-600 mb-4">
      @guest
        Silakan login dan berlangganan untuk mengakses artikel premium.
      @else
        Silakan berlangganan untuk mengakses artikel premium.
      @endguest
    </p>
    <div class="flex justify-center gap-3">
      @guest
        <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Login</a>
      @endguest
      <a href="{{ route('subscription.index') }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Berlangganan</a>
    </div>
    <button onclick="closePremiumModal()" class="text-sm text-gray-500 mt-4 hover:underline">Tutup</button>
  </div>
</div>

<script>
  function showPremiumModal() {
    document.getElementById('premiumModal').classList.remove('hidden');
  }
  function closePremiumModal() {
    document.getElementById('premiumModal').classList.add('hidden');
  }
</script>


@endsection
