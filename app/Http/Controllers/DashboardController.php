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

        $upcoming = Kegiatan::whereDate('tanggal_mulai', '>', $today)
            ->orderBy('tanggal_mulai')
            ->limit(5)
            ->get();

        $todayActivities = Kegiatan::whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->orWhere('status', 'berlangsung')
            ->orderBy('tanggal_mulai')
            ->limit(5)
            ->get();

        $pembinas = \App\Models\User::where('role', 'pembina')->get();

        return view('dashboard.index', compact('anggotaCount', 'kegiatanCount', 'absensiToday', 'saldo', 'upcoming', 'todayActivities', 'pembinas'));
    }
}
