<x-sidebar title="Dashboard">

    <h2 class="mb-4 fw-bold">Ringkasan</h2>

    <!-- CARD SUMMARY -->
    <div class="row g-4 mb-4">
        @if(auth()->user()->isKetua())
        <div class="col-lg-3">
            <div class="summary-card">
                <div class="icon blue"><i class="bi bi-people"></i></div>
                <h5>Total Anggota</h5>
                <h2>{{ $anggotaCount }}</h2>
                <p>Aktif</p>
            </div>
        </div>
        @endif
        <div class="col-lg-3">
            <div class="summary-card">
                <div class="icon green"><i class="bi bi-calendar2-event"></i></div>
                <h5>Kegiatan</h5>
                <h2>{{ $kegiatanCount }}</h2>
                <p>Bulan Ini</p>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="summary-card">
                <div class="icon purple"><i class="bi bi-check2-square"></i></div>
                <h5>Absensi</h5>
                <h2>{{ $absensiToday }}</h2>
                <p>Hari Ini</p>
            </div>
        </div>
        @if(auth()->user()->isKetua())
        <div class="col-lg-3">
            <div class="summary-card">
                <div class="icon orange"><i class="bi bi-wallet2"></i></div>
                <h5>Saldo Kas</h5>
                <h2>Rp{{ number_format($saldo, 0, ',', '.') }}</h2>
                <p>Total</p>
            </div>
        </div>
        @endif
    </div>

    <!-- ROW 1 -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="dashboard-card">
                <h5>Kegiatan Mendatang</h5>
                <ul class="list-group mt-3">
                    @forelse($upcoming as $keg)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $keg->judul }}
                            <span class="badge bg-warning text-dark">
                                {{ \Carbon\Carbon::parse($keg->tanggal)->diffInDays(now()) }} Hari Lagi
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Tidak ada kegiatan mendatang.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="dashboard-card">
                <h5>Aksi Cepat</h5>
                <div class="d-grid gap-3 mt-4">
                    <a href="/activities" class="btn btn-outline-primary">Tambah Kegiatan</a>
                    <a href="/attendance" class="btn btn-outline-success">Input Absensi</a>
                    <a href="/documents" class="btn btn-outline-warning">Upload Dokumen</a>
                    <a href="/finance" class="btn btn-outline-danger">Catat Keuangan</a>
                </div>
            </div>
        </div>
    </div>

</x-sidebar>
