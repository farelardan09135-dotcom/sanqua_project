<?php

namespace App\Http\Middleware;

use App\Models\Register;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== $role) {
            abort(403, 'Akses ditolak.');
        }

        // Cek status akun (kecuali owner, yang tidak melalui proses Register)
        $register = Register::where('user_id', $user->id)->latest()->first();
        if ($register && $register->status === 'nonaktif') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors(['email' => 'Akun Anda telah dinonaktifkan.']);
        }

        return $next($request);
    }
}