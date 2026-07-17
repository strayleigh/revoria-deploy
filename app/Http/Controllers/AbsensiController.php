<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        // Daftar kegiatan untuk card grid (tampilan baru mirip kartar)
        $kegiatans = Kegiatan::when(
            $request->status_kegiatan,
            fn($q, $s) => $q->where('status', $s)
        )->orderByDesc('tanggal')->get();

        // Ringkasan absensi milik user yang login
        $anggota        = auth()->user()?->anggota;
        $hadirCount     = 0;
        $tidakHadirCount = 0;
        $totalKegiatan  = $kegiatans->count();

        if ($anggota) {
            $hadirCount      = Absensi::where('id_anggota', $anggota->id_anggota)
                                       ->where('status_hadir', 'hadir')
                                       ->count();
            $tidakHadirCount = Absensi::where('id_anggota', $anggota->id_anggota)
                                       ->whereIn('status_hadir', ['tidak hadir', 'alpa'])
                                       ->count();
        }

        return view('absensi.index', compact('kegiatans', 'hadirCount', 'tidakHadirCount', 'totalKegiatan'));
    }

    public function create()
    {
        $kegiatans = Kegiatan::orderByDesc('tanggal')->get();
        $anggota = auth()->user()->anggota;

        return view('absensi.create', compact('kegiatans', 'anggota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kegiatan'   => 'required|exists:kegiatan,kode_kegiatan',
            'tanggal_absensi' => 'required|date',
            'waktu_absen'     => 'nullable|date_format:H:i',
            'status_hadir'    => 'required|in:hadir,tidak hadir,izin,sakit',
        ]);

        $anggota = auth()->user()->anggota;

        if (!$anggota) {
            return redirect()->route('profile.edit')
                ->with('error', 'Akun Anda belum terhubung ke data anggota. Silakan pilih nama Anda di halaman profil terlebih dahulu.');
        }

        Absensi::create([
            'id_anggota'      => $anggota->id_anggota,
            'kode_kegiatan'   => $request->kode_kegiatan,
            'tanggal_absensi' => $request->tanggal_absensi,
            'waktu_absen'     => $request->waktu_absen,
            'status_hadir'    => $request->status_hadir,
        ]);

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dicatat.');
    }

    public function edit(Absensi $absensi)
    {
        $kegiatans = Kegiatan::orderByDesc('tanggal')->get();

        return view('absensi.edit', compact('absensi', 'kegiatans'));
    }

    public function update(Request $request, Absensi $absensi)
    {
        $request->validate([
            'kode_kegiatan'   => 'required|exists:kegiatan,kode_kegiatan',
            'tanggal_absensi' => 'required|date',
            'waktu_absen'     => 'nullable|date_format:H:i',
            'status_hadir'    => 'required|in:hadir,tidak hadir,izin,sakit',
        ]);

        $absensi->update($request->only(['kode_kegiatan', 'tanggal_absensi', 'waktu_absen', 'status_hadir']));

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil diperbarui.');
    }

    public function destroy(Absensi $absensi)
    {
        $absensi->delete();

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dihapus.');
    }
}
