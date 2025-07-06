<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Statistik Admin') }}
        </h2>
    </x-slot>

    <div class="py-10 max-w-5xl mx-auto grid grid-cols-2 gap-6">
        <div class="bg-white shadow p-6 rounded">
            <h3 class="text-lg font-bold">Total Pengguna</h3>
            <p class="text-3xl">{{ $totalUsers }}</p>
        </div>

        <div class="bg-white shadow p-6 rounded">
            <h3 class="text-lg font-bold">Pengguna Premium</h3>
            <p class="text-3xl text-green-600">{{ $premiumUsers }}</p>
        </div>

        <div class="bg-white shadow p-6 rounded">
            <h3 class="text-lg font-bold">Total Artikel</h3>
            <p class="text-3xl">{{ $totalArticles }}</p>
        </div>

        <div class="bg-white shadow p-6 rounded">
            <h3 class="text-lg font-bold">Artikel Premium</h3>
            <p class="text-3xl text-yellow-500">{{ $premiumArticles }}</p>
        </div>
    </div>
</x-app-layout>
