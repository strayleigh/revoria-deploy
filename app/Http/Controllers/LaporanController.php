<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Anggota;
use App\Models\Kegiatan;
use App\Models\TransaksiKeuangan;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function export(string $jenis): StreamedResponse
    {
        return match ($jenis) {
            'anggota'  => $this->exportAnggota(),
            'kegiatan' => $this->exportKegiatan(),
            'keuangan' => $this->exportKeuangan(),
            'absensi'  => $this->exportAbsensi(),
            default    => abort(404),
        };
    }

    private function exportAnggota(): StreamedResponse
    {
        $rows = Anggota::with('user')->orderBy('nama')->get();

        $header = ['No', 'Nama', 'Alamat', 'No HP', 'Jabatan', 'Status', 'Tanggal Bergabung'];
        $data = $rows->map(fn($r, $i) => [
            $i + 1, 
            $r->nama, 
            $r->alamat ?: '-', 
            $r->user?->no_hp ?: ($r->no_hp ?: '-'),
            $r->jabatan, 
            $r->status_anggota, 
            $r->tanggal_bergabung?->format('d/m/Y') ?: '-',
        ]);

        $summary = [
            'Total Anggota'          => $rows->count() . ' Orang',
            'Anggota Aktif'          => $rows->where('status_anggota', 'aktif')->count() . ' Orang',
            'Anggota Pasif / Alumni' => $rows->where('status_anggota', 'pasif')->count() . ' Orang',
            'Total Pengurus BPH'     => $rows->where('jabatan', '!=', 'Anggota')->count() . ' Orang',
        ];

        return $this->xlsxResponse('laporan_anggota', 'Data Anggota', $header, $data, $summary);
    }

    private function exportKegiatan(): StreamedResponse
    {
        $rows = Kegiatan::with(['panitia.anggota', 'transaksi', 'absensi'])->orderByDesc('tanggal')->get();

        $header = ['No', 'Nama Kegiatan', 'Tanggal', 'Lokasi', 'Status', 'Progres (%)', 'Deskripsi', 'Dana Terkumpul', 'Kepanitiaan', 'Panitia Hadir'];
        
        $data = $rows->map(function($r, $i) {
            // Dana Terkumpul
            $dana = $r->transaksi->where('jenis_transaksi', 'pemasukan')->sum('nominal');
            $danaFormatted = 'Rp ' . number_format($dana, 0, ',', '.');
            
            // Kepanitiaan
            $kepanitiaan = $r->panitia->map(fn($p) => ($p->anggota?->nama ?? '-') . ' (' . $p->posisi . ')')->implode(', ');
            if (empty($kepanitiaan)) {
                $kepanitiaan = '-';
            }

            // Jumlah panitia yg melakukan absensi
            $panitiaIds = $r->panitia->pluck('id_anggota')->toArray();
            $absensiPanitiaCount = $r->absensi->whereIn('id_anggota', $panitiaIds)->count();
            
            return [
                $i + 1,
                $r->nama_kegiatan,
                $r->tanggal?->format('d/m/Y') ?: '-',
                $r->lokasi ?: '-',
                ucfirst($r->status),
                ($r->progres ?? 0) . '%',
                $r->deskripsi ?: '-',
                $danaFormatted,
                $kepanitiaan,
                $absensiPanitiaCount . ' Orang',
            ];
        });

        $summary = [
            'Total Kegiatan'     => $rows->count() . ' Kegiatan',
            'Status Terjadwal'   => $rows->where('status', 'terjadwal')->count() . ' Kegiatan',
            'Status Berlangsung' => $rows->where('status', 'berlangsung')->count() . ' Kegiatan',
            'Status Selesai'     => $rows->where('status', 'selesai')->count() . ' Kegiatan',
        ];

        return $this->xlsxResponse('laporan_kegiatan', 'Data Kegiatan', $header, $data, $summary);
    }

    private function exportKeuangan(): StreamedResponse
    {
        $rows = TransaksiKeuangan::with('kegiatan')->orderByDesc('tanggal')->get();

        $header = ['No', 'Tanggal', 'Jenis', 'Kategori', 'Nominal', 'Kegiatan Terkait', 'Keterangan'];
        $data = $rows->map(fn($r, $i) => [
            $i + 1, $r->tanggal?->format('d/m/Y'), ucfirst($r->jenis_transaksi),
            $r->kategori, $r->nominal, $r->kegiatan?->nama_kegiatan ?? '-', $r->keterangan,
        ]);

        $pemasukan = $rows->where('jenis_transaksi', 'pemasukan')->sum('nominal');
        $pengeluaran = $rows->where('jenis_transaksi', 'pengeluaran')->sum('nominal');

        $summary = [
            'Total Transaksi'   => $rows->count() . ' Catatan',
            'Total Pemasukan'   => 'Rp ' . number_format($pemasukan, 0, ',', '.'),
            'Total Pengeluaran' => 'Rp ' . number_format($pengeluaran, 0, ',', '.'),
            'Saldo Kas Bersih'  => 'Rp ' . number_format($pemasukan - $pengeluaran, 0, ',', '.'),
        ];

        return $this->xlsxResponse('laporan_keuangan', 'Data Keuangan', $header, $data, $summary);
    }

    private function exportAbsensi(): StreamedResponse
    {
        $rows = Absensi::with(['anggota', 'kegiatan'])->orderByDesc('tanggal_absensi')->get();

        $header = ['No', 'Tanggal', 'Nama Anggota', 'Kegiatan', 'Status Hadir', 'Waktu Absen'];
        $data = $rows->map(fn($r, $i) => [
            $i + 1, $r->tanggal_absensi?->format('d/m/Y'),
            $r->anggota?->nama ?? '-', $r->kegiatan?->nama_kegiatan ?? '-',
            $r->status_hadir, $r->waktu_absen,
        ]);

        $summary = [
            'Total Kehadiran' => $rows->count() . ' Catatan',
            'Hadir'           => $rows->where('status_hadir', 'hadir')->count() . ' Orang',
            'Sakit'           => $rows->where('status_hadir', 'sakit')->count() . ' Orang',
            'Izin'            => $rows->where('status_hadir', 'izin')->count() . ' Orang',
            'Tidak Hadir/Alpa'=> $rows->where('status_hadir', 'tidak hadir')->count() . ' Orang',
        ];

        return $this->xlsxResponse('laporan_absensi', 'Data Absensi', $header, $data, $summary);
    }

    private function xlsxResponse(string $filename, string $sheetTitle, array $header, $data, array $summary = []): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($sheetTitle);

        // Header row
        $sheet->fromArray($header, null, 'A1');

        // Style header: bold, background biru, teks putih, center
        $colCount = count($header);
        $lastCol  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colCount);
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF0F2D5C']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Data rows
        $row = 2;
        foreach ($data as $item) {
            $sheet->fromArray(array_values((array) $item), null, "A{$row}");
            $row++;
        }

        // Tambahkan Waktu Export ke summary
        $exportTime = now()->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s');
        $summary['Waktu Export'] = $exportTime . ' WIB';

        // Tampilkan summary block jika ada
        if (!empty($summary)) {
            $row += 2;
            $sheet->setCellValue("A{$row}", 'RINGKASAN REKAPITULASI');
            $sheet->mergeCells("A{$row}:C{$row}");
            $sheet->getStyle("A{$row}:C{$row}")->applyFromArray([
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF0EA5E9']], // sky-500
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            $row++;

            foreach ($summary as $label => $val) {
                $sheet->setCellValue("A{$row}", $label);
                $sheet->setCellValue("B{$row}", $val);
                $sheet->mergeCells("B{$row}:C{$row}");
                $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                $row++;
            }
        }

        // Auto width semua kolom
        foreach (range(1, $colCount) as $col) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileTimestamp = now()->setTimezone('Asia/Jakarta')->format('Ymd_His');

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, "{$filename}_{$fileTimestamp}.xlsx", [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
