<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AbsensiController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                $user = auth()->user();
                if ($user?->role === 'pembina') {
                    abort(403, 'Pembina tidak memiliki akses ke halaman absensi.');
                }

                $routeAction = $request->route()->getActionMethod();
                $jabatan = strtolower($user->anggota?->jabatan ?? '');

                // Admin bypasses all checks
                if ($user->name === 'admin') {
                    return $next($request);
                }

                // getAbsensiByKegiatan: Ketua, Wakil Ketua, Sekretaris
                if ($routeAction === 'getAbsensiByKegiatan') {
                    $allowed = in_array($jabatan, ['ketua', 'wakil ketua', 'sekretaris'], true);
                    if (!$allowed) {
                        abort(403, 'Hanya Ketua, Wakil Ketua, dan Sekretaris yang dapat melihat rekap absensi.');
                    }
                }

                // edit, update, destroy: Hanya Sekretaris
                if (in_array($routeAction, ['edit', 'update', 'destroy'], true)) {
                    if ($jabatan !== 'sekretaris') {
                        abort(403, 'Hanya Sekretaris yang dapat mengelola absensi.');
                    }
                }

                return $next($request);
            }),
        ];
    }
    public function index(Request $request)
    {
        // Daftar kegiatan untuk card grid (tampilan baru mirip kartar)
        $status = $request->status ?? $request->status_kegiatan;
        $kegiatans = Kegiatan::query()
            ->when($request->search, fn($q, $s) => $q->whereRaw('LOWER(nama_kegiatan) LIKE ?', ["%" . strtolower($s) . "%"]))
            ->when($status, fn($q, $s) => $q->where('status', $s))
            ->orderByDesc('tanggal')
            ->get();

        // Ringkasan absensi milik user yang login
        $anggota        = auth()->user()?->anggota;
        $hadirCount     = 0;
        $tidakHadirCount = 0;
        $totalKegiatan  = $kegiatans->count();
        $userAbsensiKegiatanIds = [];

        if ($anggota) {
            $userAbsensiKegiatanIds = Absensi::where('id_anggota', $anggota->id_anggota)
                                       ->pluck('kode_kegiatan')
                                       ->toArray();
            $hadirCount      = count($userAbsensiKegiatanIds);
            $tidakHadirCount = max(0, $totalKegiatan - $hadirCount);
        }

        return view('absensi.index', compact('kegiatans', 'hadirCount', 'tidakHadirCount', 'totalKegiatan', 'userAbsensiKegiatanIds'));
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

        // Validasi status kegiatan: Semua user (termasuk pengurus) kecuali admin tidak boleh absen jika status kegiatan tidak "berlangsung"
        $kegiatan = Kegiatan::where('kode_kegiatan', $request->kode_kegiatan)->firstOrFail();
        $user = auth()->user();
        if ($user->name !== 'admin') {
            if ($kegiatan->status !== 'berlangsung') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Absensi hanya dapat dilakukan untuk kegiatan yang sedang berlangsung.');
            }
        }

        // Cek duplikasi absensi untuk kegiatan yang sama
        $sudahAbsen = Absensi::where('id_anggota', $anggota->id_anggota)
            ->where('kode_kegiatan', $request->kode_kegiatan)
            ->exists();

        if ($sudahAbsen) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Anda sudah mengisi absensi untuk kegiatan ini.');
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
        $user = auth()->user();
        $jabatan = strtolower($user->anggota?->jabatan ?? '');
        if ($user->name !== 'admin' && $jabatan !== 'sekretaris') {
            abort(403, 'Hanya Sekretaris dan Admin yang dapat mengelola data absensi.');
        }

        $kegiatans = Kegiatan::orderByDesc('tanggal')->get();

        return view('absensi.edit', compact('absensi', 'kegiatans'));
    }

    public function update(Request $request, Absensi $absensi)
    {
        $user = auth()->user();
        $jabatan = strtolower($user->anggota?->jabatan ?? '');
        if ($user->name !== 'admin' && $jabatan !== 'sekretaris') {
            abort(403, 'Hanya Sekretaris dan Admin yang dapat mengelola data absensi.');
        }

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
        $user = auth()->user();
        $jabatan = strtolower($user->anggota?->jabatan ?? '');
        if ($user->name !== 'admin' && $jabatan !== 'sekretaris') {
            abort(403, 'Hanya Sekretaris dan Admin yang dapat mengelola data absensi.');
        }

        $absensi->delete();

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dihapus.');
    }

    public function getAbsensiByKegiatan($kodeKegiatan)
    {
        $absensis = Absensi::with('anggota')
            ->where('kode_kegiatan', $kodeKegiatan)
            ->get();
        return response()->json($absensis);
    }
}
