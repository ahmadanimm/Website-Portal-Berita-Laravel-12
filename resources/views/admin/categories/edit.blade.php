@extends('layouts.admin')

@section('page-title')
    <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:underline">Manajemen Kategori</a>
    <span class="text-gray-400"> / </span>
    <span class="text-black">Edit Kategori</span>
@endsection

@section('content')

<form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data"
      class="bg-white p-6 rounded shadow text-black space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label class="block font-semibold mb-1">Nama Kategori</label>
        <input type="text" name="name" value="{{ old('name', $category->name) }}" required
               class="w-full border border-gray-300 rounded px-3 py-2">
    </div>

    <div>
        <label class="block font-semibold mb-1">Ganti Ikon (Max. 2 MB)</label>
        <input type="file" name="icon" id="iconInput" accept="image/*"
               class="w-full border border-gray-300 rounded px-3 py-2">

        @error('icon')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
        @enderror

        <p class="block font-semibold mb-2 mt-2">Ikon saat ini</p>
        <img id="iconPreview"
             src="{{ $category->icon ? asset('storage/' . $category->icon) : 'https://via.placeholder.com/48' }}"
             alt="ikon"
             class="w-16 h-16 object-cover mt-1 rounded border">
    </div>

    <div class="mt-6">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Simpan
        </button>
        <a href="{{ route('admin.categories.index') }}"
           class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
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
document.addEventListener('DOMContentLoaded', function () {
    const iconInput = document.getElementById('iconInput');
    const iconPreview = document.getElementById('iconPreview');
    const sizeModal = document.getElementById('sizeModal');

    iconInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file && file.size > 2 * 1024 * 1024) {
            this.value = ''; 
            sizeModal.classList.remove('hidden');
            return;
        }

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                iconPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
});

function closeSizeModal() {
    document.getElementById('sizeModal').classList.add('hidden');
}
</script>
@endpush
