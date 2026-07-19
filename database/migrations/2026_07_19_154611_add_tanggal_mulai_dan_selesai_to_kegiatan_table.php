<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            if (!Schema::hasColumn('kegiatan', 'tanggal_mulai')) {
                $table->dateTime('tanggal_mulai')->nullable()->after('nama_kegiatan');
            }
            if (!Schema::hasColumn('kegiatan', 'tanggal_selesai')) {
                $table->dateTime('tanggal_selesai')->nullable()->after('tanggal_mulai');
            }
        });

        // Copy old data from tanggal to tanggal_mulai and tanggal_selesai
        if (Schema::hasColumn('kegiatan', 'tanggal')) {
            DB::table('kegiatan')->update([
                'tanggal_mulai' => DB::raw('tanggal'),
                'tanggal_selesai' => DB::raw('tanggal'),
            ]);

            Schema::table('kegiatan', function (Blueprint $table) {
                $table->dropColumn('tanggal');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            if (!Schema::hasColumn('kegiatan', 'tanggal')) {
                $table->date('tanggal')->nullable()->after('nama_kegiatan');
            }
        });

        // Copy back
        DB::table('kegiatan')->update([
            'tanggal' => DB::raw('tanggal_mulai'),
        ]);

        Schema::table('kegiatan', function (Blueprint $table) {
            $table->dropColumn(['tanggal_mulai', 'tanggal_selesai']);
        });
    }
};
