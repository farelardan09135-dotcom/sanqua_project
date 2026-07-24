<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Mengubah kolom role dari 2 aktor (admin, kasir) jadi 3 aktor
 * (owner, admin, kasir). Karena ini perubahan tipe ENUM, kita
 * pakai raw SQL (bukan Schema::table biasa) karena Laravel
 * butuh package tambahan (doctrine/dbal) buat modify() kolom ENUM.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('owner', 'admin', 'kasir') NOT NULL DEFAULT 'kasir'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'kasir') NOT NULL DEFAULT 'kasir'");
    }
};