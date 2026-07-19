<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan';
    protected $primaryKey = 'kode_kegiatan';
    public $timestamps = false;

    public function getRouteKeyName(): string { return 'kode_kegiatan'; }

    protected $fillable = [
        'nama_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'lokasi', 'deskripsi', 'status', 'progres', 'persiapan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'persiapan' => 'array',
    ];

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'kode_kegiatan', 'kode_kegiatan');
    }

    public function transaksi()
    {
        return $this->hasMany(TransaksiKeuangan::class, 'kode_kegiatan', 'kode_kegiatan');
    }

    public function folder()
    {
        return $this->hasMany(Folder::class, 'kode_kegiatan', 'kode_kegiatan');
    }

    public function panitia()
    {
        return $this->hasMany(Kepanitiaan::class, 'kode_kegiatan', 'kode_kegiatan');
    }
}
