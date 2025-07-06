<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
           
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            
            @auth
                @php
                    $user = auth()->user();
                    $expired = $user->subscribed_until && $user->subscribed_until < now();
                @endphp

                @if(!$user->is_subscribed || $expired)
                    <form method="POST" action="{{ route('subscribe') }}">
                        @csrf
                        <button class="bg-yellow-500 text-white px-4 py-2 rounded shadow hover:bg-yellow-600">
                            Berlangganan
                        </button>
                    </form>
                @else
                    <p class="text-green-600 font-semibold">
                        Akun Premium aktif
                        @if($user->subscribed_until)
                            sampai {{ \Carbon\Carbon::parse($user->subscribed_until)->format('d M Y') }}
                        @endif
                    </p>
                @endif

                
                 @if(auth()->user()->is_subscribed && auth()->user()->subscribed_until > now())
                    <form method="POST" action="{{ route('unsubscribe') }}" onsubmit="return confirm('Yakin ingin membatalkan langganan?');">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700 mt-4">
                            Batalkan Langganan
                        </button>
                    </form>
                @endif

                
                @if($user->subscriptions->count())
                    <div class="bg-white p-4 mt-4 rounded shadow">
                        <h3 class="text-lg font-semibold mb-2">Riwayat Langganan</h3>
                        <ul class="space-y-1 text-sm text-gray-700">
                            @foreach($user->subscriptions->sortByDesc('starts_at') as $sub)
                                <li>
                                    {{ \Carbon\Carbon::parse($sub->starts_at)->format('d M Y') }}
                                    &ndash;
                                    {{ \Carbon\Carbon::parse($sub->ends_at)->format('d M Y') }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="text-gray-600 mt-4">Belum ada riwayat langganan.</p>
                @endif
            @endauth
        </div>
    </div>
</x-app-layout>
