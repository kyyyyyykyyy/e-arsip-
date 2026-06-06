<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog; // <-- IMPORT MODEL CCTV
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    private function checkSuperAdmin()
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Akses Ditolak! Hanya Super Admin yang boleh mengakses halaman ini.');
        }
    }

    public function index(Request $request)
    {
        $this->checkSuperAdmin();

        $query = User::latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        return Inertia::render('Users/Index', [
            'users' => $query->get(),
            'filters' => $request->only(['search'])
        ]);
    }

    public function store(Request $request)
    {
        $this->checkSuperAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Rules\Password::defaults()],
            'role' => 'required|in:superadmin,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // REKAM KE CCTV
        ActivityLog::catat('Tambah Akun', 'Membuat akun pegawai baru atas nama: ' . $request->name);

        return redirect()->back()->with('message', 'Akun pegawai berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $this->checkSuperAdmin();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:superadmin,user',
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', Rules\Password::defaults()];
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // REKAM KE CCTV
        ActivityLog::catat('Edit Akun', 'Memperbarui data akun milik: ' . $request->name);

        return redirect()->back()->with('message', 'Data akun berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        $this->checkSuperAdmin();

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('message', 'Error: Kamu tidak bisa menghapus akunmu sendiri saat sedang login!');
        }

        $namaPegawai = $user->name; // Simpan nama dulu sebelum dihapus
        $user->delete();

        // REKAM KE CCTV
        ActivityLog::catat('Hapus Akun', 'Menghapus permanen akun pegawai: ' . $namaPegawai);

        return redirect()->back()->with('message', 'Akun berhasil dihapus!');
    }
}