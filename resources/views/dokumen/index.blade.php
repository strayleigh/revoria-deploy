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

    <!-- ================= FILTER & SEARCH ================= -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <form action="{{ route('dokumen.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-lg">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0"
                                   placeholder="Cari nama kegiatan..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-auto">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="terjadwal"   {{ request('status') == 'terjadwal'   ? 'selected' : '' }}>Terjadwal</option>
                            <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="selesai"     {{ request('status') == 'selesai'     ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-lg-auto d-flex gap-2">
                        <button type="submit" class="btn btn-outline-secondary px-3"><i class="bi bi-search"></i></button>
                        <a href="{{ route('dokumen.index') }}" class="btn btn-outline-secondary px-3"><i class="bi bi-x-lg"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
                <div class="card kegiatan-card dokumen-card shadow-sm border-0"
                     role="button"
                     onclick="window.location.href='{{ route('dokumen.folder', $kegiatan->kode_kegiatan) }}'">
                    <div class="card-body d-flex flex-column justify-content-between h-100 p-4">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon-kegiatan {{ $map['bg'] }} {{ $map['text'] }} rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; flex-shrink: 0;">
                                    <i class="bi {{ $map['icon'] }} fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1" style="font-size: 15px;">{{ $kegiatan->nama_kegiatan }}</h6>
                                    <small class="text-muted d-block" style="font-size: 12px;">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $kegiatan->tanggal?->format('d M Y') }}
                                        @if($kegiatan->lokasi) &bull; <i class="bi bi-geo-alt-fill me-1 text-danger"></i>{{ $kegiatan->lokasi }} @endif
                                    </small>
                                </div>
                            </div>
                            <hr class="my-3 opacity-25">
                            <div style="height: 60px; overflow: hidden;">
                                @if($kegiatan->deskripsi)
                                    <p class="text-muted small mb-0" style="line-height: 1.5;">{{ Str::limit($kegiatan->deskripsi, 100) }}</p>
                                @else
                                    <p class="text-muted small mb-0" style="visibility: hidden;">-</p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-3 text-end">
                            <span class="text-primary small fw-semibold"><i class="bi bi-folder2-open me-1"></i> Buka Dokumen</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-folder-x fs-1 d-block mb-2"></i>
                Belum ada kegiatan yang cocok dengan kriteria pencarian Anda.
            </div>
        @endforelse
    </div>

</x-sidebar>
