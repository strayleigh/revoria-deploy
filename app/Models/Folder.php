<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $table = 'folder';
    protected $primaryKey = 'id_folder';
    public $timestamps = false;

    public function getRouteKeyName(): string { return 'id_folder'; }

    protected $fillable = [
        'nama_folder', 'gdrive_folder', 'tanggal_dibuat', 'kode_kegiatan',
    ];

    protected $casts = ['tanggal_dibuat' => 'date'];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kode_kegiatan', 'kode_kegiatan');
    }
}
