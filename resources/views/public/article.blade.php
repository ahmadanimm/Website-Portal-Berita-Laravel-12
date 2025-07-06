{{-- resources/views/public/article.blade.php --}}
@extends('layouts.app')

@section('content')
@include('partials.navbar')

<div class="bg-white pt-6">
  <div class="text-center text-sm text-gray-500">
    {{ $article->created_at->translatedFormat('d F Y') }} · {{ $article->category->name }}
  </div>

  <h1 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mt-2">
    {{ $article->title }}
  </h1>

<div class="flex justify-center items-center mt-3 space-x-3">
  <img src="{{ asset('storage/' . $article->author->avatar) }}" alt="{{ $article->author->name }}" class="w-8 h-8 rounded-full object-cover">
  <div>
    <div class="text-sm text-gray-700 font-medium" style="margin-bottom: 1px;">{{ $article->author->name }}</div>
    <div class="text-xs text-gray-500" style="font-size: 10px;">
      {{ $article->author->articles->count() }} Berita
    </div>
  </div>
  @php
    
    $author = $article->author; 

    if ($author) {
        $authorAverageRating = $author->average_rating ?? 0.0;
        $authorRatingsCount = $author->ratings_count ?? 0;

        $fullStars = floor($authorAverageRating);
        $halfStar = ($authorAverageRating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
    } else {
        
        $authorAverageRating = 0.0;
        $authorRatingsCount = 0;
        $fullStars = 0;
        $halfStar = false;
        $emptyStars = 5;
    }
  @endphp

  <div class="flex items-center text-yellow-500 text-xs ml-6">
    @for ($i = 0; $i < $fullStars; $i++)
        <i class="fas fa-star"></i>
    @endfor

    @if ($halfStar)
        <i class="fas fa-star-half-alt"></i>
    @endif

    @for ($i = 0; $i < $emptyStars; $i++)
        <i class="far fa-star text-gray-400"></i>
    @endfor

    <span class="text-gray-500 ml-1">({{ number_format($authorRatingsCount) }})</span>
  </div>

</div>
</div> <div class="h-[400px] overflow-hidden mt-6" style="width: 100vw; margin-left: calc(50% - 50vw);">
  <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
</div>

@php
    $body = strip_tags($article->body);
@endphp

<div class="container mt-6 mx-auto max-w-7xl">
    <div class="flex flex-col lg:flex-row gap-3">

        
        <main class="w-full lg:w-7/12 overflow-hidden">
            <div class="px-6 pt-6 pb-6">
                <p class="text-gray-700 text-base md:text-lg leading-loose tracking-wider text-justify">
                    {{ strip_tags($article->body) }}
                </p>
            </div>
        </main>

        <aside class="w-full lg:w-2/5">
            <div class="p-6">
                <h3 class="font-semibold mb-6 text-2xl">More From Author</h3>

                @forelse ($moreFromAuthor as $item)
                    @php
                        $isLocked = $item->is_premium && (!auth()->check() || !auth()->user()->is_subscribed);
                    @endphp

                    @if ($isLocked)
                        <div onclick="showPremiumModal()" class="cursor-pointer flex items-center gap-3 mb-5 p-2 bg-gray-50 rounded-md shadow-sm hover:shadow-md transition-transform transform hover:-translate-y-1">
                            <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->title }}" class="w-28 h-20 object-cover rounded-md flex-shrink-0">
                            <div>
                                <span class="inline-block bg-blue-700 text-white px-2 py-0.5 rounded text-xs font-semibold">
                                    {{ $item->category->name ?? 'Uncategorized' }}
                                </span>
                                <span class="bg-yellow-500 text-white text-[10px] font-bold px-2 py-0.5 rounded ml-2 inline-block">
                                    Premium
                                </span>
                                <time class="text-gray-600 text-xs mt-2 mb-2 block">{{ $item->created_at->format('d/m/Y, H:i') }} WIB</time>
                                <span class="block bg-blue-600 w-[20px] h-[3px] mb-1 rounded"></span>
                                <h4 class="font-semibold text-sm">{{ $item->title }}</h4>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('article.show', $item->slug) }}"
                            class="flex items-center gap-3 mb-5 p-2 bg-gray-50 rounded-md shadow-sm hover:shadow-md transition-transform transform hover:-translate-y-1">
                            <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->title }}" class="w-28 h-20 object-cover rounded-md flex-shrink-0">
                            <div>
                                <span class="inline-block bg-blue-700 text-white px-2 py-0.5 rounded text-xs font-semibold">
                                    {{ $item->category->name ?? 'Uncategorized' }}
                                </span>
                                @if ($item->is_premium)
                                    <span class="bg-yellow-500 text-white text-[10px] font-bold px-2 py-0.5 rounded ml-2 inline-block">
                                        Premium
                                    </span>
                                @endif
                                <time class="text-gray-600 text-xs mt-2 mb-2 block">{{ $item->created_at->format('d/m/Y, H:i') }} WIB</time>
                                <span class="block bg-blue-600 w-[20px] h-[3px] mb-1 rounded"></span>
                                <h4 class="font-semibold text-sm">{{ $item->title }}</h4>
                            </div>
                        </a>
                    @endif
                @empty
                    <p class="text-sm text-gray-600">Tidak ada artikel lain dari penulis ini.</p>
                @endforelse
            </div>
        </aside>

        
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

    </div>
</div>

<div class="mt-8 px-6 pb-8">
    <div class="flex items-center justify-start gap-6 bg-blue-700 text-white rounded-lg py-3 px-6 w-fit shadow-md">

        @php
            $isLiked = auth()->check() && $article->likedByUsers->contains(auth()->id());
            $isDisliked = auth()->check() && $article->dislikedByUsers->contains(auth()->id());
        @endphp

        <div class="flex items-center justify-start gap-6 text-white">
            <form id="likeForm" action="{{ route('articles.like', $article->id) }}" method="POST" style="display: none;">
                @csrf
            </form>

            <button
                class="flex items-center gap-2 text-lg transition-colors hover:text-blue-200 {{ $isLiked ? 'text-yellow-300' : 'text-white' }}"
                onclick="@auth document.getElementById('likeForm').submit(); @else showLikeModal(); @endauth"
            >
                <i class="fa-solid fa-thumbs-up"></i> 
                <span>{{ $article->likedByUsers()->count() }}</span>
            </button>
        </div>

        
        <div id="likeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
            <h2 class="text-gray-800 font-semibold mb-2">PERHATIAN!</h2>
            <p class="text-sm text-gray-600 mb-4">Silakan login terlebih dahulu untuk menyukai artikel ini.</p>
            <div class="flex justify-center gap-3">
            <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Login</a>
            </div>
            <button onclick="closeLikeModal()" class="text-sm text-gray-500 mt-4 hover:underline">Tutup</button>
        </div>
        </div>

        <script>
            function showLikeModal() {
                document.getElementById('likeModal').classList.remove('hidden');
            }

            function closeLikeModal() {
                document.getElementById('likeModal').classList.add('hidden');
            }
        </script>

        <span class="h-6 w-px bg-blue-500"></span> 

        <form id="dislikeForm" action="{{ route('articles.dislike', $article->id) }}" method="POST" style="display: none;">
            @csrf
        </form>

        <button
            class="flex items-center gap-2 text-lg transition-colors hover:text-blue-200 {{ $isDisliked ? 'text-yellow-300' : 'text-white' }}"
            onclick="@auth document.getElementById('dislikeForm').submit(); @else showDislikeModal(); @endauth"
        >
            <i class="fa-solid fa-thumbs-down"></i> 
            <span>{{ $article->dislikedByUsers()->count() }}</span>
        </button>

        
        <div id="dislikeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
            <h2 class="text-gray-800 font-semibold mb-2">PERHATIAN!</h2>
            <p class="text-sm text-gray-600 mb-4">Silakan login terlebih dahulu untuk tidak menyukai artikel ini.</p>
            <div class="flex justify-center gap-3">
            <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Login</a>
            </div>
            <button onclick="closeDislikeModal()" class="text-sm text-gray-500 mt-4 hover:underline">Tutup</button>
        </div>
        </div>

        <script>
            function showDislikeModal() {
                document.getElementById('dislikeModal').classList.remove('hidden');
            }

            function closeDislikeModal() {
                document.getElementById('dislikeModal').classList.add('hidden');
            }
        </script>


        <span class="h-6 w-px bg-blue-500"></span> 

        @php
            $isBookmarked = auth()->check() && $article->bookmarkedByUsers->contains(auth()->id());
        @endphp

        <form id="bookmarkForm" action="{{ route('articles.bookmark', $article->id) }}" method="POST" style="display: none;">
            @csrf
        </form>

        <button
            class="flex items-center gap-2 text-lg hover:text-blue-200 transition-colors {{ $isBookmarked ? 'text-yellow-300' : '' }}"
            onclick="@auth document.getElementById('bookmarkForm').submit(); @else showBookmarkModal(); @endauth"
        >
            <i class="fa-solid fa-bookmark"></i> 
            <span>{{ $article->bookmarkedByUsers()->count() }}</span>
        </button>

        
        <div id="bookmarkModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
            <h2 class="text-gray-800 font-semibold mb-2">PERHATIAN!</h2>
            <p class="text-sm text-gray-600 mb-4">Silakan login terlebih dahulu untuk menyimpan artikel ini.</p>
            <div class="flex justify-center gap-3">
            <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Login</a>
            </div>
            <button onclick="closeBookmarkModal()" class="text-sm text-gray-500 mt-4 hover:underline">Tutup</button>
        </div>
        </div>

        <script>
        function showBookmarkModal() {
            document.getElementById('bookmarkModal').classList.remove('hidden');
        }

        function closeBookmarkModal() {
            document.getElementById('bookmarkModal').classList.add('hidden');
        }
        </script>

        <span class="h-6 w-px bg-blue-500"></span> 

        @php
            $user = auth()->user(); 

            $userCurrentRating = 0;

            if ($user) {
                $userRating = \App\Models\Rating::where('user_id', $user->id)
                                                ->where('article_id', $article->id)
                                                ->first();
                if ($userRating) {
                    $userCurrentRating = $userRating->rating;
                }
            }
        @endphp

        
        <div class="flex items-center gap-2 text-sm text-gray-100">
            <span>Suka dengan artikel? Beri ulasan untuk penulis:</span>

            <div class="flex items-center gap-1">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($user)
                        <form action="{{ route('ratings.store', $article->id) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="rating" value="{{ $i }}">
                            <button type="submit"
                                class="text-xl transition hover:scale-110
                                {{ $userCurrentRating >= $i ? 'text-yellow-400' : 'text-gray-300' }}">
                                ★
                            </button>
                        </form>
                    @else
                        <button type="button" onclick="openRatingModal()" class="text-xl text-gray-300 hover:scale-110">★</button>
                    @endif
                @endfor
            </div>
        </div>

        <div id="ratingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
                <h2 class="text-gray-800 font-semibold mb-2">PERHATIAN!</h2>
                <p class="text-sm text-gray-600 mb-4">Silakan login terlebih dahulu untuk memberi ulasan artikel ini.</p>
                <div class="flex justify-center gap-3">
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Login</a>
                </div>
                <button onclick="closeRatingModal()" class="text-sm text-gray-500 mt-4 hover:underline">Tutup</button>
            </div>
        </div>

        @if (session('success')) 
            <div
                x-data="{ open: true }"
                x-show="open"
                x-transition
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            >
                <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full text-center">
                    <h2 class="text-xl font-semibold mb-2 text-gray-800">{{ session('success') }}</h2>

                    <div class="flex justify-center gap-1 text-yellow-400 text-2xl mb-4">
                        @for ($i = 1; $i <= (session('rated_value') ?? 0); $i++) 
                            ★
                        @endfor
                    </div>

                    <button @click="open = false"
                        class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                        Tutup
                    </button>
                </div>
            </div>
        @endif

        <script>
            function openRatingModal() {
                document.getElementById('ratingModal').classList.remove('hidden');
            }

            function closeRatingModal() {
                document.getElementById('ratingModal').classList.add('hidden');
            }
        </script>
    </div>

    <div class="mt-8 bg-gray-100 rounded-lg p-6 shadow-md">
        <h3 class="font-semibold text-2xl text-gray-800 mb-6 flex items-center gap-2">
            Komentar
            <span class="text-sm text-gray-500">({{ $article->comments->count() }})</span>
        </h3>

        <form 
            @auth
                action="{{ route('comments.store', $article->id) }}" 
                method="POST"
            @else
                action="javascript:void(0);" 
            @endauth
            class="flex items-center gap-4 mb-8" 
            id="commentForm"
        >
            @csrf
            <input 
                type="text" 
                name="content"
                id="commentInput"
                placeholder="Tulis Komentar"
                class="flex-grow p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800"
                required
            >
            <button 
                type="submit" 
                class="bg-white text-gray-600 p-3 rounded-full shadow-sm hover:shadow-md transition-shadow flex-shrink-0"
                id="commentSubmit"
            >
                <i class="bi bi-send text-xl rotate-[45deg] inline-block"></i>
            </button>
        </form>

        <div class="space-y-6">
            @forelse ($article->comments as $comment)
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 bg-gray-200 flex items-center justify-center">
                        @if ($comment->user && $comment->user->profile_photo)
                            <img src="{{ asset('storage/profile_photos/' . $comment->user->profile_photo) }}"
                                alt="Foto Profil"
                                class="w-full h-full object-cover">
                        @else
                            <i class="bi bi-person-fill text-2xl text-gray-500"></i>
                        @endif
                    </div>
                    <div>
                        <h1 class="text-sm font-semibold text-gray-800">
                            {{ $comment->user->name }}
                            @if ($comment->user->is_subscribed) 
                                <i class="bi bi-patch-check-fill text-blue-600 text-sm"></i>
                            @endif
                        </h1>
                        <div class="text-xs text-gray-500 mb-1 mt-1">{{ $comment->created_at->format('d/m/Y, H:i') }} WIB</div>
                        <p class="text-gray-700 text-sm leading-relaxed">{{ $comment->content }}</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-600 text-sm">Belum ada komentar.</p>
            @endforelse
        </div>
    </div>

    <div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
            <h2 class="text-gray-800 font-semibold mb-2">PERHATIAN!</h2>
            <p class="text-sm text-gray-600 mb-4">Silakan login terlebih dahulu untuk mengirim komentar.</p>
            <div class="flex justify-center gap-3">
                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Login</a>
            </div>
            <button onclick="closeLoginModal()" class="text-sm text-gray-500 mt-4 hover:underline">Tutup</button>
        </div>
    </div>

    <script>
    function showLoginModal() {
        document.getElementById('loginModal').classList.remove('hidden');
    }

    function closeLoginModal() {
        document.getElementById('loginModal').classList.add('hidden');
    }

    document.addEventListener("DOMContentLoaded", function () {
        const commentForm = document.getElementById('commentForm');
        const commentInput = document.getElementById('commentInput');
        const isGuest = {{ auth()->check() ? 'false' : 'true' }};

        if (isGuest) {
            commentForm.addEventListener('submit', function (e) {
                e.preventDefault();
                if (commentInput.value.trim() !== '') {
                    showLoginModal();
                }
            });
        }
    });
    </script>

</div>


@endsection
