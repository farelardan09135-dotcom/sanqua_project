<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model untuk tabel `users` — akun Admin maupun Kasir.
 * Dibedakan lewat kolom `role` (ENUM: 'admin' atau 'kasir').
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi lewat mass assignment.
     * 'role' wajib ada di sini, kalau tidak, User::create([...'role' => 'admin'])
     * akan diam-diam mengabaikan nilai role yang dikirim.
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
    ];

    /**
     * Kolom yang disembunyikan saat model di-serialize ke JSON/array
     * (mis. saat dikirim ke frontend), demi keamanan.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data otomatis:
     * - email_verified_at -> jadi object Carbon (datetime), bukan string
     * - password          -> otomatis di-hash setiap kali di-set/create,
     *                        jadi TIDAK PERLU bcrypt() manual di seeder/controller
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function registers()
    {
        return $this->hasMany(Register::class, 'user_id');
    }
    /**
     * Helper: cek apakah user ini punya role admin.
     * Berguna untuk kondisi di Blade: @if(auth()->user()->isAdmin())
     */
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Helper: cek apakah user ini punya role kasir.
     */
    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }

    /**
     * Relasi one-to-many: satu user (kasir) bisa membuat banyak transaksi.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}