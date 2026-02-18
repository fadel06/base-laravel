@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Manajemen Wilayah" />

    <div class="min-h-screen rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <!-- Header Section -->
        <div class="border-b border-gray-200 px-5 py-6 dark:border-gray-800 xl:px-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800 text-theme-xl dark:text-white/90 sm:text-2xl">
                        Wilayah
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manajemen data wilayah (Provinsi, Kab/Kota, Kecamatan, Kelurahan/Desa)
                    </p>
                </div>
                @can('create-regions')
                    <button type="button" id="btnAddRegion"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambahkan Wilayah
                    </button>
                @endcan
            </div>
        </div>

        <!-- Filters Section -->
        <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-800 xl:px-10">
            <form method="GET" action="{{ route('regions.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <!-- Search -->
                <div class="flex-1">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Cari
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama atau kode..."
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">
                </div>

                <!-- Level Filter -->
                <div class="w-full sm:w-48">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Level
                    </label>
                    <select name="level"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="">All Levels</option>
                        <option value="1" {{ request('level') == '1' ? 'selected' : '' }}>Provinsi</option>
                        <option value="2" {{ request('level') == '2' ? 'selected' : '' }}>Kab/Kota</option>
                        <option value="3" {{ request('level') == '3' ? 'selected' : '' }}>Kecamatan</option>
                        <option value="4" {{ request('level') == '4' ? 'selected' : '' }}>Kelurahan/Desa</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:bg-gray-700 dark:hover:bg-gray-600">
                        Filter
                    </button>
                    <a href="{{ route('regions.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-300 xl:px-10">Kode</th>
                        <th class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-300">Nama</th>
                        <th class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-300">Level</th>
                        <th class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-300">Induk</th>
                        <th class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-300">Created</th>
                        <th class="px-5 py-3 text-right font-semibold text-gray-700 dark:text-gray-300 xl:px-10">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($regions as $region)
                        <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-900/30">
                            <td class="px-5 py-4 font-mono text-xs text-gray-600 dark:text-gray-400 xl:px-10">
                                {{ $region->code }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-medium text-gray-900 dark:text-white">{{ $region->name }}</span>
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $levelConfig = [
                                        1 => [
                                            'label' => 'Provinsi',
                                            'class' =>
                                                'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                        ],
                                        2 => [
                                            'label' => 'Kab/Kota',
                                            'class' =>
                                                'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        ],
                                        3 => [
                                            'label' => 'Kecamatan',
                                            'class' =>
                                                'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                        ],
                                        4 => [
                                            'label' => 'Kelurahan/Desa',
                                            'class' =>
                                                'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        ],
                                    ];
                                    $config = $levelConfig[$region->level] ?? [
                                        'label' => 'Unknown',
                                        'class' => 'bg-gray-100 text-gray-700',
                                    ];
                                @endphp
                                <span
                                    class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $config['class'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-400">
                                {{ $region->parent?->name ?? '-' }}
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-400">
                                {{ $region->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 xl:px-10">
                                <div class="flex items-center justify-end gap-2">
                                    @can('edit-regions')
                                        <button type="button" onclick="openEditModal('{{ $region->id }}')"
                                            class="inline-flex items-center justify-center rounded-lg p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                                            title="Edit">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    @endcan

                                    @can('delete-regions')
                                        <button type="button" onclick="deleteRegion('{{ $region->id }}')"
                                            class="inline-flex items-center justify-center rounded-lg p-2 text-red-500 transition-colors hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-900/20 dark:hover:text-red-300"
                                            title="Delete">
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
                            <td colspan="6" class="px-5 py-12 text-center xl:px-10">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-400 dark:text-gray-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="mt-3 text-sm font-medium text-gray-900 dark:text-white">Tidak ada wilayah yang
                                        ditemukan</p>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan
                                        wilayah baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($regions->hasPages())
            <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-800 xl:px-10">
                {{ $regions->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    <div id="regionModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/50 backdrop-blur-sm"
        role="dialog" aria-modal="true">
        <div class="relative mx-4 w-full max-w-lg rounded-lg bg-white shadow-xl dark:bg-gray-800">
            <form id="regionForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modal-title">
                        Tambah Wilayah Baru
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
                    <!-- Level -->
                    <div>
                        <label for="level" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Level <span class="text-red-500">*</span>
                        </label>
                        <select name="level" id="level" required
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="">Pilih Level</option>
                            <option value="1">Provinsi</option>
                            <option value="2">Kab/Kota</option>
                            <option value="3">Kecamatan</option>
                            <option value="4">Kelurahan/Desa</option>
                        </select>
                    </div>

                    <!-- Parent -->
                    <div>
                        <label for="parent_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Induk
                        </label>
                        <select name="parent_id" id="parent_id"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="">Tanpa Induk (Level Root)</option>
                        </select>
                    </div>

                    <!-- Code -->
                    <div>
                        <label for="code" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="code" id="code" required placeholder="e.g., 33"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required placeholder="e.g., Jawa Tengah"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
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
                        Simpan Wilayah
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
                    showConfirmButton: true,
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

            @if ($errors->has('delete'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menghapus!',
                    text: @json($errors->first('delete')),
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3B82F6'
                });
            @endif

            // ============ BUKA MODAL OTOMATIS JIKA ADA VALIDATION ERROR ============
            @if ($errors->any() && !$errors->has('delete'))
                openCreateModal();
                document.getElementById('level').value = '{{ old('level') }}';
                document.getElementById('code').value = '{{ old('code') }}';
                document.getElementById('name').value = '{{ old('name') }}';

                @if (old('level'))
                    document.getElementById('level').dispatchEvent(new Event('change'));
                    setTimeout(() => {
                        document.getElementById('parent_id').value = '{{ old('parent_id') }}';
                    }, 600);
                @endif
            @endif

            // ============ BUTTON TAMBAH ============
            const btnAddRegion = document.getElementById('btnAddRegion');
            if (btnAddRegion) {
                btnAddRegion.addEventListener('click', openCreateModal);
            }

            // ============ LEVEL CHANGE ============
            document.getElementById('level').addEventListener('change', function() {
                const level = this.value;
                const parentSelect = document.getElementById('parent_id');

                parentSelect.innerHTML = '<option value="">Memuat...</option>';
                parentSelect.disabled = true;

                if (level == 1 || level == '') {
                    parentSelect.innerHTML = '<option value="">Tanpa Induk (Root Level)</option>';
                    parentSelect.disabled = true;
                    return;
                }

                fetch(`{{ route('regions.get-parents') }}?level=${level}`)
                    .then(response => response.json())
                    .then(data => {
                        parentSelect.innerHTML = '<option value="">Pilih Induk</option>';
                        data.forEach(region => {
                            const option = document.createElement('option');
                            option.value = region.id;
                            option.textContent = `${region.code} - ${region.name}`;
                            parentSelect.appendChild(option);
                        });
                        parentSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error loading parents:', error);
                        parentSelect.innerHTML = '<option value="">Gagal memuat data</option>';
                        parentSelect.disabled = false;
                    });
            });

            // ============ EVENT LISTENERS MODAL ============
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeModal();
            });

            document.getElementById('regionModal').addEventListener('click', function(e) {
                if (e.target === this) closeModal();
            });

        }); // end DOMContentLoaded

        // ============ FUNGSI GLOBAL (harus di luar DOMContentLoaded) ============
        function openCreateModal() {
            document.getElementById('modal-title').textContent = 'Tambah Wilayah Baru';
            document.getElementById('regionForm').action = '{{ route('regions.store') }}';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('regionForm').reset();
            document.getElementById('parent_id').disabled = false;
            document.getElementById('parent_id').innerHTML = '<option value="">Tanpa Induk (Root Level)</option>';
            showModal();
        }

        function openEditModal(id) {
            fetch(`/regions/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modal-title').textContent = 'Edit Wilayah';
                    document.getElementById('regionForm').action = `/regions/${id}`;
                    document.getElementById('formMethod').value = 'PUT';
                    document.getElementById('level').value = data.level;
                    document.getElementById('code').value = data.code;
                    document.getElementById('name').value = data.name;

                    document.getElementById('level').dispatchEvent(new Event('change'));

                    setTimeout(() => {
                        document.getElementById('parent_id').value = data.parent_id || '';
                    }, 600);

                    showModal();
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memuat Data',
                        text: 'Terjadi kesalahan saat memuat data wilayah'
                    });
                });
        }

        function showModal() {
            const modal = document.getElementById('regionModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('regionModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function deleteRegion(id) {
            Swal.fire({
                title: 'Hapus Wilayah?',
                text: 'Wilayah ini akan dihapus permanen dan tidak bisa dikembalikan!',
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
                    form.action = `/regions/${id}`;
                    form.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush
