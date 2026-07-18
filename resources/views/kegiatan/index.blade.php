<x-sidebar title="Kegiatan">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Kegiatan</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
            {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- ================= SEARCH ================= -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <form action="{{ route('kegiatan.index') }}" method="GET">
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
                        <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary px-3"><i class="bi bi-x-lg"></i></a>
                        @if(auth()->user()?->isKetua())
                            <button type="button" class="btn btn-primary px-4"
                                    data-bs-toggle="modal" data-bs-target="#formKegiatanModal"
                                    onclick="bukaTambahKegiatan()">
                                <i class="bi bi-plus-circle"></i> Tambah Kegiatan
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= LIST KEGIATAN ================= -->
    <div class="row g-4 align-items-stretch">
        @forelse($kegiatans as $kegiatan)
            @php
                $badgeClass = match($kegiatan->status) {
                    'berlangsung' => 'bg-success',
                    'terjadwal'   => 'bg-warning text-dark',
                    default       => 'bg-secondary',
                };
            @endphp
            <div class="col-lg-4 d-flex">
                <div class="card kegiatan-card shadow-sm border-0 w-100 h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="fw-bold">{{ $kegiatan->nama_kegiatan }}</h5>
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($kegiatan->status) }}</span>
                            </div>
                        </div>
                        <hr>
                        <p><i class="bi bi-calendar3"></i> {{ $kegiatan->tanggal?->format('d M Y') ?: '-' }}</p>
                        <p><i class="bi bi-geo-alt"></i> {{ $kegiatan->lokasi ?: '-' }}</p>
                        @if($kegiatan->deskripsi)
                            <p class="text-muted small">{{ Str::limit($kegiatan->deskripsi, 80) }}</p>
                        @endif
                        <hr class="mt-auto">
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-outline-primary btn-sm"
                                    data-bs-toggle="modal" data-bs-target="#detailKegiatanModal"
                                    onclick="bukaDetailKegiatan(this)"
                                    data-id="{{ $kegiatan->kode_kegiatan }}"
                                    data-nama="{{ $kegiatan->nama_kegiatan }}"
                                    data-tanggal="{{ $kegiatan->tanggal?->format('d M Y') }}"
                                    data-lokasi="{{ $kegiatan->lokasi }}"
                                    data-deskripsi="{{ $kegiatan->deskripsi }}"
                                    data-status="{{ $kegiatan->status }}"
                                    data-progres="{{ $kegiatan->progres ?? 0 }}">
                                <i class="bi bi-eye"></i> Detail
                            </button>
                            @if(auth()->user()?->isKetua())
                                <div class="d-flex gap-1">
                                    <button class="btn btn-warning btn-sm d-inline-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px; padding: 0 !important;"
                                            data-bs-toggle="modal" data-bs-target="#formKegiatanModal"
                                            onclick="bukaEditKegiatan(this)"
                                            data-id="{{ $kegiatan->kode_kegiatan }}"
                                            data-nama="{{ $kegiatan->nama_kegiatan }}"
                                            data-tanggal="{{ $kegiatan->tanggal?->format('Y-m-d') }}"
                                            data-lokasi="{{ $kegiatan->lokasi }}"
                                            data-deskripsi="{{ $kegiatan->deskripsi }}"
                                            data-status="{{ $kegiatan->status }}"
                                            data-progres="{{ $kegiatan->progres ?? 0 }}">
                                        <i class="bi bi-pencil" style="margin: 0 !important;"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm d-inline-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px; padding: 0 !important;"
                                            data-bs-toggle="modal" data-bs-target="#hapusKegiatanModal"
                                            onclick="bukaHapusKegiatan(this)"
                                            data-id="{{ $kegiatan->kode_kegiatan }}"
                                            data-nama="{{ $kegiatan->nama_kegiatan }}">
                                        <i class="bi bi-trash" style="margin: 0 !important;"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-calendar-event fs-1 d-block mb-2"></i>
                Belum ada kegiatan.
            </div>
        @endforelse
    </div>

    @if($kegiatans->hasPages())
        <div class="mt-4 d-flex justify-content-end">{{ $kegiatans->links() }}</div>
    @endif

    <!-- ================= MODAL TAMBAH / EDIT KEGIATAN ================= -->
    <div class="modal fade" id="formKegiatanModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4">
                <form id="formKegiatan" method="POST">
                    @csrf
                    <span id="methodField"></span>
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="formKegiatanTitle">Tambah Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @if($errors->any())
                            <div class="alert alert-danger rounded-3">
                                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                            </div>
                        @endif
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_kegiatan" class="form-control"
                                       id="inputNamaKegiatan" placeholder="Masukkan nama kegiatan" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" class="form-control"
                                       id="inputTanggalKegiatan" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Lokasi</label>
                                <input type="text" name="lokasi" class="form-control"
                                       id="inputLokasiKegiatan" placeholder="Masukkan lokasi">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" id="inputStatusKegiatan" required>
                                    <option value="terjadwal">Terjadwal</option>
                                    <option value="berlangsung">Berlangsung</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control textarea-fixed" rows="3"
                                          id="inputDeskripsiKegiatan"
                                          placeholder="Masukkan deskripsi kegiatan"></textarea>
                            </div>

                            <!-- ========== CHECKLIST PERSIAPAN ========== -->
                            <div class="col-lg-12">
                                <label class="form-label d-flex justify-content-between">
                                    <span>Checklist Persiapan</span>
                                    <span class="fw-bold text-primary" id="progresValue">0%</span>
                                </label>
                                <input type="hidden" name="progres" id="inputProgres" value="0">
                                <div class="progress mb-2" style="height:8px;">
                                    <div class="progress-bar" id="progresBarForm" role="progressbar" style="width:0%"></div>
                                </div>
                                <div class="checklist-box">
                                    <div class="row g-2">
                                        @foreach(['Proposal','Surat Permohonan','Surat Peminjaman','Lokasi','Keuangan','Alat-alat','Konsumsi','LPJ'] as $item)
                                            <div class="col-lg-6">
                                                <div class="form-check">
                                                    <input class="form-check-input checklist-item" type="checkbox"
                                                           id="cek{{ Str::slug($item) }}" value="{{ $item }}">
                                                    <label class="form-check-label" for="cek{{ Str::slug($item) }}">{{ $item }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= MODAL DETAIL KEGIATAN ================= -->
    <div class="modal fade" id="detailKegiatanModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Detail Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h4 class="fw-bold mb-0" id="detailNamaKegiatan">-</h4>
                        <span class="badge bg-success fs-6" id="detailStatusKegiatan">-</span>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-lg-6">
                            <div class="detail-label"><i class="bi bi-calendar3"></i> Tanggal</div>
                            <div class="detail-value" id="detailTanggalKegiatan">-</div>
                        </div>
                        <div class="col-lg-6">
                            <div class="detail-label"><i class="bi bi-geo-alt"></i> Lokasi</div>
                            <div class="detail-value" id="detailLokasiKegiatan">-</div>
                        </div>
                        <div class="col-lg-6">
                            <div class="detail-label"><i class="bi bi-bar-chart"></i> Progres</div>
                            <div class="progress" style="height:22px;">
                                <div class="progress-bar" id="detailProgresBar"
                                     role="progressbar" style="width:0%">0%</div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="detail-label">Deskripsi</div>
                            <div class="detail-value" id="detailDeskripsiKegiatan">-</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <a id="btnDetailDokumen" href="#" class="btn btn-outline-primary px-3">
                        <i class="bi bi-folder2-open"></i> Dokumen
                    </a>
                    <a id="btnDetailAbsensi" href="#" class="btn btn-outline-success px-3">
                        <i class="bi bi-calendar2-check"></i> Absensi
                    </a>
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= MODAL HAPUS KEGIATAN ================= -->
    <div class="modal fade" id="hapusKegiatanModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:48px;"></i>
                    </div>
                    <h5 class="fw-bold">Hapus Kegiatan?</h5>
                    <p class="text-muted mb-0">
                        Apakah kamu yakin ingin menghapus kegiatan
                        <strong id="hapusNamaKegiatan">-</strong>?
                    </p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <form id="formHapusKegiatan" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        /* ====== CHECKLIST PROGRES ====== */
        function hitungProgres() {
            const items    = document.querySelectorAll('.checklist-item');
            const total    = items.length;
            const dicentang = document.querySelectorAll('.checklist-item:checked').length;
            const persen   = total === 0 ? 0 : Math.round((dicentang / total) * 100);
            document.getElementById('progresValue').innerText        = persen + '%';
            document.getElementById('progresBarForm').style.width    = persen + '%';
            document.getElementById('inputProgres').value            = persen;
            return persen;
        }
        document.querySelectorAll('.checklist-item').forEach(function (item) {
            item.addEventListener('change', hitungProgres);
        });

        /* ====== TAMBAH ====== */
        function bukaTambahKegiatan() {
            document.getElementById('formKegiatanTitle').innerText = 'Tambah Kegiatan';
            document.getElementById('formKegiatan').action = '{{ route('kegiatan.store') }}';
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('formKegiatan').reset();
            document.querySelectorAll('.checklist-item').forEach(i => i.checked = false);
            hitungProgres();
        }

        /* ====== EDIT ====== */
        function bukaEditKegiatan(btn) {
            document.getElementById('formKegiatanTitle').innerText = 'Edit Kegiatan';
            const id = btn.dataset.id;
            document.getElementById('formKegiatan').action = `/kegiatan/${id}`;
            document.getElementById('methodField').innerHTML =
                '<input type="hidden" name="_method" value="PUT">';

            document.getElementById('inputNamaKegiatan').value     = btn.dataset.nama;
            document.getElementById('inputTanggalKegiatan').value  = btn.dataset.tanggal;
            document.getElementById('inputLokasiKegiatan').value   = btn.dataset.lokasi;
            document.getElementById('inputDeskripsiKegiatan').value= btn.dataset.deskripsi;
            document.getElementById('inputStatusKegiatan').value   = btn.dataset.status;

            const persen = parseInt(btn.dataset.progres) || 0;
            document.getElementById('inputProgres').value            = persen;
            document.getElementById('progresValue').innerText        = persen + '%';
            document.getElementById('progresBarForm').style.width    = persen + '%';
        }

        /* ====== DETAIL ====== */
        function bukaDetailKegiatan(btn) {
            const id = btn.dataset.id;
            document.getElementById('detailNamaKegiatan').innerText    = btn.dataset.nama;
            document.getElementById('detailStatusKegiatan').innerText  = btn.dataset.status
                ? btn.dataset.status.charAt(0).toUpperCase() + btn.dataset.status.slice(1)
                : '-';
            document.getElementById('detailTanggalKegiatan').innerText = btn.dataset.tanggal || '-';
            document.getElementById('detailLokasiKegiatan').innerText  = btn.dataset.lokasi  || '-';
            document.getElementById('detailDeskripsiKegiatan').innerText = btn.dataset.deskripsi || '-';

            const progres = parseInt(btn.dataset.progres) || 0;
            const bar = document.getElementById('detailProgresBar');
            bar.style.width  = progres + '%';
            bar.innerText    = progres + '%';

            document.getElementById('btnDetailDokumen').href = `/dokumen/${id}/folder`;
            document.getElementById('btnDetailAbsensi').href = `/absensi?search=${encodeURIComponent(btn.dataset.nama)}`;
        }

        /* ====== HAPUS ====== */
        function bukaHapusKegiatan(btn) {
            document.getElementById('hapusNamaKegiatan').innerText = btn.dataset.nama;
            document.getElementById('formHapusKegiatan').action   = `/kegiatan/${btn.dataset.id}`;
        }
    </script>

</x-sidebar>
