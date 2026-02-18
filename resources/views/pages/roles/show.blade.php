@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Detail Role" />

    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('roles.index') }}"
                class="inline-flex items-center gap-2 text-sm text-gray-600 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Daftar
            </a>

            <div class="flex gap-2">
                @can('edit-roles')
                    <a href="{{ route('roles.edit', $role) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-yellow-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-yellow-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                @endcan
                @can('delete-roles')
                    <button type="button" onclick="deleteRole('{{ $role->id }}')"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-red-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus
                    </button>
                @endcan
            </div>
        </div>

        <!-- Role Info Card -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/30">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Peran</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Detail dan hak akses peran</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Role Name -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-500 dark:text-gray-400">Nama Peran</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $role->name }}</p>
                    </div>

                    <!-- Guard Name -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-500 dark:text-gray-400">Guard Name</label>
                        <span
                            class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                            {{ $role->guard_name }}
                        </span>
                    </div>

                    <!-- Created At -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</label>
                        <p class="text-gray-900 dark:text-white">{{ $role->created_at->format('d M Y, H:i') }}</p>
                    </div>

                    <!-- Updated At -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir
                            Diupdate</label>
                        <p class="text-gray-900 dark:text-white">{{ $role->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Card -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Hak Akses ({{ $role->permissions->count() }})
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Daftar hak akses yang dimiliki peran ini</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if ($role->permissions->count() > 0)
                    @php
                        $groupedPermissions = $role->permissions
                            ->groupBy(function ($permission) {
                                return explode('-', $permission->name, 2)[1] ?? 'other';
                            })
                            ->sortKeys();
                    @endphp
                    <div class="space-y-6">
                        @foreach ($groupedPermissions as $module => $permissions)
                            <div
                                class="rounded-xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-800/40">
                                <!-- Header Module -->
                                <div
                                    class="mb-4 flex items-center justify-between border-b border-gray-200 pb-2 dark:border-gray-700">
                                    <h4
                                        class="text-sm font-semibold uppercase tracking-wide text-gray-700 dark:text-gray-300">
                                        {{ str_replace('-', ' ', ucfirst($module)) }}
                                    </h4>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $permissions->count() }} permission(s)
                                    </span>
                                </div>
                                <!-- List Permission -->
                                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                    @foreach ($permissions->sortBy('name') as $permission)
                                        @php
                                            $action = explode('-', $permission->name, 2)[0];
                                        @endphp
                                        <div
                                            class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-gray-900/50">
                                            <div
                                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                                                <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4" />
                                                </svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ strtoupper(str_replace('-', ' ', $action)) }}
                                                </p>
                                                <p class="truncate text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $permission->guard_name }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-lg bg-gray-50 p-8 text-center dark:bg-gray-800/50">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <p class="mt-4 text-sm font-medium text-gray-900 dark:text-white">Belum ada hak akses</p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Role ini belum memiliki hak akses</p>
                        <a href="{{ route('roles.edit', $role) }}"
                            class="mt-4 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Hak Akses
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function deleteRole(id) {
            if (confirm('Yakin ingin menghapus role ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/roles/${id}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endpush
