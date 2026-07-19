<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $kegiatans = Kegiatan::with(['transaksi', 'panitia.anggota'])
            ->when($request->search, fn($q, $s) => $q->whereRaw('LOWER(nama_kegiatan) LIKE ?', ["%" . strtolower($s) . "%"]))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->orderByDesc('tanggal')
            ->paginate(12)
            ->withQueryString();

        return view('kegiatan.index', compact('kegiatans'));
    }

    public function create()
    {
        $user = auth()->user();
        $jabatan = strtolower($user->anggota?->jabatan ?? '');
        $allowed = ['ketua', 'wakil ketua', 'bendahara', 'sekretaris'];
        if ($user->name !== 'admin' && !in_array($jabatan, $allowed, true)) {
            abort(403, 'Akses ditolak. Hanya Pengurus (Ketua, Wakil Ketua, Bendahara, Sekretaris) yang dapat membuat kegiatan.');
        }
        return view('kegiatan.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $jabatan = strtolower($user->anggota?->jabatan ?? '');
        $allowed = ['ketua', 'wakil ketua', 'bendahara', 'sekretaris'];
        if ($user->name !== 'admin' && !in_array($jabatan, $allowed, true)) {
            abort(403, 'Akses ditolak. Hanya Pengurus (Ketua, Wakil Ketua, Bendahara, Sekretaris) yang dapat membuat kegiatan.');
        }

        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal'       => 'required|date',
            'lokasi'        => 'nullable|string|max:255',
            'deskripsi'     => 'nullable|string',
            'status'        => 'required|in:terjadwal,berlangsung,selesai',
            'progres'       => 'nullable|integer|min:0|max:100',
        ]);

        Kegiatan::create($request->all());

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function show(Kegiatan $kegiatan)
    {
        return view('kegiatan.show', compact('kegiatan'));
    }

    public function edit(Kegiatan $kegiatan)
    {
        $user = auth()->user();
        $jabatan = strtolower($user->anggota?->jabatan ?? '');

        if ($user->name === 'admin') {
            return view('kegiatan.edit', compact('kegiatan'));
        }

        // Cek apakah user adalah Ketua Pelaksana atau Sekretaris untuk kepanitiaan kegiatan ini
        $isPanitiaEditAuthorized = $kegiatan->panitia()
            ->where('id_anggota', $user->anggota_id)
            ->whereIn('posisi', ['Ketua Pelaksana', 'Sekretaris'])
            ->exists();

        $isBph = in_array($jabatan, ['ketua', 'wakil ketua', 'bendahara', 'sekretaris'], true);
        $allowed = $isPanitiaEditAuthorized || $isBph;

        if (!$allowed) {
            abort(403, 'Akses ditolak. Hanya Pengurus, serta Ketua Pelaksana & Sekretaris kepanitiaan kegiatan ini yang dapat mengedit kegiatan.');
        }

        return view('kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $user = auth()->user();
        $jabatan = strtolower($user->anggota?->jabatan ?? '');

        if ($user->name !== 'admin') {
            // Cek apakah user adalah Ketua Pelaksana atau Sekretaris untuk kepanitiaan kegiatan ini
            $isPanitiaEditAuthorized = $kegiatan->panitia()
                ->where('id_anggota', $user->anggota_id)
                ->whereIn('posisi', ['Ketua Pelaksana', 'Sekretaris'])
                ->exists();

            $isBph = in_array($jabatan, ['ketua', 'wakil ketua', 'bendahara', 'sekretaris'], true);
            $allowed = $isPanitiaEditAuthorized || $isBph;

            if (!$allowed) {
                abort(403, 'Akses ditolak. Hanya Pengurus, serta Ketua Pelaksana & Sekretaris kepanitiaan kegiatan ini yang dapat mengedit kegiatan.');
            }
        }

        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal'       => 'required|date',
            'lokasi'        => 'nullable|string|max:255',
            'deskripsi'     => 'nullable|string',
            'status'        => 'required|in:terjadwal,berlangsung,selesai',
            'progres'       => 'nullable|integer|min:0|max:100',
        ]);

        $kegiatan->update($request->all());

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $user = auth()->user();
        $jabatan = strtolower($user->anggota?->jabatan ?? '');
        $allowed = ['ketua', 'wakil ketua', 'bendahara', 'sekretaris'];
        if ($user->name !== 'admin' && !in_array($jabatan, $allowed, true)) {
            abort(403, 'Akses ditolak. Hanya Pengurus (Ketua, Wakil Ketua, Bendahara, Sekretaris) yang dapat menghapus kegiatan.');
        }

        $kegiatan->delete();

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}
