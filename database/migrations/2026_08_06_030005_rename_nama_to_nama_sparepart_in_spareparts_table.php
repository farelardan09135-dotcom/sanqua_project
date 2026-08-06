<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('spareparts', function (Blueprint $table) {
            $table->renameColumn('nama', 'nama_sparepart');
        });
    }

    public function down(): void
    {
        Schema::table('spareparts', function (Blueprint $table) {
            $table->renameColumn('nama_sparepart', 'nama');
        });
    }
};
