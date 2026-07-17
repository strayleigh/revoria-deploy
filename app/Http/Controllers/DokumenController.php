<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    /**
     * Halaman utama dokumen: tampilkan daftar kegiatan sebagai card grid
     * (mirip kartar dokumen.html).
     */
    public function index(Request $request)
    {
        $kegiatans = Kegiatan::orderByDesc('tanggal')->get();

        return view('dokumen.index', compact('kegiatans'));
    }

    /**
     * Halaman folder per kegiatan (kartar folder.html).
     * Tampilkan semua folder yang dimiliki kegiatan tertentu.
     */
    public function folder(string $kodeKegiatan)
    {
        $kegiatan = Kegiatan::where('kode_kegiatan', $kodeKegiatan)->firstOrFail();
        $folders  = $kegiatan->folder()->orderByDesc('id_folder')->get();

        return view('dokumen.folder', compact('kegiatan', 'folders'));
    }

    /**
     * Simpan folder baru untuk suatu kegiatan.
     */
    public function folderStore(Request $request, string $kodeKegiatan)
    {
        $kegiatan = Kegiatan::where('kode_kegiatan', $kodeKegiatan)->firstOrFail();

        $request->validate([
            'nama_folder'   => 'required|string|max:255',
            'gdrive_folder' => 'nullable|url|max:2048',
        ]);

        Folder::create([
            'nama_folder'    => $request->nama_folder,
            'gdrive_folder'  => $request->gdrive_folder,
            'kode_kegiatan'  => $kodeKegiatan,
            'tanggal_dibuat' => now()->toDateString(),
        ]);

        return redirect()
            ->route('dokumen.folder', $kodeKegiatan)
            ->with('success', 'Folder berhasil dibuat.');
    }

    /**
     * Update nama + link Google Drive folder.
     */
    public function folderUpdate(Request $request, Folder $folder)
    {
        $request->validate([
            'nama_folder'  => 'required|string|max:255',
            'gdrive_folder'=> 'nullable|url|max:2048',
        ]);

        $folder->update($request->only(['nama_folder', 'gdrive_folder']));

        return redirect()
            ->route('dokumen.folder-detail', $folder->id_folder)
            ->with('success', 'Folder berhasil diperbarui.');
    }

    /**
     * Hapus folder.
     */
    public function folderDestroy(Folder $folder)
    {
        $kode = $folder->kode_kegiatan;
        $folder->delete();

        return redirect()
            ->route('dokumen.folder', $kode)
            ->with('success', 'Folder berhasil dihapus.');
    }

    /**
     * Halaman detail folder — tampilkan info folder & link Google Drive.
     */
    public function folderDetail(Folder $folder)
    {
        $kegiatan = $folder->kegiatan;

        return view('dokumen.folder-detail', compact('folder', 'kegiatan'));
    }

    // ─── CRUD folder lama (tetap untuk backward-compatibility) ─────────────

    public function create()
    {
        $kegiatans = Kegiatan::orderByDesc('tanggal')->get();
        return view('dokumen.create', compact('kegiatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_folder'    => 'required|string|max:255',
            'gdrive_folder'  => 'nullable|url',
            'tanggal_dibuat' => 'nullable|date',
            'kode_kegiatan'  => 'nullable|exists:kegiatan,kode_kegiatan',
        ]);

        Folder::create($request->all());

        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function edit(Folder $dokumen)
    {
        $kegiatans = Kegiatan::orderByDesc('tanggal')->get();
        return view('dokumen.edit', compact('dokumen', 'kegiatans'));
    }

    public function update(Request $request, Folder $dokumen)
    {
        $request->validate([
            'nama_folder'    => 'required|string|max:255',
            'gdrive_folder'  => 'nullable|url',
            'tanggal_dibuat' => 'nullable|date',
            'kode_kegiatan'  => 'nullable|exists:kegiatan,kode_kegiatan',
        ]);

        $dokumen->update($request->all());

        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Folder $dokumen)
    {
        $dokumen->delete();
        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}
