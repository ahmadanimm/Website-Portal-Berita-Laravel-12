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
