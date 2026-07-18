<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Kegiatan;
use App\Models\Kepanitiaan;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class KepanitiaanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                $user = auth()->user();
                $jabatan = strtolower($user->anggota?->jabatan ?? '');

                if ($user->name === 'admin') {
                    return $next($request);
                }

                if ($jabatan === 'bendahara') {
                    abort(403, 'Bendahara tidak memiliki hak akses untuk mengelola kepanitiaan.');
                }

                return $next($request);
            }),
        ];
    }
    public function index(Kegiatan $kegiatan)
    {
        $kepanitiaans = Kepanitiaan::with('anggota')
            ->where('kode_kegiatan', $kegiatan->kode_kegiatan)
            ->get();

        $anggotas = Anggota::orderBy('nama')->get();

        return view('kegiatan.kepanitiaan', compact('kegiatan', 'kepanitiaans', 'anggotas'));
    }

    public function store(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'id_anggota' => 'required|exists:anggota,id_anggota',
            'posisi'     => 'required|in:Ketua Pelaksana,Sekretaris,Bendahara,Anggota',
        ]);

        $exists = Kepanitiaan::where('kode_kegiatan', $kegiatan->kode_kegiatan)
            ->where('id_anggota', $request->id_anggota)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Anggota tersebut sudah terdaftar dalam kepanitiaan kegiatan ini.');
        }

        Kepanitiaan::create([
            'kode_kegiatan' => $kegiatan->kode_kegiatan,
            'id_anggota'    => $request->id_anggota,
            'posisi'        => $request->posisi,
        ]);

        return redirect()->back()->with('success', 'Anggota panitia berhasil ditambahkan.');
    }

    public function destroy(Kepanitiaan $kepanitiaan)
    {
        $kepanitiaan->delete();

        return redirect()->back()->with('success', 'Anggota panitia berhasil dihapus.');
    }
}
