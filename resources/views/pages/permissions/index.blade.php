@extends('layouts.app', ['title' => 'Hak Akses'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Manajemen Hak Akses" />

    <div class="min-h-screen rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
        <!-- Header Section -->
        <div class="border-b border-gray-200 px-5 py-6 dark:border-gray-800 xl:px-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800 text-theme-xl dark:text-white/90 sm:text-2xl">
                        Hak Akses
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Kelola hak akses sistem
                    </p>
                </div>
                @can('create-permissions')
                    <button type="button" id="btnAddPermission"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Hak Akses
                    </button>
                @endcan
            </div>
        </div>

        <!-- Search Section -->
        <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-800 xl:px-10">
            <form method="GET" action="{{ route('permissions.index') }}" class="flex gap-3">
                <div class="flex-1">
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari hak akses..."
                            class="w-full rounded-lg border border-gray-300 bg-white py-2 pl-10 pr-4 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">
                    </div>
                </div>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-gray-900">
                    Cari
                </button>
                <a href="{{ route('permissions.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    Reset
                </a>
            </form>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-300 xl:px-10">Nama Hak Akses</th>
                        <th class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-300">Guard Name</th>
                        <th class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-300">Dibuat</th>
                        <th class="px-5 py-3 text-right font-semibold text-gray-700 dark:text-gray-300 xl:px-10">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($permissions as $permission)
                        <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-900/30">
                            <td class="px-5 py-4 xl:px-10">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                                        <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $permission->name }}</div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-400">
                                <span
                                    class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                    {{ $permission->guard_name }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-400">
                                <div class="text-sm">{{ $permission->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $permission->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-5 py-4 xl:px-10">
                                <div class="flex items-center justify-end gap-2">
                                    @can('edit-permissions')
                                        <button type="button" onclick="openEditModal('{{ $permission->id }}')"
                                            class="inline-flex items-center justify-center rounded-lg p-2 text-yellow-600 transition-colors hover:bg-yellow-50 dark:text-yellow-400 dark:hover:bg-yellow-900/20"
                                            title="Edit">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    @endcan
                                    @can('delete-permissions')
                                        <button type="button" onclick="deletePermission('{{ $permission->id }}')"
                                            class="inline-flex items-center justify-center rounded-lg p-2 text-red-600 transition-colors hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20"
                                            title="Hapus">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-16 text-center xl:px-10">
                                <div class="flex flex-col items-center justify-center">
                                    <div
                                        class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                        <svg class="h-8 w-8 text-gray-400 dark:text-gray-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <p class="mt-4 text-sm font-medium text-gray-900 dark:text-white">Belum ada hak akses
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan hak
                                        akses baru</p>
                                    @can('create-permissions')
                                        <button type="button" onclick="openCreateModal()"
                                            class="mt-4 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                            Tambah Hak Akses
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($permissions->hasPages())
            <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-800 xl:px-10">
                {{ $permissions->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    <div id="permissionModal"
        class="fixed inset-0 hidden items-center justify-center bg-gray-900/50 backdrop-blur-sm overflow-y-auto modal z-99999"
        role="dialog" aria-modal="true">
        <div class="relative mx-4 w-full max-w-md rounded-lg bg-white shadow-xl dark:bg-gray-800">
            <form id="permissionForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modal-title">
                        Tambah Hak Akses
                    </h3>
                    <button type="button" onclick="closeModal()"
                        class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-white">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="space-y-4 px-6 py-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Hak Akses <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required placeholder="Contoh: create-users"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Gunakan format kebab-case, contoh: <span
                                class="font-mono">view-users</span>, <span class="font-mono">create-reports</span></p>
                    </div>

                    <!-- Guard Name -->
                    <div>
                        <label for="guard_name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Guard Name <span class="text-red-500">*</span>
                        </label>
                        <select name="guard_name" id="guard_name" required
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="web">web</option>
                            <option value="api">api</option>
                        </select>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-3 border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                    <button type="button" onclick="closeModal()"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ============ SESSION ALERTS ============
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: @json(session('error')),
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3B82F6'
                });
            @endif

            // ============ BUKA MODAL JIKA ADA VALIDATION ERROR ============
            @if ($errors->any())
                openCreateModal();
                document.getElementById('name').value = '{{ old('name') }}';
                document.getElementById('guard_name').value = '{{ old('guard_name', 'web') }}';
            @endif

            // ============ BUTTON TAMBAH ============
            const btnAdd = document.getElementById('btnAddPermission');
            if (btnAdd) {
                btnAdd.addEventListener('click', openCreateModal);
            }

            // ============ EVENT LISTENERS MODAL ============
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeModal();
            });

            document.getElementById('permissionModal').addEventListener('click', function(e) {
                if (e.target === this) closeModal();
            });

        });

        // ============ FUNGSI GLOBAL ============
        function openCreateModal() {
            document.getElementById('modal-title').textContent = 'Tambah Hak Akses';
            document.getElementById('permissionForm').action = '{{ route('permissions.store') }}';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('permissionForm').reset();
            document.getElementById('guard_name').value = 'web';
            showModal();
        }

        function openEditModal(id) {
            fetch(`/permissions/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modal-title').textContent = 'Edit Hak Akses';
                    document.getElementById('permissionForm').action = `/permissions/${id}`;
                    document.getElementById('formMethod').value = 'PUT';
                    document.getElementById('name').value = data.name;
                    document.getElementById('guard_name').value = data.guard_name;
                    showModal();
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memuat Data',
                        text: 'Terjadi kesalahan saat memuat data hak akses.'
                    });
                });
        }

        function showModal() {
            const modal = document.getElementById('permissionModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('permissionModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function deletePermission(id) {
            Swal.fire({
                title: 'Hapus Hak Akses?',
                text: 'Hak akses ini akan dihapus permanen dan tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d92d20',
                cancelButtonColor: '#667085',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/permissions/${id}`;
                    form.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush
