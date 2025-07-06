@extends('layouts.main')

@section('hero')
<h2 class="text-3xl font-bold mt-10 mb-10 text-center">
    Jelajahi Berita yang Dibuat <br>
    <span class="text-black">Oleh {{ $author->name }}</span>
</h2>
@endsection

@section('content')
<section class="py-10 pt-1 bg-white text-center">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-4 md:px-10">
        @foreach ($articles as $article)
            @php
                $isLocked = $article->is_premium && (!auth()->check() || !auth()->user()->is_subscribed);
            @endphp

            @if ($isLocked)
                <div onclick="showPremiumModal()" class="cursor-pointer bg-white rounded-xl shadow-md border p-2 transition-transform duration-300 transform hover:-translate-y-1">
                    <div class="bg-white p-2 rounded-lg overflow-hidden">
                        <div class="relative">
                            <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}" class="w-full h-44 object-cover rounded-md">
                            <span class="absolute top-2 left-2 bg-white text-black text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                                {{ $article->category->name ?? 'Tanpa Kategori' }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4 text-left">
                        <h3 class="font-semibold text-sm mb-3 leading-snug text-black">
                            {{ Str::limit($article->title, 80) }}
                        </h3>
                        <div class="w-10 h-1 bg-blue-500 mb-2"></div>
                        <div class="flex items-center justify-between text-xs text-gray-600">
                            <span>{{ $article->created_at->format('d/m/Y, H:i') }} WIB</span>
                            <span class="bg-yellow-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                Premium
                            </span>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('article.show', $article->slug) }}" class="block bg-white rounded-xl shadow-md border p-2 transition-transform duration-300 transform hover:-translate-y-1">
                    <div class="bg-white p-2 rounded-lg overflow-hidden">
                        <div class="relative">
                            <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}" class="w-full h-44 object-cover rounded-md">
                            <span class="absolute top-2 left-2 bg-white text-black text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                                {{ $article->category->name ?? 'Tanpa Kategori' }}
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
            @endif
        @endforeach
    </div>

    {{-- <div class="mt-6">
        {{ $articles->links() }}
    </div> --}}
</section>

<div id="premiumModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
        <h2 class="text-lg font-semibold mb-2">Konten Premium</h2>
        <p class="text-sm text-gray-600 mb-4">Silakan login dan berlangganan untuk mengakses artikel premium.</p>
        <div class="flex justify-center gap-3">
            <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Login</a>
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