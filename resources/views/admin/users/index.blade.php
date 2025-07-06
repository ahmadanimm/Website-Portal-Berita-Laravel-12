@extends('layouts.admin')

@section('page-title', 'Manajemen User')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-800"></h2>
        <form method="GET" action="{{ route('admin.users.index') }}" class="relative w-64" id="adminUserSearchForm">
            <input
                type="text"
                name="search"
                id="adminUserSearchInput"
                value="{{ request('search') }}"
                placeholder="Cari nama atau email..."
                class="bg-gray-100 border border-blue-400 text-gray-800 text-sm rounded-full px-4 py-1 pl-10 pr-8 focus:outline-none focus:ring w-full"
                autocomplete="off"
            />
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-blue-400">
                <i class="fas fa-search"></i>
            </span>
            <span
                id="clearAdminUserSearch"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-blue-400 cursor-pointer {{ request('search') ? '' : 'hidden' }}"
            >
                <i class="fas fa-times"></i>
            </span>
        </form>

        <script>
            const adminUserInput = document.getElementById('adminUserSearchInput');
            const adminUserForm = document.getElementById('adminUserSearchForm');
            const clearAdminUserBtn = document.getElementById('clearAdminUserSearch');

            function submitAdminUserSearchAndKeepFocus() {
                const scrollPosition = window.scrollY || document.documentElement.scrollTop;
                localStorage.setItem('adminUserScrollPosition', scrollPosition);
                localStorage.setItem('adminUserSearchInputFocus', 'true');
                adminUserForm.submit();
            }

            function debounce(fn, delay) {
                let timeout;
                return function (...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => fn.apply(this, args), delay);
                };
            }

            const debouncedAdminUserSubmit = debounce(() => {
                if (adminUserInput.value.trim() !== '') {
                    submitAdminUserSearchAndKeepFocus();
                } else {
                    clearAdminUserBtn.classList.add('hidden');
                    window.location.href = '{{ route('admin.users.index') }}';
                }
            }, 500);

            adminUserInput.addEventListener('input', function () {
                if (adminUserInput.value.trim() === '') {
                    clearAdminUserBtn.classList.add('hidden');
                } else {
                    clearAdminUserBtn.classList.remove('hidden');
                }
                debouncedAdminUserSubmit();
            });

            clearAdminUserBtn?.addEventListener('click', () => {
                adminUserInput.value = '';
                adminUserInput.focus();
                clearAdminUserBtn.classList.add('hidden');
                window.location.href = '{{ route('admin.users.index') }}';
            });

            document.addEventListener('DOMContentLoaded', () => {
                const shouldFocus = localStorage.getItem('adminUserSearchInputFocus');
                const storedScrollPosition = localStorage.getItem('adminUserScrollPosition');

                if (shouldFocus === 'true') {
                    adminUserInput.focus();
                    const value = adminUserInput.value;
                    adminUserInput.value = '';
                    adminUserInput.value = value;

                    if (storedScrollPosition) {
                        window.scrollTo(0, parseInt(storedScrollPosition));
                    }
                    localStorage.removeItem('adminUserSearchInputFocus');
                    localStorage.removeItem('adminUserScrollPosition');
                }

                if (adminUserInput.value.trim() === '') {
                    clearAdminUserBtn.classList.add('hidden');
                } else {
                    clearAdminUserBtn.classList.remove('hidden');
                }
            });
        </script>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white text-sm text-gray-800 border border-gray-200 rounded">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-200 text-left">
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Terdaftar Sejak</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-2">
                            <button
                                type="button"
                                onclick="showDeleteModal({{ $user->id }})"
                                class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs shadow"
                            >
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-gray-500 italic">Tidak ada user ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-between items-center mt-4 text-sm text-gray-700">
        <div>
            Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} pengguna
        </div>
        <div>
            {{ $users->onEachSide(1)->links('pagination::tailwind') }}
        </div>
    </div>
</div>

<form id="deleteUserForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<div id="deleteUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center w-96">
        <h2 class="text-gray-800 font-semibold text-lg mb-2">Konfirmasi Hapus</h2>
        <p class="text-sm text-gray-600 mb-4">Apakah Anda yakin ingin menghapus user ini?</p>
        <div class="flex justify-center gap-4">
            <button onclick="submitDeleteUser()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Hapus</button>
            <button onclick="closeDeleteUserModal()" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
        </div>
    </div>
</div>

<script>
    function showDeleteModal(userId) {
        const form = document.getElementById('deleteUserForm');
        form.action = `/admin/users/${userId}`;
        document.getElementById('deleteUserModal').classList.remove('hidden');
    }

    function closeDeleteUserModal() {
        document.getElementById('deleteUserModal').classList.add('hidden');
    }

    function submitDeleteUser() {
        document.getElementById('deleteUserForm').submit();
    }

    function delayedSubmit(form) {
        clearTimeout(window.searchDelay);
        window.searchDelay = setTimeout(() => form.submit(), 500);
    }
</script>

@endsection