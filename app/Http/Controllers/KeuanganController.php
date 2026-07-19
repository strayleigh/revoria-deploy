<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\TransaksiKeuangan;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class KeuanganController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                $user = auth()->user();
                $jabatan = strtolower($user->anggota?->jabatan ?? '');
                $routeAction = $request->route()->getActionMethod();

                // Admin bypasses all checks
                if ($user->name === 'admin') {
                    return $next($request);
                }

                // If read-only index: allow Ketua, Wakil Ketua, Bendahara
                if ($routeAction === 'index') {
                    $allowed = in_array($jabatan, ['ketua', 'wakil ketua', 'bendahara'], true);
                    if (!$allowed) {
                        abort(403, 'Hanya Ketua, Wakil Ketua, dan Bendahara yang dapat melihat keuangan.');
                    }
                } else {
                    // Write actions (create, store, edit, update, destroy): only Bendahara
                    if ($jabatan !== 'bendahara') {
                        abort(403, 'Hanya Bendahara yang dapat mengelola keuangan.');
                    }
                }

                return $next($request);
            }),
        ];
    }

    public function index(Request $request)
    {
        $transaksis = TransaksiKeuangan::with('kegiatan')
            ->when($request->search, fn($q, $s) => $q->where('keterangan', 'like', "%$s%"))
            ->when($request->jenis, fn($q, $j) => $q->where('jenis_transaksi', $j))
            ->when($request->kategori, fn($q, $k) => $q->where('kategori', $k))
            ->orderByDesc('tanggal')
            ->paginate(15)
            ->withQueryString();

        $pemasukan   = TransaksiKeuangan::where('jenis_transaksi', 'pemasukan')->sum('nominal');
        $pengeluaran = TransaksiKeuangan::where('jenis_transaksi', 'pengeluaran')->sum('nominal');
        $saldo       = $pemasukan - $pengeluaran;

        // Kategori: Kas
        $kasPemasukan   = TransaksiKeuangan::where('kategori', 'Kas')->where('jenis_transaksi', 'pemasukan')->sum('nominal');
        $kasPengeluaran = TransaksiKeuangan::where('kategori', 'Kas')->where('jenis_transaksi', 'pengeluaran')->sum('nominal');
        $kasTotal       = $kasPemasukan - $kasPengeluaran;

        // Kategori: Iuran
        $iuranPemasukan   = TransaksiKeuangan::where('kategori', 'Iuran')->where('jenis_transaksi', 'pemasukan')->sum('nominal');
        $iuranPengeluaran = TransaksiKeuangan::where('kategori', 'Iuran')->where('jenis_transaksi', 'pengeluaran')->sum('nominal');
        $iuranTotal       = $iuranPemasukan - $iuranPengeluaran;

        // Kategori: Donasi
        $donasiPemasukan   = TransaksiKeuangan::where('kategori', 'Donasi')->where('jenis_transaksi', 'pemasukan')->sum('nominal');
        $donasiPengeluaran = TransaksiKeuangan::where('kategori', 'Donasi')->where('jenis_transaksi', 'pengeluaran')->sum('nominal');
        $donasiTotal       = $donasiPemasukan - $donasiPengeluaran;

        return view('keuangan.index', compact(
            'transaksis', 'pemasukan', 'pengeluaran', 'saldo',
            'kasPemasukan', 'kasPengeluaran', 'kasTotal',
            'iuranPemasukan', 'iuranPengeluaran', 'iuranTotal',
            'donasiPemasukan', 'donasiPengeluaran', 'donasiTotal'
        ));
    }

    public function create()
    {
        $kegiatans = Kegiatan::orderByDesc('tanggal_mulai')->get();

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
        $kegiatans = Kegiatan::orderByDesc('tanggal_mulai')->get();

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
