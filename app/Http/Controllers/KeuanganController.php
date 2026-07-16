<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\TransaksiKeuangan;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $transaksis = TransaksiKeuangan::with('kegiatan')
            ->when($request->search, fn($q, $s) => $q->where('keterangan', 'like', "%$s%"))
            ->when($request->jenis, fn($q, $j) => $q->where('jenis_transaksi', $j))
            ->orderByDesc('tanggal')
            ->paginate(15)
            ->withQueryString();

        $pemasukan   = TransaksiKeuangan::where('jenis_transaksi', 'pemasukan')->sum('nominal');
        $pengeluaran = TransaksiKeuangan::where('jenis_transaksi', 'pengeluaran')->sum('nominal');
        $saldo       = $pemasukan - $pengeluaran;

        return view('keuangan.index', compact('transaksis', 'pemasukan', 'pengeluaran', 'saldo'));
    }

    public function create()
    {
        $kegiatans = Kegiatan::orderByDesc('tanggal')->get();

        return view('keuangan.create', compact('kegiatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_transaksi' => 'required|in:pemasukan,pengeluaran',
            'nominal'         => 'required|numeric|min:0',
            'tanggal'         => 'required|date',
            'keterangan'      => 'nullable|string',
            'kategori'        => 'required|in:Kas,Iuran,Donasi',
            'kode_kegiatan'   => 'nullable|exists:kegiatan,kode_kegiatan',
        ]);

        TransaksiKeuangan::create($request->all());

        return redirect()->route('keuangan.index')->with('success', 'Transaksi berhasil dicatat.');
    }

    public function edit(TransaksiKeuangan $keuangan)
    {
        $kegiatans = Kegiatan::orderByDesc('tanggal')->get();

        return view('keuangan.edit', compact('keuangan', 'kegiatans'));
    }

    public function update(Request $request, TransaksiKeuangan $keuangan)
    {
        $request->validate([
            'jenis_transaksi' => 'required|in:pemasukan,pengeluaran',
            'nominal'         => 'required|numeric|min:0',
            'tanggal'         => 'required|date',
            'keterangan'      => 'nullable|string',
            'kategori'        => 'required|in:Kas,Iuran,Donasi',
            'kode_kegiatan'   => 'nullable|exists:kegiatan,kode_kegiatan',
        ]);

        $keuangan->update($request->all());

        return redirect()->route('keuangan.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(TransaksiKeuangan $keuangan)
    {
        $keuangan->delete();

        return redirect()->route('keuangan.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
