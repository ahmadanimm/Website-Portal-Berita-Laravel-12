@extends('layouts.app')

@section('content')
@include('partials.navbar-profile')

<div class="max-w-7xl mx-auto px-4 py-10">
  <div class="flex flex-col md:flex-row items-start md:gap-6 mb-10">
    <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center text-5xl">
      @if (Auth::user()->profile_photo)
        <img id="profilePhotoPreview" src="{{ asset('storage/profile_photos/' . Auth::user()->profile_photo) }}" alt="Foto Profil" class="w-full h-full object-cover">
      @else
        <img id="profilePhotoPreview" src="{{ asset('assets/images/default-avatar.png') }}" alt="Foto Default" class="w-full h-full object-cover hidden">
        <i class="bi bi-person-fill text-gray-500" id="defaultIcon"></i>
      @endif
    </div>

    
    <div class="flex-1 mt-6 md:mt-0">
      <h2 class="text-2xl font-bold mb-6">Edit Profil</h2>

      @if (session('success'))
        <div class="mb-4 text-green-600 font-semibold">{{ session('success') }}</div>
      @endif

      <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div>
          <label class="block font-medium mb-1 text-sm">Foto Profil</label>
          <input type="file" name="profile_photo" accept="image/*" id="profilePhotoInput"
            class="block w-full text-sm text-gray-700 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div>
          <label class="block font-medium mb-1 text-sm">Nama</label>
          <input type="text" name="name" value="{{ Auth::user()->name }}"
            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
          <label class="block font-medium mb-1 text-sm">Password Baru (opsional)</label>
          <input type="password" name="password" placeholder="Password baru"
            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          <input type="password" name="password_confirmation" placeholder="Konfirmasi password"
            class="mt-3 w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex flex-wrap gap-3">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700 text-sm">
            Simpan Perubahan
          </button>
          <a href="{{ url()->previous() }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded font-semibold hover:bg-gray-300 text-sm">
            Batal
          </a>
        </div>
      </form>

      <form id="delete-account-form" action="{{ route('profile.delete') }}" method="POST" class="mt-24">
          @csrf
          <button type="button" onclick="showDeleteAccountModal()" class="bg-red-600 text-white px-4 py-2 rounded font-semibold hover:bg-red-700 text-sm">
              Hapus Akun
          </button>
      </form>

    </div>
  </div>
</div>

<div id="sizeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
  <div class="bg-white p-6 rounded-lg shadow-lg text-center w-80">
      <h2 class="text-gray-800 font-semibold mb-2">PERHATIAN!</h2>
      <p class="text-sm text-gray-600 mb-4">Ukuran file terlalu besar. Maksimal 2MB.</p>
      <div class="flex justify-center">
          <button onclick="closeSizeModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tutup</button>
      </div>
  </div>
</div>

<div id="deleteAccountModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
  <div class="bg-white p-6 rounded-lg shadow-lg text-center w-96">
      <h2 class="text-gray-800 font-semibold text-lg mb-2">Konfirmasi Hapus Akun</h2>
      <p class="text-sm text-gray-600 mb-4">Apakah Anda yakin ingin menghapus akun secara permanen? Tindakan ini tidak dapat dibatalkan.</p>
      <div class="flex justify-center gap-4">
          <button onclick="submitDeleteAccount()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Ya, Hapus</button>
          <button onclick="closeDeleteAccountModal()" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
      </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('profilePhotoInput');
    const preview = document.getElementById('profilePhotoPreview');
    const defaultIcon = document.getElementById('defaultIcon');
    const sizeModal = document.getElementById('sizeModal');

    input.addEventListener('change', function (e) {
      const file = e.target.files[0];
      if (file && file.size > 2 * 1024 * 1024) {
        input.value = '';
        sizeModal.classList.remove('hidden');
        return;
      }

      if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (event) {
          preview.src = event.target.result;
          preview.classList.remove('hidden');
          if (defaultIcon) defaultIcon.classList.add('hidden');
        };
        reader.readAsDataURL(file);
      }
    });
  });

  function closeSizeModal() {
    document.getElementById('sizeModal').classList.add('hidden');
  }

  function showDeleteAccountModal() {
    document.getElementById('deleteAccountModal').classList.remove('hidden');
  }

  function closeDeleteAccountModal() {
    document.getElementById('deleteAccountModal').classList.add('hidden');
  }

  function submitDeleteAccount() {
    document.getElementById('delete-account-form').submit();
  }

</script>
@endpush
