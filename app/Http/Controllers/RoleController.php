<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-roles')->only(['index', 'show']);
        $this->middleware('permission:create-roles')->only(['create', 'store']);
        $this->middleware('permission:edit-roles')->only(['edit', 'update']);
        $this->middleware('permission:delete-roles')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Role::with('permissions')->whereNot('name', 'super-admin');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        $roles = $query->orderBy('name', 'asc')->paginate(15)->withQueryString();

        return view('pages.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name', 'asc')->get();
        return view('pages.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255|unique:roles,name',
            'guard_name'    => 'required|string|max:255',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'name.required'          => 'Nama role wajib diisi.',
            'name.unique'            => 'Nama role sudah digunakan.',
            'name.max'               => 'Nama role tidak boleh lebih dari 255 karakter.',
            'guard_name.required'    => 'Guard name wajib diisi.',
            'guard_name.max'         => 'Guard name tidak boleh lebih dari 255 karakter.',
            'permissions.array'      => 'Format hak akses tidak valid.',
            'permissions.*.exists'   => 'Salah satu hak akses yang dipilih tidak valid.',
        ]);

        $role = Role::create([
            'name'       => $validated['name'],
            'guard_name' => $validated['guard_name'],
        ]);

        if (!empty($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        $jumlahPermission = count($validated['permissions'] ?? []);

        return redirect()->route('roles.index')
            ->with('success', "Role berhasil ditambahkan dengan {$jumlahPermission} hak akses.");
    }

    public function show(Role $role)
    {
        if ($role->name === 'super-admin') {
            abort(403);
        }

        $role->load('permissions');
        return view('pages.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        if ($role->name === 'super-admin') {
            abort(403);
        }

        $permissions = Permission::orderBy('name', 'asc')->get();
        $role->load('permissions');
        return view('pages.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255|unique:roles,name,' . $role->id,
            'guard_name'    => 'required|string|max:255',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'name.required'          => 'Nama role wajib diisi.',
            'name.unique'            => 'Nama role sudah digunakan.',
            'name.max'               => 'Nama role tidak boleh lebih dari 255 karakter.',
            'guard_name.required'    => 'Guard name wajib diisi.',
            'guard_name.max'         => 'Guard name tidak boleh lebih dari 255 karakter.',
            'permissions.array'      => 'Format hak akses tidak valid.',
            'permissions.*.exists'   => 'Salah satu hak akses yang dipilih tidak valid.',
        ]);

        $role->update([
            'name'       => $validated['name'],
            'guard_name' => $validated['guard_name'],
        ]);

        $permissions = !empty($validated['permissions'])
            ? Permission::whereIn('id', $validated['permissions'])->get()
            : [];

        $role->syncPermissions($permissions);

        $jumlahPermission = count($validated['permissions'] ?? []);

        return redirect()->route('roles.index')
            ->with('success', "Role berhasil diperbarui dengan {$jumlahPermission} hak akses.");
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'super-admin') {
            abort(403);
        }

        try {
            $role->delete();

            return redirect()->route('roles.index')
                ->with('success', 'Role berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')
                ->with('error', 'Gagal menghapus role: ' . $e->getMessage());
        }
    }
}
