<?php

namespace App\Http\Controllers;

use App\Models\BidangDinas;
use App\Models\Opd;
use Illuminate\Http\Request;

class BidangDinasController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-bidang-dinas')->only(['index', 'getParents']);
        $this->middleware('permission:create-bidang-dinas')->only(['store']);
        $this->middleware('permission:edit-bidang-dinas')->only(['edit', 'update']);
        $this->middleware('permission:delete-bidang-dinas')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BidangDinas::with(['opd', 'parent']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('abbreviation', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('opd_id')) {
            $query->where('opd_id', $request->opd_id);
        }

        $query->orderBy('level', 'asc')->orderBy('name', 'asc');

        $bidangDinas = $query->paginate(15)->appends($request->all());
        $opds        = Opd::orderBy('code')->get(['id', 'name', 'code']);

        return view('pages.bidang-dinas.index', compact('bidangDinas', 'opds'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'opd_id'       => 'required|exists:opds,id',
            'level'        => 'required|integer|in:1,2',
            'parent_id'    => 'nullable|exists:bidang_dinas,id',
            'name'         => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:50',
        ], [
            'opd_id.required'    => 'OPD wajib dipilih.',
            'opd_id.exists'      => 'OPD yang dipilih tidak valid.',
            'level.required'     => 'Level wajib dipilih.',
            'level.in'           => 'Level tidak valid.',
            'parent_id.exists'   => 'Bidang induk yang dipilih tidak valid.',
            'name.required'      => 'Nama bidang wajib diisi.',
            'name.max'           => 'Nama bidang tidak boleh lebih dari 255 karakter.',
            'abbreviation.max'   => 'Singkatan tidak boleh lebih dari 50 karakter.',
        ]);

        // Bidang (level 1) tidak boleh punya parent
        if ($validated['level'] == 1 && !empty($validated['parent_id'])) {
            return back()->withErrors([
                'parent_id' => 'Bidang (Level 1) tidak boleh memiliki induk.'
            ])->withInput();
        }

        // Sub Bidang (level 2) harus punya parent
        if ($validated['level'] == 2 && empty($validated['parent_id'])) {
            return back()->withErrors([
                'parent_id' => 'Sub Bidang / Sub Bagian (Level 2) harus memiliki Bidang induk.'
            ])->withInput();
        }

        // Validasi parent harus level 1
        if (!empty($validated['parent_id'])) {
            $parent = BidangDinas::find($validated['parent_id']);
            if ($parent && $parent->level >= $validated['level']) {
                return back()->withErrors([
                    'parent_id' => 'Induk harus berupa Bidang (Level 1).'
                ])->withInput();
            }
        }

        BidangDinas::create($validated);

        return redirect()->route('bidang-dinas.index')
            ->with('success', 'Bidang dinas berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource (AJAX).
     */
    public function edit(BidangDinas $bidangDinas)
    {
        return response()->json([
            'id'           => $bidangDinas->id,
            'opd_id'       => $bidangDinas->opd_id,
            'level'        => $bidangDinas->level,
            'parent_id'    => $bidangDinas->parent_id,
            'name'         => $bidangDinas->name,
            'abbreviation' => $bidangDinas->abbreviation,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BidangDinas $bidangDinas)
    {
        $validated = $request->validate([
            'opd_id'       => 'required|exists:opds,id',
            'level'        => 'required|integer|in:1,2',
            'parent_id'    => 'nullable|exists:bidang_dinas,id',
            'name'         => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:50',
        ], [
            'opd_id.required'    => 'OPD wajib dipilih.',
            'opd_id.exists'      => 'OPD yang dipilih tidak valid.',
            'level.required'     => 'Level wajib dipilih.',
            'level.in'           => 'Level tidak valid.',
            'parent_id.exists'   => 'Bidang induk yang dipilih tidak valid.',
            'name.required'      => 'Nama bidang wajib diisi.',
            'name.max'           => 'Nama bidang tidak boleh lebih dari 255 karakter.',
            'abbreviation.max'   => 'Singkatan tidak boleh lebih dari 50 karakter.',
        ]);

        // Tidak bisa menjadi induk dirinya sendiri
        if (!empty($validated['parent_id']) && $validated['parent_id'] === $bidangDinas->id) {
            return back()->withErrors([
                'parent_id' => 'Bidang tidak bisa menjadi induk dirinya sendiri.'
            ])->withInput();
        }

        // Bidang (level 1) tidak boleh punya parent
        if ($validated['level'] == 1 && !empty($validated['parent_id'])) {
            return back()->withErrors([
                'parent_id' => 'Bidang (Level 1) tidak boleh memiliki induk.'
            ])->withInput();
        }

        // Sub Bidang (level 2) harus punya parent
        if ($validated['level'] == 2 && empty($validated['parent_id'])) {
            return back()->withErrors([
                'parent_id' => 'Sub Bidang / Sub Bagian (Level 2) harus memiliki Bidang induk.'
            ])->withInput();
        }

        // Validasi parent harus level 1
        if (!empty($validated['parent_id'])) {
            $parent = BidangDinas::find($validated['parent_id']);
            if ($parent && $parent->level >= $validated['level']) {
                return back()->withErrors([
                    'parent_id' => 'Induk harus berupa Bidang (Level 1).'
                ])->withInput();
            }
        }

        $bidangDinas->update($validated);

        return redirect()->route('bidang-dinas.index')
            ->with('success', 'Bidang dinas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BidangDinas $bidangDinas)
    {
        if ($bidangDinas->children()->count() > 0) {
            return back()->withErrors([
                'delete' => 'Tidak dapat menghapus bidang yang memiliki sub bidang. Hapus semua sub bidang terlebih dahulu.'
            ]);
        }

        $bidangDinas->delete();

        return redirect()->route('bidang-dinas.index')
            ->with('success', 'Bidang dinas berhasil dihapus.');
    }

    /**
     * Get parent bidang for dropdown (AJAX)
     */
    public function getParents(Request $request)
    {
        $opdId = $request->input('opd_id');

        if (!$opdId) {
            return response()->json([]);
        }

        $parents = BidangDinas::where('opd_id', $opdId)
            ->where('level', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'abbreviation']);

        return response()->json($parents);
    }
}
