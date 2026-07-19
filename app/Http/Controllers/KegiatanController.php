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
            ->orderByDesc('tanggal_mulai')
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
            'nama_kegiatan'   => 'required|string|max:255',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date',
            'lokasi'          => 'nullable|string|max:255',
            'deskripsi'       => 'nullable|string',
            'status'          => 'required|in:terjadwal,berlangsung,selesai',
            'progres'         => 'nullable|integer|min:0|max:100',
        ]);

        $data = $request->all();
        $data['persiapan'] = [
            ['name' => 'Proposal', 'checked' => false],
            ['name' => 'Surat Permohonan', 'checked' => false],
            ['name' => 'Surat Peminjaman', 'checked' => false],
            ['name' => 'Lokasi', 'checked' => false],
            ['name' => 'Keuangan', 'checked' => false],
            ['name' => 'Alat-alat', 'checked' => false],
            ['name' => 'Konsumsi', 'checked' => false],
            ['name' => 'LPJ', 'checked' => false],
        ];
        $data['progres'] = 0;

        Kegiatan::create($data);

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
            'nama_kegiatan'   => 'required|string|max:255',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date',
            'lokasi'          => 'nullable|string|max:255',
            'deskripsi'       => 'nullable|string',
            'status'          => 'required|in:terjadwal,berlangsung,selesai',
            'progres'         => 'nullable|integer|min:0|max:100',
        ]);

        $data = $request->all();

        // Process dynamic persiapan array
        if ($request->has('persiapan')) {
            $persiapan = [];
            foreach ($request->input('persiapan') as $item) {
                if (isset($item['name']) && trim($item['name']) !== '') {
                    $persiapan[] = [
                        'name' => trim($item['name']),
                        'checked' => isset($item['checked']) && ($item['checked'] === '1' || $item['checked'] === true),
                    ];
                }
            }
            $data['persiapan'] = $persiapan;

            // Recalculate progress dynamically
            $total = count($persiapan);
            $checked = collect($persiapan)->where('checked', true)->count();
            $data['progres'] = $total > 0 ? round(($checked / $total) * 100) : 0;
        } else {
            // If completely empty/cleared
            $data['persiapan'] = [];
            $data['progres'] = 0;
        }

        $kegiatan->update($data);

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
