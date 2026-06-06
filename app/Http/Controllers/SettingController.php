<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Akses Ditolak!');
        }

        // Ambil data setting, kalau belum ada sama sekali di database, bikin baru otomatis
        $setting = Setting::first() ?? Setting::create();

        return Inertia::render('Pengaturan/Index', [
            'setting' => $setting
        ]);
    }

    public function update(Request $request)
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Akses Ditolak!');
        }

        $request->validate([
            'app_name' => 'required|string|max:255',
            'instansi_name' => 'required|string|max:255',
            'head_office' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $setting = Setting::first();
        $setting->update($request->all());

        // REKAM KE CCTV
        ActivityLog::catat('Ubah Pengaturan', 'Memperbarui data identitas sistem dan instansi.');

        return redirect()->back()->with('message', 'Pengaturan sistem berhasil disimpan!');
    }
}