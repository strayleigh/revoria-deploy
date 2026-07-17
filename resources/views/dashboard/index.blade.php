<x-sidebar title="Dashboard">

    <h2 class="mb-4 fw-bold">Ringkasan</h2>

    <!-- ================= CARD SUMMARY ================= -->
    <div class="row g-4 mb-4">
        @if(auth()->user()->isKetua())
        <div class="col-lg-3">
            <div class="summary-card h-100">
                <div class="icon blue"><i class="bi bi-people"></i></div>
                <h5>Total Anggota</h5>
                <h2>{{ $anggotaCount }}</h2>
                <p>Aktif</p>
            </div>
        </div>
        @endif
        <div class="{{ auth()->user()->isKetua() ? 'col-lg-3' : 'col-md-6' }}">
            <div class="summary-card h-100">
                <div class="icon green"><i class="bi bi-calendar2-event"></i></div>
                <h5>Kegiatan</h5>
                <h2>{{ $kegiatanCount }}</h2>
                <p>Bulan Ini</p>
            </div>
        </div>
        <div class="{{ auth()->user()->isKetua() ? 'col-lg-3' : 'col-md-6' }}">
            <div class="summary-card h-100">
                <div class="icon purple"><i class="bi bi-check2-square"></i></div>
                <h5>Absensi</h5>
                <h2>{{ $absensiToday }}</h2>
                <p>Hari Ini</p>
            </div>
        </div>
        @if(auth()->user()->isKetua())
        <div class="col-lg-3">
            <div class="summary-card h-100">
                <div class="icon orange"><i class="bi bi-wallet2"></i></div>
                <h5>Saldo Kas</h5>
                <h2>Rp{{ number_format($saldo, 0, ',', '.') }}</h2>
                <p>Total</p>
            </div>
        </div>
        @endif
    </div>

    <!-- ================= ROW 1: Kegiatan Hari Ini + Presensi Cepat ================= -->
    <div class="row g-4 align-items-stretch">
        <div class="col-lg-8 d-flex">
            <div class="dashboard-card w-100 d-flex flex-column" style="min-height:0;">
                <h5 class="mb-3">Kegiatan Hari Ini</h5>
                <ul class="list-group overflow-auto flex-grow-1" style="max-height:260px;">
                    @forelse($todayActivities ?? [] as $keg)
                        @php
                            $badgeStatus = match($keg->status) {
                                'berlangsung' => 'bg-success',
                                'terjadwal'   => 'bg-warning text-dark',
                                default       => 'bg-secondary',
                            };
                            $diffDays = $keg->tanggal
                                ? now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($keg->tanggal)->startOfDay(), false)
                                : null;
                        @endphp
                        <li class="list-group-item d-flex justify-content-between align-items-start py-3">
                            <div>
                                <div class="fw-semibold mb-1">{{ $keg->nama_kegiatan }}</div>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $keg->tanggal?->format('d M Y') ?? '-' }}
                                </small>
                            </div>
                            <div class="text-end d-flex flex-column gap-1 ms-3 flex-shrink-0">
                                <span class="badge {{ $badgeStatus }}">{{ ucfirst($keg->status) }}</span>
                                @if($diffDays !== null)
                                    @if($diffDays == 0)
                                        <small class="text-success fw-semibold">Hari ini</small>
                                    @elseif($diffDays > 0)
                                        <small class="text-warning fw-semibold">{{ $diffDays }} hari lagi</small>
                                    @else
                                        <small class="text-muted">{{ abs($diffDays) }} hari lalu</small>
                                    @endif
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Tidak ada kegiatan hari ini.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-lg-4 d-flex">
            <div class="dashboard-card w-100 d-flex flex-column align-items-center justify-content-center text-center gap-3">
                <div>
                    <div class="icon green mx-auto mb-3" style="width:56px;height:56px;font-size:26px;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Presensi Cepat</h5>
                    <p class="text-muted small mb-0">Catat kehadiranmu sekarang</p>
                </div>
                <a href="{{ route('absensi.create') }}" class="btn btn-success px-4">
                    <i class="bi bi-check-circle me-1"></i> Mulai Presensi
                </a>
            </div>
        </div>
    </div>

    <!-- ================= ROW 2: Kegiatan Mendatang + Aksi Cepat ================= -->
    <div class="row g-4 mt-1 align-items-stretch">
        <div class="col-lg-8 d-flex">
            <div class="dashboard-card w-100 d-flex flex-column" style="min-height:0;">
                <h5 class="mb-3">Kegiatan Mendatang</h5>
                <ul class="list-group overflow-auto flex-grow-1" style="max-height:260px;">
                    @forelse($upcoming as $keg)
                        @php
                            $badgeStatus = match($keg->status) {
                                'berlangsung' => 'bg-success',
                                'terjadwal'   => 'bg-warning text-dark',
                                default       => 'bg-secondary',
                            };
                            $diffDays = $keg->tanggal
                                ? now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($keg->tanggal)->startOfDay(), false)
                                : null;
                        @endphp
                        <li class="list-group-item d-flex justify-content-between align-items-start py-3">
                            <div>
                                <div class="fw-semibold mb-1">{{ $keg->nama_kegiatan }}</div>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $keg->tanggal?->format('d M Y') ?? '-' }}
                                </small>
                            </div>
                            <div class="text-end d-flex flex-column gap-1 ms-3 flex-shrink-0">
                                <span class="badge {{ $badgeStatus }}">{{ ucfirst($keg->status) }}</span>
                                @if($diffDays !== null)
                                    @if($diffDays == 0)
                                        <small class="text-success fw-semibold">Hari ini</small>
                                    @elseif($diffDays > 0)
                                        <small class="text-warning fw-semibold">{{ $diffDays }} hari lagi</small>
                                    @else
                                        <small class="text-muted">{{ abs($diffDays) }} hari lalu</small>
                                    @endif
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Tidak ada kegiatan mendatang.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-lg-4 d-flex">
            <div class="dashboard-card w-100 d-flex flex-column justify-content-center">
                <h5 class="mb-3">Aksi Cepat</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Kegiatan
                    </a>
                    <a href="{{ route('absensi.index') }}" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-check2-square me-1"></i> Input Absensi
                    </a>
                    <a href="{{ route('dokumen.index') }}" class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-folder me-1"></i> Lihat Dokumen
                    </a>
                    <a href="{{ route('keuangan.index') }}" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-wallet2 me-1"></i> Catat Keuangan
                    </a>
                </div>
            </div>
        </div>
    </div>

</x-sidebar>
