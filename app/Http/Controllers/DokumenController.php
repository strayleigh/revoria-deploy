<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    public function index(Request $request)
    {
        $dokumens = Folder::with('kegiatan')
            ->when($request->search, fn($q, $s) => $q->where('nama_folder', 'like', "%$s%"))
            ->when($request->kegiatan_id, fn($q, $k) => $q->where('kode_kegiatan', $k))
            ->orderByDesc('tanggal_dibuat')
            ->paginate(12)
            ->withQueryString();

        $kegiatans = Kegiatan::orderByDesc('tanggal')->get();

        return view('dokumen.index', compact('dokumens', 'kegiatans'));
    }

    public function create()
    {
        $kegiatans = Kegiatan::orderByDesc('tanggal')->get();

        return view('dokumen.create', compact('kegiatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_folder'   => 'required|string|max:255',
            'gdrive_folder' => 'nullable|url',
            'tanggal_dibuat'=> 'nullable|date',
            'kode_kegiatan' => 'nullable|exists:kegiatan,kode_kegiatan',
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
            'nama_folder'   => 'required|string|max:255',
            'gdrive_folder' => 'nullable|url',
            'tanggal_dibuat'=> 'nullable|date',
            'kode_kegiatan' => 'nullable|exists:kegiatan,kode_kegiatan',
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
