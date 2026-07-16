<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('pengurus','anggota','pembina') NOT NULL DEFAULT 'anggota'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('pengurus','anggota') NOT NULL DEFAULT 'anggota'");
    }
};
