<?php

namespace Database\Seeders;

use App\Models\Register;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Utama',
            'username' => 'admin1',
            'email' => 'admin@sanqua.test',
            'password' => 'password', // otomatis di-hash lewat cast 'hashed' di model User
            'role' => 'admin',
        ]);

        $owner = User::create([
            'name' => 'Owner Utama',
            'username' => 'owner1',
            'email' => 'owner@sanqua.test',
            'password' => 'password',
            'role' => 'owner',
        ]);

        $kasir = User::create([
            'name' => 'Kasir 1',
            'username' => 'kasir1',
            'email' => 'kasir@sanqua.test',
            'password' => 'password',
            'role' => 'kasir',
        ]);

        // Catat setiap akun seed ke tabel Register, supaya fitur
        // aktif/nonaktif (toggleStatus) langsung berfungsi tanpa perlu
        // di-backfill manual lewat Tinker setiap kali migrate:fresh --seed.
        foreach ([$admin, $owner, $kasir] as $user) {
            Register::create([
                'user_id' => $user->id,
                'dibuat_oleh' => $user->id, // akun awal, dianggap "dibuat sendiri" saat seeding
                'status' => 'aktif',
            ]);
        }
    }
}