@extends('layouts.admin')

@section('page-title')
    <a href="{{ route('admin.articles.index') }}" class="text-gray-500 hover:underline">Manajemen Berita</a>
    <span class="text-gray-400"> / </span>
    <span class="text-black">Tambah Berita</span>
@endsection

@section('content')

<form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="bg-white text-gray-800 p-6 rounded-lg shadow space-y-5">
    @csrf

    <div>
        <label class="block mb-1 font-semibold text-sm">Judul</label>
        <input type="text" name="title" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" required>
    </div>

    <div>
        <label class="inline-flex items-center text-sm">
            <input type="checkbox" name="is_premium" class="mr-2">
            Premium
        </label>
    </div>

    <div>
        <label class="block mb-1 font-semibold text-sm">Kategori</label>
        <select name="category_id" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block mb-1 font-semibold text-sm">Thumbnail (Max. 2 MB)</label>
        <input type="file" name="thumbnail" id="thumbnailInput"
            class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
            accept="image/*">
        
        @error('thumbnail')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-1 font-semibold text-sm">Isi Artikel</label>
        <textarea name="body" rows="6" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" required></textarea>
    </div>

    <div class="flex items-center gap-2">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow text-sm transition">
            Simpan
        </button>
        <a href="{{ route('admin.articles.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md shadow text-sm transition">
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
    const thumbnailInput = document.getElementById('thumbnailInput');
    const sizeModal = document.getElementById('sizeModal');

    if (thumbnailInput) {
        thumbnailInput.addEventListener('change', function () {
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


