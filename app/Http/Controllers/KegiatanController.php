<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $kegiatans = Kegiatan::query()
            ->when($request->search, fn($q, $s) => $q->where('nama_kegiatan', 'like', "%$s%"))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->orderByDesc('tanggal')
            ->paginate(12)
            ->withQueryString();

        return view('kegiatan.index', compact('kegiatans'));
    }

    public function create()
    {
        return view('kegiatan.create');
    }

    public function store(Request $request)
    {
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
        return view('kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
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
        $kegiatan->delete();

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}
