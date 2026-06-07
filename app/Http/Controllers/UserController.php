<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // Fungsi pengecekan akses (Diubah dari superadmin ke admin)
    private function checkAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak! Hanya Admin yang boleh mengakses halaman ini.');
        }
    }

    public function index(Request $request)
    {
        $this->checkAdmin();

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
        $this->checkAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Rules\Password::defaults()],
            'role' => 'required|in:admin,viewer', // Validasi ke admin/viewer
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        ActivityLog::catat('Tambah Akun', 'Membuat akun pegawai baru atas nama: ' . $request->name);

        return redirect()->back()->with('message', 'Akun pegawai berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $this->checkAdmin();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,viewer', // Validasi ke admin/viewer
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

        ActivityLog::catat('Edit Akun', 'Memperbarui data akun milik: ' . $request->name);

        return redirect()->back()->with('message', 'Data akun berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        $this->checkAdmin();

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('message', 'Error: Kamu tidak bisa menghapus akunmu sendiri saat sedang login!');
        }

        $namaPegawai = $user->name;
        $user->delete();

        ActivityLog::catat('Hapus Akun', 'Menghapus permanen akun pegawai: ' . $namaPegawai);

        return redirect()->back()->with('message', 'Akun berhasil dihapus!');
    }
}