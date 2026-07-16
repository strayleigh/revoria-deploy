<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE transaksi_keuangan MODIFY kode_kegiatan BIGINT UNSIGNED NULL');

        DB::statement('ALTER TABLE folder DROP FOREIGN KEY fk_folder_kegiatan');
        DB::statement('ALTER TABLE folder MODIFY kode_kegiatan BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE transaksi_keuangan MODIFY kode_kegiatan BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE folder MODIFY kode_kegiatan BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE folder ADD CONSTRAINT fk_folder_kegiatan FOREIGN KEY (kode_kegiatan) REFERENCES kegiatan(kode_kegiatan) ON DELETE CASCADE');
    }
};
