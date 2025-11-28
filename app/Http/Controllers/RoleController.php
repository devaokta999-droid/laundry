<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user || $user->role !== 'admin') {
                abort(403, 'Akses ditolak - hanya admin yang dapat mengelola role.');
            }
            return $next($request);
        });
    }

    /**
     * Tampilkan daftar user dan rolenya.
     */
    public function index()
    {
        $users = User::orderBy('name')->get();
        $roles = ['admin', 'kasir', 'karyawan'];

        return view('admin.roles.index', compact('users', 'roles'));
    }

    /**
     * Form tambah user baru dengan role.
     */
    public function create()
    {
        $roles = ['admin', 'kasir', 'karyawan'];
        return view('admin.roles.create', compact('roles'));
    }

    /**
     * Simpan user baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,kasir,karyawan',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'],
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'User baru berhasil dibuat.');
    }

    /**
     * Form edit user + role.
     */
    public function edit(User $role)
    {
        $roles = ['admin', 'kasir', 'karyawan'];
        $user = $role;
        return view('admin.roles.edit', compact('user', 'roles'));
    }

    /**
     * Update data user & role.
     */
    public function update(Request $request, User $role)
    {
        $user = $role;

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,kasir,karyawan',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }
        $user->save();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Hapus user.
     */
    public function destroy(User $role)
    {
        $user = $role;

        // Jangan izinkan hapus dirinya sendiri
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('admin.roles.index')
                ->with('error', 'Tidak bisa menghapus akun yang sedang digunakan.');
        }

        $user->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
