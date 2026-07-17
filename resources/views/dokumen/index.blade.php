<x-sidebar title="Dokumen">

    <div class="mb-4">
        <h2 class="fw-bold mb-1">Dokumen</h2>
        <p class="text-muted mb-0">Pilih kegiatan untuk melihat dan mengelola dokumennya.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4">
            {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- ================= LIST KEGIATAN (Card Grid) ================= -->
    @php
        $iconMap = [
            'berlangsung' => ['icon' => 'bi-people',          'bg' => 'bg-success-subtle',   'text' => 'text-success'],
            'terjadwal'   => ['icon' => 'bi-calendar2-event',  'bg' => 'bg-primary-subtle',   'text' => 'text-primary'],
            'selesai'     => ['icon' => 'bi-check2-circle',    'bg' => 'bg-secondary-subtle', 'text' => 'text-secondary'],
        ];
        $iconPool = [
            ['icon' => 'bi-people',          'bg' => 'bg-success-subtle',  'text' => 'text-success'],
            ['icon' => 'bi-calendar2-event', 'bg' => 'bg-primary-subtle',  'text' => 'text-primary'],
            ['icon' => 'bi-trophy',          'bg' => 'bg-warning-subtle',  'text' => 'text-warning'],
            ['icon' => 'bi-easel',           'bg' => 'bg-purple-subtle',   'text' => 'text-purple'],
            ['icon' => 'bi-heart',           'bg' => 'bg-danger-subtle',   'text' => 'text-danger'],
            ['icon' => 'bi-mic',             'bg' => 'bg-info-subtle',     'text' => 'text-info'],
        ];
    @endphp

    <div class="row g-4">
        @forelse($kegiatans as $i => $kegiatan)
            @php
                $map = $iconMap[$kegiatan->status] ?? $iconPool[$i % count($iconPool)];
            @endphp
            <div class="col-lg-4">
                <div class="card kegiatan-card shadow-sm border-0 h-100"
                     role="button"
                     onclick="window.location.href='{{ route('dokumen.folder', $kegiatan->kode_kegiatan) }}'">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-kegiatan {{ $map['bg'] }} {{ $map['text'] }}">
                                <i class="bi {{ $map['icon'] }}"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">{{ $kegiatan->nama_kegiatan }}</h6>
                                <small class="text-muted">
                                    {{ $kegiatan->tanggal?->format('d M Y') }}
                                    @if($kegiatan->lokasi) &bull; {{ $kegiatan->lokasi }} @endif
                                </small>
                            </div>
                        </div>
                        @if($kegiatan->deskripsi)
                            <p class="text-muted small mb-0">{{ Str::limit($kegiatan->deskripsi, 90) }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-folder fs-1 d-block mb-2"></i>
                Belum ada kegiatan.
            </div>
        @endforelse
    </div>

</x-sidebar>
