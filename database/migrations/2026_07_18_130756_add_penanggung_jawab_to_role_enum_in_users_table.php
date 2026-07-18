<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE users MODIFY role ENUM('pengurus', 'anggota', 'pembina', 'penanggung jawab') NOT NULL DEFAULT 'anggota'");
    }

    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE users MODIFY role ENUM('pengurus', 'anggota', 'pembina') NOT NULL DEFAULT 'anggota'");
    }
};
