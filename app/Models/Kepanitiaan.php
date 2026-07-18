<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kepanitiaan extends Model
{
    use HasFactory;

    protected $table = 'kepanitiaan';
    protected $primaryKey = 'id_kepanitiaan';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'kode_kegiatan',
        'id_anggota',
        'posisi',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kode_kegiatan', 'kode_kegiatan');
    }
}
