<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $absensis = Absensi::with(['anggota', 'kegiatan'])
            ->when($request->search, fn($q, $s) => $q->whereHas('anggota', fn($q) => $q->where('nama', 'like', "%$s%")))
            ->when($request->kegiatan_id, fn($q, $k) => $q->where('kode_kegiatan', $k))
            ->when($request->tanggal, fn($q, $t) => $q->whereDate('tanggal_absensi', $t))
            ->orderByDesc('tanggal_absensi')
            ->paginate(15)
            ->withQueryString();

        $kegiatans = Kegiatan::orderByDesc('tanggal')->get();

        return view('absensi.index', compact('absensis', 'kegiatans'));
    }

    public function create()
    {
        $kegiatans = Kegiatan::orderByDesc('tanggal')->get();

        return view('absensi.create', compact('kegiatans'));
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

        abort_if(!$anggota, 403, 'Akun Anda belum terhubung ke data anggota.');

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
