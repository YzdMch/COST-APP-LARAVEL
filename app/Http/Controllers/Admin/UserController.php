<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('cabang');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderByDesc('created_at')->paginate(20);
        $cabangList = Cabang::where('is_active', true)->get();

        return view('admin.users.index', compact('users', 'cabangList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'no_telepon' => 'nullable|string|max:20',
            'password'   => 'required|string|min:6',
            'role'       => 'required|in:pelanggan,teknisi,admin',
            'cabang_id'  => 'nullable|exists:cabang,id',
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        ActivityLogger::log(
            'create', "User baru dibuat oleh admin: {$user->name} ({$user->role})",
            'User', $user->id, null, [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'cabang_id' => $user->cabang_id,
            ]
        );

        return redirect()->route('admin.users.index')
            ->with('pesan', "User {$user->name} berhasil dibuat.");
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'no_telepon' => 'nullable|string|max:20',
            'role'       => 'required|in:pelanggan,teknisi,admin',
            'cabang_id'  => 'nullable|exists:cabang,id',
        ]);

        $oldData = $user->only(['name', 'email', 'role', 'cabang_id']);

        // Log role change separately if changed
        if ($user->role !== $data['role']) {
            ActivityLogger::log(
                'role_change',
                "Role user {$user->name} diubah: {$user->role} → {$data['role']}",
                'User', $user->id,
                ['role' => $user->role],
                ['role' => $data['role']]
            );
        }

        $user->update($data);

        ActivityLogger::log(
            'update', "Data user {$user->name} diperbarui",
            'User', $user->id, $oldData, $data
        );

        return redirect()->route('admin.users.index')
            ->with('pesan', "Data user {$user->name} berhasil diperbarui.");
    }

    public function toggleActive(User $user)
    {
        // Prevent self-deactivation
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Tidak bisa menonaktifkan akun sendiri.']);
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLogger::log(
            'toggle_active',
            "Akun {$user->name} {$status}",
            'User', $user->id,
            ['is_active' => !$user->is_active],
            ['is_active' => $user->is_active]
        );

        return redirect()->route('admin.users.index')
            ->with('pesan', "Akun {$user->name} berhasil {$status}.");
    }

    public function resetPassword(User $user)
    {
        $newPassword = '123456'; // Default reset password
        $user->update(['password' => Hash::make($newPassword)]);

        ActivityLogger::log(
            'reset_password',
            "Password user {$user->name} di-reset oleh admin",
            'User', $user->id
        );

        return redirect()->route('admin.users.index')
            ->with('pesan', "Password {$user->name} berhasil di-reset ke default (123456).");
    }
}
