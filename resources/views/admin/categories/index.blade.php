@extends('layouts.admin')

@section('page-title', 'Manajemen Kategori')

@section('page-action')
    <a href="{{ route('admin.categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
        + Tambah Kategori
    </a>
@endsection

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-4">

        <form id="bulk-delete-form" method="POST" action="{{ route('admin.categories.destroy', ['category' => 0]) }}">
            @csrf
            @method('DELETE')
            <input type="hidden" name="selected_ids" id="selected-ids">
            <div id="selected-count" class="hidden text-gray-700 mb-1"></div>
            <button type="button" id="delete-selected" class="hidden bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-sm shadow" onclick="showBulkDeleteModal()">
                <i class="fas fa-trash mr-1"></i> Hapus item yang dipilih
            </button>
        </form>

        <div id="bulkDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg text-center w-96">
                <h2 class="text-gray-800 font-semibold text-lg mb-2">Konfirmasi Hapus</h2>
                <p class="text-sm text-gray-600 mb-4">Apakah Anda yakin ingin menghapus kategori yang dipilih?</p>
                <div class="flex justify-center gap-4">
                    <button onclick="submitBulkDelete()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Hapus</button>
                    <button onclick="closeBulkDeleteModal()" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.categories.index') }}" class="relative w-64" id="adminCategorySearchForm">
            <input
                type="text"
                name="search"
                id="adminCategorySearchInput"
                value="{{ request('search') }}"
                placeholder="Cari kategori..."
                class="bg-gray-100 border border-blue-400 text-gray-800 text-sm rounded-full px-4 py-1 pl-10 pr-8 focus:outline-none focus:ring w-full"
                autocomplete="off"
            />
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-blue-400">
                <i class="fas fa-search"></i>
            </span>
            <span
                id="clearAdminCategorySearch"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-blue-400 cursor-pointer {{ request('search') ? '' : 'hidden' }}"
            >
                <i class="fas fa-times"></i>
            </span>
        </form>

        <script>
            const adminCategoryInput = document.getElementById('adminCategorySearchInput');
            const adminCategoryForm = document.getElementById('adminCategorySearchForm');
            const clearAdminCategoryBtn = document.getElementById('clearAdminCategorySearch');

            function submitAdminCategorySearchAndKeepFocus() {
                const scrollPosition = window.scrollY || document.documentElement.scrollTop;
                localStorage.setItem('adminCategoryScrollPosition', scrollPosition);
                localStorage.setItem('adminCategorySearchInputFocus', 'true');
                adminCategoryForm.submit();
            }

            function debounce(fn, delay) {
                let timeout;
                return function (...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => fn.apply(this, args), delay);
                };
            }

            const debouncedAdminCategorySubmit = debounce(() => {
                if (adminCategoryInput.value.trim() !== '') {
                    submitAdminCategorySearchAndKeepFocus();
                } else {
                    clearAdminCategoryBtn.classList.add('hidden');
                    window.location.href = '{{ route('admin.categories.index') }}';
                }
            }, 500);

            adminCategoryInput.addEventListener('input', function () {
                if (adminCategoryInput.value.trim() === '') {
                    clearAdminCategoryBtn.classList.add('hidden');
                } else {
                    clearAdminCategoryBtn.classList.remove('hidden');
                }
                debouncedAdminCategorySubmit();
            });

            clearAdminCategoryBtn?.addEventListener('click', () => {
                adminCategoryInput.value = '';
                adminCategoryInput.focus();
                clearAdminCategoryBtn.classList.add('hidden');
                window.location.href = '{{ route('admin.categories.index') }}';
            });

            document.addEventListener('DOMContentLoaded', () => {
                const shouldFocus = localStorage.getItem('adminCategorySearchInputFocus');
                const storedScrollPosition = localStorage.getItem('adminCategoryScrollPosition');

                if (shouldFocus === 'true') {
                    adminCategoryInput.focus();
                    const value = adminCategoryInput.value;
                    adminCategoryInput.value = '';
                    adminCategoryInput.value = value;

                    if (storedScrollPosition) {
                        window.scrollTo(0, parseInt(storedScrollPosition));
                    }
                    localStorage.removeItem('adminCategorySearchInputFocus');
                    localStorage.removeItem('adminCategoryScrollPosition');
                }

                if (adminCategoryInput.value.trim() === '') {
                    clearAdminCategoryBtn.classList.add('hidden');
                } else {
                    clearAdminCategoryBtn.classList.remove('hidden');
                }
            });
        </script>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white text-sm text-gray-800 border border-gray-200 rounded">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-200 text-left">
                    <th class="px-4 py-2">
                        <input type="checkbox" id="select-all">
                    </th>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Slug</th>
                    <th class="px-4 py-2">Ikon</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($categories as $category)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">
                            <input type="checkbox" class="select-item" value="{{ $category->id }}">
                        </td>
                        <td class="px-4 py-2">{{ $category->name }}</td>
                        <td class="px-4 py-2">{{ $category->slug }}</td>
                        <td class="px-4 py-2">
                            @if($category->icon)
                                <img src="{{ asset('storage/' . $category->icon) }}" alt="Icon" class="w-6 h-6 object-contain">
                            @else
                                <span class="text-gray-500 italic">Tidak ada ikon</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs shadow">
                                <i class="fas fa-pen mr-1"></i>Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500 italic">Tidak ada kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-between items-center mt-4 text-sm text-gray-700">
        <div class="pr-6">
            Menampilkan {{ $categories->firstItem() }} - {{ $categories->lastItem() }} dari {{ $categories->total() }} Berita
        </div>
        <div>
            {{ $categories->onEachSide(1)->links('pagination::tailwind') }}
        </div>
    </div>
</div>

<script>
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.select-item');
    const selectedIdsInput = document.getElementById('selected-ids');
    const deleteButton = document.getElementById('delete-selected');
    const selectedCountText = document.getElementById('selected-count');

    function updateSelectedDisplay() {
        const selected = Array.from(checkboxes).filter(cb => cb.checked);
        if (selected.length > 0) {
            deleteButton.classList.remove('hidden');
            selectedCountText.classList.remove('hidden');
            selectedCountText.innerText = `${selected.length} selected`;
        } else {
            deleteButton.classList.add('hidden');
            selectedCountText.classList.add('hidden');
        }
    }

    function setSelectedIds() {
        const selected = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
        selectedIdsInput.value = selected.join(',');
    }

    function showBulkDeleteModal() {
        setSelectedIds();
        if (selectedIdsInput.value === '') return;
        document.getElementById('bulkDeleteModal').classList.remove('hidden');
    }

    function closeBulkDeleteModal() {
        document.getElementById('bulkDeleteModal').classList.add('hidden');
    }

    function submitBulkDelete() {
        document.getElementById('bulk-delete-form').submit();
    }

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateSelectedDisplay();
    });

    checkboxes.forEach(cb => cb.addEventListener('change', updateSelectedDisplay));

    function delayedSubmit(form) {
        clearTimeout(window.searchDelay);
        window.searchDelay = setTimeout(() => form.submit(), 500);
    }

    document.getElementById('bulk-delete-form').addEventListener('submit', setSelectedIds);
</script>
@endsection
