<x-sidebar title="Dashboard">

    @if(auth()->user()->isKetua())
    <h2 class="mb-4 fw-bold">Ringkasan</h2>
    @else
    <h2 class="mb-4 fw-bold">Dashboard</h2>
    @endif

    @if(auth()->user()->isKetua())
    <!-- ================= CARD SUMMARY ================= -->
    <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="summary-card h-100 p-4 text-center">
                <div class="icon-clean icon-blue mb-3 mx-auto"><i class="bi bi-people"></i></div>
                <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Total Anggota</span>
                <h3 class="fw-bold mb-2">{{ $anggotaCount }}</h3>
                <small class="text-muted" style="font-size: 11px;">Aktif</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="summary-card h-100 p-4 text-center">
                <div class="icon-clean icon-green mb-3 mx-auto"><i class="bi bi-calendar2-event"></i></div>
                <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Kegiatan</span>
                <h3 class="fw-bold mb-2">{{ $kegiatanCount }}</h3>
                <small class="text-muted" style="font-size: 11px;">Bulan Ini</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="summary-card h-100 p-4 text-center">
                <div class="icon-clean icon-purple mb-3 mx-auto"><i class="bi bi-check2-square"></i></div>
                <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Absensi</span>
                <h3 class="fw-bold mb-2">{{ $absensiToday }}</h3>
                <small class="text-muted" style="font-size: 11px;">Hari Ini</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="summary-card h-100 p-4 text-center">
                <div class="icon-clean icon-orange mb-3 mx-auto"><i class="bi bi-wallet2"></i></div>
                <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Saldo Kas</span>
                <h3 class="fw-bold mb-2">Rp{{ number_format($saldo, 0, ',', '.') }}</h3>
                <small class="text-muted" style="font-size: 11px;">Total</small>
            </div>
        </div>
    </div>
    @endif

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
                        <li class="list-group-item activity-item d-flex justify-content-between align-items-start py-3 mb-2">
                            <div>
                                <div class="fw-semibold mb-1">{{ $keg->nama_kegiatan }}</div>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $keg->tanggal?->format('d M Y') ?? '-' }}
                                </small>
                            </div>
                            <div class="text-end d-flex flex-column gap-2 ms-3 flex-shrink-0">
                                <span class="badge {{ $badgeStatus }} px-3 py-2 fw-semibold" style="font-size: 13px; border-radius: 8px;">{{ ucfirst($keg->status) }}</span>
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
                        <li class="list-group-item activity-item d-flex justify-content-between align-items-start py-3 mb-2">
                            <div>
                                <div class="fw-semibold mb-1">{{ $keg->nama_kegiatan }}</div>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $keg->tanggal?->format('d M Y') ?? '-' }}
                                </small>
                            </div>
                            <div class="text-end d-flex flex-column gap-2 ms-3 flex-shrink-0">
                                <span class="badge {{ $badgeStatus }} px-3 py-2 fw-semibold" style="font-size: 13px; border-radius: 8px;">{{ ucfirst($keg->status) }}</span>
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
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('kegiatan.index') }}" class="quick-action-btn btn-kegiatan btn-sm w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                            <i class="bi bi-plus-circle mb-2 fs-5"></i>
                            <span>Kegiatan</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('absensi.index') }}" class="quick-action-btn btn-absensi btn-sm w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                            <i class="bi bi-check2-square mb-2 fs-5"></i>
                            <span>Absensi</span>
                        </a>
                    </div>
                    @if(auth()->user()->role === 'anggota')
                        <div class="col-12">
                            <a href="{{ route('dokumen.index') }}" class="quick-action-btn btn-dokumen btn-sm w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                                <i class="bi bi-folder mb-2 fs-5"></i>
                                <span>Dokumen</span>
                            </a>
                        </div>
                    @else
                        <div class="col-6">
                            <a href="{{ route('dokumen.index') }}" class="quick-action-btn btn-dokumen btn-sm w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                                <i class="bi bi-folder mb-2 fs-5"></i>
                                <span>Dokumen</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('keuangan.index') }}" class="quick-action-btn btn-keuangan btn-sm w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                                <i class="bi bi-wallet2 mb-2 fs-5"></i>
                                <span>Keuangan</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-sidebar>
