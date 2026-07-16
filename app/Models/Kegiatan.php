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
        'nama_kegiatan', 'tanggal', 'lokasi', 'deskripsi', 'status', 'progres',
    ];

    protected $casts = ['tanggal' => 'date'];

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
}
