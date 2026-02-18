<?php

namespace App\Http\Controllers;

use App\Models\Opd;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OpdController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-opds')->only(['index', 'show', 'getParents']);
        $this->middleware('permission:create-opds')->only(['store']);
        $this->middleware('permission:edit-opds')->only(['edit', 'update']);
        $this->middleware('permission:delete-opds')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Opd::with('parent');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%')
                    ->orWhere('head_name', 'like', '%' . $search . '%');
            });
        }

        // Filter by level
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Order by level, code length, then code
        $query->orderBy('level', 'asc')
            ->orderByRaw('LENGTH(code) asc')
            ->orderBy('code', 'asc');

        // Paginate
        $opds = $query->paginate(15)->appends($request->all());

        return view('pages.opds.index', compact('opds'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'level' => 'required|integer|min:1|max:2',
            'parent_id' => 'nullable|exists:opds,id',
            'code' => 'required|string|max:255|unique:opds,code',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'head_name' => 'nullable|string|max:255',
            'head_nip' => 'nullable|string|max:50',
        ], [
            'level.required' => 'Level OPD wajib diisi.',
            'level.integer' => 'Level OPD harus berupa angka.',
            'level.min' => 'Level OPD tidak valid.',
            'level.max' => 'Level OPD tidak valid.',
            'parent_id.exists' => 'OPD induk yang dipilih tidak valid.',
            'code.required' => 'Kode OPD wajib diisi.',
            'code.unique' => 'Kode OPD sudah digunakan.',
            'code.max' => 'Kode OPD tidak boleh lebih dari 255 karakter.',
            'name.required' => 'Nama OPD wajib diisi.',
            'name.max' => 'Nama OPD tidak boleh lebih dari 255 karakter.',
            'phone.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'head_name.max' => 'Nama kepala OPD tidak boleh lebih dari 255 karakter.',
            'head_nip.max' => 'NIP tidak boleh lebih dari 50 karakter.',
        ]);

        // Validate: Dinas (level 1) should not have parent
        if ($validated['level'] == 1 && !empty($validated['parent_id'])) {
            return back()->withErrors([
                'parent_id' => 'Dinas (Level 1) tidak boleh memiliki induk.'
            ])->withInput();
        }

        // Validate: UPTD (level 2) must have parent
        if ($validated['level'] == 2 && empty($validated['parent_id'])) {
            return back()->withErrors([
                'parent_id' => 'UPTD (Level 2) harus memiliki Dinas induk.'
            ])->withInput();
        }

        // Validate parent level
        if (!empty($validated['parent_id'])) {
            $parent = Opd::find($validated['parent_id']);
            if ($parent && $parent->level >= $validated['level']) {
                return back()->withErrors([
                    'parent_id' => 'OPD induk harus berupa Dinas (Level 1).'
                ])->withInput();
            }
        }

        Opd::create($validated);

        return redirect()->route('opds.index')
            ->with('success', 'OPD berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Opd $opd)
    {
        $opd->load('children');
        return view('pages.opds.show', compact('opd'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Opd $opd)
    {
        return response()->json([
            'id' => $opd->id,
            'level' => $opd->level,
            'parent_id' => $opd->parent_id,
            'code' => $opd->code,
            'name' => $opd->name,
            'address' => $opd->address,
            'phone' => $opd->phone,
            'email' => $opd->email,
            'head_name' => $opd->head_name,
            'head_nip' => $opd->head_nip,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Opd $opd)
    {
        $validated = $request->validate([
            'level' => 'required|integer|min:1|max:2',
            'parent_id' => 'nullable|exists:opds,id',
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('opds', 'code')->ignore($opd->id),
            ],
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'head_name' => 'nullable|string|max:255',
            'head_nip' => 'nullable|string|max:50',
        ], [
            'level.required' => 'Level OPD wajib diisi.',
            'level.integer' => 'Level OPD harus berupa angka.',
            'level.min' => 'Level OPD tidak valid.',
            'level.max' => 'Level OPD tidak valid.',
            'parent_id.exists' => 'OPD induk yang dipilih tidak valid.',
            'code.required' => 'Kode OPD wajib diisi.',
            'code.unique' => 'Kode OPD sudah digunakan.',
            'code.max' => 'Kode OPD tidak boleh lebih dari 255 karakter.',
            'name.required' => 'Nama OPD wajib diisi.',
            'name.max' => 'Nama OPD tidak boleh lebih dari 255 karakter.',
            'phone.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'head_name.max' => 'Nama kepala OPD tidak boleh lebih dari 255 karakter.',
            'head_nip.max' => 'NIP tidak boleh lebih dari 50 karakter.',
        ]);

        // Validate: cannot set itself as parent
        if (!empty($validated['parent_id']) && $validated['parent_id'] == $opd->id) {
            return back()->withErrors([
                'parent_id' => 'OPD tidak bisa menjadi induk dirinya sendiri.'
            ])->withInput();
        }

        // Validate: Dinas should not have parent
        if ($validated['level'] == 1 && !empty($validated['parent_id'])) {
            return back()->withErrors([
                'parent_id' => 'Dinas (Level 1) tidak boleh memiliki induk.'
            ])->withInput();
        }

        // Validate: UPTD must have parent
        if ($validated['level'] == 2 && empty($validated['parent_id'])) {
            return back()->withErrors([
                'parent_id' => 'UPTD (Level 2) harus memiliki Dinas induk.'
            ])->withInput();
        }

        // Validate parent level
        if (!empty($validated['parent_id'])) {
            $parent = Opd::find($validated['parent_id']);
            if ($parent && $parent->level >= $validated['level']) {
                return back()->withErrors([
                    'parent_id' => 'OPD induk harus berupa Dinas (Level 1).'
                ])->withInput();
            }
        }

        $opd->update($validated);

        return redirect()->route('opds.index')
            ->with('success', 'OPD berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Opd $opd)
    {
        // Check if OPD has children
        if ($opd->children()->count() > 0) {
            return back()->withErrors([
                'delete' => 'Tidak dapat menghapus OPD yang memiliki UPTD anak. Hapus semua UPTD anak terlebih dahulu.'
            ]);
        }

        $opd->delete();

        return redirect()->route('opds.index')
            ->with('success', 'OPD berhasil dihapus.');
    }

    /**
     * Get parent OPDs for dropdown (AJAX)
     */
    public function getParents(Request $request)
    {
        $level = $request->input('level');

        if (!$level || $level == 1) {
            return response()->json([]);
        }

        // For UPTD (level 2), get all Dinas (level 1)
        $parents = Opd::where('level', 1)
            ->orderByRaw('LENGTH(code) asc')
            ->orderBy('code', 'asc')
            ->get(['id', 'name', 'code']);

        return response()->json($parents);
    }
}
