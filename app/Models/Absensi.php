<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $primaryKey = 'id_absensi';
    public $timestamps = false;

    public function getRouteKeyName(): string { return 'id_absensi'; }

    protected $fillable = [
        'tanggal_absensi', 'waktu_absen', 'status_hadir', 'id_anggota', 'kode_kegiatan', 'keterangan',
    ];

    protected $casts = ['tanggal_absensi' => 'date'];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kode_kegiatan', 'kode_kegiatan');
    }
}
