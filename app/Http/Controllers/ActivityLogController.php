<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-activity-logs', ['only' => 'index']);
        $this->middleware('permission:delete-activity-logs', ['only' => ['destroy', 'destroyAll']]);
    }

    public function index(Request $request)
    {
        $logs = Activity::with('causer')
            ->when($request->search, function ($query) use ($request) {
                $query->where('description', 'like', '%' . $request->search . '%');
            })
            ->when($request->source, function ($query) use ($request) {
                $query->where('properties->source', $request->source);
            })
            ->when($request->date, function ($query) use ($request) {
                $query->whereDate('created_at', $request->date);
            })
            ->latest()
            ->paginate(20);

        return view('pages.activity-logs.index', compact('logs'));
    }

    public function destroy($id)
    {
        Activity::findOrFail($id)->delete();

        return redirect()->back()
            ->with('success', 'Log aktivitas berhasil dihapus.');
    }

    public function destroyAll()
    {
        Activity::truncate();

        return redirect()->back()
            ->with('success', 'Semua log aktivitas berhasil dihapus.');
    }
}
