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
            <div class="summary-mini-card border border-primary shadow-sm" id="card-all" onclick="filterAbsensi('all')" style="cursor: pointer; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                <div class="icon-mini bg-success-subtle text-success"><i class="bi bi-people"></i></div>
                <h4 class="fw-bold mb-0">{{ $totalKegiatan ?? $kegiatans->count() }}</h4>
                <small class="text-muted">Total Kegiatan</small>
            </div>
        </div>
        <div class="col">
            <div class="summary-mini-card" id="card-hadir" onclick="filterAbsensi('hadir')" style="cursor: pointer; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                <div class="icon-mini bg-success-subtle text-success"><i class="bi bi-check-circle"></i></div>
                <h4 class="fw-bold mb-0">{{ $hadirCount ?? 0 }}</h4>
                <small class="text-muted">Kehadiran Saya</small>
            </div>
        </div>
        <div class="col">
            <div class="summary-mini-card" id="card-tidak-hadir" onclick="filterAbsensi('tidak-hadir')" style="cursor: pointer; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                <div class="icon-mini bg-danger-subtle text-danger"><i class="bi bi-x-circle"></i></div>
                <h4 class="fw-bold mb-0">{{ $tidakHadirCount ?? 0 }}</h4>
                <small class="text-muted">Tidak Hadir</small>
            </div>
        </div>
    </div>

    <!-- ================= FILTER & SEARCH ================= -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <form action="{{ route('absensi.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-lg">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control"
                                   placeholder="Cari kegiatan" value="{{ request('search') }}">
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
                        <a href="{{ route('absensi.index') }}" class="btn btn-outline-secondary px-3"><i class="bi bi-x-lg"></i></a>
                        @if(auth()->user()?->isKetua())
                            <a href="{{ route('absensi.create') }}" class="btn btn-primary px-4">
                                <i class="bi bi-plus-circle"></i> Input Absensi
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= LIST KEGIATAN (Card Grid) ================= -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Absensi per Kegiatan</h5>
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
            <div class="col-lg-4 kegiatan-item" data-has-absensi="{{ in_array($kegiatan->kode_kegiatan, $userAbsensiKegiatanIds ?? []) ? 'true' : 'false' }}">
                <div class="card kegiatan-card shadow-sm border-0 h-100"
                     role="button"
                     @if(auth()->user()?->isKetua())
                         data-bs-toggle="modal"
                         data-bs-target="#sesiAbsensiModal"
                         onclick="bukaSesiAbsensi(this)"
                     @else
                         onclick="window.location.href='{{ route('absensi.create') }}?kode_kegiatan={{ $kegiatan->kode_kegiatan }}'"
                     @endif
                     data-id="{{ $kegiatan->kode_kegiatan }}"
                     data-nama="{{ $kegiatan->nama_kegiatan }}">
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
                            <div style="height: 40px; overflow: hidden;">
                                @if($kegiatan->deskripsi)
                                    <p class="text-muted small mb-0" style="line-height: 1.5;">{{ Str::limit($kegiatan->deskripsi, 90) }}</p>
                                @else
                                    <p class="text-muted small mb-0" style="visibility: hidden;">-</p>
                                @endif
                            </div>
                        </div>
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
                        <!-- Konten diisi oleh JS -->
                    </div>
                    
                    <!-- Detail Tabel Absensi (ditampilkan dinamis saat klik Lihat Absensi) -->
                    <div id="detailAbsensiWrapper" class="mt-4 d-none">
                        <h6 class="fw-bold mb-3"><i class="bi bi-table text-primary me-2"></i>Daftar Kehadiran Anggota</h6>
                        <div class="table-responsive rounded-3 border">
                            <table class="table align-middle mb-0 text-center table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Anggota</th>
                                        <th>Status</th>
                                        <th>Waktu</th>
                                        <th id="headerAksi">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="detailAbsensiTableBody">
                                    <!-- Render data absensi -->
                                </tbody>
                            </table>
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
        const isKetua = {{ auth()->user()?->isKetua() ? 'true' : 'false' }};
        const isAdmin = {{ auth()->user()?->name === 'admin' ? 'true' : 'false' }};
        const csrfToken = '{{ csrf_token() }}';

        function bukaSesiAbsensi(card) {
            const nama = card.dataset.nama;
            const id   = card.dataset.id;
            kegiatanAktifId = id;

            document.getElementById('sesiModalTitle').innerText = 'Sesi Absensi – ' + nama;

            // Sembunyikan detail rekap tabel setiap membuka modal baru
            document.getElementById('detailAbsensiWrapper').classList.add('d-none');
            document.getElementById('detailAbsensiTableBody').innerHTML = '';

            // Update link ke input absensi dengan filter kegiatan
            const btnInput = document.getElementById('btnTambahAbsensi');
            if (btnInput) {
                btnInput.href = '{{ route('absensi.create') }}?kode_kegiatan=' + id;
            }

            const box = document.getElementById('listSesiAbsensi');
            
            if (isKetua) {
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
                                    <small class="text-muted">Lihat riwayat absensi dari seluruh anggota.</small>
                                </div>
                            </div>
                            <button type="button" onclick="muatDetailAbsensi('${id}')" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-list-check"></i> Lihat Absensi
                            </button>
                        </div>
                    </div>
                `;
            } else {
                box.innerHTML = `
                    <div class="sesi-block mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h6 class="fw-semibold mb-1">${nama}</h6>
                                <small class="text-muted">Silakan klik tombol "Input Absensi" di bawah untuk mencatat kehadiran Anda pada kegiatan ini.</small>
                            </div>
                        </div>
                    </div>
                `;
            }
        }

        function muatDetailAbsensi(kodeKegiatan) {
            const wrapper = document.getElementById('detailAbsensiWrapper');
            const tbody = document.getElementById('detailAbsensiTableBody');
            const headerAksi = document.getElementById('headerAksi');

            if (headerAksi) {
                headerAksi.style.display = isAdmin ? '' : 'none';
            }

            // Tampilkan loader sementara memuat data
            tbody.innerHTML = `
                <tr>
                    <td colspan="${isAdmin ? 5 : 4}" class="text-muted py-4">
                        <div class="spinner-border spinner-border-sm me-2 text-primary"></div>
                        Mengambil data absensi...
                    </td>
                </tr>
            `;
            wrapper.classList.remove('d-none');

            fetch(`/absensi/kegiatan/${kodeKegiatan}`)
                .then(response => response.json())
                .then(data => {
                    tbody.innerHTML = '';
                    if (data.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="${isAdmin ? 5 : 4}" class="text-muted py-4">Belum ada anggota yang melakukan absensi pada kegiatan ini.</td>
                            </tr>
                        `;
                        return;
                    }

                    data.forEach((row, idx) => {
                        const nama = row.anggota ? row.anggota.nama : 'Tidak Dikenal';
                        let badgeColor = 'bg-secondary';
                        if (row.status_hadir === 'hadir') badgeColor = 'bg-success';
                        else if (row.status_hadir === 'tidak hadir') badgeColor = 'bg-danger';
                        else if (row.status_hadir === 'izin') badgeColor = 'bg-warning text-dark';
                        else if (row.status_hadir === 'sakit') badgeColor = 'bg-info text-dark';

                        const waktu = row.waktu_absen ? row.waktu_absen.substring(0, 5) : '-';

                        let actionCell = '';
                        if (isAdmin) {
                            actionCell = `
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="/absensi/${row.id_absensi}/edit" class="btn btn-sm btn-outline-warning py-1 px-2" style="font-size: 11px;">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <form action="/absensi/${row.id_absensi}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus absensi ini?')">
                                            <input type="hidden" name="_token" value="${csrfToken}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" style="font-size: 11px;">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            `;
                        }

                        tbody.innerHTML += `
                            <tr>
                                <td>${idx + 1}</td>
                                <td class="text-start fw-semibold">${nama}</td>
                                <td><span class="badge ${badgeColor}">${row.status_hadir}</span></td>
                                <td>${waktu}</td>
                                ${actionCell}
                            </tr>
                        `;
                    });
                })
                .catch(err => {
                    console.error(err);
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="${isAdmin ? 5 : 4}" class="text-danger py-4">Gagal memuat data absensi. Silakan coba lagi.</td>
                        </tr>
                    `;
                });
        }

        function filterAbsensi(filterType) {
            // Hapus border active di semua card summary
            document.querySelectorAll('.summary-mini-card').forEach(card => {
                card.classList.remove('border', 'border-primary', 'shadow-sm');
            });
            
            // Tambahkan border active ke card yang diklik
            const selectedCard = document.getElementById('card-' + filterType);
            if (selectedCard) {
                selectedCard.classList.add('border', 'border-primary', 'shadow-sm');
            }

            const items = document.querySelectorAll('.kegiatan-item');
            let visibleCount = 0;

            items.forEach(item => {
                const hasAbsensi = item.getAttribute('data-has-absensi') === 'true';
                if (filterType === 'all') {
                    item.style.setProperty('display', '', 'important');
                    visibleCount++;
                } else if (filterType === 'hadir') {
                    if (hasAbsensi) {
                        item.style.setProperty('display', '', 'important');
                        visibleCount++;
                    } else {
                        item.style.setProperty('display', 'none', 'important');
                    }
                } else if (filterType === 'tidak-hadir') {
                    if (!hasAbsensi) {
                        item.style.setProperty('display', '', 'important');
                        visibleCount++;
                    } else {
                        item.style.setProperty('display', 'none', 'important');
                    }
                }
            });

            // Tampilkan pesan kosong jika tidak ada data yang cocok dengan filter
            let emptyState = document.getElementById('empty-state-message');
            if (visibleCount === 0) {
                if (!emptyState) {
                    const rowContainer = document.querySelector('.row.g-4');
                    emptyState = document.createElement('div');
                    emptyState.id = 'empty-state-message';
                    emptyState.className = 'col-12 text-center py-5 text-muted';
                    emptyState.innerHTML = `
                        <i class="bi bi-info-circle fs-1 d-block mb-2"></i>
                        Tidak ada kegiatan untuk filter ini.
                    `;
                    rowContainer.appendChild(emptyState);
                } else {
                    emptyState.style.setProperty('display', '', 'important');
                }
            } else if (emptyState) {
                emptyState.style.setProperty('display', 'none', 'important');
            }
        }
    </script>

</x-sidebar>
