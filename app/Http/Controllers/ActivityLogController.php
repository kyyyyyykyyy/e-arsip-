<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Kunci pintu: Cuma Admin yang boleh lihat CCTV ini! (diperbaiki dari 'superadmin' → 'admin')
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak!');
        }

        // Ambil data log terbaru beserta nama pelakunya
        $logs = ActivityLog::with('user')->latest()->get();

        return Inertia::render('LogAktivitas/Index', [
            'logs' => $logs
        ]);
    }
}   