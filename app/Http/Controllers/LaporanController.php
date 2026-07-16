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
        $rows = Anggota::orderBy('nama')->get();

        $header = ['No', 'Nama', 'NIK', 'Alamat', 'No HP', 'Jabatan', 'Status', 'Tanggal Bergabung'];
        $data = $rows->map(fn($r, $i) => [
            $i + 1, $r->nama, $r->nik, $r->alamat, $r->no_hp,
            $r->jabatan, $r->status_anggota, $r->tanggal_bergabung?->format('d/m/Y'),
        ]);

        return $this->xlsxResponse('laporan_anggota', 'Data Anggota', $header, $data);
    }

    private function exportKegiatan(): StreamedResponse
    {
        $rows = Kegiatan::withCount('absensi')->orderByDesc('tanggal')->get();

        $header = ['No', 'Nama Kegiatan', 'Tanggal', 'Lokasi', 'Status', 'Progres (%)', 'Jumlah Peserta'];
        $data = $rows->map(fn($r, $i) => [
            $i + 1, $r->nama_kegiatan, $r->tanggal?->format('d/m/Y'),
            $r->lokasi, $r->status, $r->progres, $r->absensi_count,
        ]);

        return $this->xlsxResponse('laporan_kegiatan', 'Data Kegiatan', $header, $data);
    }

    private function exportKeuangan(): StreamedResponse
    {
        $rows = TransaksiKeuangan::with('kegiatan')->orderByDesc('tanggal')->get();

        $header = ['No', 'Tanggal', 'Jenis', 'Kategori', 'Nominal', 'Kegiatan Terkait', 'Keterangan'];
        $data = $rows->map(fn($r, $i) => [
            $i + 1, $r->tanggal?->format('d/m/Y'), ucfirst($r->jenis_transaksi),
            $r->kategori, $r->nominal, $r->kegiatan?->nama_kegiatan ?? '-', $r->keterangan,
        ]);

        return $this->xlsxResponse('laporan_keuangan', 'Data Keuangan', $header, $data);
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

        return $this->xlsxResponse('laporan_absensi', 'Data Absensi', $header, $data);
    }

    private function xlsxResponse(string $filename, string $sheetTitle, array $header, $data): StreamedResponse
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

        // Auto width semua kolom
        foreach (range(1, $colCount) as $col) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, "{$filename}.xlsx", [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
