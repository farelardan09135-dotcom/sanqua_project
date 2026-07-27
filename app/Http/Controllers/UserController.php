<?php

namespace App\Http\Controllers;

use App\Models\Register;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, fn ($q, $search) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.user', compact('users'));
    }

    /**
     * Admin mendaftarkan akun baru (Admin/Kasir).
     * Setiap pendaftaran dicatat ke tabel Register (siapa yang membuat).
     */
    public function store(Request $request)
    {
       $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,kasir',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
        ]);
        Register::create([
            'user_id' => $user->id,
            'dibuat_oleh' => auth()->id(),
            'status' => 'aktif',
        ]);

        return redirect()->route('admin.user')->with('status', 'Akun berhasil didaftarkan.');
    }

    /**
     * Toggle status aktif/nonaktif akun (bukan hapus permanen).
     */
    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menonaktifkan akun sendiri.');
        }

        $register = Register::where('user_id', $user->id)->latest()->first();

        if ($register) {
            $register->update([
                'status' => $register->status === 'aktif' ? 'nonaktif' : 'aktif',
            ]);
        } else {
            Register::create([
                'user_id' => $user->id,
                'dibuat_oleh' => auth()->id(),
                'status' => 'nonaktif',
            ]);
        }

        return redirect()->route('admin.user')->with('status', 'Status akun berhasil diperbarui.');
    }
}