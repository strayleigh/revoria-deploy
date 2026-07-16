<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';
    protected $primaryKey = 'id_anggota';
    public $timestamps = false;

    public function getRouteKeyName(): string
    {
        return 'id_anggota';
    }

    protected $fillable = [
        'nama', 'nik', 'alamat', 'no_hp',
        'tanggal_bergabung', 'status_anggota', 'jabatan',
    ];

    protected $casts = ['tanggal_bergabung' => 'date'];

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_anggota', 'id_anggota');
    }

    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'anggota_id', 'id_anggota');
    }
}
