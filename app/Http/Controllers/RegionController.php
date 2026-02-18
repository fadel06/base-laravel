<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-regions')->only(['index', 'show', 'getParents', 'getByLevel']);
        $this->middleware('permission:create-regions')->only(['store']);
        $this->middleware('permission:edit-regions')->only(['edit', 'update']);
        $this->middleware('permission:delete-regions')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Region::with('parent');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        $query->orderBy('level', 'asc')
            ->orderByRaw('LENGTH(code) asc')
            ->orderBy('code', 'asc');

        $regions = $query->paginate(15)->appends($request->all());

        return view('pages.regions.index', compact('regions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tidak digunakan karena menggunakan modal
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'level'     => 'required|integer|min:1|max:4',
            'parent_id' => 'nullable|exists:regions,id',
            'code'      => 'required|string|max:255|unique:regions,code',
            'name'      => 'required|string|max:255',
        ], [
            'level.required'     => 'Level wilayah wajib diisi.',
            'level.integer'      => 'Level wilayah harus berupa angka.',
            'level.min'          => 'Level wilayah tidak valid.',
            'level.max'          => 'Level wilayah tidak valid.',
            'parent_id.exists'   => 'Wilayah induk yang dipilih tidak valid.',
            'code.required'      => 'Kode wilayah wajib diisi.',
            'code.unique'        => 'Kode wilayah sudah digunakan.',
            'code.max'           => 'Kode wilayah tidak boleh lebih dari 255 karakter.',
            'name.required'      => 'Nama wilayah wajib diisi.',
            'name.max'           => 'Nama wilayah tidak boleh lebih dari 255 karakter.',
        ]);

        // Provinsi (level 1) tidak boleh memiliki induk
        if ($validated['level'] == 1 && !empty($validated['parent_id'])) {
            return back()->withErrors([
                'parent_id' => 'Provinsi (Level 1) tidak boleh memiliki wilayah induk.'
            ])->withInput();
        }

        // Validasi level induk harus satu level di atas
        if (!empty($validated['parent_id'])) {
            $parent = Region::find($validated['parent_id']);
            if ($parent && $parent->level >= $validated['level']) {
                return back()->withErrors([
                    'parent_id' => 'Wilayah induk harus berada satu level di atas wilayah ini.'
                ])->withInput();
            }
        }

        Region::create([
            'level'     => $validated['level'],
            'parent_id' => $validated['parent_id'] ?? null,
            'code'      => $validated['code'],
            'name'      => $validated['name'],
        ]);

        return redirect()->route('regions.index')
            ->with('success', 'Wilayah berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region)
    {
        $region->load('children');
        return view('pages.regions.show', compact('region'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region)
    {
        return response()->json([
            'id'        => $region->id,
            'level'     => $region->level,
            'parent_id' => $region->parent_id,
            'code'      => $region->code,
            'name'      => $region->name,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Region $region)
    {
        $validated = $request->validate([
            'level'     => 'required|integer|min:1|max:4',
            'parent_id' => 'nullable|exists:regions,id',
            'code'      => [
                'required',
                'string',
                'max:255',
                Rule::unique('regions', 'code')->ignore($region->id),
            ],
            'name'      => 'required|string|max:255',
        ], [
            'level.required'     => 'Level wilayah wajib diisi.',
            'level.integer'      => 'Level wilayah harus berupa angka.',
            'level.min'          => 'Level wilayah tidak valid.',
            'level.max'          => 'Level wilayah tidak valid.',
            'parent_id.exists'   => 'Wilayah induk yang dipilih tidak valid.',
            'code.required'      => 'Kode wilayah wajib diisi.',
            'code.unique'        => 'Kode wilayah sudah digunakan.',
            'code.max'           => 'Kode wilayah tidak boleh lebih dari 255 karakter.',
            'name.required'      => 'Nama wilayah wajib diisi.',
            'name.max'           => 'Nama wilayah tidak boleh lebih dari 255 karakter.',
        ]);

        // Tidak bisa menjadi induk dirinya sendiri
        if (!empty($validated['parent_id']) && $validated['parent_id'] == $region->id) {
            return back()->withErrors([
                'parent_id' => 'Wilayah tidak bisa menjadi induk dirinya sendiri.'
            ])->withInput();
        }

        // Provinsi (level 1) tidak boleh memiliki induk
        if ($validated['level'] == 1 && !empty($validated['parent_id'])) {
            return back()->withErrors([
                'parent_id' => 'Provinsi (Level 1) tidak boleh memiliki wilayah induk.'
            ])->withInput();
        }

        // Validasi level induk harus satu level di atas
        if (!empty($validated['parent_id'])) {
            $parent = Region::find($validated['parent_id']);
            if ($parent && $parent->level >= $validated['level']) {
                return back()->withErrors([
                    'parent_id' => 'Wilayah induk harus berada satu level di atas wilayah ini.'
                ])->withInput();
            }
        }

        $region->update([
            'level'     => $validated['level'],
            'parent_id' => $validated['parent_id'] ?? null,
            'code'      => $validated['code'],
            'name'      => $validated['name'],
        ]);

        return redirect()->route('regions.index')
            ->with('success', 'Wilayah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region)
    {
        if ($region->children()->count() > 0) {
            return back()->withErrors([
                'delete' => 'Tidak dapat menghapus wilayah yang memiliki wilayah anak. Hapus semua wilayah anak terlebih dahulu.'
            ]);
        }

        $region->delete();

        return redirect()->route('regions.index')
            ->with('success', 'Wilayah berhasil dihapus.');
    }

    /**
     * Get parent regions for dropdown (AJAX)
     */
    public function getParents(Request $request)
    {
        $level = $request->input('level');

        if (!$level || $level == 1) {
            return response()->json([]);
        }

        $parentLevel = $level - 1;

        $parents = Region::where('level', $parentLevel)
            ->orderByRaw('LENGTH(code) asc')
            ->orderBy('code', 'asc')
            ->get(['id', 'name', 'code']);

        return response()->json($parents);
    }

    public function getByLevel(Request $request, $level)
    {
        $regions = Region::where('level', $level)
            ->orderByRaw('LENGTH(code) asc')
            ->orderBy('code', 'asc')
            ->get(['id', 'name', 'code']);

        return response()->json($regions);
    }
}
