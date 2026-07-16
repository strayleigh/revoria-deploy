<x-sidebar title="Laporan">
    <h2 class="mb-4 fw-bold">Laporan</h2>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="dashboard-card text-center">
                <div class="icon blue mx-auto mb-3"><i class="bi bi-people"></i></div>
                <h5 class="fw-bold">Laporan Anggota</h5>
                <p class="text-muted small">Rekap data seluruh anggota aktif dan tidak aktif.</p>
                <a href="{{ route('reports.export', 'anggota') }}" class="btn btn-outline-primary mt-2">
                    <i class="bi bi-download"></i> Unduh
                </a>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="dashboard-card text-center">
                <div class="icon green mx-auto mb-3"><i class="bi bi-calendar-event"></i></div>
                <h5 class="fw-bold">Laporan Kegiatan</h5>
                <p class="text-muted small">Rekap seluruh kegiatan beserta peserta dan status.</p>
                <a href="{{ route('reports.export', 'kegiatan') }}" class="btn btn-outline-success mt-2">
                    <i class="bi bi-download"></i> Unduh
                </a>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="dashboard-card text-center">
                <div class="icon orange mx-auto mb-3"><i class="bi bi-wallet2"></i></div>
                <h5 class="fw-bold">Laporan Keuangan</h5>
                <p class="text-muted small">Rekap pemasukan, pengeluaran, dan saldo kas.</p>
                <a href="{{ route('reports.export', 'keuangan') }}" class="btn btn-outline-warning mt-2">
                    <i class="bi bi-download"></i> Unduh
                </a>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="dashboard-card text-center">
                <div class="icon purple mx-auto mb-3"><i class="bi bi-check2-square"></i></div>
                <h5 class="fw-bold">Laporan Absensi</h5>
                <p class="text-muted small">Rekap kehadiran anggota per kegiatan.</p>
                <a href="{{ route('reports.export', 'absensi') }}" class="btn btn-outline-secondary mt-2">
                    <i class="bi bi-download"></i> Unduh
                </a>
            </div>
        </div>
    </div>

</x-sidebar>
