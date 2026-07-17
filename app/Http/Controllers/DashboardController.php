<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Kegiatan;
use App\Models\Absensi;
use App\Models\TransaksiKeuangan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $anggotaCount = Anggota::count();
        $kegiatanCount = Kegiatan::count();
        $today = now()->toDateString();
        $absensiToday = Absensi::whereDate('tanggal_absensi', $today)->count();

        $saldo = TransaksiKeuangan::selectRaw("COALESCE(SUM(CASE WHEN jenis_transaksi='pemasukan' THEN nominal WHEN jenis_transaksi='pengeluaran' THEN -nominal ELSE 0 END),0) as saldo")->value('saldo');

        $upcoming = Kegiatan::whereDate('tanggal', '>', $today)
            ->orderBy('tanggal')
            ->limit(5)
            ->get();

        $todayActivities = Kegiatan::whereDate('tanggal', $today)
            ->orWhere('status', 'berlangsung')
            ->orderBy('tanggal')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('anggotaCount', 'kegiatanCount', 'absensiToday', 'saldo', 'upcoming', 'todayActivities'));
    }
}
