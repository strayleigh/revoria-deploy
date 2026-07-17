<x-sidebar title="Absensi">

    <div class="mb-4">
        <h2 class="fw-bold mb-1">Daftar Kehadiran</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4">
            {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- ================= RINGKASAN ================= -->
    <div class="row g-3 mb-4">
        <div class="col">
            <div class="summary-mini-card">
                <div class="icon-mini bg-success-subtle text-success"><i class="bi bi-people"></i></div>
                <h4 class="fw-bold mb-0">{{ $totalKegiatan ?? $kegiatans->count() }}</h4>
                <small class="text-muted">Total Kegiatan</small>
            </div>
        </div>
        <div class="col">
            <div class="summary-mini-card">
                <div class="icon-mini bg-success-subtle text-success"><i class="bi bi-check-circle"></i></div>
                <h4 class="fw-bold mb-0">{{ $hadirCount ?? 0 }}</h4>
                <small class="text-muted">Kehadiran Saya</small>
            </div>
        </div>
        <div class="col">
            <div class="summary-mini-card">
                <div class="icon-mini bg-danger-subtle text-danger"><i class="bi bi-x-circle"></i></div>
                <h4 class="fw-bold mb-0">{{ $tidakHadirCount ?? 0 }}</h4>
                <small class="text-muted">Tidak Hadir</small>
            </div>
        </div>
    </div>

    <!-- ================= LIST KEGIATAN (Card Grid) ================= -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Absensi per Kegiatan</h5>
        <div class="d-flex gap-2 align-items-center">
            <form action="{{ route('absensi.index') }}" method="GET" class="d-flex gap-2">
                <select name="status_kegiatan" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="berlangsung" {{ request('status_kegiatan') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                    <option value="selesai"     {{ request('status_kegiatan') == 'selesai'     ? 'selected' : '' }}>Selesai</option>
                </select>
            </form>
            @if(auth()->user()?->isKetua())
                <a href="{{ route('absensi.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Input Absensi
                </a>
            @endif
        </div>
    </div>

    @php
        $iconMap = [
            'berlangsung' => ['icon' => 'bi-people',         'bg' => 'bg-success-subtle', 'text' => 'text-success'],
            'terjadwal'   => ['icon' => 'bi-calendar2-event','bg' => 'bg-primary-subtle', 'text' => 'text-primary'],
            'selesai'     => ['icon' => 'bi-check2-circle',  'bg' => 'bg-secondary-subtle','text'=> 'text-secondary'],
        ];
    @endphp

    <div class="row g-4">
        @forelse($kegiatans as $kegiatan)
            @php
                $map = $iconMap[$kegiatan->status] ?? ['icon' => 'bi-calendar-event', 'bg' => 'bg-info-subtle', 'text' => 'text-info'];
            @endphp
            <div class="col-lg-4">
                <div class="card kegiatan-card shadow-sm border-0 h-100"
                     role="button"
                     data-bs-toggle="modal"
                     data-bs-target="#sesiAbsensiModal"
                     onclick="bukaSesiAbsensi(this)"
                     data-id="{{ $kegiatan->kode_kegiatan }}"
                     data-nama="{{ $kegiatan->nama_kegiatan }}">
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
                <i class="bi bi-check2-square fs-1 d-block mb-2"></i>
                Belum ada kegiatan untuk ditampilkan.
            </div>
        @endforelse
    </div>

    <!-- ================= MODAL SESI ABSENSI ================= -->
    <div class="modal fade" id="sesiAbsensiModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="sesiModalTitle">Sesi Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Berikut daftar rekap absensi untuk kegiatan ini.</p>
                    <div id="listSesiAbsensi">
                        <div class="text-center py-4 text-muted">
                            <div class="spinner-border spinner-border-sm me-2"></div>
                            Memuat data...
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold mb-1">Input Absensi</h6>
                            <small class="text-muted">Tambah data kehadiran untuk kegiatan ini.</small>
                        </div>
                        <a id="btnTambahAbsensi" href="{{ route('absensi.create') }}" class="btn btn-outline-primary">
                            <i class="bi bi-plus-circle"></i> Input Absensi
                        </a>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let kegiatanAktifId = '';

        function bukaSesiAbsensi(card) {
            const nama = card.dataset.nama;
            const id   = card.dataset.id;
            kegiatanAktifId = id;

            document.getElementById('sesiModalTitle').innerText = 'Sesi Absensi – ' + nama;

            // Update link ke input absensi dengan filter kegiatan
            const btnInput = document.getElementById('btnTambahAbsensi');
            btnInput.href = '{{ route('absensi.create') }}?kegiatan_id=' + id;

            // Render ringkasan sederhana – data real bisa di-fetch dari server
            const box = document.getElementById('listSesiAbsensi');
            box.innerHTML = `
                <div class="sesi-block mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="fw-semibold mb-1">${nama}</h6>
                            <small class="text-muted">Klik "Input Absensi" untuk menambah atau mengelola data kehadiran kegiatan ini.</small>
                        </div>
                    </div>
                    <div class="status-card status-belum">
                        <div class="d-flex align-items-center gap-3 flex-grow-1">
                            <div class="status-icon bg-primary text-white"><i class="bi bi-calendar2-check"></i></div>
                            <div>
                                <div class="fw-bold text-primary">Data Absensi Kegiatan</div>
                                <small class="text-muted">Lihat riwayat absensi di halaman daftar absensi.</small>
                            </div>
                        </div>
                        <a href="{{ route('absensi.index') }}?kegiatan_id=${id}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-list-check"></i> Lihat Absensi
                        </a>
                    </div>
                </div>
            `;
        }
    </script>

</x-sidebar>
