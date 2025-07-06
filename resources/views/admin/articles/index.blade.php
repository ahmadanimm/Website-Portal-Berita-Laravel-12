@extends('layouts.admin')

@section('page-title', 'Manajemen Berita')

@section('page-action')
    <a href="{{ route('admin.articles.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
        + Tambah Berita
    </a>
@endsection

@section('content')
<div class="bg-white p-4 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">

        <form id="bulk-delete-form" method="POST" action="{{ route('admin.articles.destroy', ['article' => 0]) }}">
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
                <p class="text-sm text-gray-600 mb-4">Apakah Anda yakin ingin menghapus artikel yang dipilih?</p>
                <div class="flex justify-center gap-4">
                    <button onclick="submitBulkDelete()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Hapus</button>
                    <button onclick="closeBulkDeleteModal()" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.articles.index') }}" class="relative w-64" id="adminArticleSearchForm">
            <input
                type="text"
                name="search"
                id="adminArticleSearchInput"
                value="{{ request('search') }}"
                placeholder="Cari berita..."
                class="bg-gray-100 border border-blue-400 text-gray-800 text-sm rounded-full px-4 py-1 pl-10 pr-8 focus:outline-none focus:ring w-full"
                autocomplete="off"
            />
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-blue-400">
                <i class="fas fa-search"></i>
            </span>
            <span
                id="clearAdminArticleSearch"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-blue-400 cursor-pointer {{ request('search') ? '' : 'hidden' }}"
            >
                <i class="fas fa-times"></i>
            </span>
        </form>

        <script>
            const adminArticleInput = document.getElementById('adminArticleSearchInput');
            const adminArticleForm = document.getElementById('adminArticleSearchForm');
            const clearAdminArticleBtn = document.getElementById('clearAdminArticleSearch');

            function submitAdminArticleSearchAndKeepFocus() {
                const scrollPosition = window.scrollY || document.documentElement.scrollTop;
                localStorage.setItem('adminArticleScrollPosition', scrollPosition);
                localStorage.setItem('adminArticleSearchInputFocus', 'true');
                adminArticleForm.submit();
            }

            function debounce(fn, delay) {
                let timeout;
                return function (...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => fn.apply(this, args), delay);
                };
            }

            const debouncedAdminArticleSubmit = debounce(() => {
                if (adminArticleInput.value.trim() !== '') {
                    submitAdminArticleSearchAndKeepFocus();
                } else {
                    clearAdminArticleBtn.classList.add('hidden');
                    window.location.href = '{{ route('admin.articles.index') }}';
                }
            }, 500); 

            adminArticleInput.addEventListener('input', function () {
                if (adminArticleInput.value.trim() === '') {
                    clearAdminArticleBtn.classList.add('hidden');
                } else {
                    clearAdminArticleBtn.classList.remove('hidden');
                }
                debouncedAdminArticleSubmit();
            });

            clearAdminArticleBtn?.addEventListener('click', () => {
                adminArticleInput.value = '';
                adminArticleInput.focus();
                clearAdminArticleBtn.classList.add('hidden');
                window.location.href = '{{ route('admin.articles.index') }}';
            });

            document.addEventListener('DOMContentLoaded', () => {
                const shouldFocus = localStorage.getItem('adminArticleSearchInputFocus');
                const storedScrollPosition = localStorage.getItem('adminArticleScrollPosition');

                if (shouldFocus === 'true') {
                    adminArticleInput.focus();
                    const value = adminArticleInput.value;
                    adminArticleInput.value = '';
                    adminArticleInput.value = value;

                    if (storedScrollPosition) {
                        window.scrollTo(0, parseInt(storedScrollPosition));
                    }
                    localStorage.removeItem('adminArticleSearchInputFocus');
                    localStorage.removeItem('adminArticleScrollPosition');
                }

                if (adminArticleInput.value.trim() === '') {
                    clearAdminArticleBtn.classList.add('hidden');
                } else {
                    clearAdminArticleBtn.classList.remove('hidden');
                }
            });
        </script>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white text-gray-800 text-sm border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="px-4 py-2 border-b border-gray-200">
                        <input type="checkbox" id="select-all">
                    </th>
                    <th class="px-4 py-2 border-b border-gray-200">Judul</th>
                    <th class="px-4 py-2 border-b border-gray-200">Kategori</th>
                    <th class="px-4 py-2 border-b border-gray-200">Status</th>
                    <th class="px-4 py-2 border-b border-gray-200">Thumbnail</th>
                    <th class="px-4 py-2 border-b border-gray-200">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($articles as $article)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">
                            <input type="checkbox" class="select-item" value="{{ $article->id }}">
                        </td>
                        <td class="px-4 py-2">{{ $article->title }}</td>
                        <td class="px-4 py-2">{{ $article->category->name ?? '-' }}</td>
                        <td class="px-4 py-2">
                            @if($article->is_premium)
                                <span class="text-yellow-600 font-semibold">Premium</span>
                            @else
                                <span class="text-green-600 font-semibold">Gratis</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if($article->thumbnail)
                                <img src="{{ asset('storage/'.$article->thumbnail) }}" class="w-20 h-12 object-cover rounded" />
                            @else
                                <span class="text-gray-500 italic">Tidak ada thumbnail</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.articles.edit', $article->id) }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs shadow">
                                <i class="fas fa-pen mr-1"></i>Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-2 text-center text-gray-500 italic">Tidak ada berita.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-between items-center mt-4 text-sm text-gray-600">
        <div class="pr-6">
            Menampilkan {{ $articles->firstItem() }} - {{ $articles->lastItem() }} dari {{ $articles->total() }} Berita
        </div>
        <div>
            {{ $articles->onEachSide(1)->links('pagination::tailwind') }}
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
            selectedCountText.innerText = `${selected.length} terpilih`;
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
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
        });
        updateSelectedDisplay();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectedDisplay);
    });

    let timeout;
    function delayedSubmit(form) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            form.submit();
        }, 500);
    }

    document.getElementById('bulk-delete-form').addEventListener('submit', function(e) {
        setSelectedIds();
    });
</script>
@endsection
