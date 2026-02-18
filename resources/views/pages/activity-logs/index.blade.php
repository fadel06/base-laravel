@extends('layouts.app', ['title' => 'Log Aktivitas'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Log Aktivitas" />

    <div class="min-h-screen rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">

        <!-- Header Section -->
        <div class="border-b border-gray-200 px-5 py-6 dark:border-gray-800 xl:px-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800 text-theme-xl dark:text-white/90 sm:text-2xl">
                        Log Aktivitas
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Pantau seluruh aktivitas pengguna sistem
                    </p>
                </div>
                <button type="button" onclick="deleteAllLogs()"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus Semua Log
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-800 xl:px-10">
            <form method="GET" action="{{ route('activity-logs.index') }}" class="flex flex-wrap gap-3">
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas..."
                            class="w-full rounded-lg border border-gray-300 bg-white py-2 pl-10 pr-4 text-sm text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500">
                    </div>
                </div>

                <select name="source"
                    class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    <option value="">Semua Sumber</option>
                    <option value="web" {{ request('source') == 'web' ? 'selected' : '' }}>Web</option>
                    <option value="api" {{ request('source') == 'api' ? 'selected' : '' }}>API</option>
                </select>

                <input type="date" name="date" value="{{ request('date') }}"
                    class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">

                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-gray-900">
                    Filter
                </button>

                <a href="{{ route('activity-logs.index') }}"
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
                        <th class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-300 xl:px-10">Pengguna</th>
                        <th class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-300">Aktivitas</th>
                        <th class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-300">IP Address</th>
                        <th class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-300">Sumber</th>
                        <th class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-300">Status</th>
                        <th class="px-5 py-3 font-semibold text-gray-700 dark:text-gray-300">Waktu</th>
                        <th class="px-5 py-3 text-right font-semibold text-gray-700 dark:text-gray-300 xl:px-10">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($logs as $log)
                        <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-900/30">

                            {{-- Pengguna --}}
                            <td class="px-5 py-4 xl:px-10">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-100 dark:bg-brand-900/30">
                                        <svg class="h-5 w-5 text-brand-600 dark:text-brand-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $log->causer?->name ?? 'Tamu' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $log->causer?->email ?? 'Tidak login' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Aktivitas --}}
                            <td class="px-5 py-4">
                                <div class="max-w-xs truncate font-medium text-gray-900 dark:text-white">
                                    {{ $log->description }}
                                </div>
                                <div class="mt-0.5 max-w-xs truncate text-xs text-gray-500 dark:text-gray-400">
                                    {{ $log->properties['url'] ?? '-' }}
                                </div>
                            </td>

                            {{-- IP --}}
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-400">
                                <span
                                    class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                    {{ $log->properties['ip'] ?? '-' }}
                                </span>
                            </td>

                            {{-- Sumber --}}
                            <td class="px-5 py-4">
                                @if (($log->properties['source'] ?? '') === 'api')
                                    <span
                                        class="inline-flex rounded-full bg-brand-50 px-2 py-1 text-xs font-medium text-brand-600 dark:bg-brand-900/30 dark:text-brand-400">
                                        API
                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-success-50 px-2 py-1 text-xs font-medium text-success-600 dark:bg-success-900/30 dark:text-success-400">
                                        Web
                                    </span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4">
                                @php $status = $log->properties['status'] ?? 0 @endphp
                                @if ($status >= 200 && $status < 300)
                                    <span
                                        class="inline-flex rounded-full bg-success-50 px-2 py-1 text-xs font-medium text-success-600 dark:bg-success-900/30 dark:text-success-400">
                                        {{ $status }}
                                    </span>
                                @elseif($status >= 300 && $status < 400)
                                    <span
                                        class="inline-flex rounded-full bg-warning-50 px-2 py-1 text-xs font-medium text-warning-600 dark:bg-warning-900/30 dark:text-warning-400">
                                        {{ $status }}
                                    </span>
                                @elseif($status >= 400)
                                    <span
                                        class="inline-flex rounded-full bg-error-50 px-2 py-1 text-xs font-medium text-error-600 dark:bg-error-900/30 dark:text-error-400">
                                        {{ $status }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                        -
                                    </span>
                                @endif
                            </td>

                            {{-- Waktu --}}
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-400">
                                <div class="text-sm">{{ $log->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4 xl:px-10">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" onclick="deleteLog('{{ $log->id }}')"
                                        class="inline-flex items-center justify-center rounded-lg p-2 text-red-600 transition-colors hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20"
                                        title="Hapus">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center xl:px-10">
                                <div class="flex flex-col items-center justify-center">
                                    <div
                                        class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                        <svg class="h-8 w-8 text-gray-400 dark:text-gray-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <p class="mt-4 text-sm font-medium text-gray-900 dark:text-white">Belum ada log
                                        aktivitas</p>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Log aktivitas akan muncul di
                                        sini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-800 xl:px-10">
                {{ $logs->withQueryString()->links() }}
            </div>
        @endif

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

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

        });

        function deleteLog(id) {
            Swal.fire({
                title: 'Hapus Log?',
                text: 'Log aktivitas ini akan dihapus permanen dan tidak bisa dikembalikan!',
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
                    form.action = `/activity-logs/${id}`;
                    form.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function deleteAllLogs() {
            Swal.fire({
                title: 'Hapus Semua Log?',
                text: 'Seluruh data log aktivitas akan dihapus permanen dan tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d92d20',
                cancelButtonColor: '#667085',
                confirmButtonText: 'Ya, hapus semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('activity-logs.destroyAll') }}`;
                    form.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush
