<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user', 'staff'])->orderByDesc('logged_at');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('description', 'like', $s)
                  ->orWhere('ip_address', 'like', $s);
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('logged_at', $request->date);
        }

        $logs  = $query->paginate(30)->withQueryString();
        $types = ActivityLog::distinct()->orderBy('type')->pluck('type');

        return view('admin.logs.index', compact('logs', 'types'));
    }
}
