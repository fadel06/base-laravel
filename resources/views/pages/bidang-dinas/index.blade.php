@extends('layouts.app', ['title' => 'Bidang Dinas'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Manajemen Bidang Dinas" />

    <div class="min-h-screen rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">

        <!-- Header Section -->
        <div class="border-b border-gray-200 px-5 py-6 dark:border-gray-800 xl:px-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800 text-theme-xl dark:text-white/90 sm:text-2xl">
                        Bidang Dinas
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Kelola data bidang dan sub bidang per OPD
                    </p>
                </div>
                @can('create-bidang-dinas')
                    <button type="button" id="btnAddBidang"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Bidang
                    </button>
                @endcan
            </div>
        </div>

        <!-- Filters Section -->
        <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-800 xl:px-10">
            <form method="GET" action="{{ route('bidang-dinas.index') }}"
                class="flex flex-col gap-3 sm:flex-row sm:items-end">

                <!-- Search -->
                <div class="flex-1">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama atau singkatan..."
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">
                </div>

                <!-- OPD Filter (Select2) -->
                <div class="w-full sm:w-64 select2-filter-sm">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">OPD</label>
                    <select name="opd_id" id="filterOpdId" style="width:100%">
                        <option value=""></option>
                        @foreach ($opds as $opd)
                            <option value="{{ $opd->id }}" {{ request('opd_id') == $opd->id ? 'selected' : '' }}>
                                {{ $opd->code }} – {{ $opd->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Bidang/Sekretariat Filter -->
                <div class="w-full sm:w-52">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Bidang/Sekretariat</label>
                    <select name="level"
                        class="h-[38px] w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="">Semua</option>
                        <option value="1" {{ request('level') == '1' ? 'selected' : '' }}>Bidang / Sekretariat</option>
                        <option value="2" {{ request('level') == '2' ? 'selected' : '' }}>Sub Bidang / Sub Bagian
                        </option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600">
                        Filter
                    </button>
                    <a href="{{ route('bidang-dinas.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
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
                        <th class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-300 xl:px-10">Nama Bidang</th>
                        <th class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-300">Singkatan</th>
                        <th class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-300">Bidang/Sekretariat</th>
                        <th class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-300">OPD</th>
                        <th class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-300">Induk</th>
                        <th class="px-5 py-3 text-right font-semibold text-gray-700 dark:text-gray-300 xl:px-10">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($bidangDinas as $bidang)
                        <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-900/30">
                            <td class="px-5 py-4 xl:px-10">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $bidang->name }}</div>
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-400">
                                {{ $bidang->abbreviation ?? '-' }}
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $levelConfig = [
                                        1 => [
                                            'label' => 'Bidang / Sekretariat',
                                            'class' =>
                                                'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                        ],
                                        2 => [
                                            'label' => 'Sub Bidang / Sub Bagian',
                                            'class' =>
                                                'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        ],
                                    ];
                                    $cfg = $levelConfig[$bidang->level] ?? [
                                        'label' => 'Tidak Diketahui',
                                        'class' => 'bg-gray-100 text-gray-700',
                                    ];
                                @endphp
                                <span
                                    class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $cfg['class'] }}">
                                    {{ $cfg['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-400">
                                <div class="text-sm">{{ $bidang->opd->name ?? '-' }}</div>
                                @if ($bidang->opd)
                                    <div class="text-xs text-gray-400 font-mono">{{ $bidang->opd->code }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-400">
                                {{ $bidang->parent?->name ?? '-' }}
                            </td>
                            <td class="px-5 py-4 xl:px-10">
                                <div class="flex items-center justify-end gap-2">
                                    @can('edit-bidang-dinas')
                                        <button type="button" onclick="openEditModal('{{ $bidang->id }}')"
                                            class="inline-flex items-center justify-center rounded-lg p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                                            title="Edit">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    @endcan

                                    @can('delete-bidang-dinas')
                                        <button type="button" onclick="deleteBidang('{{ $bidang->id }}')"
                                            class="inline-flex items-center justify-center rounded-lg p-2 text-red-500 transition-colors hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-900/20 dark:hover:text-red-300"
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
                            <td colspan="6" class="px-5 py-12 text-center xl:px-10">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-400 dark:text-gray-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <p class="mt-3 text-sm font-medium text-gray-900 dark:text-white">Belum ada bidang dinas
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan
                                        bidang baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($bidangDinas->hasPages())
            <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-800 xl:px-10">
                {{ $bidangDinas->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    <div class="fixed inset-0 items-center justify-center hidden p-5 overflow-y-auto z-99999" id="bidangModal">
        <div class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]" onclick="closeModal()"></div>
        <div
            class="relative w-full max-w-lg flex flex-col overflow-y-auto rounded-3xl bg-white p-6 lg:p-10 dark:bg-gray-900">

            <!-- Close Button -->
            <button onclick="closeModal()"
                class="transition-color absolute top-5 right-5 z-999 flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-600 sm:h-11 sm:w-11 dark:bg-white/5 dark:hover:bg-white/[0.07] dark:hover:text-gray-300">
                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z" />
                </svg>
            </button>

            <form id="bidangForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <!-- Modal Header -->
                <div>
                    <h5 class="mb-2 font-semibold text-gray-800 text-theme-xl lg:text-2xl dark:text-white/90"
                        id="modal-title">
                        Tambah Bidang Dinas
                    </h5>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Tambah atau ubah data bidang dinas
                    </p>
                </div>

                <!-- Modal Body -->
                <div class="mt-8 space-y-5">

                    <!-- OPD (Select2) -->
                    <div>
                        <label for="opd_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            OPD <span class="text-red-500">*</span>
                        </label>
                        <select name="opd_id" id="opd_id" required style="width:100%">
                            <option value=""></option>
                            @foreach ($opds as $opd)
                                <option value="{{ $opd->id }}">{{ $opd->code }} – {{ $opd->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Bidang/Sekretariat -->
                    <div>
                        <label for="level" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Bidang/Sekretariat <span class="text-red-500">*</span>
                        </label>
                        <select name="level" id="level" required
                            class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="">Pilih Level</option>
                            <option value="1">Bidang / Sekretariat</option>
                            <option value="2">Sub Bidang / Sub Bagian</option>
                        </select>
                    </div>

                    <!-- Parent (muncul hanya jika level 2) -->
                    <div id="parentWrapper" class="hidden">
                        <label for="parent_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Bidang Induk <span class="text-red-500">*</span>
                        </label>
                        <select name="parent_id" id="parent_id"
                            class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="">Pilih Bidang Induk</option>
                        </select>
                    </div>

                    <!-- Nama -->
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nama Bidang <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                            placeholder="Contoh: Bidang Pengelolaan Sampah"
                            class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    </div>

                    <!-- Singkatan -->
                    <div>
                        <label for="abbreviation"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Singkatan <span class="text-xs text-gray-400">(opsional)</span>
                        </label>
                        <input type="text" name="abbreviation" id="abbreviation" placeholder="Contoh: BPS"
                            class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-500 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="flex items-center gap-3 mt-8 sm:justify-end">
                    <button type="button" onclick="closeModal()"
                        class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 sm:w-auto dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/3">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex w-full justify-center rounded-lg bg-blue-600 hover:bg-blue-700 px-4 py-2.5 text-sm font-medium text-white sm:w-auto">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* ===== Select2 — sesuai design system TailAdmin ===== */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 44px;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            background-color: transparent;
            display: flex;
            align-items: center;
        }

        /* Filter area: height lebih kecil supaya sejajar dengan input filter */
        .select2-filter-sm .select2-container--default .select2-selection--single {
            height: 38px;
        }

        .select2-filter-sm .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }

        .select2-filter-sm .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 1rem;
            padding-right: 2rem;
            font-size: 0.875rem;
            line-height: 38px;
        }

        .select2-filter-sm .select2-container--default .select2-selection--single .select2-selection__clear {
            top: 50%;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 1rem;
            padding-right: 2rem;
            color: #1f2937;
            font-size: 0.875rem;
            line-height: 1.25rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #9ca3af;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px;
            right: 10px;
        }

        /* Clear (x) button */
        .select2-container--default .select2-selection--single {
            position: relative;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear {
            float: none;
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            margin: 0;
            padding: 0 4px;
            font-size: 14px;
            line-height: 1;
            color: #9ca3af;
            z-index: 1;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear:hover {
            color: #374151;
        }

        .select2-container--default .select2-selection--single:has(.select2-selection__clear) .select2-selection__rendered {
            padding-left: 2rem;
        }

        .select2-container--default.select2-container--open .select2-selection--single,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #465fff;
            box-shadow: 0px 0px 0px 4px rgba(70, 95, 255, 0.12);
            outline: none;
        }

        /* Dropdown */
        .select2-dropdown {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            z-index: 999999 !important;
        }

        .select2-container--default .select2-search--dropdown {
            padding: 0.5rem;
            background: #fff;
            border-bottom: 1px solid #f3f4f6;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.375rem 0.625rem;
            font-size: 0.875rem;
            width: 100%;
            outline: none;
            transition: border-color 0.15s;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: #465fff;
        }

        .select2-results__options {
            max-height: 220px;
            overflow-y: auto;
        }

        .select2-container--default .select2-results__option {
            padding: 0.5rem 0.875rem;
            font-size: 0.875rem;
            color: #374151;
            cursor: pointer;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #465fff;
            color: #fff;
        }

        .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: #f2f7ff;
            color: #465fff;
        }

        /* ===== Dark mode ===== */
        .dark .select2-container--default .select2-selection--single {
            background-color: #111827;
            border-color: #374151;
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: rgba(255, 255, 255, 0.9);
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .dark .select2-container--default.select2-container--open .select2-selection--single,
        .dark .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #465fff;
        }

        .dark .select2-dropdown {
            background-color: #1f2937;
            border-color: #374151;
        }

        .dark .select2-container--default .select2-search--dropdown {
            background-color: #1f2937;
            border-bottom-color: #374151;
        }

        .dark .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: #111827;
            border-color: #374151;
            color: rgba(255, 255, 255, 0.9);
        }

        .dark .select2-container--default .select2-results__option {
            background-color: #1f2937;
            color: rgba(255, 255, 255, 0.85);
        }

        .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #465fff;
            color: #fff;
        }

        .dark .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: #1e3a5f;
            color: #93c5fd;
        }

        .dark .select2-search--dropdown .select2-search__field::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // ============ INIT SELECT2 ============
        function initSelect2() {
            // Select2 di filter (tanpa dropdownParent agar dropdown tidak terpotong)
            $('#filterOpdId').select2({
                placeholder: 'Semua OPD',
                allowClear: true,
                language: {
                    noResults: () => 'OPD tidak ditemukan',
                    searching: () => 'Mencari...',
                },
            });

            // Select2 di modal
            $('#opd_id').select2({
                placeholder: 'Cari atau pilih OPD...',
                allowClear: true,
                dropdownParent: $('#bidangModal'),
                language: {
                    noResults: () => 'OPD tidak ditemukan',
                    searching: () => 'Mencari...',
                },
            });

            // Sync perubahan Select2 modal → trigger loadParents
            $('#opd_id').on('change', function() {
                const level = parseInt($('#level').val());
                if (level === 2) {
                    loadParents(this.value);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {

            initSelect2();

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
                    confirmButtonColor: '#465FFF'
                });
            @endif

            @if ($errors->has('delete'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menghapus!',
                    text: @json($errors->first('delete')),
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#465FFF'
                });
            @endif

            // ============ BUKA MODAL JIKA ADA VALIDATION ERROR ============
            @if ($errors->any() && !$errors->has('delete'))
                openCreateModal();
                // Set Select2 value
                $('#opd_id').val('{{ old('opd_id') }}').trigger('change.select2');
                document.getElementById('level').value = '{{ old('level') }}';
                document.getElementById('name').value = '{{ old('name') }}';
                document.getElementById('abbreviation').value = '{{ old('abbreviation') }}';

                @if (old('level') == 2)
                    toggleParentField(2);
                    loadParents('{{ old('opd_id') }}', '{{ old('parent_id') }}');
                @else
                    toggleParentField(parseInt('{{ old('level') }}') || 0);
                @endif
            @endif

            // ============ BUTTON TAMBAH ============
            const btnAdd = document.getElementById('btnAddBidang');
            if (btnAdd) btnAdd.addEventListener('click', openCreateModal);

            // ============ LEVEL CHANGE ============
            document.getElementById('level').addEventListener('change', function() {
                const level = parseInt(this.value);
                const opdId = $('#opd_id').val();
                toggleParentField(level);
                if (level === 2 && opdId) {
                    loadParents(opdId);
                } else {
                    resetParentSelect();
                }
            });

            // ============ MODAL KEYBOARD ============
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeModal();
            });
        });

        // ============ FUNGSI GLOBAL ============

        function toggleParentField(level) {
            const wrapper = document.getElementById('parentWrapper');
            if (level === 2) {
                wrapper.classList.remove('hidden');
            } else {
                wrapper.classList.add('hidden');
                document.getElementById('parent_id').value = '';
            }
        }

        function resetParentSelect() {
            const sel = document.getElementById('parent_id');
            sel.innerHTML = '<option value="">Pilih Bidang Induk</option>';
            sel.disabled = false;
        }

        function loadParents(opdId, selectedId = null) {
            const sel = document.getElementById('parent_id');
            sel.innerHTML = '<option value="">Memuat...</option>';
            sel.disabled = true;

            if (!opdId) {
                resetParentSelect();
                return;
            }

            fetch(`{{ route('bidang-dinas.get-parents') }}?opd_id=${opdId}`)
                .then(r => r.json())
                .then(data => {
                    sel.innerHTML = '<option value="">Pilih Bidang Induk</option>';
                    data.forEach(item => {
                        const opt = document.createElement('option');
                        opt.value = item.id;
                        opt.textContent = item.abbreviation ?
                            `${item.name} (${item.abbreviation})` :
                            item.name;
                        if (selectedId && item.id === selectedId) opt.selected = true;
                        sel.appendChild(opt);
                    });
                    sel.disabled = false;
                })
                .catch(() => {
                    sel.innerHTML = '<option value="">Gagal memuat data</option>';
                    sel.disabled = false;
                });
        }

        function openCreateModal() {
            document.getElementById('modal-title').textContent = 'Tambah Bidang Dinas';
            document.getElementById('bidangForm').action = '{{ route('bidang-dinas.store') }}';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('bidangForm').reset();
            $('#opd_id').val(null).trigger('change.select2'); // reset Select2
            toggleParentField(0);
            showModal();
        }

        function openEditModal(id) {
            fetch(`/bidang-dinas/${id}/edit`)
                .then(r => r.json())
                .then(data => {
                    console.log('Edit data:', data); // Debug: lihat data di console

                    document.getElementById('modal-title').textContent = 'Edit Bidang Dinas';
                    document.getElementById('bidangForm').action = `/bidang-dinas/${id}`;
                    document.getElementById('formMethod').value = 'PUT';

                    // Set form values
                    document.getElementById('level').value = data.level;
                    document.getElementById('name').value = data.name;
                    document.getElementById('abbreviation').value = data.abbreviation || '';

                    // Set Select2 OPD — pastikan option ada sebelum set value
                    const $opd = $('#opd_id');
                    if ($opd.find(`option[value="${data.opd_id}"]`).length) {
                        $opd.val(data.opd_id).trigger('change.select2');
                    } else {
                        // Jika option belum ada (misalnya dynamic), tambahkan dulu
                        $opd.val(data.opd_id).trigger('change.select2');
                    }

                    // Toggle parent field
                    toggleParentField(parseInt(data.level));
                    if (data.level == 2 && data.opd_id) {
                        loadParents(data.opd_id, data.parent_id);
                    }

                    showModal();
                })
                .catch((err) => {
                    console.error('Error:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memuat Data',
                        text: 'Terjadi kesalahan saat memuat data bidang dinas.'
                    });
                });
        }

        function showModal() {
            const modal = document.getElementById('bidangModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            const modal = document.getElementById('bidangModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        function deleteBidang(id) {
            Swal.fire({
                title: 'Hapus Bidang Dinas?',
                text: 'Data ini akan dihapus permanen dan tidak bisa dikembalikan!',
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
                    form.action = `/bidang-dinas/${id}`;
                    form.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush
