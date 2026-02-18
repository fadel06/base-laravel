<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-permissions')->only(['index', 'show']);
        $this->middleware('permission:create-permissions')->only(['create', 'store']);
        $this->middleware('permission:edit-permissions')->only(['edit', 'update']);
        $this->middleware('permission:delete-permissions')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Permission::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        $permissions = $query->orderBy('name', 'asc')->paginate(15)->withQueryString();

        return view('pages.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255|unique:permissions,name',
            'guard_name' => 'required|string|max:255',
        ], [
            'name.required'       => 'Nama hak akses wajib diisi.',
            'name.unique'         => 'Nama hak akses sudah digunakan.',
            'name.max'            => 'Nama hak akses tidak boleh lebih dari 255 karakter.',
            'guard_name.required' => 'Guard name wajib diisi.',
            'guard_name.max'      => 'Guard name tidak boleh lebih dari 255 karakter.',
        ]);

        Permission::create($request->only('name', 'guard_name'));

        return redirect()->route('permissions.index')
            ->with('success', 'Hak akses berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        return view('pages.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return view('pages.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name'       => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'guard_name' => 'required|string|max:255',
        ], [
            'name.required'       => 'Nama hak akses wajib diisi.',
            'name.unique'         => 'Nama hak akses sudah digunakan.',
            'name.max'            => 'Nama hak akses tidak boleh lebih dari 255 karakter.',
            'guard_name.required' => 'Guard name wajib diisi.',
            'guard_name.max'      => 'Guard name tidak boleh lebih dari 255 karakter.',
        ]);

        $permission->update($request->only('name', 'guard_name'));

        return redirect()->route('permissions.index')
            ->with('success', 'Hak akses berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        try {
            $permission->delete();

            return redirect()->route('permissions.index')
                ->with('success', 'Hak akses berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('permissions.index')
                ->with('error', 'Gagal menghapus hak akses: ' . $e->getMessage());
        }
    }
}
