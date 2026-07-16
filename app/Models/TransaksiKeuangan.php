<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiKeuangan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_keuangan';
    protected $primaryKey = 'id_transaksi';
    public $timestamps = false;

    public function getRouteKeyName(): string { return 'id_transaksi'; }

    protected $fillable = [
        'jenis_transaksi', 'nominal', 'tanggal', 'keterangan', 'kategori', 'kode_kegiatan',
    ];

    protected $casts = ['tanggal' => 'date'];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kode_kegiatan', 'kode_kegiatan');
    }
}
