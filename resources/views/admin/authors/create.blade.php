@extends('layouts.admin')

@section('page-title')
    <a href="{{ route('admin.authors.index') }}" class="text-gray-500 hover:underline">Manajemen Penulis</a>
    <span class="text-gray-400"> / </span>
    <span class="text-black">Tambah Penulis</span>
@endsection

@section('content')

<form action="{{ route('admin.authors.store') }}" method="POST" enctype="multipart/form-data" class="bg-white text-black p-6 rounded shadow space-y-4">
    @csrf

    <div>
        <label for="name" class="block mb-1 font-semibold">Nama</label>
        <input type="text" name="name" id="name" required
            class="w-full border border-gray-300 rounded px-3 py-2" />
    </div>

    <div>
        <label for="avatar" class="block mb-1 font-semibold">Avatar / Gambar (Max. 2 MB)</label>
        <input type="file" name="avatar" id="avatarInput"
            class="w-full border border-gray-300 rounded px-3 py-2" accept="image/*" />
        @error('avatar')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Simpan
        </button>
        <a href="{{ route('admin.authors.index') }}" class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
            Batal
        </a>
    </div>
</form>

<div id="sizeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
        <h2 class="text-gray-800 font-semibold mb-2">PERHATIAN!</h2>
        <p class="text-sm text-gray-600 mb-4">Ukuran file terlalu besar. Maksimal 2MB.</p>
        <div class="flex justify-center">
            <button onclick="closeSizeModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tutup</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const avatarInput = document.getElementById('avatarInput');
    const sizeModal = document.getElementById('sizeModal');

    if (avatarInput) {
        avatarInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file && file.size > 2 * 1024 * 1024) {
                this.value = '';
                sizeModal.classList.remove('hidden');
            }
        });
    }

    function closeSizeModal() {
        sizeModal.classList.add('hidden');
    }
</script>
@endpush
