<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $roles = ['admin', 'kasir'];

        return view('admin.roles.index', compact('users', 'roles'));
    }

    /**
     * Form tambah user baru dengan role.
     */
    public function create()
    {
        $roles = ['admin', 'kasir'];
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
            'role' => 'required|in:admin,kasir',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'],
            'avatar' => $avatarPath,
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
        $roles = ['admin', 'kasir'];
        $user = $role;
        return view('admin.roles.edit', compact('user', 'roles'));
    }

    /**
     * Update data user & role.
     */
    public function update(Request $request, User $role)
    {
        $user = $role;

        // Jika datang dari form edit penuh (name/email/password ada), jalankan validasi lengkap
        if ($request->has('name') || $request->has('email')) {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:6',
                'role' => 'required|in:admin,kasir',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->role = $data['role'];

            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $user->avatar = $request->file('avatar')->store('avatars', 'public');
            }

            if (!empty($data['password'])) {
                $user->password = bcrypt($data['password']);
            }
            $user->save();

            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Data pengguna berhasil diperbarui.');
        }

        // Jika datang dari tombol "Simpan" cepat di halaman index (hanya field role),
        // cukup validasi dan update role saja.
        $data = $request->validate([
            'role' => 'required|in:admin,kasir',
        ]);

        $user->role = $data['role'];
        $user->save();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role pengguna berhasil diperbarui.');
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
